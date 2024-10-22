<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// injection des différentes entitées que je vue xcontroller
use App\Entity\Competence;
use App\Entity\Endroit;
use App\Entity\Formations;
use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        //return parent::index();
        // je récupère la pseudo d'un user
        $admin = $this->getUser()->getPseudo();
        // je récupère le tableau contenant toutes les formations en cours
        $formations = $this->getDoctrine()->getRepository(Formations::class)->getCurrentFormation();

        return $this->render('easyadmin/index.html.twig', [
            'admin' => $admin,
            'formations' => $formations,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tableau de bord');
    }

    // configuration du menu principal
    public function configureMenuItems(): iterable
    {
        // retour sur la page accueil
        yield MenuItem::section('RETOUR MENU');
        yield MenuItem::linkToRoute('Retour accueil', 'far fa-calendar-alt', 'home');

        // le menu general
        yield MenuItem::section('GESTION DES DONNEES');
        // la gestion du menu avec les entitées relièes
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Gérer les compétences', 'fas fa-code', Competence::class);
        yield MenuItem::linkToCrud('Gérer les endroits', 'fas fa-city', Endroit::class);
        yield MenuItem::linkToCrud('Gérer les formations', 'fas fa-graduation-cap', Formations::class);
        yield MenuItem::linkToCrud('Gérer les utilisateurs', 'fas fa-users', User::class);

        // la deconnexion
        yield MenuItem::section('DECONNEXION');
        yield MenuItem::linkToLogout('Logout', 'fas fa-times');
    }
}
