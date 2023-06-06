<?php

declare(strict_types=1);

namespace TPG\IsPresentable\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use TPG\IsPresentable\IsPresentableService;
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
        return $this?->presentables ?? [];
    }

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

    /**
     * @return Attribute<Presenter>
     */
    public function present(): Attribute
    {
        return Attribute::get(fn () => $this->presentable());
    }
}
