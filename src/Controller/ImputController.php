<?php

namespace App\Controller;

use App\Entity\CodeProjet;
use App\Entity\Imput;
use App\Entity\DateV;
use App\Entity\Taches;
use App\Entity\User;
use App\Entity\Activite;
use App\Form\ImputType;
use App\Repository\DateVRepository;
use App\Repository\ImputRepository;
use App\Repository\ActiviteRepository;
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
        $code = 200;
        //verifier si il ya eu une Modification
        $modif = 0;
        //Boucle pour ajoutez plusieur imputation
        for ($j = 0; $j < $donnees->nbrmodification; $j++) {

            //Debut du traitement
            foreach ($dateVlistes as $dateVliste) {
                $dateV = $dateVliste;
                //editer la date valeur
                if ($donnees->tableaumodif[$j]->imputID == $dateVliste->getImput()->getId() && $donnees->tableaumodif[$j]->valeur[$i] != $dateVliste->getValeur()) {
                    $dateV = new DateV;
                    $dateV = $dateVliste;
                    $dateV->setValeur($donnees->tableaumodif[$j]->valeur[$i]);
                    $modif = 1;
                    $em->persist($dateV);
                }
                //edit le commenataire
                if ($donnees->tableaumodif[$j]->imputID == $dateVliste->getImput()->getId() && $donnees->tableaumodif[$j]->Commentaires != $dateVliste->getImput()->getCommentaire() && $bool == 0) {
                    $imput = new Imput;
                    $imput = $dateV->getImput();
                    $imput->setCommentaire($donnees->tableaumodif[$j]->Commentaires);
                    $modif = 1;
                    $em->persist($imput);
                    $bool = 1;
                }
                $i++;
                if ($i == 5)
                    $i = 0;
            }
        }

        for ($Q = 0; $Q < 5; $Q++) {
            if ($donnees->tabcumuleimput[$Q] > 1)
                $code = 202;
        }
        if ($modif == 0) {
            $code = 0;
            return new Response($code);
        } else if ($code == 202) {
            return new Response($code);
        } else {
            //$em->flush();
            return new Response($code);
        }
    }
    /**
     * @Route("/apii/new", name="api_new", methods={"GET","POST"})
     */
    public function apinew(DateVRepository $dateVRepository, ActiviteRepository $activiteRepository, CodeProjetRepository $codeProjetRepository, TachesRepository $tachesrepository, UserRepository $userRepository, Request $request)
    {

        //on recupère les données
        $donnees = json_decode($request->getContent());
        if ($donnees->nbr > 0) {
            //Declaration
            $dateVlistes = $dateVRepository->findAll();
            $userlistes = $userRepository->findAll();
            $tachelistes = $tachesrepository->findAll();
            $codeprojetlistes = $codeProjetRepository->findAll();
            $activitelistes = $activiteRepository->findAll();
            $code = 200;

            if ($donnees->nbr > 1) {
                for ($j = 0; $j < $donnees->nbr; $j++) {
                    for ($d = $j + 1; $d < $donnees->nbr; $d++) {
                        if (
                            $donnees->tableauimput[$j]->tache == $donnees->tableauimput[$d]->tache &&
                            $donnees->tableauimput[$j]->codeprojet == $donnees->tableauimput[$d]->codeprojet
                        ) {
                            $code = 201;
                        }
                    }
                }
            }

            //Boucle pour ajoutez plusieur imputation
            for ($j = 0; $j < $donnees->nbr; $j++) {

                $imput = new Imput;
                $tache = new Taches;
                $user = new User;
                $codeP = new CodeProjet;
                $activite = new Activite;
                //connaitre le user
                foreach ($userlistes as $userliste) {
                    if ($donnees->tableauimput[$j]->user == $userliste->getId())
                        $user = $userliste;
                }
                //connaitre la tache
                foreach ($tachelistes as $tacheliste) {
                    if ($donnees->tableauimput[$j]->tache == $tacheliste->getId())
                        $tache = $tacheliste;
                }
                //connaitre le codeprojet
                foreach ($codeprojetlistes as $codeprojetliste) {
                    if ($donnees->tableauimput[$j]->codeprojet == $codeprojetliste->getId())
                        $codeP = $codeprojetliste;
                }
                //connaitre l'activite
                foreach ($activitelistes as $activiteliste) {
                    if ($donnees->tableauimput[$j]->activite == $activiteliste->getId())
                        $activite = $activiteliste;
                }
                //condition sur l'existance des code
                foreach ($dateVlistes as $dateVliste) {
                    $datetest1 = new DateTime($donnees->tableauimput[$j]->date[0]);
                    $datetest = $datetest1->format('Y-m-d');
                    $datecomp = $dateVliste->getDate()->format('Y-m-d');
                    if (
                        $donnees->tableauimput[$j]->user == $dateVliste->getImput()->getUser()->getId() &&
                        $donnees->tableauimput[$j]->tache == $dateVliste->getTache()->getId() &&
                        $donnees->tableauimput[$j]->codeprojet == $dateVliste->getCodeprojet()->getId() &&
                        $donnees->tableauimput[$j]->activite == $dateVliste->getActivite()->getId() &&
                        date($datetest) == date($datecomp)
                    ) {
                        $code = 201;
                    }
                }
                //Condition pour voir si les imputation depasse 1
                $cm = 0;
                $totalbase = [];
                $totalbase[0] = $donnees->tableauimput[$j]->tabcumuleimput[0] + $donnees->tableauimput[$j]->tabcumuleimputM[0];
                $totalbase[1] = $donnees->tableauimput[$j]->tabcumuleimput[1] + $donnees->tableauimput[$j]->tabcumuleimputM[1];
                $totalbase[2] = $donnees->tableauimput[$j]->tabcumuleimput[2] + $donnees->tableauimput[$j]->tabcumuleimputM[2];
                $totalbase[3] = $donnees->tableauimput[$j]->tabcumuleimput[3] + $donnees->tableauimput[$j]->tabcumuleimputM[3];
                $totalbase[4] = $donnees->tableauimput[$j]->tabcumuleimput[4] + $donnees->tableauimput[$j]->tabcumuleimputM[4];
                /*foreach ($dateVlistes as $dateVliste) {
                    $datetest1 = new DateTime($donnees->tableauimput[$j]->date[$cm]);
                    $datetest = $datetest1->format('Y-m-d');
                    $datecomp = $dateVliste->getDate()->format('Y-m-d');

                    if (
                        date($datetest) == date($datecomp) &&
                        $donnees->tableauimput[$j]->user == $dateVliste->getImput()->getUser()->getId()
                    ) {
                        $totalbase[$cm] = $totalbase[$cm] + $dateVliste->getValeur();
                        $cm++;
                    }
                    if ($cm == 5) {
                        $cm = 0;
                    }
                }*/

                for ($ver = 0; $ver < 5; $ver++) {
                    if ($totalbase[$ver] > 1)
                        $code = 202;
                }


                //création de l'imput
                $imput->setUser($user);
                $imput->setCommentaire($donnees->tableauimput[$j]->Commentaires);

                $em = $this->getDoctrine()->getManager();
                $em->persist($imput);
                //Création des donnée
                for ($i = 0; $i < 5; $i++) {
                    $dateV = new DateV;
                    //on hydrate l'objet avec les données
                    $dateV->setImput($imput);
                    $dateV->setValeur($donnees->tableauimput[$j]->valeur[$i]);
                    $dateV->setDate(new DateTime($donnees->tableauimput[$j]->date[$i]));
                    $dateV->setTache($tache);
                    $dateV->setCodeprojet($codeP);
                    $dateV->setActivite($activite);

                    $em->persist($dateV);
                }
            }
            if ($code == 201) {
                return new Response($code);
            } else if ($code == 202) {
                return new Response($code);
            } else {
                //$em->flush();
                return new Response($code);
            }
            //return $this->redirectToRoute('imput_index');
        }
        $code = 0;
        return new Response($code);
    }

    /**
     * @Route("/apii/delete", name="api_imput_delete", methods={"PUT"})
     */
    public function apidelete(DateVRepository $dateVRepository, Request $request)
    {

        //on recupère les données
        $donnees = json_decode($request->getContent());

        //Declaration
        $dateVlistes = $dateVRepository->findAll();
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($dateVlistes as $dateVliste) {
            if ($donnees->imputID == $dateVliste->getImput()->getId()) {
                $dateV = new DateV;
                $imput = new Imput;
                $dateV = $dateVliste;
                $imput = $dateVliste->getImput();
                $entityManager->remove($dateV);
            }
        }
        $entityManager->remove($imput);
        $entityManager->flush();
        return new Response(200);
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
    public function ajaxAction(ActiviteRepository $activiteRepository, CodeProjetRepository $codeProjetRepository, TachesRepository $tachesRepository, Request $request, DateVRepository $dateVRepository)
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
            //lieste des tache
            $tacheliste = [];
            $tache = $tachesRepository->findAll();
            foreach ($tache as $taches) {
                $tacheliste[] = [
                    'id' => $taches->getId(),
                    'libelle' => $taches->getLibelle(),
                ];
            }
            //liste des code projet
            $codeprojetlist = [];
            $codeP = $codeProjetRepository->findAll();
            foreach ($codeP as $codePs) {
                $codeprojetlist[] = [
                    'id' => $codePs->getId(),
                    'libelle' => $codePs->getLibelle(),
                    'description' => $codePs->getDescription(),
                ];
            }

            //liste des code projet
            $activitelist = [];
            $activite = $activiteRepository->findAll();
            foreach ($activite as $activites) {
                $activitelist[] = [
                    'id' => $activites->getId(),
                    'libelle' => $activites->getLibelle(),
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
                    'activite' => $dateV->getActivite()->getLibelle(),
                    'commentaire' =>  $dateV->getImput()->getCommentaire(),
                    'week' => $week,
                    'codeprojet' => $dateV->getCodeprojet()->getLibelle(),
                    'date' => $dateV->getDate(),
                    'valeur' => $dateV->getValeur(),
                    'tacheliste' => $tacheliste,
                    'codeprojetlist' => $codeprojetlist,
                    'activitelist' => $activitelist,
                ];
            }

            $data = json_encode($imputation);

            return new JsonResponse($data);
        } else {

            return $this->render('imput/index.html.twig');
        }
    }

    /**
     * @Route("/export", name="export-csv" )
     */

    public function exportAction(DateVRepository $dateVRepository, ImputRepository $imputRepository, Request $request)
    {

        $donnees = json_decode($request->getContent());


        $dateVs = $dateVRepository->findAll();
        $tabexport = [];
        $i = 0;
        $compteurDateV = 0;
        $Timputsemaine = 0;

        $jour = new DateTime($donnees->dates[0]);
        $jourj = $jour->format('Y-m-d');
        foreach ($dateVs as $dateV) {
            $jourbase = $dateV->getDate()->format('Y-m-d');
            if (date($jourj) == date($jourbase) || $compteurDateV != 0) {
                //incremente pour afficher la dernier DateV pour recuperer le total
                $compteurDateV++;
                $Timputsemaine += $dateV->getValeur();
                //condition pour ajoutez la ligne de l'export avec les information
                if ($compteurDateV == 5) {
                    //rendre le nombre format française
                    $Timputsemainefr = number_format($Timputsemaine, 2, ',', ' ');
                    $semaine = substr($donnees->week, 1);
                    $tabexport[$i] = array(
                        'D00550', 'Pole Digital B2B', 'Interne', $dateV->getImput()->getUser()->getUsername(),
                        $dateV->getImput()->getUser()->getEmail(), $dateV->getTache()->getDomaine(), $dateV->getImput()->getUser()->getPoste(),
                        '', '', '', '', '', $dateV->getCodeprojet()->getLibelle(), $dateV->getCodeprojet()->getDescription(),
                        $dateV->getTache()->getLibelle(), $dateV->getActivite()->getLibelle(), '', $donnees->year, 'Present',
                        $jour->format('d/m/Y 00:00'), $Timputsemainefr, $dateV->getImput()->getCommentaire(), '', $semaine,
                        $dateV->getImput()->getUser()->getSalaire(), 'somme des cout', 'DEBUT charge', '', '', '', ''
                    );
                    $compteurDateV = 0;
                    $i++;
                    $Timputsemaine = 0;
                }
            }
        }

        $list = array(
            //these are the columns
            array(
                'CENTRE_DE_COUT_RESSOURCE', 'STRUCTURE_BT_RESSOURCE', 'INTERNE_/_EXTERNE', 'NOM_RESSOURCE',
                'LOGIN_RESSOURCE', 'RESSOURCE_CS_RESSOURCE', 'RESSOURCE_CS_FOURNISSEUR', 'TYPE_DE_PROJET', 'NOM_PROJET',
                'CC_PORTEUR_PROJET', 'STATUT_PROJET', 'CHEF_DE_PROJET', 'CODE_TACHE', 'DESCRIPTION_TACHE', 'JIRA',
                'TYPE_ACTIVITE', 'ID-MOE', 'ANNEE', 'TYPE_IMPUTATION', 'SEMAINE', 'TOTAL', 'COMMENT_SEMAINE',
                'COMMENT_MOIS', 'no_semaine', 'Coût unitaire', 'Montant', 'Charge DEV', 'Charge Testeur', 'Charge Analyste',
                'Charge Pilotage', 'Charge architecte'
            ),
        );
        for ($j = 0; $j < $i; $j++) {
            $list[$j + 1] = $tabexport[$j];
        }

        $fp = fopen('php://temp', 'w+');
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }

        rewind($fp);
        $response = new Response(stream_get_contents($fp));
        fclose($fp);

        $response->headers->set('Content-Type', 'text/csv', 'charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="testing.csv"');

        return $response;
    }
}
