<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $upstreamUrl = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $cachedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUpstreamUrl(): ?string
    {
        return $this->upstreamUrl;
    }

    public function setUpstreamUrl(string $upstreamUrl): static
    {
        $this->upstreamUrl = $upstreamUrl;

        return $this;
    }

    public function getCachedAt(): ?\DateTimeImmutable
    {
        return $this->cachedAt;
    }

    public function setCachedAt(?\DateTimeImmutable $cachedAt): static
    {
        $this->cachedAt = $cachedAt;

        return $this;
    }
}
