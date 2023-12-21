<?php

namespace App\Controller;

use App\Entity\Taches;
use App\Entity\CodeProjet;
use App\Form\TachesType;
use App\Form\FileUploadType;
use App\Repository\TachesRepository;
use App\Repository\CodeProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\FileUploader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * @Route("/taches")
 */
class TachesController extends AbstractController
{
    /**
     * @Route("/", name="taches_index", methods={"GET"})
     */
    public function index(TachesRepository $tachesRepository, CodeProjetRepository $codeProjetRepository): Response
    {
        return $this->render('taches/index.html.twig', [
            'taches' => $tachesRepository->findAll(),
            'code_projets' => $codeProjetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="taches_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tach = new Taches();
        $form = $this->createForm(TachesType::class, $tach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tach);
            $entityManager->flush();

            return $this->redirectToRoute('code_projet_index');
        }

        return $this->render('taches/new.html.twig', [
            'tach' => $tach,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="taches_show", methods={"GET"})
     */
    public function show(Taches $tach): Response
    {
        return $this->render('taches/show.html.twig', [
            'tach' => $tach,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="taches_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Taches $tach): Response
    {
        $form = $this->createForm(TachesType::class, $tach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('code_projet_index');
        }

        return $this->render('taches/edit.html.twig', [
            'tach' => $tach,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="taches_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Taches $tach): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tach->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tach);
            $entityManager->flush();
        }

        return $this->redirectToRoute('taches_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/doUpload", name="do-upload", methods={"GET","POST"})
     */

    public function uploadFile(Request $request, FileUploader $file_uploader, TachesRepository $tachesrepository, CodeProjetRepository $codeProjetRepository)
    {
        $codeprojetlistes = $codeProjetRepository->findAll();
        $tachelistes = $tachesrepository->findAll();

        $form = $this->createForm(FileUploadType::class);
        $form->handleRequest($request);

        $tacheExistante = [];
        $nbrAjout = 0;

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['upload_file']->getData();
            $file_name = $file_uploader->upload($file);

            $directory = $file_uploader->getTargetDirectory();
            $full_path = $directory . '/' . $file_name;

            $reader = new Xlsx();
            $reader->setReadDataOnly(TRUE);
            $spreadsheet = $reader->load($full_path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(); // here, the read data is turned into an array

            $em = $this->getDoctrine()->getManager();

            if (!empty($sheetData)) {
                $codePERR = [];
                for ($i = 1; $i < count($sheetData); $i++) { //skipping first row
                    // $tabsplit = explode(';', $sheetData[$i][0]);
                    $tache = new Taches;
                    $codeP = new CodeProjet;
                    $existe = 0;
                    $existingCP = 1;


                    //connaitre le codeprojet
                    foreach ($codeprojetlistes as $codeprojetliste) {
                        if ($sheetData[$i][3] == $codeprojetliste->getLibelle()) {
                            $codeP = $codeprojetliste;
                            $existingCP = 0;
                            break;
                        }
                        $codeP = $codeprojetliste;
                        $existingCP = 1;
                    }
                    //connaitre si la tache existe
                    foreach ($tachelistes as $tacheliste) {
                        if ($sheetData[$i][1] == $tacheliste->getLibelle()) {
                            $existe = 1;
                            $tacheExistante[] = $sheetData[$i][1];
                        }
                    }

                    if ($existe == 0) {
                        if ($existingCP == 0) {
                            $tache->setLibelle($sheetData[$i][1]);
                            $tache->setDescription($sheetData[$i][2]);
                            $tache->setCodeProjet($codeP);
                            $tache->setDomaine($sheetData[$i][4]);
                            $em->persist($tache);
                            $nbrAjout++;
                        }
                        if ($existingCP == 1) {
                            $codePERR[] = $sheetData[$i][3];
                        }
                    }
                }

                $em->flush();
                $nT = count($sheetData) - $nbrAjout - 1;
                $comma_separated = implode(",", $tacheExistante);
                $codePER = implode(",", $codePERR);
                $this->addFlash('success', 'Le nombre de lignes  insérées est : ' . $nbrAjout . ' Le nombre de ligne non insérées est : ' . $nT);
                $this->addFlash('default', ' Les taches doublantes sont :' . $comma_separated);
                if ($codePER != null) {
                    $this->addFlash('danger', ' Erreur de saisie pour le code projet : ' . $codePER);
                }

                //  return $this->redirectToRoute('taches_index');

            } else {
            }
        }
        return $this->render('taches/upload.html.twig', [
            'formUp' => $form->createView(),
        ]);
    }
}
