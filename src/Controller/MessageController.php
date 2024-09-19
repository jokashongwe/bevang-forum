<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Room;
use App\Repository\MessageRepository;
use App\Repository\RoomRepository;
use App\Repository\UserProfilRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    #[Route('/api/v1/messages/{roomId}', name: 'app_api_room_all_messages', methods:['GET'])]
    public function getAll($roomId, MessageRepository $messageRepository, RoomRepository $roomRepository, Request $request): Response
    {
        $room = $roomRepository->find($roomId);
        if(is_null($room)){
            return $this->json(["message" => "No Room Found!"]);
        }
        $lastMsgId = $request->query->get("lastMsgId");
        if($lastMsgId){
            //only message that are not aleady read in the phone
            return $this->json($messageRepository->findBy(['room' => $room], offset: $lastMsgId));
        }

        return $this->json($messageRepository->findBy(['room' => $room]));
    }

    #[Route('/api/v1/messages/{roomId}', name: 'app_api_room_message_create', methods:['POST'])]
    public function postMessage($roomId, 
        RoomRepository $roomRepository, 
        UserProfilRepository $userProfilRepository, 
        Request $request): Response
    {
        $profileId = $request->getPayload()->get('profileId');
        $content = $request->getPayload()->get('content');
        $message = new Message();
        $message->setRoom($roomRepository->find($roomId));
        $message->setOwner($userProfilRepository->find($profileId));
        $message->setContent($content);
        $message->setCreatedAt(new \DateTimeImmutable());

        return $this->json($message);
    }
    
}
