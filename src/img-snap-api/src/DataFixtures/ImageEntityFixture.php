<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImageEntityFixture extends Fixture
{
    public Image $image;

    public function load(ObjectManager $manager): void {
        $slug = "fixture-slug";
        $upstream = 'https://nextcloud.local/fixture-resource.png';

        $this->image = new Image();
        $this->image->setSlug($slug);
        $this->image->setUpstreamUrl($upstream);
        $this->image->setCachedAt(new \DateTimeImmutable());

        $manager->persist($this->image);
        $manager->flush();
    }

    public function getSlug(): string {
        return $this->image->getSlug();
    }
}
