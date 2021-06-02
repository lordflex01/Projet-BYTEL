<?php

namespace App\Controller;

use App\Entity\Imput;
use App\Entity\DateV;
use App\Entity\Taches;
use App\Entity\User;
use App\Form\ImputType;
use App\Repository\DateVRepository;
use App\Repository\ImputRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CodeProjetRepository;
use App\Repository\TachesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use DateTime;

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

        $data = json_encode($imputation);

        return $this->render('imput/index.html.twig', [
            'datas' => $data,
            'imputs' => $imputRepository->findAll(),
            'users' => $userRepository->findAll(),
            'dateVs' => $dateVRepository->findAll(),
            'code_projets' => $codeProjetRepository->findAll(),
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
     * @Route("/apii/new", name="api_new", methods={"GET","POST"})
     */
    public function apinew(TachesRepository $tachesrepository, UserRepository $userRepository, Request $request)
    {

        //on recupère les données
        $donnees = json_decode($request->getContent());
        //Declaration
        $userlistes = $userRepository->findAll();
        $tachelistes = $tachesrepository->findAll();
        $imput = new Imput;
        $tache = new Taches;
        $user = new User;
        //connaitre le user
        foreach ($userlistes as $userliste) {
            if ($donnees->user == $userliste->getId())
                $user = $userliste;
        }
        //connaitre la tache
        foreach ($tachelistes as $tacheliste) {
            if ($donnees->tache == $tacheliste->getId())
                $tache = $tacheliste;
        }
        //création de l'imput
        $imput->setUser($user);
        $imput->setCommentaire($donnees->Commentaires);

        $em = $this->getDoctrine()->getManager();
        $em->persist($imput);
        //Création des donnée
        for ($i = 0; $i < 5; $i++) {
            $dateV = new DateV;
            //on hydrate l'objet avec les données
            $dateV->setImput($imput);
            $dateV->setValeur($donnees->valeur[$i]);
            $dateV->setDate(new DateTime($donnees->date[$i]));
            $dateV->setTache($tache);

            $em->persist($dateV);
        }

        $em->flush();

        $code = 200;
        return new Response('Imputation confirmé');
        //return $this->redirectToRoute('imput_index');
    }

    /**
     * @Route("/apii/edit", name="api_imput_edit", methods={"PUT"})
     */
    public function apiedit(TachesRepository $tachesrepository, DateVRepository $dateVRepository, Request $request)
    {

        //on recupère les données
        $donnees = json_decode($request->getContent());

        //Declaration
        $dateVlistes = $dateVRepository->findAll();
        $i = 0;
        //Bool pour voir si on a changer deja le commantaire
        $bool = 0;
        $em = $this->getDoctrine()->getManager();
        //Debut du traitement
        foreach ($dateVlistes as $dateVliste) {
            $dateV = $dateVliste;
            //editer la date valeur
            if ($donnees->imputID == $dateVliste->getImput()->getId() && $donnees->valeur[$i] != $dateVliste->getValeur()) {
                $dateV = new DateV;
                $dateV = $dateVliste;
                $dateV->setValeur($donnees->valeur[$i]);
                $em->persist($dateV);
            }
            //edit le commenataire
            if ($donnees->imputID == $dateVliste->getImput()->getId() && $donnees->Commentaires != $dateVliste->getImput()->getCommentaire() && $bool == 0) {
                $imput = new Imput;
                $imput = $dateV->getImput();
                $imput->setCommentaire($donnees->Commentaires);
                $em->persist($imput);
                $bool = 1;
            }
            $i++;
            if ($i == 5)
                $i = 0;
        }
        $em->flush();
        return new Response('Modification confirmé');
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
    public function ajaxAction(TachesRepository $tachesRepository, Request $request, DateVRepository $dateVRepository)
    {
        $imputs = $this->getDoctrine()
            ->getRepository('App:Imput')
            ->findAll();
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            /*  $jsonData = array();
            $idx = 0;
            foreach ($imputs as $imput) {
                $temp = array(
                    'tache' => $imput->getTache(),
                    'user' => $imput->getUser(),
                    'dateVs' => $imput->getDateVs(),
                );
                $jsonData[$idx++] = $temp;
            }*/

            $tacheliste = [];
            $tache = $tachesRepository->findAll();
            foreach ($tache as $taches) {
                $tacheliste[] = [
                    'id' => $taches->getId(),
                    'libelle' => $taches->getLibelle(),
                ];
            }
            $imputation = [];
            $dateVs = $dateVRepository->findAll();
            foreach ($dateVs as $dateV) {
                //Numero de semaine
                $dmy = $dateV->getDate()->format('d-m-Y');
                $week = "W" . date("W", strtotime($dmy));

                $imputation[] = [
                    'imputID' => $dateV->getImput()->getId(),
                    'user' => $dateV->getImput()->getUser()->getId(),
                    'tache' => $dateV->getTache()->getLibelle(),
                    'commentaire' =>  $dateV->getImput()->getCommentaire(),
                    'week' => $week,
                    'codeprojet' => $dateV->getTache()->getCodeprojet()->getLibelle(),
                    'date' => $dateV->getDate(),
                    'valeur' => $dateV->getValeur(),
                    'tacheliste' => $tacheliste,
                ];
            }

            $data = json_encode($imputation);

            return new JsonResponse($data);
        } else {

            return $this->render('imput/index.html.twig');
        }
    }
}
