<?php

declare(strict_types=1);

namespace TPG\Tests;

use Illuminate\Database\Eloquent\Model;
use TPG\IsPresentable\Traits\IsPresentable;

class User extends Model
{
    use IsPresentable;

    protected $guarded = [];

    protected array $presentables = [
        'created_at' => CreatedAtPresenter::class,
        'hidden' => HiddenPresenter::class,
    ];

    public function presentableTest(): string
    {
        return 'presentable-test';
    }
}
