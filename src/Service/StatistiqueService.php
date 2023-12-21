<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StatistiqueService
{
    public function createStatistiqueSpreadsheet($data)
    {
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        // columns names
        $columnNames = array('libelle', 'description',
            'budget global', 'budget consommé', 'delta',
            'budget NRJ', 'budget NRJ consommé', 'delta NRJ',
            'budget DECO', 'budget DECO consommé', 'delta DECO',
            'budget CLOE', 'budget CLOE consommé', 'delta CLOE',
            // charge par domaine
            // charge NRJ
            'Charges prévues en j/H NRJ', 'Charges Consommées en j/H NRJ', 'DELTA NRJ',
            // charge DECO
            'Charges prévues en j/H DECO', 'Charges Consommées en j/H DECO', 'DELTA DECO',
            // charge CLOE
            'Charges prévues en j/H CLOE', 'Charges Consommées en j/H CLOE', 'DELTA CLOE',
            // charge Total
            'Charges prévues en j/H Total', 'Charges Consommées en j/H Total', 'DELTA Total',

            // charge par poste
            // charge DEV
            'NRJ', 'DECO', 'CLOE', 'Total',
            // charge Testeur
            'NRJ', 'DECO', 'CLOE', 'Total',
            // charge analyste
            'NRJ', 'DECO', 'CLOE', 'Total',
            // charge pilotage
            'NRJ', 'DECO', 'CLOE', 'Total',
        );

        $sheet->setCellValue('A1', 'Projects');
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('F1', 'Budget NRJ');
        $sheet->mergeCells('F1:H1');
        $sheet->setCellValue('I1', 'Budget DECO');
        $sheet->mergeCells('I1:K1');
        $sheet->setCellValue('L1', 'Budget CLOE');
        $sheet->mergeCells('L1:N1');

        // charge par domaine
        $sheet->setCellValue('O1', 'Charge par domaine - NRJ');
        $sheet->mergeCells('O1:Q1');
        $sheet->setCellValue('R1', 'Charge par domaine - DECO');
        $sheet->mergeCells('R1:T1');
        $sheet->setCellValue('U1', 'Charge par domaine - CLOE');
        $sheet->mergeCells('U1:W1');
        $sheet->setCellValue('X1', 'Budget Total');
        $sheet->mergeCells('X1:Z1');

        // charge par poste
        $sheet->setCellValue('AA1', 'Charge par poste - DEV');
        $sheet->mergeCells('AA1:AD1');
        $sheet->setCellValue('AE1', 'Charge par poste - Testeur');
        $sheet->mergeCells('AE1:AH1');
        $sheet->setCellValue('AI1', 'Charge par poste - Analyste');
        $sheet->mergeCells('AI1:AL1');
        $sheet->setCellValue('AM1', 'Charge par poste - Pilotage');
        $sheet->mergeCells('AM1:AP1');

        // Style
        $sheet->getStyle('A1:AP1')->getFont()->setBold(true);
        $sheet->getStyle('A1:AP1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('A1:AP1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);;
        $sheet->getColumnDimension('A')->setAutoSize(true);

        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            $sheet->setCellValue($columnLetter . '2', $columnName);
            $columnLetter++;
        }

        $l = 3;
        $i = 0;
        foreach ($data['code_projets'] as $code_projet) {
            $columnLetter = 'A';

            $columnValue = array(
                $code_projet->getLibelle(),
                $code_projet->getDescription(),
                $code_projet->getBudget(),
                $code_projet->getBudgetConsomme(),
                $code_projet->getBudget() - $code_projet->getBudgetConsomme(),

                $code_projet->getBudgetNRJ(),
                $code_projet->getBudgetNRJConsomme(),
                $code_projet->getBudgetNRJ() - $code_projet->getBudgetNRJConsomme(),

                $code_projet->getBudgetDECO(),
                $code_projet->getBudgetDECOConsomme(),
                $code_projet->getBudgetDECO() - $code_projet->getBudgetDECOConsomme(),

                $code_projet->getBudgetCLOE(),
                $code_projet->getBudgetCLOEConsomme(),
                $code_projet->getBudgetCLOE() - $code_projet->getBudgetCLOEConsomme(),

                // charge par domaine
                // charge NRJ
                $code_projet->getChageNRJ(),
                $code_projet->getChargeNRJConsomme(),
                $code_projet->getChageNRJ() - $code_projet->getChargeNRJConsomme(),
                // charge DECO
                $code_projet->getChageDECO(),
                $code_projet->getChargeDECOConsomme(),
                $code_projet->getChageDECO() - $code_projet->getChargeDECOConsomme(),
                // charge CLOE
                $code_projet->getChargeCLOE(),
                $code_projet->getChargeCLOEConsomme(),
                $code_projet->getChargeCLOE() - $code_projet->getChargeCLOEConsomme(),
                // charge Total
                $code_projet->getChageJH(),
                $code_projet->getChargeConsomme(),
                $code_projet->getChageJH() - $code_projet->getChargeConsomme(),
                // charge par poste
                // charge Dev
                $data['devNRJ'][$i],
                $data['devDECO'][$i],
                $data['devCLOE'][$i],
                $data['dev'][$i],
                // charge Testeur
                $data['testeurNRJ'][$i],
                $data['testeurDECO'][$i],
                $data['testeurCLOE'][$i],
                $data['testeur'][$i],
                // charge Analyste
                $data['analysteNRJ'][$i],
                $data['analysteDECO'][$i],
                $data['analysteCLOE'][$i],
                $data['analyste'][$i],
                // charge Pilotage
                $data['pilotageNRJ'][$i],
                $data['pilotageDECO'][$i],
                $data['pilotageCLOE'][$i],
                $data['pilotage'][$i],
            );

            foreach ($columnValue as $value) {
                $sheet->setCellValue($columnLetter . $l, $value);
                $columnLetter++;
            }
            $i++;
            $l++;
        }

        return $spreadSheet;
    }
}
