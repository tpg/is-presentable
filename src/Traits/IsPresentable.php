<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use TPG\IsPresentable\Presenter;

trait IsPresentable
{
    protected $presentables = [];

    public function present(): Presenter
    {
        return new Presenter($this, $this->getPresentables());
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'presentable' => $this->getPresentables(),
            ],
        );
    }

    protected function getPresentables(bool $refresh = false): array
    {
        if ($this->presentables && ! $refresh) {
            return $this->presentables;
        }

        return $this->presentables = $this->getPresentableMethods()->mapWithKeys(function (\ReflectionMethod $method) {
            $name = Str::after($method->name, 'presentable');

            return [Str::snake($name) => $this->{'presentable'.$name}()];
        })->toArray();
    }

    protected function getPresentableMethods(): Collection
    {
        $reflection = new \ReflectionClass($this);

        return collect($reflection->getMethods())
            ->filter(
                fn (\ReflectionMethod $method) => Str::startsWith($method->name, 'presentable')
            );
    }
}
