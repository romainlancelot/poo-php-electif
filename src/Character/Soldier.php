<?php

namespace Eliot\Character;
require_once "functions.php";
class Soldier extends Character
{
    public function __construct($health, $defenseRatio, $attackDamages, $magicDamages, $element, $spells)
    {
        parent::__construct($health, $defenseRatio, $attackDamages, $magicDamages, $element, $spells);
    }

    protected function takesMagicalDamagesFrom(Character $character):float
    {
        echo "Ce n'est pas très efficace…".PHP_EOL;
        return $character->getMagicDamages()*0.8;
    }

    public function getAttackDamages(): float
    {
        if (chance(10)) {
            echo "Coup critique !".PHP_EOL;
            return $this->attackDamages*2;
        }
        return parent::getAttackDamages();
    }
}
