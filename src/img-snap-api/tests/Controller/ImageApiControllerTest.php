<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

use Symfony\Component\Filesystem\Filesystem;

use App\Entity\Image;
use App\Repository\ImageRepository;

use App\DataFixtures\ImageEntityFixture;

use PHPUnit\Framework\Attributes\DataProvider;

final class ImageApiControllerTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;
    private ImageRepository $imageRepository;
    private string $configuredCacheDir;

    public function setUp(): void {
        $this->client = static::createClient();
        $this->configuredCacheDir = static::getContainer()->getParameter('app.image_cache_dir');

        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->imageRepository = $this->entityManager->getRepository(Image::class);
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function testGetImageCollection() {
        $fixture = new ImageEntityFixture();
        $fixture->load($this->entityManager);

        $this->client->request(
            'GET', '/image', [], [],
            ["HTTP_X-AUTH-TOKEN" => $_ENV['APP_API_KEY']]
        );
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();

        $this->assertNotNull($data);
        $this->assertIsList($data);
        $this->assertNotEmpty($data);

        $firstItem = $data[0];
        $this->assertArrayHasKey('id', $firstItem);
        $this->assertArrayHasKey('slug', $firstItem);
        $this->assertArrayHasKey('upstreamUrl', $firstItem);
        $this->assertArrayNotHasKey('cachedAt', $firstItem);
    }

    public function testPostImageSuccess() {
        $slug = "test-create-slug";
        $upstream = "https://upstream.local";
        $data = json_encode([
            "slug" => $slug,
            "upstreamUrl" => $upstream
        ]);

        $this->client->request(
            'POST', '/image', [], [],
            [
                "CONTENT_TYPE" => "application/json",
                "HTTP_X-AUTH-TOKEN" => $_ENV['APP_API_KEY']
            ],
            $data
        );

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $this->AssertIsArray($data);
        $this->assertSame($slug, $data['slug']);
        $this->assertSame($upstream, $data['upstreamUrl']);

        $images = $this->imageRepository->findAll();
        $this->assertIsList($images);
        $this->assertEquals(1, count($images));
    }

    public static function slugFailValidatorProvider() {
        return [
            "Slug null | Upstream null" => ["slug" => null, "upstream" => null, "expected_errors" => 2],
            "Slug null | Upstreaam Invalid" => ["slug" => null, "upstream" => "test", "expected_errors" => 2],
            "Slug null | Upstream valid" => ["slug" => null, "upstream" => "https://test.local", "expected_errors"  => 1],
            "Slug valid | Upstream Invalid" => ["slug" => "test", "upstream" => "test", "expected_errors"  => 1],
            "Slug valid | Upstream null" => ["slug" => "test", "upstream" => null, "expected_errors" => 1],
        ];
    }

    #[DataProvider('slugFailValidatorProvider')]
    public function testPostImageFailValidator(string|null $slug, ?string $upstream, int $expected_errors) {
        $data = json_encode([
            "slug" => $slug,
            "upstreamUrl" => $upstream
        ]);

        $this->client->request(
            'POST', '/image', [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-AUTH-TOKEN' => $_ENV['APP_API_KEY']
            ],
            $data);
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(400);
        $this->assertEquals(count($result['violations']), $expected_errors);
    }

    public function testSlugIsUnique() {
        $slug = "fixture-slug";
        $upstream = 'https://nextcloud.local/fixture-resource.png';
        $fixture = new ImageEntityFixture();
        $fixture->load($this->entityManager);

        $data = json_encode([
            "slug" => $slug,
            "upstreamUrl" => $upstream
        ]);

        $this->client->request(
            'POST', '/image', [], [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-AUTH-TOKEN' => $_ENV['APP_API_KEY']
            ],
            $data
        );
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(400);
        $this->assertEquals(count($result['violations']), 1);
        $violation = $result['violations'][0];
        // There are more keys but they are not THAT relevant
        $this->assertArrayHasKey("propertyPath", $violation);
        $this->assertArrayHasKey("title", $violation);

        // This is important to identify the issue
        $this->assertSame("slug", $violation['propertyPath']);
        $this->assertSame("The slug \"{$slug}\" is already in use.", $violation['title']);
    }

    public function testDeleteSlug() {
        // Setup
        $fs = new Filesystem();
        $fixture = new ImageEntityFixture();
        $fixture->load($this->entityManager);
        $slug = $fixture->getSlug();
        $path = "{$this->configuredCacheDir}/{$slug}.webp";
        /** @disregard P1001 */
        $fs->mkdir($this->configuredCacheDir, 0o755);
        $fs->touch($path);

        // Execution
        $this->client->request(
            'DELETE', "/image/{$slug}", [], [],
            ['HTTP_X-AUTH-TOKEN' => $_ENV['APP_API_KEY']]
        );

        // Assertion
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(204);
        $this->assertFalse($fs->exists($path));
    }

    public function testDeleteMissingReturns404() {
        $this->client->request(
            'DELETE', '/image/randomTestThatShouldNeverExist', [], [],
            ['HTTP_X-AUTH-TOKEN' => $_ENV['APP_API_KEY']]
        );
        $this->assertResponseStatusCodeSame(404);
    }

    public static function imageRequestsToBeProtected(): array {
        return [
            "GET /image" => ['GET', '/image'],
            "POST /image" => ['POST', '/image'],
            "DELETE /image/{slug}" => ['DELETE', '/image/test'],
        ];
    }

    #[DataProvider('imageRequestsToBeProtected')]
    public function testApiSlashImageIsProtected(string $method, string $url) {
        $this->client->request($method, $url);
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertArrayHasKey('message', $result);
        $this->assertSame($result['message'], "Invalid or missing API Key.");
    }

    public function tearDown(): void {
        parent::tearDown();
        $this->entityManager->close();
        $fs = new Filesystem();
        $fs->remove($this->configuredCacheDir);
    }
}
