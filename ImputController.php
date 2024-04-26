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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use DateTime;

/**
* @IsGranted("ROLE_USER")
 * @Route("/imput")
 */
class ImputController extends AbstractController
{
    /**
     * @Route("/", name="imput_index", methods={"GET"})
     */
    public function index(CodeProjetRepository $codeProjetRepository, ImputRepository $imputRepository, UserRepository $userRepository, DateVRepository $dateVRepository): Response
    {
        $thisweek = date('Y') . '-W' . date('W');

        $today = date('Y-m-d');

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
        $user = $userRepository->findAll();
        foreach ($user as $users) {
            if ($users->getFlag()) {
                $alluser[] = $users;
            }
        }

        $data = json_encode($imputation);

        return $this->render('imput/index.html.twig', [
            'datas' => $data,
            'thisweek' => $thisweek,
            'today' => $today,
            'imputs' => $imputRepository->findAll(),
            'users' => $alluser,
            'dateVs' => $dateVRepository->findAll(),
            'code_projets' => $codeProjetRepository->findAll(),
        ]);
    }

    public function getJoursFeries($pays, $annee){
        if ($pays == "France"){
            // Appel à l'API pour obtenir la liste des jours fériés
            $apiUrl = 'https://calendrier.api.gouv.fr/jours-feries/metropole/'.$annee.'.json'; // URL de l'API 
            $response = file_get_contents($apiUrl); // Appel de l'API 
            // Traitement de la réponse de l'API pour obtenir les jours fériés
            $joursFeries = json_decode($response, true); // Recupération de la liste des jours fériés en format JSON
        }else if ($pays == "Maroc"){
            $joursFeries = [
                "2024-01-01"=>"Jour de l'an", 
                "2024-01-11"=>"Manifeste de l'indépendance", 
                "2024-04-10"=>"Aid El Fitr", 
                "2024-05-01"=>"Fête du travail", 
                "2024-06-17"=>"Aid Al Adha", 
                "2024-07-08"=>"1er Moharram", 
                "2024-08-30"=>"Fête du trône", 
                "2024-08-14"=>"Journée de Oued Ed-Dahab", 
                "2024-08-20"=>"La révolution du roi et du peuple", 
                "2024-08-21"=>"Fête de la jeunesse", 
                "2024-09-16"=>"Aid Al Mawlid", 
                "2024-11-06"=>"Marche verte", 
                "2024-11-18"=>"Fête de l'indépendance"
            ];
        } 
        return $joursFeries;
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

        //Boucle pour ajoutez plusieurs imputations
        $dateVs = [];
        $dateDs = [] ;

        $dateTs = [] ;
        $tableTs = [] ;

        $nbDateV = 0;
        $nbDateT = 0;

        foreach ($donnees->tableaumodif as $tab){
            foreach($dateVlistes as $dateVliste){
                if($tab->imputID == $dateVliste->getImput()->getId()){
                    $nbDateV++;
                    ($dateVs[$nbDateV] = $dateVliste->getValeur());
                    ($dateDs[$nbDateV] = $dateVliste->getDate());
                }
            }
            foreach($tab->valeur as $tabvalue){
                $nbDateT++;
                ($dateTs[$nbDateT] = $tabvalue);
                ($tableTs[$nbDateT] = $tab->imputID);
            }
        }

        for($i = 1; $i < count($dateTs)+1; $i++){
            if(isset($dateVs[$i]) && isset($dateTs[$i]) && $dateVs[$i] != $dateTs[$i]){
                foreach($dateVlistes as $listeD){
                    $dateV = new DateV;
                    $dateV = $listeD;
                    $codeP = new CodeProjet;
                    $tache = new Taches;
                    $imput = new Imput;
                    $charge = 0;
                    $codeP = $listeD->getCodeprojet();
                    $tache = $listeD->getTache();
                    if($tableTs[$i] == $listeD->getImput()->getId() && $dateDs[$i] == $listeD->getDate()){
                        $charge = $dateTs[$i] - $dateVs[$i];
                        $chargeFinale = $codeP->getChargeConsomme() + $charge;
                        $budgetFinale = $codeP->getBudgetConsomme() + ($charge * $listeD->getImput()->getUser()->getSalaire());
                        $codeP->setBudgetConsomme($budgetFinale);
                        $codeP->setChargeConsomme($chargeFinale);

                        if ($tache->getDomaine() == "NRJ") {
                            $budgetFinale = $codeP->getBudgetNRJConsomme() + ($charge * $listeD->getImput()->getUser()->getSalaire());
                            $chargeFinale =  $codeP->getChargeNRJConsomme() + $charge;
                            $codeP->setChargeNRJConsomme($chargeFinale);
                            $codeP->setBudgetNRJConsomme($budgetFinale);
                        }
                        if ($tache->getDomaine() == "DECO") {
                            $budgetFinale = $codeP->getBudgetDECOConsomme() + ($charge * $listeD->getImput()->getUser()->getSalaire());
                            $chargeFinale =  $codeP->getChargeDECOConsomme() + $charge;
                            $codeP->setChargeDECOConsomme($chargeFinale);
                            $codeP->setBudgetDECOConsomme($budgetFinale);
                        }
                        if ($tache->getDomaine() == "CLOE") {
                            $budgetFinale = $codeP->getBudgetCLOEConsomme() + ($charge * $listeD->getImput()->getUser()->getSalaire());
                            $chargeFinale =  $codeP->getChargeCLOEConsomme() + $charge;
                            $codeP->setChargeCLOEConsomme($chargeFinale);
                            $codeP->setBudgetCLOEConsomme($budgetFinale);
                        }
                        if ($tache->getDomaine() == "Transverse") {
                            $budgetFinale = $codeP->getBudgetTransverseconsomme() + ($charge * $listeD->getImput()->getUser()->getSalaire());
                            $chargeFinale =  $codeP->getChargeTransverseconsomme() + $charge;
                            $codeP->setChargeTransverseconsomme($chargeFinale);
                            $codeP->setBudgetTransverseconsomme($budgetFinale);
                        }
                        //editer les valeur
                        $dateV->setValeur($dateTs[$i]);
                        $modif = 1;
                    }
                    if ($tab->imputID == $listeD->getImput()->getId() && $tab->Commentaires != $listeD->getImput()->getCommentaire() && $bool == 0) {
                        $imput = $dateV->getImput();
                        $imput->setCommentaire($tab->Commentaires);
                        $modif = 1;
                        $bool = 1;
                    }

                    $em->persist($codeP);
                    $em->persist($dateV);
                    $em->persist($imput);
                } 
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
            $em->flush();
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
                            $donnees->tableauimput[$j]->codeprojet == $donnees->tableauimput[$d]->codeprojet &&
                            $donnees->tableauimput[$j]->activite == $donnees->tableauimput[$d]->activite
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
                $charge_imput = 0;
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
                    $charge_imput += $donnees->tableauimput[$j]->valeur[$i];
                    $em->persist($dateV);
                }
                
                //Ajout des charge et budget consomé
                $budgetFinale = $codeP->getBudgetConsomme() + ($charge_imput * $user->getSalaire());
                $chargeFinale =  $codeP->getChargeConsomme() + $charge_imput;
                $codeP->setChargeConsomme($chargeFinale);
                $codeP->setBudgetConsomme($budgetFinale);
                if ($tache->getDomaine() == "NRJ") {
                    $budgetFinale = $codeP->getBudgetNRJConsomme() + ($charge_imput * $user->getSalaire());
                    $chargeFinale =  $codeP->getChargeNRJConsomme() + $charge_imput;
                    $codeP->setChargeNRJConsomme($chargeFinale);
                    $codeP->setBudgetNRJConsomme($budgetFinale);
                }
                if ($tache->getDomaine() == "DECO") {
                    $budgetFinale = $codeP->getBudgetDECOConsomme() + ($charge_imput * $user->getSalaire());
                    $chargeFinale =  $codeP->getChargeDECOConsomme() + $charge_imput;
                    $codeP->setChargeDECOConsomme($chargeFinale);
                    $codeP->setBudgetDECOConsomme($budgetFinale);
                }
                if ($tache->getDomaine() == "CLOE") {
                    $budgetFinale = $codeP->getBudgetCLOEConsomme() + ($charge_imput * $user->getSalaire());
                    $chargeFinale =  $codeP->getChargeCLOEConsomme() + $charge_imput;
                    $codeP->setChargeCLOEConsomme($chargeFinale);
                    $codeP->setBudgetCLOEConsomme($budgetFinale);
                }
                if ($tache->getDomaine() == "Transverse") {
                    $budgetFinale = $codeP->getBudgetTransverseconsomme() + ($charge_imput * $user->getSalaire());
                    $chargeFinale =  $codeP->getChargeTransverseconsomme() + $charge_imput;
                    $codeP->setChargeTransverseconsomme($chargeFinale);
                    $codeP->setBudgetTransverseconsomme($budgetFinale);
                }

                $em->persist($codeP);
            }
            if ($code == 201) {
                return new Response($code);
            } else if ($code == 202) {
                return new Response($code);
            } else {
                $em->flush();
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
    public function apidelete(CodeProjetRepository $codeProjetRepository, DateVRepository $dateVRepository, Request $request)
    {
        //on recupère les données
        $donnees = json_decode($request->getContent());

        //Declaration
        $codeprojet = $codeProjetRepository->findAll();
        $dateVlistes = $dateVRepository->findAll();
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($dateVlistes as $dateVliste) {
            if ($donnees->imputID == $dateVliste->getImput()->getId()) {
                $codeP = new CodeProjet;
                $dateV = new DateV;
                $imput = new Imput;
                $dateV = $dateVliste;
                $imput = $dateVliste->getImput();
                $entityManager->remove($dateV);

                //calcules avec soustraction des imputation
                $codeP = $dateV->getCodeprojet();
                $chargeC = $codeP->getChargeConsomme() - $dateV->getValeur();
                $budgetC = $codeP->getBudgetConsomme() - ($dateV->getValeur() * $dateV->getImput()->getUser()->getSalaire());
                $codeP->setChargeConsomme($chargeC);
                $codeP->setBudgetConsomme($budgetC);

                if ($dateV->getTache()->getDomaine() == "NRJ") {
                    $budgetFinale = $codeP->getBudgetNRJConsomme() - ($dateV->getValeur() * $dateV->getImput()->getUser()->getSalaire());
                    $chargeFinale =  $codeP->getChargeNRJConsomme() - $dateV->getValeur();
                    $codeP->setChargeNRJConsomme($chargeFinale);
                    $codeP->setBudgetNRJConsomme($budgetFinale);
                }
                if ($dateV->getTache()->getDomaine() == "DECO") {
                    $budgetFinale = $codeP->getBudgetDECOConsomme() - ($dateV->getValeur() * $dateV->getImput()->getUser()->getSalaire());
                    $chargeFinale =  $codeP->getChargeDECOConsomme() - $dateV->getValeur();
                    $codeP->setChargeDECOConsomme($chargeFinale);
                    $codeP->setBudgetDECOConsomme($budgetFinale);
                }
                if ($dateV->getTache()->getDomaine() == "CLOE") {
                    $budgetFinale = $codeP->getBudgetCLOEConsomme() - ($dateV->getValeur() * $dateV->getImput()->getUser()->getSalaire());
                    $chargeFinale =  $codeP->getChargeCLOEConsomme() - $dateV->getValeur();
                    $codeP->setChargeCLOEConsomme($chargeFinale);
                    $codeP->setBudgetCLOEConsomme($budgetFinale);
                }
                if ($dateV->getTache()->getDomaine() == "Transverse") {
                    $budgetFinale = $codeP->getBudgetTransverseconsomme() - ($dateV->getValeur() * $dateV->getImput()->getUser()->getSalaire());
                    $chargeFinale =  $codeP->getChargeTransverseconsomme() - $dateV->getValeur();
                    $codeP->setChargeTransverseconsomme($chargeFinale);
                    $codeP->setBudgetTransverseconsomme($budgetFinale);
                }

                $entityManager->persist($codeP);
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
    
    public function ajaxAction(ActiviteRepository $activiteRepository, UserRepository $userRepository, CodeProjetRepository $codeProjetRepository, TachesRepository $tachesRepository, Request $request, DateVRepository $dateVRepository)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $donnees = json_decode($request->getContent());
            if(!empty($donnees[0])){
                //lieste des tache
                $tacheliste = [];
                $tache = $tachesRepository->findBy(['statut' => 1]);
                foreach ($tache as $taches) {
                    $tacheliste[] = [
                        'id' => $taches->getId(),
                        'libelle' => $taches->getLibelle(),
                        'description' => $taches->getDescription(),
                    ];
                }
                //liste des code projet
                $codeprojetlist = [];
                $codeP = $codeProjetRepository->findBy(['statut' => 1]);
                foreach ($codeP as $codePs) {
                    $codeprojetlist[] = [
                        'id' => $codePs->getId(),
                        'libelle' => $codePs->getLibelle(),
                        'description' => $codePs->getDescription(),
                    ];
                }

                //liste des activité
                $activitelist = [];
                $activite = $activiteRepository->findAll();
                foreach ($activite as $activites) {
                    $activitelist[] = [
                        'id' => $activites->getId(),
                        'libelle' => $activites->getLibelle(),
                    ];
                }

                //Tri par ordre alphabetique activité
                $columns = array_column($activitelist, 'libelle');
                array_multisort($columns, SORT_ASC, $activitelist);

                //tri par ordre alphabetique code projet
                $columns1 = array_column($codeprojetlist, 'libelle');
                array_multisort($columns1, SORT_ASC, $codeprojetlist);

                //tri par ordre alphabetique taches
                $columns2 = array_column($tacheliste, 'libelle');
                array_multisort($columns2, SORT_ASC, $tacheliste);

                $imputation = [];
                //recupere juste les dateV de l'utilisateur selectionné
                $dateVs = $dateVRepository->findByUserId($donnees[0]);
                $tabvide = 0;
                //remplire une seul fois les activite, tache, code
                $deja = 0;
                
                $estFerie = 0;

                // Condition pour voir si le jour d'imputation tombe un jour férié
                $collab = $userRepository->find($donnees[0]);
                $lieu = $collab->getSite();
                $lieuArray = explode(', ',$lieu);
                $pays = end($lieuArray);
                $annee = $donnees[2];
                $joursFeries = $this->getJoursFeries($pays, $annee);
            

                foreach ($dateVs as $dateV) {
                    //Numero de semaine
                    $dmy = $dateV->getDate()->format('Y-m-d');
                    $week = "W" . date("W", strtotime($dmy));
                    
                    if ($week == $donnees[1] && $dateV->getDate()->format('Y') == $donnees[2]) {
                        if(array_key_exists($dmy,$joursFeries)){
                            $estFerie = 1;
                        }
                        $tabvide = 1;
                        if ($deja == 0) {
                            $imputation[] = [
                                'imputID' => $dateV->getImput()->getId(),
                                'user' => $dateV->getImput()->getUser()->getId(),
                                'tache' => $dateV->getTache()->getLibelle(),
                                'tacheD' => $dateV->getTache()->getDescription(),
                                'activite' => $dateV->getActivite()->getLibelle(),
                                'commentaire' =>  $dateV->getImput()->getCommentaire(),
                                'week' => $week,
                                'codeprojet' => $dateV->getCodeprojet()->getLibelle(),
                                'date' => $dateV->getDate(),
                                'valeur' => $dateV->getValeur(),
                                'tacheliste' => $tacheliste,
                                'codeprojetlist' => $codeprojetlist,
                                'activitelist' => $activitelist,
                                'estFerie' => $estFerie,
                            ];
                            $deja = 1;
                        } else {
                            $imputation[] = [
                                'imputID' => $dateV->getImput()->getId(),
                                'user' => $dateV->getImput()->getUser()->getId(),
                                'tache' => $dateV->getTache()->getLibelle(),
                                'tacheD' => $dateV->getTache()->getDescription(),
                                'activite' => $dateV->getActivite()->getLibelle(),
                                'commentaire' =>  $dateV->getImput()->getCommentaire(),
                                'week' => $week,
                                'codeprojet' => $dateV->getCodeprojet()->getLibelle(),
                                'date' => $dateV->getDate(),
                                'valeur' => $dateV->getValeur(),
                                'estFerie' => $estFerie,
                            ];
                        }
                    }
                }
                if ($tabvide == 0) {
                    $W = substr($donnees[1], 1);
                    $firstday = new \DateTime();
                    $monday = new \DateTime();
                    $firstday->setISODate($annee, $W, 1);
                    $monday = $firstday->format('Y-m-d');
                    $dates = array();
                    $dates[0] = $monday;
                    for($i = 1; $i <7; $i++) {
                        $dates[$i] = $firstday->modify('+1 day')->format('Y-m-d');;
                    }
                    
                    foreach($dates as $date){
                        if(array_key_exists($date,$joursFeries)){
                            $estFerie = 1;
                        }
                    }

                    $date = new DateTime('2000-01-01');
                    $imputation[] = [
                        'date' => $date,
                        'tacheliste' => $tacheliste,
                        'codeprojetlist' => $codeprojetlist,
                        'activitelist' => $activitelist,
                        'estFerie' => $estFerie,
                    ];
                }

                $data = json_encode($imputation);

                return new JsonResponse($data);
            }else{
                return new JsonResponse();
            }
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
        
        $list = array(
            //these are the columns
            array(
                'CENTRE_DE_COUT_RESSOURCE', 
                'STRUCTURE_BT_RESSOURCE', 
                'INTERNE_/_EXTERNE', 
                'NOM_RESSOURCE',
                'LOGIN_RESSOURCE', 
                'RESSOURCE_CS_RESSOURCE', 
                'RESSOURCE_CS_FOURNISSEUR', 
                'TYPE_DE_PROJET', 'NOM_PROJET', 'CC_PORTEUR_PROJET', 'STATUT_PROJET', 'CHEF_DE_PROJET', 
                'CODE_TACHE', 
                'DESCRIPTION_TACHE', 
                'JIRA',
                'TYPE_ACTIVITE', 
                'ID-MOE', 
                'ANNEE', 
                'TYPE_IMPUTATION', 
                'SEMAINE', 
                'TOTAL', 
                'COMMENT_SEMAINE',
                'COMMENT_MOIS', 
                'no_semaine', 
                'Coût unitaire', 
                'Montant', 
                'Charge DEV', 'Charge Testeur', 'Charge Analyste', 'Charge Pilotage', 'Charge architecte',
                'Concat'
            ),
        );
        foreach ($dateVs as $dateV) {
            $jourbase = $dateV->getDate()->format('Y-m-d');
            if (date($jourj) == date($jourbase) || $compteurDateV != 0) {
                //incremente pour afficher la dernier DateV pour recuperer le total
                $compteurDateV++;
                $Timputsemaine += $dateV->getValeur();
                //condition pour ajoutez la ligne de l'export avec les information
                if ($compteurDateV == 5) {
                    //condition pour savoir si il est present ou abs
                    if ($dateV->getTache()->getDescription() == "CAPABS" )
                        $p = "Absent";
                    else
                        $p = "Présent";

                    //Charge en française
                    if ($dateV->getImput()->getUser()->getPoste() == "Développeur") {
                        $T[0] = number_format($Timputsemaine, 2, ',', ' ');
                        $T[1] = 0;
                        $T[2] = 0;
                        $T[3] = 0;
                        $T[4] = 0;
                    } elseif ($dateV->getImput()->getUser()->getPoste() == "Testeur") {
                        $T[0] = 0;
                        $T[1] = number_format($Timputsemaine, 2, ',', ' ');
                        $T[2] = 0;
                        $T[3] = 0;
                        $T[4] = 0;
                    } elseif ($dateV->getImput()->getUser()->getPoste() == "Business analyste") {
                        $T[0] = 0;
                        $T[1] = 0;
                        $T[2] = number_format($Timputsemaine, 2, ',', ' ');
                        $T[3] = 0;
                        $T[4] = 0;
                    } elseif ($dateV->getImput()->getUser()->getPoste() == "Pilotage") {
                        $T[0] = 0;
                        $T[1] = 0;
                        $T[2] = 0;
                        $T[3] = number_format($Timputsemaine, 2, ',', ' ');
                        $T[4] = 0;
                    } elseif ($dateV->getImput()->getUser()->getPoste() == "Architecte") {
                        $T[0] = 0;
                        $T[1] = 0;
                        $T[2] = 0;
                        $T[3] = 0;
                        $T[4] = number_format($Timputsemaine, 2, ',', ' ');
                    }
                    //Budget en franaçais
                    $budgeten = $Timputsemaine * $dateV->getImput()->getUser()->getSalaire();
                    $budgetfr = number_format($budgeten, 2, ',', ' ');
                    if ($p == "Absent") {
                        $budgetfr = 0;
                    }
                    //rendre le nombre format française
                    $Timputsemainefr = number_format($Timputsemaine, 2, ',', ' ');
                    $semaine = substr($donnees->week, 1);
                    //Séparer le CODE-Tache de ID-MOE
                    $CodeTache = $dateV->getCodeprojet()->getLibelle();
                    $IDMOE = $dateV->getCodeprojet()->getLibelle();
                    if (substr($CodeTache, -6, 1) == '-' || substr($CodeTache, -6, 1) == '_') {
                        $IDMOE = substr($CodeTache, -5);
                        $CodeTache = substr($CodeTache, 0, -6);
                    } else if ((substr($CodeTache, -7, 1) == '-' || substr($CodeTache, -7, 1) == '_') && substr($CodeTache, -6, 1) == ' ') {
                        $IDMOE = substr($CodeTache, -5);
                        $CodeTache = substr($CodeTache, 0, -7);
                    }
                    $tabexport[$i] = array(
                        'D00550', 
                        'Pole Digital B2B', 
                        'Capgemini', 
                        $dateV->getImput()->getUser()->getUsername(),
                        $dateV->getImput()->getUser()->getCapit(), 
                        $dateV->getTache()->getDomaine(), 
                        $dateV->getImput()->getUser()->getPoste(),
                        '', '', '', '', '', 
                        $CodeTache, 
                        $dateV->getCodeprojet()->getDescription(),
                        $dateV->getTache()->getLibelle(), 
                        $dateV->getActivite()->getLibelle(), 
                        $IDMOE, 
                        $donnees->year, 
                        $p,
                        $jour->format('d/m/Y 00:00'), 
                        $Timputsemainefr, 
                        $dateV->getImput()->getCommentaire(), 
                        '', 
                        $semaine,
                        $dateV->getImput()->getUser()->getSalaire(), 
                        $budgetfr, 
                        $T[0], $T[1], $T[2], $T[3], $T[4],
                        $CodeTache .' '. $IDMOE .' | '. $dateV->getCodeprojet()->getDescription()
                    );
                    $compteurDateV = 0;
                    $i++;
                    $Timputsemaine = 0;
                }
            }
        }

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

    /**
     * @Route("/exportmois", name="export-csv" )
     */
    public function exportmoisAction(DateVRepository $dateVRepository, ImputRepository $imputRepository, Request $request)
    {

        $donnees = json_decode($request->getContent());


        $dateVs = $dateVRepository->findAll();

        $tabexport = [];

        $i = 0;
        $compteurDateV = 0;
        $Timputsemaine = 0;
        $findemois = 0;
        $moisverif = 0;
        $moisverifin = 0;
        $boolnext = 0;

        $jour = new DateTime($donnees->dates[0]);
        $jourj = $jour->format('Y-m-d');
        $moisEX = substr($jourj, 0, 7);

        $list = array(
            //these are the columns
            array(
                'CENTRE_DE_COUT_RESSOURCE', 
                'STRUCTURE_BT_RESSOURCE', 
                'INTERNE_/_EXTERNE', 
                'NOM_RESSOURCE',
                'LOGIN_RESSOURCE', 
                'RESSOURCE_CS_RESSOURCE', 
                'RESSOURCE_CS_FOURNISSEUR', 
                'TYPE_DE_PROJET', 'NOM_PROJET', 'CC_PORTEUR_PROJET', 'STATUT_PROJET', 'CHEF_DE_PROJET', 
                'CODE_TACHE', 
                'DESCRIPTION_TACHE', 
                'JIRA',
                'TYPE_ACTIVITE', 
                'ID-MOE', 
                'ANNEE', 
                'TYPE_IMPUTATION', 
                'SEMAINE', 
                'TOTAL', 
                'COMMENT_SEMAINE',
                'COMMENT_MOIS', 
                'no_semaine', 
                'Coût unitaire', 
                'Montant', 
                'Charge DEV', 'Charge Testeur', 'Charge Analyste', 'Charge Pilotage', 'Charge architecte', 
                'Concat'
            ),
        );

        foreach ($dateVs as $dateV) {
            $bool = 0;
            $jourbase = $dateV->getDate()->format('Y-m-d');
            $weekdatetime = new DateTime($jourbase);
            $weeknumber = $weekdatetime->format("W");
            $moisBase = substr($jourbase, 0, 7);
            //condition sur le mois selectionner
            if ($moisEX == $moisBase) {
                $bool = 1;
                $moisverifin = 0;
                $moisverif = 1;
                $Timputsemaine += $dateV->getValeur();
            }
            //condition pour la date de debut de semaine
            if ($compteurDateV == 0)
                $datedebutsemaine =  $dateV->getDate()->format('d/m/Y 00:00');

            //Condition sur la fin du mois
            if ($findemois == $dateV->getImput()->getId() && $moisEX != $moisBase && $moisverif == 1 && $moisverifin == 0 && $boolnext == 1) {
                //garder la valeur du compteur
                $retour = $compteurDateV;
                //pour afficher le resumtat au milieux de la semaine vu que le mois est fini
                $compteurDateV = 4;
                //pour savoir qu'on a deja acceder a cette condition
                $moisverifin = 1;
                //pour savoir qu'on est deja passé par le mois choisi
                $bool = 1;
            }

            //incremente pour afficher la dernier DateV pour recuperer le total
            $compteurDateV++;

            //condition pour ajoutez la ligne de l'export avec les information
            if ($bool == 1  && $compteurDateV == 5) {
                //condition pour savoir si il est present ou abs
                if ($dateV->getTache()->getDescription() == "CAPABS")
                    $p = "Absent";
                else
                    $p = "Présent";

                //Charge en française
                if ($dateV->getImput()->getUser()->getPoste() == "Développeur") {
                    $T[0] = number_format($Timputsemaine, 2, ',', ' ');
                    $T[1] = 0;
                    $T[2] = 0;
                    $T[3] = 0;
                    $T[4] = 0;
                } elseif ($dateV->getImput()->getUser()->getPoste() == "Testeur") {
                    $T[0] = 0;
                    $T[1] = number_format($Timputsemaine, 2, ',', ' ');
                    $T[2] = 0;
                    $T[3] = 0;
                    $T[4] = 0;
                } elseif ($dateV->getImput()->getUser()->getPoste() == "Business analyste") {
                    $T[0] = 0;
                    $T[1] = 0;
                    $T[2] = number_format($Timputsemaine, 2, ',', ' ');
                    $T[3] = 0;
                    $T[4] = 0;
                } elseif ($dateV->getImput()->getUser()->getPoste() == "Pilotage") {
                    $T[0] = 0;
                    $T[1] = 0;
                    $T[2] = 0;
                    $T[3] = number_format($Timputsemaine, 2, ',', ' ');
                    $T[4] = 0;
                } elseif ($dateV->getImput()->getUser()->getPoste() == "Architecte") {
                    $T[0] = 0;
                    $T[1] = 0;
                    $T[2] = 0;
                    $T[3] = 0;
                    $T[4] = number_format($Timputsemaine, 2, ',', ' ');
                }
                //Budget en franaçais
                $budgeten = $Timputsemaine * $dateV->getImput()->getUser()->getSalaire();
                $budgetfr = number_format($budgeten, 2, ',', ' ');
                if ($p == "Absent") {
                    $budgetfr = 0;
                }
                //rendre le nombre format française
                $Timputsemainefr = number_format($Timputsemaine, 2, ',', ' ');
                //Séparer le CODE-Tache de ID-MOE
                $CodeTache = $dateV->getCodeprojet()->getLibelle();
                $IDMOE = $dateV->getCodeprojet()->getLibelle();
                if (substr($CodeTache, -6, 1) == '-' || substr($CodeTache, -6, 1) == '_') {
                    $IDMOE = substr($CodeTache, -5);
                    $CodeTache = substr($CodeTache, 0, -6);
                } else if ((substr($CodeTache, -7, 1) == '-' || substr($CodeTache, -7, 1) == '_') && substr($CodeTache, -6, 1) == ' ') {
                    $IDMOE = substr($CodeTache, -5);
                    $CodeTache = substr($CodeTache, 0, -7);
                }
                //remplire la ligne d'export avec les information de chaque imput
                $tabexport[$i] = array(
                    'D00550', 
                    'Pole Digital B2B', 
                    'Capgemini', 
                    $dateV->getImput()->getUser()->getUsername(),
                    $dateV->getImput()->getUser()->getCapit(), 
                    $dateV->getTache()->getDomaine(), 
                    $dateV->getImput()->getUser()->getPoste(),
                    '', '', '', '', '', 
                    $CodeTache, 
                    $dateV->getCodeprojet()->getDescription(),
                    $dateV->getTache()->getLibelle(), 
                    $dateV->getActivite()->getLibelle(), 
                    $IDMOE, 
                    $donnees->year, 
                    $p,
                    $datedebutsemaine, 
                    $Timputsemainefr, 
                    $dateV->getImput()->getCommentaire(), 
                    '', 
                    $weeknumber,
                    $dateV->getImput()->getUser()->getSalaire(), 
                    $budgetfr, 
                    $T[0], $T[1], $T[2], $T[3], $T[4], 
                    $CodeTache .' '. $IDMOE .' | '. $dateV->getCodeprojet()->getDescription()
                );
                //si plusieur imputation sont dans une semaine qui chevauche deux mois
                if ($moisverifin ==  1)
                    $compteurDateV = $retour + 1;
                $i++;
                $Timputsemaine = 0;
            }
            //pour passer a une autre imputation
            if ($compteurDateV == 5) {
                $compteurDateV = 0;
            }
            $findemois = $dateV->getImput()->getId();
            $boolnext = $bool;
        }

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

    /**
     * @Route("/exportinter", name="export-csv" )
     */
    public function exportInterval(DateVRepository $dateVRepository, ImputRepository $imputRepository, Request $request)
    {
        // Récuperation des données
        $donnees = json_decode($request->getContent());
        $dateDebut = new DateTime($donnees->dateDebut);
        $dateFin = new DateTime($donnees->dateFin);

        $dateVs = $dateVRepository->findByDateRange($dateDebut, $dateFin);


        $list = array(
            // COLONNES
            array(
                'CENTRE_DE_COUT_RESSOURCE', 
                'STRUCTURE_BT_RESSOURCE', 
                'INTERNE_/_EXTERNE', 
                'NOM_RESSOURCE',
                'LOGIN_RESSOURCE', 
                'RESSOURCE_CS_RESSOURCE', 
                'RESSOURCE_CS_FOURNISSEUR', 
                'TYPE_DE_PROJET', 'NOM_PROJET','CC_PORTEUR_PROJET', 'STATUT_PROJET', 'CHEF_DE_PROJET', 
                'CODE_TACHE', 
                'DESCRIPTION_TACHE', 
                'JIRA',
                'TYPE_ACTIVITE', 
                'ID-MOE', 
                'ANNEE', 
                'TYPE_IMPUTATION', 
                'JOUR', 
                'TOTAL', 
                'COMMENT_SEMAINE',
                'COMMENT_MOIS', 
                'no_semaine', 
                'Coût unitaire', 
                ' Montant', 
                'Charge DEV', 'Charge Testeur', 'Charge Analyste', 'Charge Pilotage', 'Charge architecte',
                'Concat'
            ),
        );

        // Collecte des informations de chaque collaborateur sous forme de tableau
        $tabexport = [];
        $i = 0;
        $compteurDateV = 0; // Nombre total d'imputation
        $Timputsemaine = 0;

        foreach ($dateVs as $dateV) {
            $bool = 0;
            
            $dateimput = $dateV->getDate()->format('Y-m-d'); // date imputation
            $annee = $dateV->getDate()->format('Y'); // année imputation
            $semaineimput = new DateTime($dateimput); // date complete imputation avec semaine
            $numsemaine = $semaineimput->format("W"); // numero semaine
            
            $Timputsemaine = $dateV->getValeur(); // valeur total des imputs

            //condition pour la date de debut de semaine
            if ($compteurDateV == 0)
                $dateformat =  $dateV->getDate()->format('d/m/Y 00:00'); // date imputation sous format JJ/MM/AAAA HH:mm
            else
                $dateformat = $dateimput;

            //incremente pour afficher le nombre total d'imputation
            $compteurDateV++;

            //condition pour savoir si il est present ou abs
            if ($dateV->getTache()->getDescription() == "CAPABS")
                $p = "Absent";
            else
                $p = "Présent";

            //Charge en français
            if ($dateV->getImput()->getUser()->getPoste() == "Développeur") {
                $T[0] = number_format($Timputsemaine, 2, ',', ' ');
                $T[1] = 0;
                $T[2] = 0;
                $T[3] = 0;
                $T[4] = 0;
            } elseif ($dateV->getImput()->getUser()->getPoste() == "Testeur") {
                $T[0] = 0;
                $T[1] = number_format($Timputsemaine, 2, ',', ' ');
                $T[2] = 0;
                $T[3] = 0;
                $T[4] = 0;
            } elseif ($dateV->getImput()->getUser()->getPoste() == "Business analyste") {
                $T[0] = 0;
                $T[1] = 0;
                $T[2] = number_format($Timputsemaine, 2, ',', ' ');
                $T[3] = 0;
                $T[4] = 0;
            } elseif ($dateV->getImput()->getUser()->getPoste() == "Pilotage") {
                $T[0] = 0;
                $T[1] = 0;
                $T[2] = 0;
                $T[3] = number_format($Timputsemaine, 2, ',', ' ');
                $T[4] = 0;
            } elseif ($dateV->getImput()->getUser()->getPoste() == "Architecte") {
                $T[0] = 0;
                $T[1] = 0;
                $T[2] = 0;
                $T[3] = 0;
                $T[4] = number_format($Timputsemaine, 2, ',', ' ');
            }
            //Budget en français
            $budgeten = $Timputsemaine * $dateV->getImput()->getUser()->getSalaire();
            $budgetfr = number_format($budgeten, 2, ',', ' ');

            if ($p == "Absent") {
                $budgetfr = 0;
            }

            //rendre le nombre format française
            $Timputsemainefr = number_format($Timputsemaine, 2, ',', ' ');

            //Séparer le CODE-Tache de ID-MOE
            $CodeTache = $dateV->getCodeprojet()->getLibelle();

            $IDMOE = $dateV->getCodeprojet()->getLibelle();

            if (substr($CodeTache, -6, 1) == '-' || substr($CodeTache, -6, 1) == '_') 
            {
                $IDMOE = substr($CodeTache, -5);
                $CodeTache = substr($CodeTache, 0, -6);
            } else if ((substr($CodeTache, -7, 1) == '-' || 
                substr($CodeTache, -7, 1) == '_') && 
                substr($CodeTache, -6, 1) == ' ') 
            {
                $IDMOE = substr($CodeTache, -5);
                $CodeTache = substr($CodeTache, 0, -7);
            }

            //remplire la ligne d'export avec les information de chaque imput
            $tabexport[$i] = array(
                'D00550', // centre_de_cout_ressource
                'Pole Digital B2B', // structure
                'Capgemini', // interne ou  externe 
                $dateV->getImput()->getUser()->getUsername(), // nom_collab (nom_ressource)
                $dateV->getImput()->getUser()->getCapit(), // capit (login_ressource)
                $dateV->getTache()->getDomaine(), // projet (ressource_cs_ressource)
                $dateV->getImput()->getUser()->getPoste(), // poste (ressource_cs_fournisseur)
                '', '', '', '', '', // type_projet, nom_projet, cc_porteur_projet, statut_projet, chef_de_projet
                $CodeTache, // code projet(code_tache)
                $dateV->getCodeprojet()->getDescription(), // description (description_tache)
                $dateV->getTache()->getLibelle(), // tache (jira)
                $dateV->getActivite()->getLibelle(), // activite (type_activite)
                $IDMOE, // libelle activité (id-moe)
                $annee, // annee 
                $p, // presence
                $dateformat, // jour imputation (jour)
                $Timputsemainefr, // total imputation (total)
                $dateV->getImput()->getCommentaire(), // commentaire imputation (comment_semaine)
                '', // commentaire mois 
                $numsemaine, // numéro semaine (no_semaine)
                $dateV->getImput()->getUser()->getSalaire(), // TJM (cout_unitaire)
                $budgetfr, // budget tache (montant)
                $T[0], $T[1], $T[2], $T[3], $T[4], // charges(dev, testeur, analyste, chargé de pilotage, architecte)
                $CodeTache .' '. $IDMOE .' | '. $dateV->getCodeprojet()->getDescription()
            );
            
            $i++; 

        }

        // Remplissage de la liste 
        for ($j = 0; $j < $i; $j++) {
            $list[$j + 1] = $tabexport[$j];
        }

        // Création du fichier CSV
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

    /**
     * @Route("/remplirSelect2", name="remplirSelect2" )
     */
    public function remplirSelect2Action(TachesRepository $tachesRepository, Request $request)
    {
        //on recupère les données (id: id_select)
        $donnees = json_decode($request->getContent());

        $tacheliste = []; // crée un tableau de taches

        $tache = $tachesRepository->findBy(['statut' => 1]); // recherche les taches actives

        foreach ($tache as $taches) {
            // vérifie si le code lié à la tache correspond à celui qui est envoyé
            if ($taches->getCodeProjet()->getId() == $donnees->id) { 
                // Rempli le tableau
                $tacheliste[] = [ 
                    'id' => $taches->getId(),
                    'libelle' => $taches->getLibelle(),
                    'description' => $taches->getDescription(),
                ];
            }
        }

        //tri par ordre alphabetique taches
        $columns2 = array_column($tacheliste, 'libelle');
        array_multisort($columns2, SORT_ASC, $tacheliste);

        $data = json_encode($tacheliste);

        return new JsonResponse($data);
    }
}
