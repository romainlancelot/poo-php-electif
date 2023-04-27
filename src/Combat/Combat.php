<?php

namespace Eliot\Combat;
use Eliot\Character\Character;
use Eliot\Weapon\PhysicalWeapon;
use Eliot\Weapon\MagicWeapon;
use Eliot\Weapon\Weapon;

class Combat {

    private array $weapons;

    public function __construct(private array $myTeam, private array $enemyTeam) {
        $shortSword = new PhysicalWeapon("Short Sword", "A small sword", 4);
        $lightAxe = new PhysicalWeapon("Light Axe", "A light axe", 4);
        $lightSpear = new PhysicalWeapon("Light Spear", "A light spear", 4);
        $shortBow = new PhysicalWeapon("Short Bow", "A short bow", 4);
        $broadSword = new PhysicalWeapon("Broadsword", "A large sword", 6);
        $greatAxe = new PhysicalWeapon("Great Axe", "A pretty big axe", 6);
        $spear = new PhysicalWeapon("Spear", "A nice spear", 6);
        $bow = new PhysicalWeapon("Bow", "A precise bow", 6);
        $zweihander = new PhysicalWeapon("Zweihander", "A two-handed sword capable of slicing a horse in half", 9);
        $battleAxe = new PhysicalWeapon("Battleaxe", "An excellent axe ready for war", 9);
        $pike = new PhysicalWeapon("Pike", "A long spear, used to stop cavalry charges", 9);
        $longBow = new PhysicalWeapon("Longbow", "A massive and deadly bow", 9);

        $oldStaff = new MagicWeapon("Old Staff", "An old staff", 4);
        $oldBook = new MagicWeapon("Old Book", "An old book", 4);
        $brokenCatalyst = new MagicWeapon("Broken Catalyst", "A broken orb", 4);
        $magicStaff = new MagicWeapon("Magic Staff", "A magic staff for a good magician", 6);
        $spellBook = new MagicWeapon("Spell Book", "A magic book for the academy", 6);
        $shiningCatalyst = new MagicWeapon("Shining Catalyst", "A catalyst to concentrate your powers", 6);
        $dragonStaff = new MagicWeapon("Dragon Staff", "The powerful staff from the dragon wizard", 9);
        $necronomicon = new MagicWeapon("Necronomicon", "The forbidden book", 9);
        $drainingOrb = new MagicWeapon("Draining Orb", "A powerful catalyst known to drain his user's saninty", 9);

        $this->weapons = [$shortSword, $lightAxe, $lightSpear, $shortBow, $broadSword, $greatAxe, $spear, $bow, $zweihander, $battleAxe, $pike, $longBow, $oldStaff, $oldBook, $brokenCatalyst, $magicStaff, $spellBook, $shiningCatalyst, $dragonStaff, $necronomicon, $drainingOrb];
    }

    function start() {
        echo "Combat has started\n";
        $outcome = $this->makeTurn();
        echo "You ".($outcome ? "won !" : " lost...");
        return $outcome;
    }

    function chance($percentage): bool
    {
        return rand() % 100 < $percentage;
    }

    function makeTurn(): int
    {
        $myTeamCount = count($this->myTeam);
        $enemyTeamCount = count($this->enemyTeam);
        $max = max($myTeamCount, $enemyTeamCount);
        
        $queue = [];

        for($i = 0; $i < $max; $i++) {
            if($i < $myTeamCount) {
            $queue[] = $this->myTeam[$i];
            }
            if($i < $enemyTeamCount) {
                $queue[] = $this->enemyTeam[$i];
            }
        }
        
        /*foreach($queue as $char) {
            $this->decision($char);
        }*/

        shuffle($queue);

        while (count($queue) > 1) {
            $attacker = array_shift($queue);
            $key = array_rand($queue);
            $attackee = $queue[$key];

            if ($this->chance(25)) {
                echo "Coup de chance ! {$attacker} trouve une arme par terre !" . PHP_EOL;
                if (in_array($attacker, $this->myTeam)) {
                    for ($i = 0; $i < count($this->weapons); $i++) {
                        echo $i . " - " . $this->weapons[$i] . PHP_EOL;
                    }
                    echo "Choose your weapon : ";
                    do {
                        $weaponKey = (int) readline();
                        if (!isset($this->weapons[$weaponKey])) {
                            echo "Invalid weapon, choose again : ";
                        }
                    } while (!isset($this->weapons[$weaponKey]));
                    $attacker->takesWeapon($this->weapons[$weaponKey]);
                } else {
                    $weaponKey = array_rand($this->weapons);
                }
            }

            if (in_array($attacker, $this->myTeam)) {
                echo "Member of my team attacks !".PHP_EOL;
            } else {
                echo "Member of enemy team attacks !".PHP_EOL;
            }
            $attacker->attacks($attackee);
            $attacker->dropsWeapon();

            if ($attackee->isDead()) {
                unset($queue[$key]);
                echo "{$attackee} est mort".PHP_EOL;
                array_unshift($queue, $attacker);
                shuffle($queue);
                if (count($queue) != 1) {
                    echo "Il reste ".count($queue)." joueurs".PHP_EOL.PHP_EOL;
                }
                continue;
            }

            echo "{$attackee} a {$attackee->getHealth()} points de vie".PHP_EOL.PHP_EOL;;
            echo PHP_EOL;
            array_unshift($queue, $attacker);
            shuffle($queue);
        }

        $winner = array_pop($queue);
        return in_array($winner, $this->myTeam);
    }

    function decision(Character $c) {
        echo "It's ".$c."'s turn. What should he do ?";

    }


}