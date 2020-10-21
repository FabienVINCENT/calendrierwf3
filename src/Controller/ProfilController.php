<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfilType;

/**
 * @Route("/profil", name="profil_")
 */

class ProfilController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $repo;


    public function __construct(EntityManagerInterface $entityManager, UserRepository $UserRepository)
    {
        $this->em = $entityManager;
        $this->repo = $UserRepository;
    }


    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        // $view = $this->getUser();
        // dd($view);

        return $this->render('profil/index.html.twig', [
            'formateurs' => $this->getUser()
        ]);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->em->flush();
            return $this->redirectToRoute('profil_index');
        }

        return $this->render('profil/edit.html.twig', [
            'formateurs' => $user,
            'form' => $form->createView()
        ]);
    }
}
