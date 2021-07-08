<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CodeProjetRepository;
use App\Repository\TachesRepository;

class StatistiqueController extends AbstractController
{
    /**
     * @Route("/statistiques");
     */

    public function statistique(TachesRepository $tachesRepository, CodeProjetRepository $codeProjetRepository)
    {
        //Declaration des variable de statistique
        $devNRJ = [];
        $testeurNRJ = [];
        $analysteNRJ = [];
        $pilotageNRJ = [];
        $devDECO = [];
        $testeurDECO = [];
        $analysteDECO = [];
        $pilotageDECO = [];
        $devCLOE = [];
        $testeurCLOE = [];
        $analysteCLOE = [];
        $pilotageCLOE = [];
        $i = 0;
        $codeprojets = $codeProjetRepository->findAll();

        foreach ($codeprojets as $codeprojet) {
        }



        return $this->render('statistique/statistique.html.twig', [
            'code_projets' => $codeProjetRepository->findAll(),
            'taches' => $tachesRepository->findAll(),
        ]);
    }
}
