<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\JsRoutingBundle\FOSJsRoutingBundle;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", options = { "expose" = true })
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
