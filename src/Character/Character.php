<?php

namespace Eliot\Character;

use Eliot\Contracts\DealsPhysicalDamages;
use Eliot\Contracts\DealsMagicDamages;
use Eliot\HasWeapon;
use Eliot\Weapon\Weapon;
use Eliot\Elements\Element;
use Eliot\Spell\Spell;
use Eliot\Buff\Buff;

abstract class Character implements DealsPhysicalDamages, DealsMagicDamages
{
    use HasWeapon;

    protected ?Weapon $weapon = null;
    protected Buff $buffs;
    protected int $exp = 0;
    protected int $level = 1;

    protected int $baseHealth;
    protected int $baseMana;

    public function __construct(
        protected float $health,
        protected float $defenseRatio,
        protected int $attackDamages,
        protected int $magicDamages,
        protected Element $element,
        protected Array $spells,
        protected int $mana=100
    ) {
        $this->baseHealth = $health;
        $this->baseMana = $mana;
    }

    public function resetHealth(): void
    {
        $this->health = $this->baseHealth;
        $this->mana = $this->baseMana;
    }

    public function getHealth(): float
    {
        return $this->health;
    }

    public function setHealth(float $health): void
    {
        if ($health < 0) {
            $this->health = 0;
        } else {
            $this->health = round($health, 2);
        }
    }

    static function getElementRatio(Element $caster, Element $target): float
    {
        return match ([$caster, $target]) {
            [Element::Fire, Element::Water], [Element::Plant, Element::Fire], [Element::Water, Element::Plant] => 0.5,
            [Element::Fire, Element::Plant], [Element::Water, Element::Fire], [Element::Plant, Element::Water] => 1.5,
            default => 1,
        };
    }

    public function heal(int $value): void {
        echo "{$this} se soigne de {$value} points de vie !".PHP_EOL;
        $this->health += $value;
    }

    public function isDead(): bool
    {
        return $this->health == 0;
    }

    public function getAttackDamages(): float
    {
        if ($this->hasWeapon()) {
            return $this->attackDamages + $this->weapon->getAttackDamages();
        }
        return $this->attackDamages;
    }

    public function getAttack(): float
    {
        return $this->attackDamages;
    }

    public function getMagicDamages(): float
    {
        if ($this->hasWeapon()) {
            return $this->magicDamages + $this->weapon->getMagicDamages();
        }
        return $this->magicDamages;
    }

    public function getMagic(): float
    {
        return $this->magicDamages;
    }

    public function getDefenseRatio(): float
    {
        return $this->defenseRatio;
    }

    public function getDefense(): float
    {
        return $this->defenseRatio;
    }

    public function getSpells(): array
    {
        return $this->spells;
    }

    public function getMana(): int
    {
        return $this->mana;
    }

    public function removeMana(int $value): void
    {
        $this->mana -= $value;
    }

    public function attacks(Character $character)
    {
        echo "{$this} attaque {$character}";
        if ($this->hasWeapon()) {
            echo " avec {$this->weapon}";
        }
        echo " !".PHP_EOL;

        $character->takesDamagesFrom($this);
    }
    
    public function takesDamagesFrom(Character $character)
    {
        $damages = $this->takesPhysicalDamagesFrom($character) + $this->takesMagicalDamagesFrom($character);
        $damages = (int)($damages * ($this->getDefenseRatio()) *
            Character::getElementRatio($character->getElement(), $this->getElement()));
        echo "{$this} subit {$damages} dégâts !".PHP_EOL;
        $this->setHealth(
            $this->getHealth() - $damages 
        );
    }

    public function takesDamagesFromSpell(Character $character, int $damages, string $name){
        $damages = (int)($damages * ($this->getDefenseRatio()) *
        Character::getElementRatio($character->getElement(), $this->getElement()));
        echo "{$this} subit {$damages} dégâts par le sort {$name} !".PHP_EOL;
        $this->setHealth(
            $this->getHealth() - $damages
        );
    }

    protected function takesPhysicalDamagesFrom(Character $character)
    {
        return $character->getAttackDamages();
    }

    public function getElement(): Element
    {
        return $this->element;
    }

    protected function takesMagicalDamagesFrom(Character $character): float
    {
        return $character->getMagicDamages();
    }

    public function __toString()
    {
        return static::class;
    }

    public function pushBuff(Buff $b) {
        $buffs[] = $b;
    }

    function elementToString() {
        switch ($this->element){
            case (Element::Fire):
                return "Fire";
            case (Element::Water):
                return "Water";
            case (Element::Plant):
                return "Plant";
            default:
                return "neutre";
        }
    }

    public function display() : void {
        echo("Health : ".$this->getHealth()."\n");
        echo("Defense Ratio : ".$this->getDefense()."\n");
        echo("Attack Damage : ".$this->getAttack()."\n");
        echo("Magic Damage : ".$this->getMagic()."\n");
        echo("Element : ".$this->elementToString()."\n");
    }


}
