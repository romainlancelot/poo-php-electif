<?php

namespace Eliot\Weapon;

class PhysicalWeapon extends Weapon
{
    public function __construct(
        string $name,
        string $description,
        protected int $attackDamages
    ) {
        parent::__construct($name, $description);
    }

    public function getAttackDamages(): float 
    {
        return $this->attackDamages;
    }
}
