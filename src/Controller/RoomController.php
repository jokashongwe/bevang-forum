<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\RoomMember;
use App\Repository\RoomMemberRepository;
use App\Repository\RoomRepository;
use App\Repository\UserProfilRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomController extends AbstractController
{
    #[Route('/api/v1/rooms', name: 'app_api_room_all', methods: ['GET'])]
    public function getAll(RoomRepository $roomRepository): Response
    {
        return $this->json($roomRepository->findBy(['visibility' => 'PUBLIC']));
    }

    #[Route('/api/v1/rooms/my/{ownerId}', name: 'app_api_room_my', methods: ['GET'])]
    public function getPersonnalRooms($ownerId, RoomRepository $roomRepository, UserProfilRepository $userProfilRepository): Response
    {
        $profile = $userProfilRepository->find($ownerId);
        if (is_null($profile)) {
            return $this->json(["message" => "No Data Found!"], 404);
        }
        $rooms = $roomRepository->findBy(['visibility' => 'PUBLIC']);
        $members = $profile->getRoomMembers();
        foreach ($members as $member) {
            $currentRoom = $member->getRoom();
            if (!in_array($member->getRoom(), $rooms)) {
                array_push($rooms, $currentRoom);
            }
        }
        return $this->json($rooms);
    }

    #[Route('/api/v1/rooms/{roomId}', name: 'app_api_room_update', methods: ['PUT'])]
    public function update($roomId, RoomRepository $roomRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $room  = $roomRepository->find($roomId);
        if (is_null($room)) {
            return $this->json(["message" => "No Room Data Found for ID " + $roomId], 404);
        }

        $subject = $request->getPayload()->get("subject");
        if (!is_null($subject)) {
            $room->setSubject($subject);
            $room->setUpdatedAt(new \DateTimeImmutable());
            $manager = $managerRegistry->getManager();
            $manager->persist($room);
            $manager->flush();
        }
        return $this->json([
            "id" => $room->getId(),
            "subject" => $room->getSubject(),
            "owner" => [
                "id" => $room->getOwner()->getId(),
                "firstName" => $room->getOwner()->getFirstName(),
                "lastName" => $room->getOwner()->getLastName()
            ],
            "visibility" => $room->getVisibility(),
            "createdAt" => $room->getCreatedAt()
        ]);
    }

    #[Route('/api/v1/rooms/{roomId}/add', name: 'app_api_room_add_member', methods: ['POST'])]
    public function addMember($roomId, RoomRepository $roomRepository, UserProfilRepository $userProfilRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $room  = $roomRepository->find($roomId);
        if (is_null($room)) {
            return $this->json(["message" => "No Room Data Found for ID " + $roomId], 404);
        }

        $profileId = $request->getPayload()->get("profileId");
        $profile = $userProfilRepository->find($profileId);
        if (is_null($profile)) {
            return $this->json(["message" => "No User Found"]);
        }

        $roomMember = new RoomMember();
        $roomMember->setRoom($room);
        $roomMember->setMemberRole("MEMBER"); //par défaut
        $roomMember->setMember($profile);
        $roomMember->setJoinDate(new  \DateTime());

        $manager = $managerRegistry->getManager();
        $manager->persist($roomMember);
        $manager->flush();

        return $this->json([
            "id" => $roomMember->getId(),
            "room" => [
                "id" => $room->getId(),
                "subject" => $room->getSubject()
            ],
            "profile" => [
                "id" => $profile->getId(),
                "firstName" => $profile->getFirstName(),
                "lastName" => $profile->getLastName()
            ],
            "joinDate" => $roomMember->getJoinDate(),
            "memberRole" => $roomMember->getMemberRole()
        ]);
    }

    #[Route('/api/v1/rooms/{roomId}/addAdmin', name: 'app_api_room_add_member', methods: ['POST'])]
    public function addAdmin(
        $roomId,
        RoomRepository $roomRepository,
        UserProfilRepository $userProfilRepository,
        Request $request,
        ManagerRegistry $managerRegistry,
        RoomMemberRepository $roomMemberRepository
    ): Response {
        $room  = $roomRepository->find($roomId);
        if (is_null($room)) {
            return $this->json(["message" => "No Room Data Found for ID " + $roomId], 404);
        }

        $profileId = $request->getPayload()->get("profileId");
        $profile = $userProfilRepository->find($profileId);
        if (is_null($profile)) {
            return $this->json(["message" => "No User Found"]);
        }


        $roomMember = $roomMemberRepository->findOneBy(['room' => $room, 'member' => $profile]);
        $roomMember->setMemberRole("ADMIN"); //par défaut
        $manager = $managerRegistry->getManager();
        $manager->persist($roomMember);
        $manager->flush();

        return $this->json([
            "id" => $roomMember->getId(),
            "room" => [
                "id" => $room->getId(),
                "subject" => $room->getSubject()
            ],
            "profile" => [
                "id" => $profile->getId(),
                "firstName" => $profile->getFirstName(),
                "lastName" => $profile->getLastName()
            ],
            "joinDate" => $roomMember->getJoinDate(),
            "memberRole" => $roomMember->getMemberRole()
        ]);
    }

    #[Route('/api/v1/rooms/{roomId}/remove', name: 'app_api_room_add_member', methods: ['POST'])]
    public function removeMember(
        $roomId,
        RoomRepository $roomRepository,
        UserProfilRepository $userProfilRepository,
        Request $request,
        ManagerRegistry $managerRegistry,
        RoomMemberRepository $roomMemberRepository
    ): Response {
        $room  = $roomRepository->find($roomId);
        if (is_null($room)) {
            return $this->json(["message" => "No Room Data Found for ID " + $roomId], 404);
        }

        $profileId = $request->getPayload()->get("profileId");
        $profile = $userProfilRepository->find($profileId);
        if (is_null($profile)) {
            return $this->json(["message" => "No User Found"]);
        }

        $roomMember = $roomMemberRepository->findOneBy(['room' => $room, 'member' => $profile]);
        $manager = $managerRegistry->getManager();
        $manager->remove($roomMember);
        $manager->flush();

        return $this->json([
            "message" => "Member Removed"
        ]);
    }

    #[Route('/api/v1/rooms', name: 'app_api_room_create', methods: ['POST'])]
    public function create(RoomRepository $roomRepository, Request $request, ManagerRegistry $managerRegistry, UserProfilRepository $userProfilRepository): Response
    {
        $room = new Room();
        $subject = $request->getPayload()->get("subject");
        $description = $request->getPayload()->get("description");
        $ownerId  = $request->getPayload()->get("ownerId");
        $visibility  = $request->getPayload()->get("visibility");

        $userProfile = $userProfilRepository->find($ownerId);
        if (is_null($userProfile)) {
            return $this->json(["message" => "No Data Found!"], 404);
        }

        $room->setSubject($subject);
        $room->setDescription($description);
        $room->setOwner($userProfile);
        $room->setVisibility($visibility);
        $room->setCreatedAt(new \DateTimeImmutable());

        $manager = $managerRegistry->getManager();
        $manager->persist($room);
        $manager->flush();

        return $this->json([
            "id" => $room->getId(),
            "subject" => $room->getSubject(),
            "owner" => [
                "id" => $room->getOwner()->getId(),
                "firstName" => $room->getOwner()->getFirstName(),
                "lastName" => $room->getOwner()->getLastName()
            ],
            "visibility" => $room->getVisibility(),
            "createdAt" => $room->getCreatedAt()
        ]);
    }
}
