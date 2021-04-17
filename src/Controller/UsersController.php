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

    private function calculateHash($cnp)
    {
        $controlKey = [2, 7, 9, 1, 4, 6, 3, 5, 8, 2, 7, 9];
        $hashSum = 0;

        for ($i = 0; $i < 12; $i++) {
            $hashSum += $cnp[$i] * $controlKey[$i];
        }

        $hashSum = $hashSum % 11;
        if ($hashSum == 10) {
            $hashSum = 1;
        }

        return $hashSum;
    }
    public function isCNPValid(String $cnp)
    {
        $len = strlen($cnp);
        if($len != 13)
            return false;
        if(!ctype_digit($cnp))
            return false;
        $month = $cnp[3].$cnp[4];
        $day = $cnp[4].$cnp[5];
        $country = $cnp[7].$cnp[8];
        $year = ($cnp[1] * 10) + $cnp[2];
        switch ($cnp[0]) {
            // romanian citizen born between 1900.01.01 and 1999.12.31
            case 1 :
            case 2 :
                $year = $year + 1900;
                break;
            // romanian citizen born between 1800.01.01 and 1899.12.31
            case 3 :
            case 4 :
                $year = $year + 1800;
                break;
            // romanian citizen born between 2000.01.01 and 2099.12.31
            case 5 :
            case 6 :
                $year = $year + 2000;
                break;
            // residents && people with foreign citizenship
            case 7 :
            case 8 :
            case 9 :
                $year = $year + 2000;
                if ($this->$year > (int)date('Y') - 14) {
                    $this->$year -= 100;
                }
                break;
            default :
                $year = 0;
        }
        return $cnp[12] == $this->calculateHash($cnp);

    }
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
            if(!$this->isCNPValid($cnp))
                return $this->view("CNP is invalid", Response::HTTP_BAD_REQUEST);
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
