<?php

namespace EnumTools\Traits;

use EnumTools\Attributes\Color;
use EnumTools\Attributes\Icon;
use EnumTools\Attributes\Label;
use ReflectionEnumUnitCase;

trait HasLabel
{
    /**
     * @return string
     */
    public function label(): string
    {
        if ($label = $this->getAttributeValue(Label::class)) {
            return $label;
        }

        $translationKey = $this->getTranslationKey();
        $translated = __($translationKey);

        if ($translated !== $translationKey) {
            return $translated;
        }

        return ucwords(strtolower(str_replace('_', ' ', $this->name)));
    }

    /**
     * @return string|null
     */
    public function color(): ?string
    {
        return $this->getAttributeValue(Color::class);
    }

    /**
     * @return string|null
     */
    public function icon(): ?string
    {
        return $this->getAttributeValue(Icon::class);
    }

    /**
     * @return string
     */
    protected function getTranslationKey(): string
    {
        $className = class_basename(static::class);
        return "enums.{$className}.{$this->value}";
    }

    /**
     * @return array
     */
    public static function casesForSelect(): array
    {
        return array_map(function (self $case) {
            return [
                'label' => $case->label(),
                'value' => $case->value,
            ];
        }, self::cases());
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * @return array
     */
    public static function labels(): array
    {
        return array_map(fn($case) => $case->label(), self::cases());
    }

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return array_map(fn(self $case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }

    /**
     * @param string $attributeClass
     * @return string|null
     */
    protected function getAttributeValue(string $attributeClass): ?string
    {
        $reflection = new ReflectionEnumUnitCase($this, $this->name);
        $attributes = $reflection->getAttributes($attributeClass);

        return !empty($attributes)
            ? $attributes[0]->newInstance()->value
            : null;
    }
}
