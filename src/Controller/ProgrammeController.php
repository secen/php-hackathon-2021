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


class ProgrammeController extends AbstractFOSRestController
{
    /**
     * @Route("/createProgramme")
     * @RequestParam(name="AuthToken")
     * @RequestParam(name="Name")
     * @RequestParam(name="StartDate")
     * @RequestParam(name="EndDate")
     * @RequestParam(name="RoomName")
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
            $programmeRoomName = $fetcher->get('RoomName');
            $programme->setName($programmeName);
            $programme->setEndingdate($programmeEndDate);
            $programme->setMaxparticipans($programmeMaxParticipants);
            $selectedRoom = $entityManager->getRepository(Rooms::class)->findBy(['name'=>$programmeRoomName])[0];
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
}
