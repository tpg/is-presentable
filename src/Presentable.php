<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Database\Eloquent\Model;

abstract class Presentable
{
    public function __construct(protected Model $model)
    {
    }

    public function render(): string|null
    {
        return '';
    }
}
