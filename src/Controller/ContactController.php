<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

/** 
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
     /**
     * @Route("/", name="contact_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('contact.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
