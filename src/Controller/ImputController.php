<?php

namespace App\Controller;

use App\Entity\Imput;
use App\Form\ImputType;
use App\Repository\DateVRepository;
use App\Repository\ImputRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CodeProjetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;

/**
 * @Route("/imput")
 */
class ImputController extends AbstractController
{
    /**
     * @Route("/", name="imput_index", methods={"GET"})
     */
    public function index(CodeProjetRepository $codeProjetRepository, ImputRepository $imputRepository, UserRepository $userRepository, DateVRepository $dateVRepository): Response
    {

        $imputation = [];
        $dateVs = $dateVRepository->findAll();
        foreach ($dateVs as $dateV) {
            $imputation[] = [
                'user' => $dateV->getImput()->getUser()->getId(),
                'tache' => $dateV->getTache()->getId(),
                'date' => $dateV->getDate(),
                'valeur' => $dateV->getValeur(),
            ];
        }
        $codeP = $codeProjetRepository->findAll();
        //DEBUT calcule des chiifre d'$imputation
        $somme = 0;
        $somme =
            //FIN de calcule des chiffre d'imputation

            $data = json_encode($imputation);

        return $this->render('imput/index.html.twig', [
            'datas' => $data,
            'imputs' => $imputRepository->findAll(),
            'users' => $userRepository->findAll(),
            'dateVs' => $dateVRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="imput_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $imput = new Imput();
        $imput->setUser($this->container->get('security.token_storage')->getToken()->getUser());
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
        if ($this->isCsrfTokenValid('delete' . $imput->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($imput);
            $entityManager->flush();
        }

        return $this->redirectToRoute('imput_index');
    }
    public function ajaxAction(Request $request, DateVRepository $dateVRepository)
    {
     
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            /*  $jsonData = array();
            $idx = 0;
            foreach ($imputs as $imput) {
                $temp = array(
                    'tache' => $imput->getTache(),
                    'user' => $imput->getUser(),
                    'dateVs' => $imput->getDateVs(),
                );
                $jsonData[$idx++] = $temp;$imputs = $this->getDoctrine()
            ->getRepository('App:Imput')
            ->findAll();
            }*/

            $imputation = [];
            $dateVs = $dateVRepository->findAll();
            foreach ($dateVs as $dateV) {
                //Numero de semaine
                $dmy = $dateV->getDate()->format('d-m-Y');
                $week = "W" . date("W", strtotime($dmy));

                $imputation[] = [
                    'user' => $dateV->getImput()->getUser()->getId(),
                    'tache' => $dateV->getTache()->getLibelle(),
                    'commentaire' =>  $dateV->getImput()->getCommentaire(),
                    'week' => $week,
                    'codeprojet' => $dateV->getTache()->getCodeprojet()->getLibelle(),
                    'date' => $dateV->getDate(),
                    'valeur' => $dateV->getValeur(),
                ];
            }

            $data = json_encode($imputation);

            return new JsonResponse($data);
        } else {

            return $this->render('imput/index.html.twig');
        }
    }
}
