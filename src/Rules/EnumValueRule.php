<?php

namespace EnumTools\Rules;

use Illuminate\Contracts\Validation\Rule;

class EnumValueRule implements Rule
{
    public function __construct(protected string $enumClass)
    {
    }

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (!enum_exists($this->enumClass)) return false;

        return in_array($value,
            array_column($this->enumClass::cases(), 'value'),
            true);
    }

    /**
     * @return string
     */
    public function message(): string
    {
        $key = 'validation.enum';
        $translated = __($key);

        return $translated !== $key
            ? $translated
            : 'The selected :attribute is invalid.';
    }
}
