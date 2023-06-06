<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Support\Arr;
use TPG\IsPresentable\Contracts\PresenterInterface;

class Presenter implements PresenterInterface
{
    /**
     * @param  array<string, mixed>  $presentables
     */
    public function __construct(protected array $presentables)
    {
    }

    public function __get(string $key): mixed
    {
        return Arr::get(
            $this->presentables,
            $key,
            null
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->presentables;
    }
}
