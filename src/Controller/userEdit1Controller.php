<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class userEdit1Controller extends AbstractController
{
    /**
     * @Route("/userEdit1");
     */

    public function userEdit1()
    {

        return $this->render('userEdit1.html.twig');
    }
}
