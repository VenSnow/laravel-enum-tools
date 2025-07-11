<?php

namespace EnumTools\Attributes;
use Attribute;

#[Attribute]
class Label
{
    public function __construct(public string $value)
    {
    }
}
