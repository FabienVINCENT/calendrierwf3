<?php

namespace App\Controller;

use App\Entity\Animer;
use App\Entity\User;
use App\Form\AnimerType;
use App\Repository\FormationsRepository;
use App\Repository\UserRepository;
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
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        FormationsRepository $formationsRepository
    ) {
        $animer = new Animer();
        $form = $this->createForm(AnimerType::class, $animer);
        $form->handleRequest($request);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'formateurs' => $userRepository->getFormateurs(),
            'formations' => $formationsRepository->getListFormationDate()
        ]);
    }
    /**
     * @Route("/formateur/{id}", name="home_formateur")
     */
    public function formateur(User $user, UserRepository $repo)
    {
        return $this->render('home/formateur.html.twig', [
            'formateur' => $user,
            'listFormateur' => $repo->getFormateurs()
        ]);
    }
}
