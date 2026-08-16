<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

use Symfony\Component\Filesystem\Filesystem;

use App\Entity\Image;
use App\Repository\ImageRepository;
use App\DataFixtures;

final class ImageStoreControllerTest extends WebTestCase
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

    public function testMissingImage(): void {
        $this->client->request('GET', '/store/api');
        $response = $this->client->getResponse();

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $data);
        $this->assertSame($data['message'], 'image not found');
        $this->assertSame($response->getStatusCode(), 404);
        $this->assertSame($response->headers->get('Content-Type'), 'application/json');
    }

    public function testRetrieveImage() {
        $image = new Image();
        $slug = "test-with-placehold-co";
        $image->setSlug($slug);
        $image->setUpstreamUrl(DataFixtures\ImageFixtures::placeholdCoImageUrl600x400());
        $image->setCachedAt(new \DateTimeImmutable());
        $this->entityManager->persist($image);
        $this->entityManager->flush();

        $this->client->request('GET', "/store/{$slug}");
        $response = $this->client->getResponse();
        self::assertResponseIsSuccessful();
        $this->assertSame($response->headers->get('Content-Type'), 'image/webp');
    }
}
