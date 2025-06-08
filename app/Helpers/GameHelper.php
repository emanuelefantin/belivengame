<?php

namespace App\Helpers;

class GameHelper
{
    /**
     * Calcola la complessità del progetto in base all'esperienza attuale.
     *
     * @param integer $xp
     * @return void
     */
    public static function calcComplexity(int $xp){
        return $xp * 1.6;
    }
    
    /**
     * Calcola il budget del progetto in base all'esperienza attuale.
     *
     * @param integer $xp
     * @return void
     */
    public static function calcBudget(int $xp){
        return $xp * 1.7;
    }

    /**
     * Calcola l'esperienza guadagnata in base alla complessità del progetto e all'esperienza attuale.
     * Se l'esperienza supera 10.000, non viene guadagnata esperienza.
     * 
     *
     * @param integer $project_complexity
     * @param integer $xp
     * @return void
     */
    public static function calcXpGain(int $project_complexity, int $xp){
        if($xp >= 10000){
            return 0;
        }
        return ($project_complexity / 1000 * rand(5,10));
    }

    /**
     * Calcola l'avanzamento salariale in base alla complessità del progetto, all'esperienza attuale e allo stipendio.
     * Se lo stipendio è superiore a 5000, non viene calcolato alcun guadagno salariale.
     *
     * @param integer $project_complexity
     * @param integer $xp
     * @param float $salary
     * @return void
     */
    public static function calcSalaryGain(int $project_complexity, int $xp, float $salary){
        if($salary >= 5000){
            return 0;
        }
        return ($project_complexity / 1000 * rand(5,10));
    }
}