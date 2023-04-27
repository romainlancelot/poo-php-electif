<?php

namespace Eliot\Weapon;

class MagicWeapon extends Weapon
{
    public function __construct(
        string $name,
        string $description,
        protected int $magicDamages
    ) {
        parent::__construct($name, $description);
    }

    public function getMagicDamages(): float
    {
        return $this->magicDamages;
    }
}
