<?php

namespace App\Entity;

use App\Repository\UserProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProfilRepository::class)]
class UserProfil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoUrl = null;

    #[ORM\Column(length: 5)]
    private ?string $status = null;

    #[ORM\OneToOne(inversedBy: 'userProfil', cascade: ['persist', 'remove'])]
    private ?User $relatedUser = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Room>
     */
    #[ORM\OneToMany(targetEntity: Room::class, mappedBy: 'owner')]
    private Collection $rooms;

    /**
     * @var Collection<int, RoomAdmin>
     */
    #[ORM\OneToMany(targetEntity: RoomAdmin::class, mappedBy: 'relatedProfil')]
    private Collection $roomAdmins;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'owner')]
    private Collection $messages;

    /**
     * @var Collection<int, RoomMember>
     */
    #[ORM\OneToMany(targetEntity: RoomMember::class, mappedBy: 'member')]
    private Collection $roomMembers;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->roomAdmins = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->roomMembers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl): static
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRelatedUser(): ?User
    {
        return $this->relatedUser;
    }

    public function setRelatedUser(?User $relatedUser): static
    {
        $this->relatedUser = $relatedUser;

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

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): static
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms->add($room);
            $room->setOwner($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): static
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getOwner() === $this) {
                $room->setOwner(null);
            }
        }

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
            $roomAdmin->setRelatedProfil($this);
        }

        return $this;
    }

    public function removeRoomAdmin(RoomAdmin $roomAdmin): static
    {
        if ($this->roomAdmins->removeElement($roomAdmin)) {
            // set the owning side to null (unless already changed)
            if ($roomAdmin->getRelatedProfil() === $this) {
                $roomAdmin->setRelatedProfil(null);
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
            $message->setOwner($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getOwner() === $this) {
                $message->setOwner(null);
            }
        }

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
            $roomMember->setMember($this);
        }

        return $this;
    }

    public function removeRoomMember(RoomMember $roomMember): static
    {
        if ($this->roomMembers->removeElement($roomMember)) {
            // set the owning side to null (unless already changed)
            if ($roomMember->getMember() === $this) {
                $roomMember->setMember(null);
            }
        }

        return $this;
    }
}
