<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\UserProfil;
use App\Repository\RoomRepository;
use App\Repository\UserProfilRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/api/v1/profiles', name: 'app_api_profile_all', methods:['GET'])]
    public function getAll(UserProfilRepository $userProfilRepository): Response
    {
        return $this->json($userProfilRepository->findAll());
    }

    #[Route('/api/v1/profiles/{id}', name: 'app_api_profile_my', methods:['GET'])]
    public function getPersonnalProfile($id, UserProfilRepository $userProfilRepository): Response
    {
        $profile = $userProfilRepository->find($id);
        if(is_null($profile)){
            return $this->json(["message" => "No Data Found!"], 404);
        }
        return $this->json($profile);
    }

    #[Route('/api/v1/profiles/{id}', name: 'app_api_profile_update', methods:['PUT'])]
    public function update($id, UserProfilRepository $userProfilRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $profile  = $userProfilRepository->find($id);
        if(is_null($profile)){
            return $this->json(["message" => "No Profile Data Found for ID " + $id], 404);
        }

        $firstName = $request->getPayload()->get("firstName");
        $lastName = $request->getPayload()->get("lastName");
        $pseudo = $request->getPayload()->get("pseudo");
        //$status = $request->getPayload()->get("status");
        
        if(!is_null($firstName)){
            $profile->setFirstName($firstName);   
        }

        if(!is_null($lastName)){
            $profile->setLastName($lastName);   
        }

        if(!is_null($pseudo)){
            $profile->setPseudo($pseudo);   
        }

        $profile->setUpdatedAt(new \DateTimeImmutable());
        $manager = $managerRegistry->getManager();
        $manager->persist($profile);
        $manager->flush();

        return $this->json($profile);
    }

    #[Route('/api/v1/profiles', name: 'app_api_profile_create', methods:['POST'])]
    public function create(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $profile  = new UserProfil();
        $firstName = $request->getPayload()->get("firstName");
        $lastName = $request->getPayload()->get("lastName");
        $pseudo = $request->getPayload()->get("pseudo");
        //$status = $request->getPayload()->get("status");
    
        $profile->setFirstName($firstName);
        $profile->setLastName($lastName); 
        $profile->setPseudo($pseudo);
        $profile->setStatus("ACTIVE");

        $profile->setCreatedAt(new \DateTimeImmutable());
        $profile->setUpdatedAt(new \DateTimeImmutable());
        $manager = $managerRegistry->getManager();
        $manager->persist($profile);
        $manager->flush();

        return $this->json($profile);
    }

}
