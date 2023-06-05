<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Tests;

use TPG\IsPresentable\Contracts\IsHidden;
use TPG\IsPresentable\Presentable;

class HiddenPresenter extends Presentable implements IsHidden
{
    public function render(): string|null
    {
        return 'hidden';
    }
}
