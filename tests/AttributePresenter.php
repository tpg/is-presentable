<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Tests;

use TPG\IsPresentable\Presentable;

class AttributePresenter extends Presentable
{
    public function render(): string|null
    {
        return $this->model->{$this->attribute};
    }
}
