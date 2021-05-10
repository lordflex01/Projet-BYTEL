<?php

namespace App\Controller;

use App\Entity\Imput;
use App\Form\ImputType;
use App\Repository\ImputRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/imput")
 */
class ImputController extends AbstractController
{
    /**
     * @Route("/", name="imput_index", methods={"GET"})
     */
    public function index(ImputRepository $imputRepository): Response
    {
        return $this->render('imput/index.html.twig', [
            'imputs' => $imputRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="imput_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $imput = new Imput();
        $form = $this->createForm(ImputType::class, $imput);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($imput);
            $entityManager->flush();

            return $this->redirectToRoute('imput_index');
        }

        return $this->render('imput/new.html.twig', [
            'imput' => $imput,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="imput_show", methods={"GET"})
     */
    public function show(Imput $imput): Response
    {
        return $this->render('imput/show.html.twig', [
            'imput' => $imput,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="imput_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Imput $imput): Response
    {
        $form = $this->createForm(ImputType::class, $imput);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('imput_index');
        }

        return $this->render('imput/edit.html.twig', [
            'imput' => $imput,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="imput_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Imput $imput): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imput->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($imput);
            $entityManager->flush();
        }

        return $this->redirectToRoute('imput_index');
    }
}
