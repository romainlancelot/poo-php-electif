<?php

namespace Eliot;

use Eliot\Weapon\Weapon;

trait HasWeapon
{

    public function takesWeapon(Weapon $weapon): void
    {
        $this->weapon = $weapon;
    }

    public function dropsWeapon(): void
    {
        if ($this->hasWeapon()) {
            $this->weapon = null;
        }
    }

    public function hasWeapon(): bool
    {
        return $this->weapon instanceof Weapon;
    }
}
