<?php

namespace App\Controller;

use App\Entity\Imputation;
use App\Form\ImputationType;
use App\Repository\ImputationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use DateTime;

/** 
 * @Route("/imputation")
 */
class ImputationController extends AbstractController
{
    /**
     * @Route("/", name="imputation_index", methods={"GET"})
     */
    public function index(ImputationRepository $imputationRepository, UserRepository $userRepository): Response
    {
        $imputation = [];
        $users = $userRepository->findAll();
        $events = $imputationRepository->findAll();
        foreach ($events as $event) {

            $title = $event->getUser()->getUsername() . ' ' . '[' . $event->getCodeprojet()->getProjet()->getLibelle() . '] ' . $event->getCodeprojet()->getLibelle() . ': ' . $event->getCommentaire();
            $imputation[] = [
                'id' => $event->getId(),
                'resourceId' => $event->getUser()->getId(),
                'start' => $event->getDateD()->format('Y-m-d H:i:s'),
                'end' => $event->getDateF()->format('Y-m-d H:i:s'),
                'title' => $title,
                //'url' => $event->getId() . "/edit",
                //'Commantaire' => $event->getCommentaire(),
                // 'codeprojet' => $event->getCodeprojet(),
                //'user' => $event->getUser(),

            ];
        }
        //LISTE DES UTILISATEUR DANS LE CALENDAR
        //$color = array('#f39c12', '#f56954', '#0073b7', '#00c0ef', '#00a65a');
        $color = array('green', 'brown', 'red', 'blue', 'purple', 'teal');
        $j = 0;
        foreach ($users as $user) {
            if ($j > 5)
                $j = 0;

            $listuser[] = [
                'id' => $user->getId(),
                'title' => $user->getUsername(),
                'eventColor' => $color[$j],
            ];
            $j = $j + 1;
        }

        $data = json_encode($imputation);
        $use = json_encode($listuser);


        return $this->render('imputation/index.html.twig', [
            'datas' => $data,
            'uses' => $use,
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
        $date = date('Y-m-d H:i:s');

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
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Imputation $imputation, Request $request)
    {
        //on recupère les données
        $donnees = json_decode($request->getContent());

        if (
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) // &&
            //isset($donnees->resourceId) && !empty($donnees->resourceId) &&
            //isset($donnees->Commantaire) && !empty($donnees->Commantaire)
        ) {
            //les données sont complètes
            //on initialise un code
            $code = 200;

            //On vérifie si l'id existe
            if (!$imputation) {
                //on instancie un rendez vous
                $imputation = new Imputation;
                //on change de code
                $code = 201;
            }
            //on hydrate l'objet avec les données
            //$imputation->setCommentaire($donnees->Commantaire);
            $imputation->setDateD(new DateTime($donnees->start));
            $imputation->setDateF(new DateTime($donnees->end));

            $em = $this->getDoctrine()->getManager();
            $em->persist($imputation);
            $em->flush();

            //on retourne le code
            return new Response('Ok', $code);
        } else {
            //les données sont incomplètes
            return new Response('Données incomplètes', 404);
        }

        return $this->render('imputation/index.html.twig', [
            'imputation' => $imputation,
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
        $date = date('Y-m-d H:i:s');

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
