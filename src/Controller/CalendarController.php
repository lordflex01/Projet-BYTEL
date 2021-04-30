<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ImputationRepository;
use App\Entity\Imputation;
use App\Form\ImputationType;
use Laminas\EventManager\Event;
use Symfony\Component\Console\Color;
use Symfony\Component\VarDumper\Cloner\Data;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar");
     */

    public function calendar(ImputationRepository $imputationRepository)
    {
        $imputation = [];
        $color = array('#f39c12', '#f56954', '#0073b7', '#00c0ef', '#00a65a');
        $i = 0;
        $events = $imputationRepository->findAll();
        foreach ($events as $event) {
            if ($i == 5)
                $i = 0;

            $title = $event->getUser()->getUsername() . ' ' . '[' . $event->getCodeprojet()->getProjet()->getLibelle() . '] ' . $event->getCodeprojet()->getLibelle() . ': ' . $event->getCommentaire();
            $imputation[] = [
                'id' => $event->getId(),
                'start' => $event->getDateD()->format('Y-m-d H:i:s'),
                'end' => $event->getDateF()->format('Y-m-d H:i:s'),
                'title' => $title,
                'backgroundColor' => $color[$i],
                'borderColor' => $color[$i],
            ];
            $i = $i + 1;
        }

        $data = json_encode($imputation);

        return $this->render('planification/calendar.html.twig', compact('data'));
    }
}
