<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class PresentableCollection
{
    protected Collection $presentables;

    /**
     * @param  Collection  $presentables
     */
    public function __construct(mixed $presentables = [])
    {
        $this->presentables = new Collection($presentables);
    }


    public function add(string $attribute, string $presentable, array $data = []): self
    {
        $this->presentables[$attribute] = [
            'class' => $presentable,
            'data' => $data,
        ];

        return $this;
    }

    public function get(string $attribute): ?array
    {
        return $this->presentables->get($attribute);
    }

    public function toArray(): array
    {
        return $this->presentables->mapWithKeys(function ($presentable, $attribute) {
            return [
                $attribute => [
                    $presentable['class'],
                    $presentable['data'],
                ]
            ];
        })->toArray();
    }
}
