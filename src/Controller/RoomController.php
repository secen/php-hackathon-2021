<?php

namespace App\Controller;

use App\Entity\Programmes;
use App\Entity\Reservation;
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
class RoomController extends AbstractFOSRestController
{
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
}
