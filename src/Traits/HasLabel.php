<?php

namespace EnumTools\Traits;

trait HasLabel
{
    /**
     * @return string
     */
    public function label(): string
    {
        $translationKey = $this->getTranslationKey();

        $translated = __($translationKey);

        if ($translated !== $translationKey) {
            return $translated;
        }

        return ucwords(strtolower(str_replace('_', ' ', $this->name)));
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
}
