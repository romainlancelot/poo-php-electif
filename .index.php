<?php
/*
require_once('./autoload.php');
require_once('./functions.php');

use Eliot\Character\Archer;
use Eliot\Character\Soldier;
use Eliot\Character\Wizard;
use Eliot\Weapon\PhysicalWeapon;
use Eliot\Weapon\MagicWeapon;

const GAME_NUMBER = 100000;
$gameCount = 0;

$wins = [
    Archer::class => 0,
    Wizard::class => 0,
    Soldier::class => 0,
];

while ($gameCount < GAME_NUMBER) {
    $archer = new Archer(health: 100, defenseRatio: 0.05, attackDamages: 16, magicDamages: 4);
    $soldier = new Soldier(110, 0.175, 13);
    $wizard = new Wizard(health: 90, defenseRatio: 0.15, attackDamages: 6, magicDamages: 17);
    
    $infinityEdge = new PhysicalWeapon("Lame d'infini", "Attention ça coupe fort", 5);
    $rodOfAges = new MagicWeapon("Baton des âges", "Ce sacré baton", 6);
    
    $queue = [$archer, $soldier, $wizard];
    $weapons = [$infinityEdge, $rodOfAges];
    
    shuffle($queue);
    
    while (count($queue) > 1) {
        $attacker = array_shift($queue);
        $key = array_rand($queue);
        $attackee = $queue[$key];
    
        if (chance(25)) {
            $weaponKey = array_rand($weapons);
            $attacker->takesWeapon($weapons[$weaponKey]);
        }
        $attacker->attacks($attackee);
        $attacker->dropsWeapon();
    
        if ($attackee->isDead()) {
            unset($queue[$key]);
            // echo "{$attackee} est mort".PHP_EOL;
            // echo PHP_EOL;
            array_unshift($queue, $attacker);
            shuffle($queue);
            continue;
        }
        
        // echo "{$attackee} a {$attackee->getHealth()} points de vie".PHP_EOL;
        // echo PHP_EOL;
        array_unshift($queue, $attacker);
        shuffle($queue);
    }

    $winner = array_pop($queue);
    $wins[$winner::class]++;
    // echo "==================".PHP_EOL;
    // echo "{$winner} a gagné !".PHP_EOL;
    // echo "==================".PHP_EOL;
    // echo PHP_EOL;

    $gameCount++;
    progressBar($gameCount, GAME_NUMBER);
}

echo PHP_EOL;
var_dump(array_map(fn (int $charWins) => ($charWins / GAME_NUMBER * 100)."%", $wins));
