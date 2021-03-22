<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class userEditController extends AbstractController
{
    /**
     * @Route("/userEdit");
     */

    public function userEdit(){

        return $this->render('userEdit.html.twig');

    }
}