<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formations;
use App\Repository\FormationsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_FORMATEUR")
 */
class ApiFormationController extends AbstractController
{

    /**
     * @Route("/api/formation/listFormation", name="listFormation", methods={"GET","POST"}, format="json")
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
     * @Route("/api/formation/listnotended", name="listNotEndedFormation", methods={"GET","POST"}, format="json")
     */
    public function listNotEndedFormation(FormationsRepository $formationRepo)
    {
        $formations = $formationRepo->getFormationEvent();
        $evenements = [];
        foreach ($formations as $formation) {
            $e['title'] = $formation->getNom();
            $e['start'] = $formation->getDateDebut();
            $e['end'] = $formation->getDateFin();

            $evenements[] = $e;
        }
        return $this->json($evenements);
    }
}
