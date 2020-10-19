<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');        
    }

     /**
     * @Route("/boutons", name="home_boutons")
     */
    public function boutons()
    {
        return $this->render('home/boutons.html.twig');
    }
}
