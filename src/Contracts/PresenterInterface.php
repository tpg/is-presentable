<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PresenterInterface
{
    public function __construct(Model $model, array $presentables);

    public function __get(string $key): mixed;
}
