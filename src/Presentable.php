<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

abstract class Presentable
{
    public function __construct(
        protected object $model,
        protected string $attribute,
        protected mixed $option = null
    ) {
    }

    public function render(): string|null
    {
        return '';
    }
}
