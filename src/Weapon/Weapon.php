<?php

namespace Eliot\Weapon;

use Eliot\Contracts\DealsPhysicalDamages;
use Eliot\Contracts\DealsMagicDamages;

abstract class Weapon implements DealsPhysicalDamages, DealsMagicDamages
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
    ) {}

    public function getAttackDamages(): float
    {
        return 0;
    }

    public function getMagicDamages(): float
    {
        return 0;
    }

    public function __toString()
    {
        return "{$this->name}, {$this->description}";
    }
}
