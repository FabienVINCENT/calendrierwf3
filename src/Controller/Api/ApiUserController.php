<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;

class ApiUserController extends AbstractController
{

    /**
     * @Route("/api/user/listFormateur", name="listUser", methods={"GET","POST"}, format="json", options={"expose"=true})
     */
    public function listUser(UserRepository $userRepository)
    {
        return $this->json($userRepository->getInfosUser());
    }
    /**
     * @Route("/user/{id}", name="getUser", methods={"GET"}, format="json")
     */
    public function getInfoUser(User $user, UserRepository $userRepository)
    {
        return $this->json($userRepository->getInfosUserById($user->getId()));
    }
}
