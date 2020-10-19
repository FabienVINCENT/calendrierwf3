<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Endroit;
use App\Entity\Formations;
use App\Repository\UserRepository;
use App\Repository\EndroitRepository;
use App\Repository\FormationsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        dd('coucou');
    }


    /**
     * @Route("/endroit/listVille", name="listVille", methods={"GET"}, format="json")
     */
    public function listVille(EndroitRepository $endroitRepo)
    {
        return $this->json($endroitRepo->getListVille());
    }
    /**
     * @Route("/endroit/{id}", name="getEndroit", methods={"GET"}, format="json")
     */
    public function getInfoEndroit(Endroit $endroit, EndroitRepository $endroitRepo)
    {
        return $this->json($endroitRepo->getListVilleById($endroit->getId()));
    }


    /**
     * @Route("/formation/listFormation", name="listFormation", methods={"GET"}, format="json")
     */
    public function listFormation(FormationsRepository $formationRepo)
    {
        return $this->json($formationRepo->getListFormation());
    }

    /**
     * @Route("/formation/{id}", name="getFormation", methods={"GET"}, format="json")
     */
    public function getInfoFormation(Formations $formation, FormationsRepository $formationRepo)
    {
        return $this->json($formationRepo->getListFormationById($formation->getId()));
    }

    /**
     * @Route("/user/listUser", name="listFormation", methods={"GET"}, format="json")
     */
    public function listUser(UserRepository $userRepository)
    {
        return $this->json($userRepository->getInfosUser());
    }
    /**
     * @Route("/user/{id}", name="getFormation", methods={"GET"}, format="json")
     */
    public function getInfoUser(User $user, UserRepository $userRepository)
    {
        return $this->json($userRepository->getInfosUserById($user->getId()));
    }
}
