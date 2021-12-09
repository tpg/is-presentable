<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Database\Eloquent\Model;

abstract class Presentable
{
    public function __construct(
        protected Model $model,
        protected string $attribute,
        protected mixed $option = null
    ) {
    }

    public function render(): string|null
    {
        return '';
    }
}
