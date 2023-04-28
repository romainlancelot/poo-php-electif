<?php

namespace Eliot\Character;

use Exception;
require_once "functions.php";

class Rogue extends Character
{
    public function __construct($health, $defenseRatio, $attackDamages, $magicDamages, $element, $spells)
    {
        if ($magicDamages > $attackDamages) {
            throw new Exception("The rogue cannot have more magic damages than physical damages.");
        }
        parent::__construct($health, $defenseRatio, $attackDamages, $magicDamages, $element, $spells);
    }

    public function getMagicDamages(): float
    {
        if (chance(10)) {
            echo "Coup critique !".PHP_EOL;
            return $this->magicDamages*2;
        }
        return parent::getMagicDamages();
    }

    public function getDefenseRatio():float
    {
        if (chance(10)) {
            echo "Esquive !".PHP_EOL;
            return 1;
        }
        return parent::getDefenseRatio();
    }

    public function getAttackDamages(): float
    {
        if (chance(15)) {
            echo "Coup critique !".PHP_EOL;
            return $this->attackDamages*3;
        }
        return parent::getAttackDamages();
    }
}
