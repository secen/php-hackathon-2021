<?php

namespace App\Controller;

use App\Entity\Programmes;
use App\Entity\Rooms;
use DateTime;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use App\Entity\Admins;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\FileParam;
use Symfony\Component\Validator\Constraints;

use FOS\RestBundle\Controller\AbstractFOSRestController;

class UsersController extends AbstractFOSRestController
{

    /**
     * @Route("/createUser")
     * @RequestParam(name="cnp")
    */
    public function createUser(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new Users();
        try {
            $cnp = $fetcher->get('cnp');
        }
        catch (exception $e)
        {
            return $this->view("No CNP submitted!", Response::HTTP_BAD_REQUEST);
        }
        if($cnp != null) {
            $user->setCnp($cnp);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->view("Successfully creatd user", Response::HTTP_OK);
        }
        return $this->view("Failed to create user", Response::HTTP_BAD_REQUEST);
    }
    /**
     * @Route("/removeUser")
     * @RequestParam(name="cnp")
     */
    public function removeUserByCNP(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cnp = $fetcher->get('cnp');
        $user = $entityManager->getRepository(Users::class)->findBy(['cnp' => $cnp]);
        $entityManager->remove($user[0]);
        $entityManager->flush();
        return $this->view("Removed User",Response::HTTP_ACCEPTED);

    }
    /**
     * @Route("/createRoom")
     * @RequestParam(name="AuthToken")
     * @RequestParam(name="Name")
     */
    public function createRoom(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $token = $fetcher->get('AuthToken');
        $admin = $entityManager->getRepository(Admins::class)->findBy(['authtoken'=>$token]);
        if($admin != null) {
            $room = new Rooms();
            $roomName = $fetcher->get('Name');
            $room->setName($roomName);
            $entityManager->persist($room);
            $entityManager->flush();
            return $this->view("Room Created", Response::HTTP_ACCEPTED);
        }
        return $this->view("AuthToken invalid", Response::HTTP_BAD_REQUEST);
    }
    /**
     * @Route("/deleteRoom")
     * @RequestParam(name="AuthToken")
     * @RequestParam(name="Name")
     */
    public function removeRoom(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $token = $fetcher->get('AuthToken');
        $admin = $entityManager->getRepository(Admins::class)->findBy(['authtoken'=>$token]);
        if($admin != null)
        {
            $roomName = $fetcher->get('Name');
            $room = $entityManager->getRepository(Rooms::class)->findBy(['name'=>$roomName]);
            $entityManager->remove($room[0]);
            $entityManager->flush();
            return $this->view("Room deleted", Response::HTTP_ACCEPTED);
        }
        return $this->view("Invalid AuthToken", Response::HTTP_BAD_REQUEST);

    }

    /**
     * @Route("/createProgramme")
     * @RequestParam(name="AuthToken")
     * @RequestParam(name="Name")
     * @RequestParam(name="StartDate")
     * @RequestParam(name="EndDate")
     * @RequestParam(name="RoomId")
     * @RequestParam(name="MaxParticipants")
     */
    public function createProgramme(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $token = $fetcher->get('AuthToken');
        $admin = $entityManager->getRepository(Admins::class)->findBy(['authtoken'=>$token]);
        if($admin != null)
        {
            $programme = new Programmes();
            $programmeName = $fetcher->get('Name');
            $programmeEndDate = new DateTime($fetcher->get('EndDate'));
            $programmeStartDate = new DateTime($fetcher->get('StartDate'));
            $programmeMaxParticipants = $fetcher->get('MaxParticipants');
            $programmeRoomId = $fetcher->get('RoomId');
            $programme->setName($programmeName);
            $programme->setEndingdate($programmeEndDate);
            $programme->setMaxparticipans($programmeMaxParticipants);
            $selectedRoom = $entityManager->getRepository(Rooms::class)->find($programmeRoomId);
            $programme->setRoomid($selectedRoom);
            $programme->setStartingdate($programmeStartDate);
            $entityManager->persist($programme);
            $entityManager->flush();
            return $this->view("Added Programme", Response::HTTP_ACCEPTED);
        }
        return $this->view("Invalid AuthToken", Response::HTTP_BAD_REQUEST);
    }
    /**
     * @Route("/removeProgramme")
     * @RequestParam(name="AuthToken")
     * @RequestParam(name="Name")
     */
    public function removeProgramme(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $token = $fetcher->get('AuthToken');
        $admin = $entityManager->getRepository(Admins::class)->findBy(['authtoken'=>$token]);
        if($admin!=null)
        {
            $programmeName = $fetcher->get('Name');
            $programme = $entityManager->getRepository(Programmes::class)->findBy(['name'=>$programmeName]);
            $entityManager->remove($programme[0]);
            $entityManager->flush();
            return $this->view("Successfully removed programme", Response::HTTP_ACCEPTED);
        }
        return $this->view("Couldn't remove programme", Response::HTTP_BAD_REQUEST);
    }
    #[Route('/users', name: 'users')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsersController.php',
        ]);
    }
}
