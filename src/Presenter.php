<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Support\Arr;
use TPG\IsPresentable\Contracts\PresenterInterface;

class Presenter implements PresenterInterface
{
    protected array $presentables;

    public function __construct(array $presentables)
    {
        $this->presentables = $presentables;
    }

    public function __get(string $key)
    {
        return Arr::get(
            $this->presentables,
            $key,
            null
        );
    }
}
