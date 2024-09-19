<?php

namespace App\Entity;

use App\Repository\RoomMemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomMemberRepository::class)]
class RoomMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roomMembers')]
    private ?Room $room = null;

    #[ORM\ManyToOne(inversedBy: 'roomMembers')]
    private ?UserProfil $member = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $joinDate = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $memberRole = null;

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

    public function getMember(): ?UserProfil
    {
        return $this->member;
    }

    public function setMember(?UserProfil $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->joinDate;
    }

    public function setJoinDate(\DateTimeInterface $joinDate): static
    {
        $this->joinDate = $joinDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getMemberRole(): ?string
    {
        return $this->memberRole;
    }

    public function setMemberRole(?string $memberRole): static
    {
        $this->memberRole = $memberRole;

        return $this;
    }
}
