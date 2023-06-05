<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Tests;

use TPG\IsPresentable\Presentable;

class DefaultPresenter extends Presentable
{
    public function render(): string|null
    {
        return 'default-presenter';
    }
}
