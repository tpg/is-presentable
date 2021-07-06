<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use TPG\IsPresentable\Presenter;

trait IsPresentable
{
    protected $presentables = [];

    public function presentable(): Presenter
    {
        return new Presenter($this->getPresentables());
    }

    public function toArray(): array
    {
        return array_merge(
            $this->getOriginalAttributes(),
            [
                config('presentable.key') => $this->getPresentables(),
            ],
        );
    }

    protected function getOriginalAttributes(): array
    {
        if (method_exists(parent::class, 'toArray')) {
            return parent::toArray();
        }

        return [];
    }

    protected function getPresentables(): array
    {
        return $this->getPresentableMethods()->mapWithKeys(function (ReflectionMethod $method) {
            $name = Str::after($method->name, 'presentable');

            return [Str::snake($name) => $this->{'presentable'.$name}()];
        })->toArray();
    }

    protected function getPresentableMethods(): Collection
    {
        $reflection = new ReflectionClass($this);

        return collect($reflection->getMethods())
            ->filter(
                fn (ReflectionMethod $method) => $method->name !== 'presentable'
                    && Str::startsWith($method->name, 'presentable')
            );
    }
}
