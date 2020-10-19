<?php

namespace App\Controller;

use App\Entity\Animer;
use App\Form\AnimerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/boutons", name="home_boutons")
     */
    public function boutons()
    {
        return $this->render('home/boutons.html.twig');
    }
}
