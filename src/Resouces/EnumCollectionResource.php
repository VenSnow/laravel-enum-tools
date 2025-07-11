<?php

namespace EnumTools\Resouces;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EnumCollectionResource extends ResourceCollection
{
    public static function from(string $enumClass): self
    {
        return new self($enumClass::cases());
    }

    public function toArray($request)
    {
        return $this->collection->map(function ($case) {
            return [
                'label' => method_exists($case, 'label') ? $case->label() : $case->name,
                'value' => $case->value,
            ];
        })->values();
    }
}
