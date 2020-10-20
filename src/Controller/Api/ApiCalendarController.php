<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnimerRepository;
use Symfony\Component\HttpFoundation\Response;

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
    		$allDay= false;
    		
    		switch ($typeJournee) {

	    		case '0':
	    			$start->setTime(8,0);
	    			$end->setTime(17,0);
	    			$allDay= true;
	    			break;

				case '1':
					$start->setTime(8,0);
	    			$end->setTime(12,0);
					break;

				case '2':
					$start->setTime(14,0);
	    			$end->setTime(17,0);
					break;
    		}

    		$data[] = [
    	   		
    	   		'start' => $start,
    	   		'end' => $end,
    	   		'title' => $animer->getFkAnimerFormation()->getNom().'/'.$animer->getFkAnimerFormation()->getLocalisation()->getVille(),
    	   		'description' =>$animer->getFkAnimerUser()->getPseudo(),
    	   		'allDay'=>$allDay,	
    	   	];
    	}

        return $this->json($data);
    }
}
