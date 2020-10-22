<?php

namespace App\Controller;

use App\Entity\Animer;
use App\Form\AnimerType;
use App\Entity\Formations;
use App\Entity\User;
use App\Repository\AnimerRepository;
use App\Repository\FormationsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/", name="api_")
 * @IsGranted("ROLE_FORMATEUR")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("formation/listnotended", name="listNotEndedFormation", methods={"GET","POST"}, format="json")
     * Route qui affiche les formations sur l'accueil
     */
    public function listNotEndedFormation(FormationsRepository $formationRepo)
    {
        $formations = $formationRepo->getFormationEvent();
        $evenements = [];
        foreach ($formations as $formation) {
            $e['id'] = $formation->getId();
            $e['title'] = $formation->getNom();
            $e['start'] = $formation->getDateDebut();
            $e['end'] = $formation->getDateFin();
            $e['backgroundColor'] = $formation->getColor();
            $e['borderColor'] = $formation->getColor();

            $evenements[] = $e;
        }
        return $this->json($evenements);
    }

    /**
     * @Route("animer/{id}", name="animer_solo", methods={"GET"}, format="json")
     */
    public function getAnimerId(Formations $formation, AnimerRepository $repo)
    {
        $animers = $repo->findByFormationId($formation->getId());
        $data = [];
        foreach ($animers as $animer) {
            $start = clone $animer->getDate();
            $end = clone $animer->getDate();
            $typeJournee = $animer->getTypeJournee();
            $bgColor = $animer->getFkAnimerFormation()->getColor();
            $allDay = false;
            switch ($typeJournee) {
                case '0':
                    $start->setTime(Animer::DEBUT_MATINNEE, 0);
                    $end->setTime(Animer::FIN_APRESMIDI, 0);
                    $allDay = true;
                    break;
                case '1':
                    $start->setTime(Animer::DEBUT_MATINNEE, 0);
                    $end->setTime(Animer::FIN_MATINNEE, 0);
                    break;
                case '2':
                    $start->setTime(Animer::DEBUT_APRESMIDI, 0);
                    $end->setTime(Animer::FIN_APRESMIDI, 0);
                    break;
            }

            $data[] = [
                'id' => $animer->getId(),
                'start' => $start,
                'end' => $end,
                'title' => $animer->getFkAnimerFormation()->getNom() . '/' . $animer->getFkAnimerFormation()->getLocalisation()->getVille(),
                'description' => $animer->getFkAnimerUser()->getPseudo(),
                'idFormateur' => $animer->getFkAnimerUser()->getId(),
                'allDay' => $allDay,
                'backgroundColor' => $bgColor,
                'borderColor' => $bgColor,
                'editable' => true
            ];
        }
        return $this->json($data);
    }
    /**
     * @Route("addAnimer", name="addAnimer", methods={"POST"})
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

            $dateDebut = $animer->getFkAnimerFormation()->getDateDebut();
            $dateFin = $animer->getFkAnimerFormation()->getDateFin();
            $dateSasie = $animer->getDate();
            if ($dateDebut > $dateSasie) {
                return $this->json(['error' => 'La date de début est postérieure à la date choisie.<br>
                La formation ' . $animer->getFkAnimerFormation()->getNom() . ' commence le ' . $dateDebut->format('d/m/Y') . ' et fini le ' . $dateFin->format('d/m/Y')]);
            }
            if ($dateFin < $dateSasie) {
                return $this->json(['error' => 'La date de fin est antérieure à la date choisie.']);
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
     * @Route("formateur/listAnimer/{id}", name="listAnimer", methods={"GET"})
     * recupération planning formateurs
     */
    public function listAnimer(User $user, UserRepository $repo)
    {
        $animers = $user->getAnimers();
        // $animers = $repo->getByFormateurIdEvent($id);
        $data = [];
        foreach ($animers as $animer) {
            $start = clone $animer->getDate();
            $end = clone $animer->getDate();
            $typeJournee = $animer->getTypeJournee();
            $bgColor = $animer->getFkAnimerFormation()->getColor();
            $allDay = false;
            switch ($typeJournee) {
                case '0':
                    $start->setTime(Animer::DEBUT_MATINNEE, 0);
                    $end->setTime(Animer::FIN_APRESMIDI, 0);
                    $allDay = true;
                    break;
                case '1':
                    $start->setTime(Animer::DEBUT_MATINNEE, 0);
                    $end->setTime(Animer::FIN_MATINNEE, 0);
                    break;
                case '2':
                    $start->setTime(Animer::DEBUT_APRESMIDI, 0);
                    $end->setTime(Animer::FIN_APRESMIDI, 0);
                    break;
            }

            $data[] = [
                'id' => $animer->getId(),
                'start' => $start,
                'end' => $end,
                'title' => $animer->getFkAnimerFormation()->getNom() . '/' . $animer->getFkAnimerFormation()->getLocalisation()->getVille(),
                'description' => $animer->getFkAnimerUser()->getPseudo(),
                'allDay' => $allDay,
                'backgroundColor' => $bgColor,
                'borderColor' => $bgColor,
            ];
        }
        return $this->json($data);
    }

    /**
     * @Route("deleteAnimer/{id}", name="deleteAnimer", methods={"GET"})
     */
    public function deleteAnimer(EntityManagerInterface $em, Animer $animer)
    {
        try {
            $em->remove($animer);
            $em->flush();
            return $this->json(true);
        } catch (\Exception $e) {
            return $this->json(false);
        }
    }

    /**
     * @Route("editAnimer/{id}", name="editAnimer", methods={"POST"})
     * Gestion edit drag&drop
     */
    public function editAnimer(EntityManagerInterface $em, Animer $animer, Request $request)
    {
        try {
            $dateStr = json_decode($request->getContent());
            $date =  new \DateTime($dateStr);
            $animer->setDate($date);

            $em->persist($animer);
            $em->flush();


            return $this->json(true);
        } catch (\Exception $e) {
            return $this->json($e);
        }
    }
}
