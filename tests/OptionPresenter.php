<?php

declare(strict_types=1);

namespace TPG\Tests;

use TPG\IsPresentable\Presentable;

class OptionPresenter extends Presentable
{
    public function render(): string|null
    {
        return implode(' + ', $this->option);
    }
}
