<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use TPG\IsPresentable\Contracts\PresenterInterface;

class Presenter implements PresenterInterface
{
    protected Model $model;
    protected array $presentables;

    public function __construct(Model $model, array $presentables)
    {
        $this->model = $model;
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
