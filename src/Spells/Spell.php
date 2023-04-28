<?php

namespace Eliot\Spells;
use Eliot\Buff\Buff;
use Eliot\Character\Character;
use Eliot\Spells\SpellKind;

class Spell {

    public function __construct(
        protected string $name,
        protected string $description,
        protected int $mana_cost,
        protected int $value,
        protected int $coolDown,
        protected int $baseCoolDown,
        protected SpellKind $kind,
    ){}

    public function cast(Character $caster, Character $target): void
    {
        
        switch ($this->kind) {
            case SpellKind::Heal:
                $target->heal($this->value);
                break;
            // case SpellKind::Defense:
            //     $buff = new Buff(SpellKind::Defense, $this->value);
            //     $target->pushBuff($buff);
            //     break;
            case SpellKind::Attack:
                $target->takesDamagesFromSpell($caster, $this->value, $this->name);
                break;
            default:
                $target->takesDamagesFromSpell($caster, $this->value);
                break;
        }
        $this->coolDown = $this->baseCoolDown;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getManaCost(): int
    {
        return $this->mana_cost;
    }

    public function getKind(): SpellKind
    {
        return $this->kind;
    }

    public function getBaseCoolDown(): int
    {
        return $this->baseCoolDown;
    }

    public function getCoolDown(): int
    {
        return $this->coolDown;
    }

    public function setCoolDown(int $coolDown): void
    {
        $this->coolDown = $coolDown;
    }
}

?>