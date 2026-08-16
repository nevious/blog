<?php

namespace App\Tests\Integration;

use App\DataFixtures\ImageEntityFixture;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImagePersistenceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    public function setUp(): void {
        // Setup internals
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        // Create the in-memory database
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    // Leave this for now
    // Issue:
    // Creation of dynamic property App\Tests\Integration\ImagePersistenceTest::$kernel is deprecated
    public function testCorrectEnvironment(): void
    {
        $this->assertSame('test', self::$kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testImageCanBePersisted(): void {
        // Setup
        $slug = 'test-slug';
        $upstream = 'https://nextcloud.local/some-resource.png';
        $cacheAt = new \DateTimeImmutable();

        $image = new Image();
        $image->setSlug($slug);
        $image->setUpstreamUrl($upstream);
        $image->setCachedAt($cacheAt);

        // Create image
        $this->entityManager->persist($image);
        $this->entityManager->flush();
        $this->entityManager->clear();

        // Retrieve Image
        $resultImage = $this->entityManager->getRepository(Image::class)->findOneBy(['slug' => $slug]);

        // Assert
        $this->assertNotNull($resultImage);
        $this->assertSame($resultImage->getId(), 1);
        $this->assertIsInt($resultImage->getId());
        $this->assertSame($resultImage->getSlug(), $slug);
        $this->assertSame($resultImage->getUpstreamUrl(), $upstream);
        $this->assertNotNull($resultImage->getCachedAt());
    }


    public function testFixtureIsAvailable(): void {
        $fixture = new ImageEntityFixture();
        $fixture->load($this->entityManager);
        $result = $this->entityManager->getRepository(Image::class)->findBy(['slug' => 'fixture-slug']);
        $this->assertIsArray($result);
        $this->assertSame('fixture-slug', $result[0]->getSlug());
    }

    public function tearDown(): void {
        parent::tearDown();
        $this->entityManager->close();
    }
}
