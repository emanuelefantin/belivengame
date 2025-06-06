<?php

namespace App\Helpers;

class GameHelper
{
    public static function calcComplexity(int $xp){
        return $xp * 1.2;
    }
    
    public static function calcBudget(int $xp){
        return $xp * 1.2;
    }
}