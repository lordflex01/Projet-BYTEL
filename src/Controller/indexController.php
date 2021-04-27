<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CodeProjetRepository;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;

class indexController extends AbstractController
{
    /**
     * @Route("/index");
     */

    public function index(ProjetRepository $projetRepository, CodeProjetRepository $codeProjetRepository, UserRepository $userRepository)
    {

        return $this->render('index.html.twig', [
            'projets' => $projetRepository->findAll(),
            'code_projets' => $codeProjetRepository->findAll(),
            'users' => $userRepository->findAll(),
        ]);
    }
}
