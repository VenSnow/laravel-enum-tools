<?php

namespace EnumTools\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use BackedEnum;
use EnumTools\Traits\HasLabel;

class EnumToolsCast implements CastsAttributes
{
    protected string $enumClass;

    /**
     * @param string $enumClass
     */
    public function __construct(string $enumClass)
    {
        if (!enum_exists($enumClass)) {
            throw new InvalidArgumentException("Class {$enumClass} is not exists");
        }

        if (!is_subclass_of($enumClass, BackedEnum::class)) {
            throw new InvalidArgumentException("Class {$enumClass} must implement BackedEnum");
        }

        if (!self::usesTrait($enumClass, HasLabel::class)) {
            throw new InvalidArgumentException("Class {$enumClass} must use the HasLabel trait");
        }

        $this->enumClass = $enumClass;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return BackedEnum|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): BackedEnum|null
    {
        if ($value === null) return null;

        return ($this->enumClass)::tryFrom($value);
    }

    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string|int|null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string|int|null
    {
        if ($value instanceof BackedEnum) return $value->value;

        return $value;
    }

    /**
     * @param string $enumClass
     * @return string
     */
    public static function for(string $enumClass): string
    {
        return static::class . ':' . $enumClass;
    }

    /**
     * @param string $enumClass
     * @param string $trait
     * @return bool
     */
    protected static function usesTrait(string $enumClass, string $trait): bool
    {
        $traits = class_uses_recursive($enumClass);
        return in_array($trait, $traits, true);
    }
}
