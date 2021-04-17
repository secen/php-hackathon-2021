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

class ReservationController extends AbstractFOSRestController
{

    public function isProgrammeFull(Programmes $programme)
    {
        $sum = 0;
        $identityManager = $this->getDoctrine()->getManager();
        $programmeId = $programme->getId();
        $reservations = $identityManager->getRepository(Reservation::class)->findBy(['programmeid'=>$programmeId]);
        foreach($reservations as &$reservation)
        {
            $sum+=1;
        }
        return $programme->getMaxparticipans() == $sum;
    }
    public function hasOverlappingReservation(Users $user,Reservation $reservation)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $programmeId = $reservation->getProgrammeid();
        $userID = $user->getId();
        $programme = $entityManager->getRepository(Programmes::class)->findBy(['id'=>$programmeId])[0];
        $userReservations = $entityManager->getRepository(Reservation::class)->findBy(['userid'=>$userID]);
        foreach($userReservations as &$res)
        {
            $progId = $res->getProgrammeid();
            $prog = $entityManager->getRepository(Programmes::class)->findBy(['id'=>$progId])[0];
            if($prog->getStartingdate() < $programme->getEndingdate() and $prog->getEndingdate() > $programme->getStartingdate() )
                return true;
        }
        return false;
    }
    /**
     * @Route("/addReservation")
     * @RequestParam(name="CNP")
     * @RequestParam(name="Name")
     */
    public function addReservation(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cnp = $fetcher->get('CNP');
        $user = $entityManager->getRepository(Users::class)->findBy(['cnp'=>$cnp])[0];
        if($user!=null)
        {
            $reservation = new Reservation();
            $name = $fetcher->get('Name');
            $programme = $entityManager->getRepository(Programmes::class)->findBy(['name'=>$name])[0];
            $reservation->setProgrammeid($programme);
            $reservation->setUserid($user);
            if($this->hasOverlappingReservation($user,$reservation))
                return $this->view("Reservation overlaps", Response::HTTP_BAD_REQUEST);
            if($this->isProgrammeFull($programme))
                return $this->view("Programme full",Response::HTTP_BAD_REQUEST);
            $entityManager->persist($reservation);
            $entityManager->flush();
            return $this->view("Reservation added", Response::HTTP_ACCEPTED);
        }
        return $this->view("User not found, CNP is incorrect",Response::HTTP_BAD_REQUEST);
    }
    /**
     * @Route("/removeReservation")
     * @RequestParam(name="CNP")
     * @RequestParam(name="Name")
     */
    public function removeReservation(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cnp = $fetcher->get('CNP');
        $user = $entityManager->getRepository(Users::class)->findBy(['cnp'=>$cnp])[0];
        if($user != null)
        {
            $reservation = null;
            $name = $fetcher->get('Name');
            $userID = $user->getId();
            $programme = $entityManager->getRepository(Programmes::class)->findBy(["name"=>$name])[0];
            $userReservations = $entityManager->getRepository(Reservation::class)->findBy(["userid"=>$userID]);
            foreach($userReservations as $res)
            {
                $programmeId =  $programme->getId();
                $resID = $res->getProgrammeid()->getId();
                if($resID == $programmeId)
                    $reservation = $res;
            }
            if($reservation == null)
                return $this->view("Could not find reservation", Response::HTTP_BAD_REQUEST);
            $entityManager->remove($reservation);
            $entityManager->flush();
            return $this->view("Succesfully deleted reservation", Response::HTTP_ACCEPTED);
        }
        return $this->view("Could not delete reservation", Response::HTTP_BAD_REQUEST);
    }
}
