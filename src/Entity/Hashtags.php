<?php

namespace App\Entity;

use App\Repository\HashtagsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HashtagsRepository::class)
 */
class Hashtags
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $feeds;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getViews(): ?string
    {
        return $this->views;
    }

    public function setViews(string $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getFeeds(): ?string
    {
        return $this->feeds;
    }

    public function setFeeds(?string $feeds): self
    {
        $this->feeds = $feeds;

        return $this;
    }
}
