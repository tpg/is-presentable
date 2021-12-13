<?php

declare(strict_types=1);

namespace TPG\Tests;

use Illuminate\Database\Eloquent\Model;
use TPG\IsPresentable\PresentableCollection;
use TPG\IsPresentable\Traits\IsPresentable;

class User extends Model
{
    use IsPresentable;

    protected $guarded = [];

    public function isPresentableCollection(): PresentableCollection
    {
        return (new PresentableCollection())
            ->add('created_at', CreatedAtPresenter::class)
            ->add('hidden', HiddenPresenter::class);
    }

    public function presentableTest(): string
    {
        return 'presentable-test';
    }
}
