<?php

namespace Eliot\Menu;

use Eliot\Character\Archer;
use Eliot\Character\Soldier;
use Eliot\Character\Wizard;
use Eliot\Elements\Element;
use Eliot\Combat\Combat;

class MainMenu {

    private $options;
    private $myTeam;

    private int $myGold = 300;

    public function __construct() {
        $this->options = [
            "1. See my Characters",
            "2. Summon new Character (100G)",
            "3. Random fight"
        ];
        $this->myTeam = [];
    }

    function displayOptions() {
        foreach($this->options as $option) {
            echo $option."\n";
        }
    }

    function initMenu(): void
    {
        system("clear");
        do {
            echo "Gold : ".$this->myGold."\n";
            echo "=====================\n\n";
            $this->displayOptions();
            echo "\n=====================\n";
            $choice = readline("Pick something : ");
            if ($choice == "exit") {
                break;
            }
            $choice = intval($choice);
            $this->selectOption($choice);
            system("clear");
        } while($choice != "exit");
        system("clear");
    }

    function selectOption($index): void
    {
        switch ($index) {
            case 1:
                $this->displayCharacters();
                break;
            case 2:
                $this->summonCharacter();
                break;
            case 3:
                $this->combat();
                break;
        }
    }

    function displayCharacters():void {
        system('clear');
        $count = count( $this->myTeam);
        if ($count == 0) {
            echo "You have no character.";
        }
        else {
            for ($i = 0; $i < $count; $i++) {
                echo ($i + 1)." ".$this->myTeam[$i]."\n";
            }
        }
        readline();
    }

    function randomCharacter() {
        $random = random_int(0, 2);
        $element = [Element::Fire, Element::Plant, Element::Water];
        $spells = [];
        $random_el = random_int(0, 2);
        $element = $element[$random_el];
        $random_stats = (float)random_int(0, 10);
        $c = null;
        switch ($random) {
            case 0:
                $c = new Archer(
                    300 + $random_stats * 10,
                    $random_stats * 1,
                    50 + $random_stats,
                    0,
                    $element,
                    $spells
                );
                break;
            case 1:
                $c = new Soldier(
                    410 + $random_stats * 12,
                    $random_stats * 2,
                    70 + $random_stats,
                    0,
                    $element,
                    $spells
                );
                break;
            case 2:
                $c = new Wizard(
                    280 + $random_stats * 2,
                    $random_stats,
                    0,
                    $random_stats * 10,
                    $element,
                    $spells
                );
                break;
        }
        return $c;
    }

    function summonCharacter():void {
        if ($this->myGold < 100) {
            echo "Not enough money\n";
            readline();
            return;
        }
        $this->myGold -= 100;

        $this->myTeam[] = $this->randomCharacter();
    }

    function combat() {
        if(!count($this->myTeam)) {
            echo ("No character.\n");
            readline();
            return;
        }
        $enemyTeam = [$this->randomCharacter(), $this->randomCharacter(), $this->randomCharacter()];

        $cbt = new Combat($this->myTeam, $enemyTeam);
        $cbt->start();
        readline();
    }
}

