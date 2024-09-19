<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    private ?UserProfil $owner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pictureUrl = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, RoomAdmin>
     */
    #[ORM\OneToMany(targetEntity: RoomAdmin::class, mappedBy: 'room')]
    private Collection $roomAdmins;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'room')]
    private Collection $messages;

    #[ORM\Column(length: 10)]
    private ?string $visibility = null;

    /**
     * @var Collection<int, RoomMember>
     */
    #[ORM\OneToMany(targetEntity: RoomMember::class, mappedBy: 'room')]
    private Collection $roomMembers;

    public function __construct()
    {
        $this->roomAdmins = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->roomMembers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOwner(): ?UserProfil
    {
        return $this->owner;
    }

    public function setOwner(?UserProfil $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): static
    {
        $this->pictureUrl = $pictureUrl;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, RoomAdmin>
     */
    public function getRoomAdmins(): Collection
    {
        return $this->roomAdmins;
    }

    public function addRoomAdmin(RoomAdmin $roomAdmin): static
    {
        if (!$this->roomAdmins->contains($roomAdmin)) {
            $this->roomAdmins->add($roomAdmin);
            $roomAdmin->setRoom($this);
        }

        return $this;
    }

    public function removeRoomAdmin(RoomAdmin $roomAdmin): static
    {
        if ($this->roomAdmins->removeElement($roomAdmin)) {
            // set the owning side to null (unless already changed)
            if ($roomAdmin->getRoom() === $this) {
                $roomAdmin->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setRoom($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getRoom() === $this) {
                $message->setRoom(null);
            }
        }

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return Collection<int, RoomMember>
     */
    public function getRoomMembers(): Collection
    {
        return $this->roomMembers;
    }

    public function addRoomMember(RoomMember $roomMember): static
    {
        if (!$this->roomMembers->contains($roomMember)) {
            $this->roomMembers->add($roomMember);
            $roomMember->setRoom($this);
        }

        return $this;
    }

    public function removeRoomMember(RoomMember $roomMember): static
    {
        if ($this->roomMembers->removeElement($roomMember)) {
            // set the owning side to null (unless already changed)
            if ($roomMember->getRoom() === $this) {
                $roomMember->setRoom(null);
            }
        }

        return $this;
    }
}
