<?php

namespace App\Service;

use App\Entity\CodeProjet;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CodeProjetService
{
    private $fileUploader;
    private $entityManager;

    public function __construct(FileUploader $fileUploader, EntityManagerInterface $entityManager)
    {
        $this->fileUploader = $fileUploader;
        $this->entityManager = $entityManager;
    }

    public function uploadCodeProjets($file)
    {
        $codeProjectRepository = $this->entityManager->getRepository(CodeProjet::class);

        $file_name = $this->fileUploader->upload($file);
        $directory = $this->fileUploader->getTargetDirectory();
        $full_path = $directory . '/' . $file_name;

        $reader = new Xlsx();
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($full_path);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $nbrAdd = 0;
        $notAdd = array();
        $codeProjectsExists = array();
        if (!empty($sheetData)){
            $cmpt = 1;

            // start from the second line
            foreach (array_slice($sheetData,1) as $line){
                if(!empty($line[0])){

                    // check if libelle exists in database
                    $checkLibelle = $codeProjectRepository->findOneBy(['libelle' => $line[0]]);
                    if($checkLibelle instanceof CodeProjet){
                        $codeProjectsExists[] = $line[0];
                        continue;
                    }

                    if(str_starts_with($line[0], 'DECO') or str_starts_with($line[0], 'CLOE')){
                        $line[0] = substr($line[0], 7);
                    }elseif(str_starts_with($line[0], 'NRJ')){
                        $line[0] = substr($line[0], 6);
                    }

                    $codeProjet = new CodeProjet();
                    $codeProjet->setLibelle($line[0]);
                    $codeProjet->setDescription($line[1]);
                    $codeProjet->setBudget($line[2]);
                    $codeProjet->setBudgetNRJ($line[3]);
                    $codeProjet->setBudgetDECO($line[4]);
                    $codeProjet->setBudgetCLOE($line[5]);
                    $codeProjet->setChageJH($line[6]);
                    $codeProjet->setChageNRJ($line[7]);
                    $codeProjet->setChageDECO($line[8]);
                    $codeProjet->setChargeCLOE($line[9]);
                    if($line[10]) $codeProjet->setDateD(Date::excelToDateTimeObject($line[10]));
                    if($line[11]) $codeProjet->setDateF(Date::excelToDateTimeObject($line[11]));
                    $codeProjet->setStatut(true);
                    try {
                        $this->entityManager->persist($codeProjet);
                        $cmpt++;
                        $nbrAdd++;
                    }catch(\Exception $exception){
                        $notAdd[] = $line[0];
                    }
                }
                if($cmpt >= 20){
                    $this->entityManager->flush();
                    $cmpt = 1;
                }
            }
            $this->entityManager->flush();
        }
        return [$nbrAdd, $notAdd, $codeProjectsExists];
    }
}