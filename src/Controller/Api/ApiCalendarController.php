<?php

namespace App\Controller\Api;

use App\Entity\Animer;
use App\Entity\Formations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnimerRepository;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_FORMATEUR")
 */
class ApiCalendarController extends AbstractController
{
	/**
	 * @Route("/event_display", name="event_display", methods={"GET"})
	 */
	public function getEventDisplay(AnimerRepository $repo): Response
	{
		$animers = $repo->findAll();

		$data = [];

		foreach ($animers as $animer) {

			$start = clone $animer->getDate();
			$end = clone $animer->getDate();
			$typeJournee = $animer->getTypeJournee();
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
			];
		}

		return $this->json($data);
	}

	/**
	 * @Route("/api/animer/{id}", name="api_animer_solo", methods={"GET"})
	 */
	public function getAnimerId(Formations $formation, AnimerRepository $repo): Response
	{
		$animers = $repo->findByFormationId($formation->getId());
		dump($animers);
		$data = [];
		foreach ($animers as $animer) {
			$start = clone $animer->getDate();
			$end = clone $animer->getDate();
			$typeJournee = $animer->getTypeJournee();
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
			];
		}
		return $this->json($data);
	}
}
