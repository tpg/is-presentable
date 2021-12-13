<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Traits;

use TPG\IsPresentable\IsPresentableService;
use TPG\IsPresentable\PresentableCollection;
use TPG\IsPresentable\Presenter;

trait IsPresentable
{
    public function presentable(): Presenter
    {
        return new Presenter($this->instance()->getPresentables($this));
    }

    public function setPresentables(array $presentable): void
    {
        $this->presentables = $presentable;
    }

    public function getPresentables(): array
    {
        return $this->isPresentableCollection()->toArray();
    }

    abstract public function isPresentableCollection(): PresentableCollection;

    public function toArray(): array
    {
        return array_merge(
            $this->getOriginalAttributes(),
            [
                config('presentable.key') => $this->instance()->getPresentables($this, true),
            ],
        );
    }

    protected function getOriginalAttributes(): array
    {
        if (method_exists(parent::class, 'toArray')) {
            return parent::toArray();
        }

        return [];
    }

    protected function instance(): IsPresentableService
    {
        return app('is-presentable');
    }
}
