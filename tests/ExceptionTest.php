<?php

declare(strict_types=1);

namespace TPG\Tests;

use Illuminate\Database\Eloquent\Model;
use TPG\IsPresentable\Exceptions\InvalidPresentableClass;
use TPG\IsPresentable\Traits\IsPresentable;

class ExceptionTest extends TestCase
{
    /**
     * @test
     **/
    public function it_will_throw_an_exception_if_the_class_is_invalid(): void
    {
        $user = new class extends Model {
            use IsPresentable;

            protected $guarded = [];

            protected $presenters = [
                'bad_presenter' => 'ClassDoesNotExist',
            ];
        };

        $this->expectException(InvalidPresentableClass::class);
        $user->presentable()->bad_presenter;
    }
}
