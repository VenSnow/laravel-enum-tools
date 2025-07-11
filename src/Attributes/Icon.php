<?php

namespace EnumTools\Attributes;

use Attribute;

#[Attribute]
class Icon
{
    public function __construct(public string $value)
    {
    }
}
