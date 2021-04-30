<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ImputationRepository;
use App\Repository\UserRepository;

class ImpCalendarController extends AbstractController
{
    /**
     * @Route("/imputationCalendar");
     */

    public function impCalendar(ImputationRepository $imputationRepository, UserRepository $userRepository): Response
    {
        $imputation = [];
        $users = $userRepository->findAll();
        $events = $imputationRepository->findAll();
        foreach ($events as $event) {

            $title = $event->getUser()->getUsername() . ' ' . '[' . $event->getCodeprojet()->getProjet()->getLibelle() . '] ' . $event->getCodeprojet()->getLibelle() . ': ' . $event->getCommentaire();
            $imputation[] = [
                'id' => $event->getId(),
                'resourceId' => $event->getUser()->getId(),
                'start' => $event->getDateD()->format('Y-m-d H:i:s'),
                'end' => $event->getDateF()->format('Y-m-d H:i:s'),
                'title' => $title,

            ];
        }
        //LISTE DES UTILISATEUR DANS LE CALENDAR
        //$color = array('#f39c12', '#f56954', '#0073b7', '#00c0ef', '#00a65a');
        $color = array('green', 'orange', 'red', 'blue', 'yellow');
        $j = 0;
        foreach ($users as $user) {
            if ($j > 4)
                $j = 0;

            $listuser[] = [
                'id' => $user->getId(),
                'title' => $user->getUsername(),
                'eventColor' => $color[$j],
            ];
            $j = $j + 1;
        }

        $data = json_encode($imputation);
        $use = json_encode($listuser);


        return $this->render('imputation/imputationCalendar.html.twig', [
            'datas' => $data,
            'uses' => $use,

        ]);
    }
}
