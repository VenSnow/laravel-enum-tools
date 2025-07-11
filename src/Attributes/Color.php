<?php

namespace EnumTools\Attributes;

use Attribute;

#[Attribute]
class Color
{
    public function __construct(public string $value)
    {
    }
}
