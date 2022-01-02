<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use TPG\IsPresentable\Contracts\IsHidden;
use TPG\IsPresentable\Exceptions\InvalidPresentableClass;

class IsPresentableService
{
    public function getPresentables(object $object, bool $excludeHidden = false): array
    {
        $defaults = $this->getDefaults($object, $excludeHidden);

        $presenters = collect($object->getPresentables())->merge($this->getPresentableMethods($object));

        return $defaults->merge($presenters->mapWithKeys(function (string|array|ReflectionMethod $value, $key) use ($object, $excludeHidden) {
            if (is_array($value) || is_string($value)) {
                return $this->renderClass($object, $value, $key, $excludeHidden);
            }

            $name = Str::after($value->name, 'presentable');

            return [Str::snake($name) => $object->{'presentable'.$name}()];
        }))->toArray();
    }

    protected function getDefaults(object $object, bool $excluseIfHidden): Collection
    {
        return collect(config('presentable.defaults'))
            ->mapWithKeys(fn (string $presentableClass, $attribute) => $this->renderClass($object, $presentableClass, $attribute, $excluseIfHidden));
    }

    public function getPresentableMethods(object $class): Collection
    {
        $reflection = new ReflectionClass($class);

        return collect($reflection->getMethods())
            ->filter(
                fn (ReflectionMethod $method) => $method->name !== 'presentable'
                    && Str::startsWith($method->name, 'presentable')
            );
    }

    public function renderClass(object $object, string|array $className, string $attribute, bool $excludeIfHidden = false): array
    {
        $class = $className;
        $option = null;

        if (is_array($class)) {
            [$class, $option] = $className;
        }

        if (! class_exists($class)) {
            throw new InvalidPresentableClass($class);
        }

        if ($excludeIfHidden && (new ReflectionClass($class))->implementsInterface(IsHidden::class)) {
            return [];
        }

        return [$attribute => (new $class($object, $attribute, $option))?->render() ?? ''];
    }
}
