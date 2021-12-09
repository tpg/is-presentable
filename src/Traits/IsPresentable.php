<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use TPG\IsPresentable\Exceptions\InvalidPresentableClass;
use TPG\IsPresentable\Presenter;

trait IsPresentable
{
    protected array $presentables = [];

    public function presentable(): Presenter
    {
        return new Presenter($this->getPresenters());
    }

    public function toArray(): array
    {
        return array_merge(
            $this->getOriginalAttributes(),
            [
                config('presentable.key') => $this->getPresenters(),
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

    protected function getPresenters(): array
    {
        $presenters = collect($this->presenters)->merge($this->getPresentableMethods());

        return $presenters->mapWithKeys(function (string|ReflectionMethod $value, $key) {

            if (is_string($value)) {
                return [$key => $this->renderClass($value)];
            }

            $name = Str::after($value->name, 'presentable');

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

    protected function renderClass(string $className): string
    {
        if (! class_exists($className)) {
            throw new InvalidPresentableClass($className);
        }

        return (new $className($this))?->render() ?? '';
    }
}
