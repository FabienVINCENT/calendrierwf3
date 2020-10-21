<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfilType;
use Symfony\App\Controller\Api\ApiUserController;

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


    public function __construct(EntityManagerInterface $entityManager, UserRepository $UserRepository) {
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

    // /**
    // * @Route("/edit", name="edit")
    // */
    // public function edit(Request $request, $id)
    // {
        
    // 	$this->repo->findOneById($id);
 	// 	$form = $this->createForm(ProfilType::class, $formateur);
 	// 	$form->handleRequest($request);

 	// 	if ($form->isSubmitted()) {

    // 		$this->em->flush();
 	// 		return $this->redirectToRoute('profil_index');
    //      }
         
    // 	return $this->render('profil/index.html.twig', [
    // 		'formateur' => $formateur,
    // 		'form' => $form->createView() ]);
    // }
   

}
