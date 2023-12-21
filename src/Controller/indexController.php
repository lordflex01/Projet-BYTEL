<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CodeProjetRepository;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class IndexController extends AbstractController
{
    /**
     * @Route("/index");
     */

    public function index(ProjetRepository $projetRepository, CodeProjetRepository $codeProjetRepository, UserRepository $userRepository)
    {
        $ProjetFait = $projetRepository->findBy(['statut' => 0]);
        $ProjetRest = $projetRepository->findBy(['statut' => 1]);


        $totalBudgetCloe = $codeProjetRepository->createQueryBuilder('c')
        ->select('SUM(c.budgetCLOE) as CLOEBud')
        ->getQuery()
        ->getResult();

        $totalDepenseCloe = $codeProjetRepository->createQueryBuilder('dc')
        ->select('SUM(dc.budgetCLOEConsomme) as CLOEDep')
        ->getQuery()
        ->getResult();
    
        $totalBudgetDeco = $codeProjetRepository->createQueryBuilder('d')
        ->select('SUM(d.budgetDECO) as DECOBud')
        ->getQuery()
        ->getResult();

        $totalDepenseDeco = $codeProjetRepository->createQueryBuilder('dd')
        ->select('SUM(dd.budgetDECOConsomme) as DECODep')
        ->getQuery()
        ->getResult();

        $totalBudgetNrj = $codeProjetRepository->createQueryBuilder('n')
        ->select('SUM(n.budgetNRJ) as NRJBud')
        ->getQuery()
        ->getResult();

        $totalDepenseNrj = $codeProjetRepository->createQueryBuilder('dn')
        ->select('SUM(dn.budgetNRJConsomme) as NRJDep')
        ->getQuery()
        ->getResult();

        return $this->render('accueil/index.html.twig', [
            'projets' => $projetRepository->findAll(),
            'code_projets' => $codeProjetRepository->findAll(),
            'users' => $userRepository->findAll(),
            'userProjet' => $userRepository->findByProject(),
            'totalBudgetCloe' => $totalBudgetCloe,
            'totalBudgetDeco' => $totalBudgetDeco,
            'totalBudgetNrj' => $totalBudgetNrj,
            'totalDepenseCloe' => $totalDepenseCloe,
            'totalDepenseDeco' => $totalDepenseDeco,
            'totalDepenseNrj' => $totalDepenseNrj,
        ]);
    }

}
