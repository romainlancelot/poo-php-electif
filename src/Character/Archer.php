<?php

namespace Eliot\Character;

use Exception;
require_once "functions.php";
class Archer extends Character
{
    public function __construct($health, $defenseRatio, $attackDamages, $magicDamages, $element, $spells)
    {
        if ($magicDamages > $attackDamages) {
            throw new Exception("The archer cannot have more magic damages than physical damages.");
        }
        parent::__construct($health, $defenseRatio, $attackDamages, $magicDamages, $element, $spells);
    }

    public function getAttackDamages(): float
    {
        if (chance(20)) {
            echo "Coup critique !".PHP_EOL;
            return $this->attackDamages*1.2;
        }
        return parent::getAttackDamages();
    }

    public function getDefenseRatio():float
    {
        if (chance(10)) {
            echo "Esquive !".PHP_EOL;
            return 1;
        }
        return parent::getDefenseRatio();
    }
}
