<?php

namespace App\Controller;

use App\Entity\Animer;
use App\Form\AnimerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_FORMATEUR")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $animer = new Animer();
        $form = $this->createForm(AnimerType::class, $animer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($animer);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/addAnimer", name="addAnimer", methods={"POST"})
     */
    public function ajoutAnimer(Request $request, EntityManagerInterface $em)
    {
        $animer = new Animer();
        $form = $this->createForm(AnimerType::class, $animer);
        $form->handleRequest($request);



        if ($form->isSubmitted()) {
            if (null == $animer->getFkAnimerFormation()) {
                return $this->json(['error' => 'La formation ne peut pas etre vide']);
            }
            if (null == $animer->getFkAnimerUser()) {
                return $this->json(['error' => 'Le formateur ne peut pas etre vide']);
            }
            if (null == $animer->getDate()) {
                return $this->json(['error' => 'Le formateur ne peut pas etre vide']);
            }

            try {
                $em->persist($animer);
                $em->flush();
                return $this->json(true);
            } catch (\Throwable $th) {
                return $this->json(['error' => 'Une erreur est survenue...']);
            }
        }

        return $this->json(['error' => 'Une erreur est survenue...']);
    }

    /**
     * @Route("/boutons", name="home_boutons")
     */
    public function boutons()
    {
        return $this->render('home/boutons.html.twig');
    }
}
