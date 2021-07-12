<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CodeProjetRepository;
use App\Repository\TachesRepository;
use App\Repository\DateVRepository;

class StatistiqueController extends AbstractController
{
    /**
     * @Route("/statistiques");
     */

    public function statistique(DateVRepository $dateVRepository, TachesRepository $tachesRepository, CodeProjetRepository $codeProjetRepository)
    {
        //Declaration des variable de statistique
        $dev = [];
        $testeur = [];
        $analyste = [];
        $pilotage = [];
        $i = 0;
        $codeprojets = $codeProjetRepository->findAll();
        $dateVs = $dateVRepository->findAll();

        foreach ($codeprojets as $codeprojet) {
            $dev[$i] = 0;
            $testeur[$i] = 0;
            $analyste[$i] = 0;
            $pilotage[$i] = 0;
            foreach ($dateVs as $dateV) {
                if ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Développeur") {
                    $dev[$i] = $dev[$i] + $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Testeur") {
                    $testeur[$i] = $testeur[$i] + $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Business analyste") {
                    $analyste[$i] = $analyste[$i] + $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Pilotage") {
                    $pilotage[$i] = $pilotage[$i] + $dateV->getValeur();
                }
            }
            $i++;
        }



        return $this->render('statistique/statistique.html.twig', [
            'dev' => $dev,
            'testeur' => $testeur,
            'analyste' => $analyste,
            'pilotage' => $pilotage,
            'code_projets' => $codeProjetRepository->findAll(),
            'taches' => $tachesRepository->findAll(),
        ]);
    }
}
