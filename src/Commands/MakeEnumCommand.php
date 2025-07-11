<?php

namespace EnumTools\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeEnumCommand extends GeneratorCommand
{
    protected $name = 'make:enum';
    protected $description = 'Create a new enum class with HasLabel and attributes';
    protected $type = 'Enum';

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/enum.stub';
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\Enums';
    }

}
