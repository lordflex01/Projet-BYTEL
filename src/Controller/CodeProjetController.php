<?php

namespace App\Controller;

use App\Entity\CodeProjet;
use App\Form\CodeProjetType;
use App\Repository\CodeProjetRepository;
use App\Repository\TachesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/code/projet")
 */
class CodeProjetController extends AbstractController
{
    /**
     * @Route("/", name="code_projet_index", methods={"GET"})
     */
    public function index(CodeProjetRepository $codeProjetRepository): Response
    {
        return $this->render('code_projet/index.html.twig', [
            'code_projets' => $codeProjetRepository->findAll(),
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="code_projet_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $codeProjet = new CodeProjet();
        $form = $this->createForm(CodeProjetType::class, $codeProjet);
        $form->handleRequest($request);
        $codeProjet->setBudgetConsomme(0);
        $codeProjet->setBudgetNRJConsomme(0);
        $codeProjet->setBudgetDECOConsomme(0);
        $codeProjet->setBudgetCLOEConsomme(0);
        $codeProjet->setChargeConsomme(0);
        $codeProjet->setChargeNRJConsomme(0);
        $codeProjet->setChargeDECOConsomme(0);
        $codeProjet->setChargeCLOEConsomme(0);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($codeProjet);
            $entityManager->flush();

            return $this->redirectToRoute('code_projet_index');
        }

        return $this->render('code_projet/new.html.twig', [
            'code_projet' => $codeProjet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="code_projet_show", methods={"GET"})
     */
    public function show(TachesRepository $tachesRepository, CodeProjet $codeProjet): Response
    {
        return $this->render('code_projet/show.html.twig', [
            'code_projet' => $codeProjet,
            'taches' => $tachesRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="code_projet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CodeProjet $codeProjet): Response
    {
        $form = $this->createForm(CodeProjetType::class, $codeProjet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('code_projet_index');
        }

        return $this->render('code_projet/edit.html.twig', [
            'code_projet' => $codeProjet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="code_projet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CodeProjet $codeProjet): Response
    {
        if ($this->isCsrfTokenValid('delete' . $codeProjet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($codeProjet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('projet_index');
    }
}
