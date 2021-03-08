<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use TPG\IsPresentable\Contracts\PresenterInterface;

class Presenter implements PresenterInterface
{
    public function __construct(protected Model $model, protected array $presentables)
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
}
