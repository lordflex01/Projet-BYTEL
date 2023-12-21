<?php

namespace App\Service;

use App\Entity\DateV;
use App\Repository\DateVRepository;

class DateVService
{
    private $dateVRepository;

    public function __construct(DateVRepository $dateVRepository)
    {
        $this->dateVRepository = $dateVRepository;
    }

    public function getDateVValuesPerDay($user, $date)
    {
        return $this->dateVRepository->getDateVValuesPerDay($user->getId(), $date);
    }

    public function getDateVValuesPerWeek($user, $weekDate)
    {
        return $this->dateVRepository->getDateVValuesPerWeek($user->getId(), $weekDate);
    }

    public function getSumValuesInDay($user, $date)
    {
        $somme = 0;
        $dateVs = $this->getDateVValuesPerDay($user, $date);
        foreach ($dateVs as $dateV) {
            if ($dateV instanceof DateV) {
                $somme = $somme + $dateV->getValeur();
            }
        }

        return $somme;
    }

    public function getLastDateVInWeek($user, $weekDate){
        $dateVs = $this->getDateVValuesPerWeek($user, $weekDate);
        $lastDateV = null;
        if($dateVs){
            $lastDateV = $dateVs[0];
            foreach ($dateVs as $dateV) {
                if($dateV instanceof DateV){
                    if(($dateV->getDate() > $lastDateV->getDate()) and $dateV->getValeur() != 0 and $dateV->getValeur() != null ){
                        $lastDateV = $dateV;
                    }
                }
            }
        }
        return $lastDateV;
    }
}