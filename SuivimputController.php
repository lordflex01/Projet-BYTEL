<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Command\Command;
use App\Repository\ImputRepository;
use App\Repository\DateVRepository;
use App\Repository\UserRepository;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


class SuivimputController extends AbstractController
{
    /**
     * @Route("/suivimputation");
     */
    public function suivimputation(UserRepository $userRepository, DateVRepository $dateVRepository, ImputRepository $imputRepository, Request $request)
    {

        $thisweek = date('Y') . '-W' . date('W');

        return $this->render('suivImput/suivi.html.twig', [
            'imputs' => $imputRepository->findAll(),
            'users' => $userRepository->findAll(),
            'dateVs' => $dateVRepository->findAll(),
            'thisweek' => $thisweek,
        ]);
    }

    /**
     * @Route("/suivimputation/recherche");
     */
    public function ajaxAction(Request $request, UserRepository $userRepository, DateVRepository $dateVRepository, ImputRepository $imputRepository)
    {
        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $donnees = json_decode($request->getContent());
            $dateVs = $dateVRepository->findAll();
            $nombreuser = 0;

            $codeprojet = [];
            $tache = [];
            $activite = [];
            $total = [];
            $total[0] = 0;
            $me = $this->getUser();

            //liste des utilisateurs
            $userlist = [];
            $user = $userRepository->findAll();
            foreach ($user as $users) {
                if ($users->getFlag() && $users->getProjet() == $me->getProjet()) {
                    $userlist[] = [
                        'id' => $users->getId(),
                        'email' => $users->getEmail(),
                        'username' => $users->getUsername(),
                        'domaine' => $users->getProjet()->getLibelle(),
                        'codeprojet' => $codeprojet,
                        'tache' => $tache,
                        'activité' => $activite,
                        'total' => $total,
                        'nbr' => 0,
                        'valeur' => 0,
                    ];
                    $nombreuser++;
                }
            }

            $verif = 0;
            foreach ($dateVs as $dateV) {
                //Numero de semaine
                $dmy = $dateV->getDate()->format('d-m-Y');
                $week = "W" . date("W", strtotime($dmy));
                $i = 0;
                do {
                    //boolean pour savoir si on est entrer dans la condition
                    $bool = 0;
                    //condition pour mettre les imputation de chaque collaboreteur 
                    if (
                        !empty ($userlist[$i]['id']) &&
                        $dateV->getDate()->format('Y') == $donnees[0] &&
                        $week == $donnees[1] &&
                        $dateV->getImput()->getUser()->getId() == $userlist[$i]['id']
                    ) {
                        $userlist[$i]['valeur'] = $userlist[$i]['valeur'] + $dateV->getValeur();
                        $userlist[$i]['codeprojet'][$userlist[$i]['nbr']] = $dateV->getCodeprojet()->getLibelle()/* . ": " . $dateV->getCodeprojet()->getDescription()*/ ;
                        $userlist[$i]['tache'][$userlist[$i]['nbr']] = $dateV->getTache()->getLibelle()/* . ": " . $dateV->getTache()->getDescription()*/ ;
                        $userlist[$i]['activité'][$userlist[$i]['nbr']] = $dateV->getActivite()->getLibelle()/* . ": " . $dateV->getActivite()->getDescription()*/ ;

                        $bool = 1;
                        if ($dateV->getImput()->getId() != $verif && $verif != 0) {
                            $userlist[$i]['nbr']++;
                            $userlist[$i]['total'][$userlist[$i]['nbr']] = 0;
                        }
                        $verif = $dateV->getImput()->getId();
                    }
                    $i++;
                } while ($i < $nombreuser || $bool == 1);
                //boolean pour savoir si on est entrer dans la condition
                $bool2 = 0;
                //boucle pour remplire le tableau des total imputation (ça na pas marché dans l'autre boucle donc j'ai fait une nouvelle)
                for ($i = 0; $i < $nombreuser; $i++) {
                    if (
                        $dateV->getDate()->format('Y') == $donnees[0] &&
                        $week == $donnees[1] &&
                        $dateV->getImput()->getUser()->getId() == $userlist[$i]['id'] &&
                        $dateV->getImput()->getId() == $verif
                    ) {
                        $bool2 = 1;
                        $userlist[$i]['total'][$userlist[$i]['nbr']] += $dateV->getValeur();
                    }
                    if ($dateV->getImput()->getId() != $verif && $verif != 0 && $bool2 == 1) {
                        $userlist[$i]['nbr']++;
                        $userlist[$i]['total'][$userlist[$i]['nbr']] = 0;
                        $bool2 = 0;
                    }
                    $verif = $dateV->getImput()->getId();
                }
            }

            $data = json_encode($userlist);

            return new JsonResponse($data);
        } else {

            return $this->render('suivImput/suivi.html.twig');
        }
    }

    /**
     * @Route("/suivmputationMois");
     */
    public function suivImputationMois(UserRepository $userRepository, DateVRepository $dateVRepository, ImputRepository $imputRepository, Request $request)
    {
        // Initialisation des semaines
        $weeks = []; // creation du tableau des semaines
        $semaine = date('W'); // récuperation de la semaine
        $annes = date('Y'); // récuperation du mois

        // Récuperation des 4 dernieres semaines
        for ($g = 0; $g < 4; $g++) {
            if ($semaine >= 1 && $semaine < 10 && $g > 0) {
                $weeks[] = $annes . '-W0' . $semaine;
            } else if ($semaine >= 1) {
                $weeks[] = $annes . '-W' . $semaine;
            } else {
                $annes--;
                $semaine = date('W', strtotime($annes . '-12-31'));
                $weeks[] = $annes . '-W' . $semaine;
            }
            $semaine = $semaine - 1;
        }

        // Remplissage des tableaux
        $userlisteTotal = []; // creattion du tableau des collaborateurs avec leurs imputations
        for ($g = 0; $g < 4; $g++) {
            $donnees = explode("-", $weeks[$g]); // recuperation des donnees d'imputations

            $dateVs = $dateVRepository->findAll(); // liste des imputations
            $nombreuser = 0; // initialisation du nombres de collaborateurs dans le tableau 

            $codeprojet = []; // creation du tableau des projets
            $tache = []; // creation du tableau des taches
            $activite = []; // creation du tableau des activités
            $total = []; // total des imputations
            $total[0] = 0;
            $me = $this->getUser(); // recuperation du collaborateur connecté

            $userlist = []; // tableau des collaborateurs
            $user = $userRepository->findAll(); // liste des collaborateurs

            // Dispatching des collaborateurs 
            foreach ($user as $users) {
                // Condition pour recuperer que les collaborateurs suivis
                if ($users->getFlag() && ($users->getProjet() == $me->getProjet() || ($users->getProjet()->getLibelle() == 'NRJ' && $me->getProjet()->getLibelle() != 'CLOE') || ($users->getProjet()->getLibelle() == 'DECO' && $me->getProjet()->getLibelle() != 'CLOE'))) {
                    // Ajout des collaborateurs dans la liste
                    $userlist[] = [
                        'id' => $users->getId(),
                        'email' => $users->getEmail(),
                        'username' => $users->getUsername(),
                        'domaine' => $users->getProjet()->getLibelle(),
                        'codeprojet' => $codeprojet,
                        'tache' => $tache,
                        'activité' => $activite,
                        'total' => $total,
                        'nbr' => 0,
                        'valeur' => 0,
                    ];
                    $nombreuser++; // Incrementation du nombre de collaborateurs
                }
            }

            $verif = 0; // Pour vérifier si l'imputation n'est pas deja enregistré dans le tableau

            // Dispatching des imputations
            foreach ($dateVs as $dateV) {
                $dmy = $dateV->getDate()->format('d-m-Y'); // recuperation des dates
                $week = "W" . date("W", strtotime($dmy)); // recuperation du numéro de semaine a partir de la date
                $i = 0; // initialisation du nombre de jours
                do {
                    $bool = 0; //boolean pour savoir si on est entrer dans la condition
                    //condition pour voir si l'imputation correspond à la semaine 
                    if (!empty ($userlist[$i]['id']) && $dateV->getDate()->format('Y') == $donnees[0] && $week == $donnees[1] && $dateV->getImput()->getUser()->getId() == $userlist[$i]['id']) {
                        // Remplissage du tableau
                        $userlist[$i]['valeur'] = $userlist[$i]['valeur'] + $dateV->getValeur();
                        $userlist[$i]['codeprojet'][$userlist[$i]['nbr']] = $dateV->getCodeprojet()->getLibelle()/* . ": " . $dateV->getCodeprojet()->getDescription()*/ ;
                        $userlist[$i]['tache'][$userlist[$i]['nbr']] = $dateV->getTache()->getLibelle()/* . ": " . $dateV->getTache()->getDescription()*/ ;
                        $userlist[$i]['activité'][$userlist[$i]['nbr']] = $dateV->getActivite()->getLibelle()/* . ": " . $dateV->getActivite()->getDescription()*/ ;

                        $bool = 1; // incrementation 
                        // condition pour voir si l'imputation n'est pas deja dans le tableau
                        if ($dateV->getImput()->getId() != $verif && $verif != 0) {
                            $userlist[$i]['nbr']++;
                            $userlist[$i]['total'][$userlist[$i]['nbr']] = 0;
                        }
                        $verif = $dateV->getImput()->getId();
                    }
                    $i++; // incrementation du nombre de jours
                } while ($i < $nombreuser || $bool == 1);

                $bool2 = 0; //boolean pour savoir si on est entrer dans la condition
                //boucle (secours) pour remplire le tableau des total imputation
                for ($i = 0; $i < $nombreuser; $i++) {
                    if ($dateV->getDate()->format('Y') == $donnees[0] && $week == $donnees[1] && $dateV->getImput()->getUser()->getId() == $userlist[$i]['id'] && $dateV->getImput()->getId() == $verif) {
                        $bool2 = 1;
                        $userlist[$i]['total'][$userlist[$i]['nbr']] += $dateV->getValeur();
                    }
                    if ($dateV->getImput()->getId() != $verif && $verif != 0 && $bool2 == 1) {
                        $userlist[$i]['nbr']++;
                        $userlist[$i]['total'][$userlist[$i]['nbr']] = 0;
                        $bool2 = 0;
                    }
                    $verif = $dateV->getImput()->getId();
                }
            }

            // Remplissage du tableau des collaborateurs et leus imputations
            $userlisteTotal[$g] = $userlist;
        }

        return $this->render('suivImput/suivi_mois.html.twig', [
            'userlisteTotal' => $userlisteTotal,
            'weeks' => $weeks,
        ]);
    }

    /**
     * @Route("/envoirelance", name="envoyer_relance");
     */
    public function envoiRelance(MailerInterface $mailer, UserRepository $userRepository, DateVRepository $dateVRepository, ImputRepository $imputRepository, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $selectedCollaborators = $data['selectedCollaborators'];

        if (!empty ($data)) {
            foreach ($selectedCollaborators as $userId) {
                $LeUser = $userRepository->find($userId);
                $emailUser = $LeUser->getEmail();

                $email = (new Email())
                    ->from('abass.diene@capgemini.com')
                    ->to($emailUser)
                    ->cc('marouane.chaker-lamrani@capgemini.com')
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject('Rappel Imputation SIPRA')
                    ->html('<p>Bonjour,</p><br><br><p>Ceci est un rappel pour effectuer vos imputations SIPRA</p><br><br><p>Merci</p>');
                try {
                    $mailer->send($email);
                } catch (\Throwable $th) {
                    throw $th;
                }
            }
            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse(['success' => false]);
        }
    }
}
