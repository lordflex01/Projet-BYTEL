<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class userController extends AbstractController
{
    /**
     * @Route("/user");
     */

    public function user(){

        return $this->render('user.html.twig');

    }
}