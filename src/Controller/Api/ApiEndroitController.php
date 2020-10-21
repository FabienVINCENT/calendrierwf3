<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Endroit;
use App\Repository\EndroitRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_FORMATEUR")
 */
class ApiEndroitController extends AbstractController
{
    /**
     * @Route("/api/endroit", name="api_endroit")
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
}
