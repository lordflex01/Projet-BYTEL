<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class user1Controller extends AbstractController
{
    /**
     * @Route("/user1");
     */

    public function user()
    {

        return $this->render('user.html.twig');
    }
}
