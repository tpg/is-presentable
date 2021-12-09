<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use TPG\IsPresentable\Contracts\IsHidden;
use TPG\IsPresentable\Exceptions\InvalidPresentableClass;
use TPG\IsPresentable\Presenter;

trait IsPresentable
{
    public function presentable(): Presenter
    {
        return new Presenter($this->getPresenters());
    }

    public function toArray(): array
    {
        return array_merge(
            $this->getOriginalAttributes(),
            [
                config('presentable.key') => $this->getPresenters(true),
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

    protected function getPresenters(bool $excludeHidden = false): array
    {
        $presenters = collect($this->presenters)->merge($this->getPresenterMethods());

        return $presenters->mapWithKeys(function (string|ReflectionMethod $value, $key) use ($excludeHidden) {
            if (is_string($value)) {
                return $this->renderClass($value, $key, $excludeHidden);
            }

            $name = Str::after($value->name, 'presentable');

            return [Str::snake($name) => $this->{'presentable'.$name}()];
        })->toArray();
    }

    protected function getPresenterMethods(): Collection
    {
        $reflection = new ReflectionClass($this);

        return collect($reflection->getMethods())
            ->filter(
                fn (ReflectionMethod $method) => $method->name !== 'presentable'
                    && Str::startsWith($method->name, 'presentable')
            );
    }

    protected function renderClass(string $className, string $attribute, bool $excludeIfHidden = false): array
    {
        if (! class_exists($className)) {
            throw new InvalidPresentableClass($className);
        }

        if ($excludeIfHidden && (new ReflectionClass($className))->implementsInterface(IsHidden::class)) {
            return [];
        }

        return [$attribute => (new $className($this))?->render() ?? ''];
    }
}
