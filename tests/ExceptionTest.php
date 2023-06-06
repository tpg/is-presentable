<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use TPG\IsPresentable\Exceptions\InvalidPresentableClass;
use TPG\IsPresentable\Traits\IsPresentable;

it('will throw an exception if the class is invalid', function () {
    $user = new class extends Model
    {
        use IsPresentable;

        protected $guarded = [];

        protected array $presentables = [
            'bad_presenter' => 'ClassDoesNotExist',
        ];
    };

    $user->presentable()->bad_presenter;

})->throws(InvalidPresentableClass::class);
