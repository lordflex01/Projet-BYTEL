<?php

namespace App\Controller;

use App\Entity\Imputation;
use App\Form\ImputationType;
use App\Repository\ImputationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** 
 * @Route("/imputation")
 */
class ImputationController extends AbstractController
{
    /**
     * @Route("/", name="imputation_index", methods={"GET"})
     */
    public function index(ImputationRepository $imputationRepository): Response
    {
        return $this->render('imputation/index.html.twig', [
            'imputations' => $imputationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="imputation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $imputation = new Imputation();
        $imputation->setUser($this->container->get('security.token_storage')->getToken()->getUser());
        $form = $this->createForm(ImputationType::class, $imputation);
        $form->handleRequest($request);
        // Définir le nouveau fuseau horaire
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d h:i:s');

        if ($form->isSubmitted() && $form->isValid()) {
            $imputation->setTime($date);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($imputation);
            $entityManager->flush();

            return $this->redirectToRoute('imputation_index');
        }

        return $this->render('imputation/new.html.twig', [
            'imputation' => $imputation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="imputation_show", methods={"GET"})
     */
    public function show(Imputation $imputation): Response
    {
        return $this->render('imputation/show.html.twig', [
            'imputation' => $imputation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="imputation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Imputation $imputation): Response
    {
        $form = $this->createForm(ImputationType::class, $imputation);
        $form->handleRequest($request);
        // Définir le nouveau fuseau horaire
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d h:i:s');

        if ($form->isSubmitted() && $form->isValid()) {
            $imputation->setTime($date);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('imputation_index');
        }

        return $this->render('imputation/edit.html.twig', [
            'imputation' => $imputation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="imputation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Imputation $imputation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $imputation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($imputation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('imputation_index');
    }
}
