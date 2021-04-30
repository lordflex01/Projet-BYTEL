<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImpCalendarController extends AbstractController
{
    /**
     * @Route("/imputationCalendar");
     */

    public function impCalendar()
    {

        return $this->render('imputation/imputationCalendar.html.twig');
    }
}
