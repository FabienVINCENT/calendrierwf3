<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\FormationsController;
use App\Controller\EndroitController;
use App\Entity\Formations;
use App\Entity\Endroit;
use App\Repository\EndroitRepository;
use App\Repository\FormationsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/toto", name="endroit_list", methods={"GET"}, format="json")
     */
    public function villeList(EndroitRepository $endroitRepo)
    
    {
        $villes = $endroitRepo->findAll();
        $liste = [];
        foreach($villes as $ville){
            $liste[] = 
            [
                'id' => $ville->getId(),
                'ville' => $ville->getVille(),
            ];
        }
        $data = json_encode($liste);
        return $this->render('test/index.html.twig', compact('data'));
    }

    // /**
    // * @Route("/", name="formation_list", methods={"GET"}, format="json")
    // */
    // public function formationList(FormationsRepository $formRepo)
    
    // {
    // $formRepo = $this->getDoctrine()->getRepository(Formations::class);
    // $reponse = $formRepo->findAll();

    // dd ($reponse);
    // }
}


    // /**
    //  * @Route("/formations/getall", name="formations")
    //  */ 
    // public function formationList()
    // {
    //     $req = $bdd->prepare('SELECT nom FROM formations');
    //     $reponse = $req->execute() or die('Erreur');
    //     while($ligne = $reponse->fetch(PDO::FETCH_ASSOC))
    //     {
    //         $data []= $ligne;
    //     }
    //     $encode_donnees = json_encode($data);
    // }
 

    // /**
    //  * @Route("/endroit/getall", name="ville")
    //  */
    // public function villeList()
    // {
    //     $req = $bdd->prepare('SELECT ville FROM endroit');
    //     $reponse = $req->execute() or die('Erreur');
    //     while($ligne = $reponse->fetch(PDO::FETCH_ASSOC))
    //     {
    //         $data []= $ligne;
    //     }
    //     $encode_donnees = json_encode($data);
    // ]



