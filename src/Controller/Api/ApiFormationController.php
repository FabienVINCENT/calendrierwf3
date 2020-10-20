<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formations;
use App\Repository\FormationsRepository;

class ApiFormationController extends AbstractController
{
    /**
     * @Route("/api/formation", name="api_formation")
     */
    public function index()
    {
        dd('coucou');
    }

    /**
     * @Route("/formation/listFormation", name="listFormation", methods={"GET","POST"}, format="json")
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
}
