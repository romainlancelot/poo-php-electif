<?php

namespace Eliot\Combat;
use Eliot\Character\Character;
use Eliot\Weapon\PhysicalWeapon;
use Eliot\Weapon\MagicWeapon;
use Eliot\Weapon\Weapon;
use Eliot\Spells\Spell;
use Eliot\Spells\SpellKind;
// use Eliot\Buff\Buff;

class Combat {

    private array $weapons;

    public function __construct(private array $myTeam, private array $enemyTeam) {
        $this->weapons = [
            new PhysicalWeapon("Short Sword", "A small sword", 4),
            new PhysicalWeapon("Light Axe", "A light axe", 4),
            new PhysicalWeapon("Light Spear", "A light spear", 4),
            new PhysicalWeapon("Short Bow", "A short bow", 4),
            new PhysicalWeapon("Broadsword", "A large sword", 6),
            new PhysicalWeapon("Great Axe", "A pretty big axe", 6),
            new PhysicalWeapon("Spear", "A nice spear", 6),
            new PhysicalWeapon("Bow", "A precise bow", 6),
            new PhysicalWeapon("Zweihander", "A two-handed sword capable of slicing a horse in half", 9),
            new PhysicalWeapon("Battleaxe", "An excellent axe ready for war", 9),
            new PhysicalWeapon("Pike", "A long spear, used to stop cavalry charges", 9),
            new PhysicalWeapon("Longbow", "A massive and deadly bow", 9),

            new MagicWeapon("Old Staff", "An old staff", 4),
            new MagicWeapon("Old Book", "An old book", 4),
            new MagicWeapon("Broken Catalyst", "A broken orb", 4),
            new MagicWeapon("Magic Staff", "A magic staff for a good magician", 6),
            new MagicWeapon("Spell Book", "A magic book for the academy", 6),
            new MagicWeapon("Shining Catalyst", "A catalyst to concentrate your powers", 6),
            new MagicWeapon("Dragon Staff", "The powerful staff from the dragon wizard", 9),
            new MagicWeapon("Necronomicon", "The forbidden book", 9),
            new MagicWeapon("Draining Orb", "A powerful catalyst known to drain his user's saninty", 9)
        ];
        // $this->weapons = [$shortSword, $lightAxe, $lightSpear, $shortBow, $broadSword, $greatAxe, $spear, $bow, $zweihander, $battleAxe, $pike, $longBow, $oldStaff, $oldBook, $brokenCatalyst, $magicStaff, $spellBook, $shiningCatalyst, $dragonStaff, $necronomicon, $drainingOrb];
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
            $health_start = $attackee->getHealth();

            if (in_array($attacker, $this->myTeam) && count($attacker->getSpells()) > 0) {
                echo "Member of my team attacks !".PHP_EOL;
                echo "Do you want use a spell ? (y/n) ";
                $spell = readline();
                if ($spell == "y") {
                    foreach ($attacker->getSpells() as $k=>$s) {
                        echo $k . " - " . $s->getName() . " (" . $s->getManaCost() . " mana cost)" . PHP_EOL;
                    }
                    echo "Choose your spell ({$attacker->getMana()} mana left) : ";
                    do {
                        $spellKey = (int) readline();
                        if (!isset($attackee->getSpells()[$spellKey])) {
                            echo "Invalid spell, choose again ('q' to cancel) : ";
                        } 
                        if (isset($attacker->getSpells()[$spellKey]) && $attacker->getMana() < $attacker->getSpells()[$spellKey]->getManaCost()) {
                            echo "Not enough mana, choose again ('q' to cancel) : ";
                        }
                        if ($spellKey == "q") {
                            $q = True;
                            break;
                        }
                    } while (!isset($attacker->getSpells()[$spellKey]));
                    if (isset($q) && $q) { continue; }
                    $attacker->getSpells()[$spellKey]->cast($attacker, $attackee);
                    $attacker->removeMana($attacker->getSpells()[$spellKey]->getManaCost());
                    unset($attacker->getSpells()[$spellKey]);
                } else {

                }
            } elseif (in_array($attacker, $this->enemyTeam)) {
                echo "Member of enemy team attacks !".PHP_EOL;
            }
            
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

            echo "{$attackee} a {$health_start} -> {$attackee->getHealth()} points de vie".PHP_EOL.PHP_EOL;;
            echo PHP_EOL;
            array_unshift($queue, $attacker);
            shuffle($queue);
        }

        $winner = array_pop($queue);

        for($i = 0; $i < count($this->myTeam); $i++) {
            $this->myTeam[$i]->resetHealth();
        }

        return in_array($winner, $this->myTeam);
    }

    function decision(Character $c) {
        echo "It's ".$c."'s turn. What should he do ?";

    }


}