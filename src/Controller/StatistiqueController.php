<?php

namespace App\Controller;

use App\Service\StatistiqueService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\CodeProjetRepository;
use App\Repository\TachesRepository;
use App\Repository\DateVRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class StatistiqueController extends AbstractController
{
    /**
* @IsGranted("ROLE_ADMIN")
     * @Route("/statistiques");
     */

    public function statistique(Request $request,DateVRepository $dateVRepository, TachesRepository $tachesRepository, CodeProjetRepository $codeProjetRepository, StatistiqueService $statistiqueService)
    {
        //Declaration des variable de statistique
        $dev = [];
        $testeur = [];
        $analyste = [];
        $pilotage = [];
        $i = 0;
        $codeprojets = $codeProjetRepository->findAll();
        $dateVs = $dateVRepository->findAll();

        //NRJ
        $devNRJ = [];
        $testeurNRJ = [];
        $analysteNRJ = [];
        $pilotageNRJ = [];

        //DECO
        $devDECO = [];
        $testeurDECO = [];
        $analysteDECO = [];
        $pilotageDECO = [];

        //CLOE
        $devCLOE = [];
        $testeurCLOE = [];
        $analysteCLOE = [];
        $pilotageCLOE = [];

        foreach ($codeprojets as $codeprojet) {
            $dev[$i] = 0;
            $testeur[$i] = 0;
            $analyste[$i] = 0;
            $pilotage[$i] = 0;
//NRJ
            $devNRJ[$i] = 0;
            $testeurNRJ[$i] = 0;
            $analysteNRJ[$i] = 0;
            $pilotageNRJ[$i] = 0;
            //DECO
            $devDECO[$i] = 0;
            $testeurDECO[$i] = 0;
            $analysteDECO[$i] = 0;
            $pilotageDECO[$i] = 0;
            //CLOE
            $devCLOE[$i] = 0;
            $testeurCLOE[$i] = 0;
            $analysteCLOE[$i] = 0;
            $pilotageCLOE[$i] = 0;
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
//NRJ
                if ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Développeur" && $dateV->getTache()->getDomaine() == "NRJ") {
                    $devNRJ[$i] =  $devNRJ[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Testeur" && $dateV->getTache()->getDomaine() == "NRJ") {
                    $testeurNRJ[$i] =  $testeurNRJ[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Business analyste" && $dateV->getTache()->getDomaine() == "NRJ") {
                    $analysteNRJ[$i] =  $analysteNRJ[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Pilotage" && $dateV->getTache()->getDomaine() == "NRJ") {
                    $pilotageNRJ[$i] =  $pilotageNRJ[$i] +  $dateV->getValeur();
                }
                //DECO
                if ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Développeur" && $dateV->getTache()->getDomaine() == "DECO") {
                    $devDECO[$i] =  $devDECO[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Testeur" && $dateV->getTache()->getDomaine() == "DECO") {
                    $testeurDECO[$i] =  $testeurDECO[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Business analyste" && $dateV->getTache()->getDomaine() == "DECO") {
                    $analysteDECO[$i] =  $analysteDECO[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Pilotage" && $dateV->getTache()->getDomaine() == "DECO") {
                    $pilotageDECO[$i] =  $pilotageDECO[$i] +  $dateV->getValeur();
                }
                //CLOE
                if ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Développeur" && $dateV->getTache()->getDomaine() == "CLOE") {
                    $devCLOE[$i] =  $devCLOE[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Testeur" && $dateV->getTache()->getDomaine() == "CLOE") {
                    $testeurCLOE[$i] =  $testeurCLOE[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Business analyste" && $dateV->getTache()->getDomaine() == "CLOE") {
                    $analysteCLOE[$i] =  $analysteCLOE[$i] +  $dateV->getValeur();
                } elseif ($codeprojet == $dateV->getCodeprojet() && $dateV->getImput()->getUser()->getPoste() == "Pilotage" && $dateV->getTache()->getDomaine() == "CLOE") {
                    $pilotageCLOE[$i] =  $pilotageCLOE[$i] +  $dateV->getValeur();
                }
            }
            $i++;
        }

$data = [
            'dev' => $dev,
            'testeur' => $testeur,
            'analyste' => $analyste,
            'pilotage' => $pilotage,
'devNRJ' => $devNRJ,
            'testeurNRJ' => $testeurNRJ,
            'analysteNRJ' => $analysteNRJ,
            'pilotageNRJ' => $pilotageNRJ,
            'devDECO' => $devDECO,
            'testeurDECO' => $testeurDECO,
            'analysteDECO' => $analysteDECO,
            'pilotageDECO' => $pilotageDECO,
            'devCLOE' => $devCLOE,
            'testeurCLOE' => $testeurCLOE,
            'analysteCLOE' => $analysteCLOE,
            'pilotageCLOE' => $pilotageCLOE,
            'code_projets' => $codeProjetRepository->findAll(),
            'taches' => $tachesRepository->findAll()
        ];

        if($request->get('_route') == 'export-statistique'){
            $statistiqueSpreadSheet = $statistiqueService->createStatistiqueSpreadsheet($data);
            $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            $writer = new Xlsx ($statistiqueSpreadSheet);
            $filename = 'statistique.xlsx';

            $response = new StreamedResponse();
            $response->headers->set('Content-Type', $contentType);
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCallback(function() use ($writer) {
                $writer->save('php://output');
            });

            return $response;
        }

        return $this->render('statistique/statistique.html.twig', $data);
    }
}
