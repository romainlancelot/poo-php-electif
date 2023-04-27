<?php

namespace Eliot\Spell;
use Eliot\Buff\Buff;
use Eliot\Character\Character;

enum SpellKind {
    case Defense;
    case Attack;
    case Heal;
}

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
            case SpellKind::Defense:
                $buff = new Buff(SpellKind::Defense, $this->value);
                $target->pushBuff($buff);
                break;
            default:
                $target->takesDamagesFromSpell($caster, $this->value);
                break;
        }
        $this->coolDown = $this->baseCoolDown;
    }
}

?>