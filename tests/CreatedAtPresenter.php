<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Tests;

use TPG\IsPresentable\Presentable;

class CreatedAtPresenter extends Presentable
{
    public function render(): string|null
    {
        return now()->format('d F Y H:i a');
    }
}
