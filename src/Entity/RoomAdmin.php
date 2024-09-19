<?php

namespace App\Entity;

use App\Repository\RoomAdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomAdminRepository::class)]
class RoomAdmin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roomAdmins')]
    private ?Room $room = null;

    #[ORM\ManyToOne(inversedBy: 'roomAdmins')]
    private ?UserProfil $relatedProfil = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getRelatedProfil(): ?UserProfil
    {
        return $this->relatedProfil;
    }

    public function setRelatedProfil(?UserProfil $relatedProfil): static
    {
        $this->relatedProfil = $relatedProfil;

        return $this;
    }

    public function getAddedBy(): ?string
    {
        return $this->addedBy;
    }

    public function setAddedBy(?string $addedBy): static
    {
        $this->addedBy = $addedBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
