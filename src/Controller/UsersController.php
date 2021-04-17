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

class UsersController extends AbstractFOSRestController
{

    /**
     * @Route("/createUser")
     * @RequestParam(name="CNP")
    */
    public function createUser(ParamFetcher $fetcher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new Users();
        try {
            $cnp = $fetcher->get('CNP');
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

}
