<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/user", name="api_user")
     */
    public function index()
    {
      dd('coucou');
    }

     /**
     * @Route("/user/listUser", name="listUser", methods={"GET"}, format="json")
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
