<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use App\Form\ProfilType;
use App\Form\ChangePasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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

    /**
     * @Route("/resetpass", name="resetpass")
     */
    public function resetPassword(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            // $passwordEncoder = $this->get('security.password_encoder');
            // dump($request->request);die();
            $oldPassword = $request->request->get('change_password')['oldPassword'];

            //Si ancien mot de passe vérifié =>
            if($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($newEncodedPassword);

                $em->persist($user);
                $em->flush();

                $this->addFlash('notice','Votre mot de passe a bien été changé !');

                return $this->redirectToRoute('profil_index');
            
            // Si échec de la vérification de l'ancien mot de passe =>
            } else {
                $form->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }
        return $this->render('profil\resetPassword.html.twig', array(
            'form'=>$form->createView(),
        ));
    }
}
