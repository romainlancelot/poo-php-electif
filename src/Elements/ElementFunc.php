<?php

namespace Eliot\Elements;


function getElementRatio(Element $caster, Element $target): float
    {
    return match ([$caster, $target]) {
    [Element::Fire, Element::Water], [Element::Plant, Element::Fire], [Element::Water, Element::Plant] => 0.5,
    [Element::Fire, Element::Plant], [Element::Water, Element::Fire], [Element::Plant, Element::Water] => 1.5,
    default => 1,
    };
}