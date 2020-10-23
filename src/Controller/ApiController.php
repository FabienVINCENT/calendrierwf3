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
     * Fonction qui affiche les formations sur l'accueil
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
     * Fonction qui affiche les crénaux lors une seule formation est sélectionné
     */
    public function getAnimerId(Formations $formation, AnimerRepository $repo)
    {
        $animers = $repo->findByFormationId($formation->getId());
        $data = [];
        foreach ($animers as $animer) {
            // On construit l'objet evenement comme l'attends fullcalendar
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
     * @Route("animerfull/", name="animer_full", methods={"POST"}, format="json")
     * Fonction qui affiche les crénaux lors plusieurs formations sont sélectionnés
     */
    public function getAnimerFull(AnimerRepository $repo, Request $request)
    {
        // On récupère quelles formations sont sélectionnées
        $animers = $repo->findByfkAnimerFormation(json_decode($request->getContent()));
        $data = [];
        foreach ($animers as $animer) {
            // On construit l'objet evenement comme l'attends fullcalendar
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
     * Fonction qui permet d'ajouter un crénaux
     */
    public function ajoutAnimer(Request $request, EntityManagerInterface $em)
    {
        $animer = new Animer();
        $form = $this->createForm(AnimerType::class, $animer);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // On fait des vérifications
            if (null == $animer->getFkAnimerFormation()) {
                return $this->json(['error' => 'La formation ne peut pas etre vide']);
            }
            if (null == $animer->getFkAnimerUser()) {
                return $this->json(['error' => 'Le formateur ne peut pas etre vide']);
            }
            if (null == $animer->getDate()) {
                return $this->json(['error' => 'Le formateur ne peut pas etre vide']);
            }
            // On vérifie que le crénau est a l'intérieur de la formation
            $dateDebut = $animer->getFkAnimerFormation()->getDateDebut();
            $dateFin = $animer->getFkAnimerFormation()->getDateFin();
            $dateSasie = $animer->getDate();
            if ($dateDebut > $dateSasie) {
                return $this->json(['error' => 'La date de début est postérieure à la date choisie.<br>
                La formation ' . $animer->getFkAnimerFormation()->getNom() . ' commence le ' . $dateDebut->format('d/m/Y') . ' et finit le ' . $dateFin->format('d/m/Y')]);
            }
            if ($dateFin < $dateSasie) {
                return $this->json(['error' => 'La date de fin est antérieure à la date choisie.']);
            }
            // Si les vérification sont ok on enregistre
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
        // On récupère les crénaux d'un utilisateurs
        $animers = $user->getAnimers();
        $data = [];
        foreach ($animers as $animer) {
            // On construit l'objet evenement comme l'attends fullcalendar
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
     * Permet la suppression d'un crénau
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


    /**
     * @Route("isDispo/", name="isDispo", methods={"POST"})
     * Gestion de la dispo dun formateur.
     */
    public function isDispo(EntityManagerInterface $em, AnimerRepository $repo, Request $request)
    {
        try {
            $infos = json_decode($request->getContent());
            $retour = $repo->isDispo($infos[1], $infos[0]);

            if (count($retour) > 0) {
                return $this->json(false);
            }
            return $this->json(true);
        } catch (\Exception $e) {
            return $this->json($e);
        }
    }
}
