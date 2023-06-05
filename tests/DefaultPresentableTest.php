<?php

declare(strict_types=1);

use TPG\IsPresentable\Tests\DefaultPresenter;
use TPG\IsPresentable\Tests\User;

it('can have default presentables', function () {
    config(['presentable.defaults' => [
        'default' => DefaultPresenter::class,
    ]]);

    $user = new User();

    expect($user->presentable()->default)->toBe('default-presenter');
});

//namespace TPG\IsPresentable\Tests;
//
//class DefaultPresentableTest extends TestCase
//{
//    /**
//     * @test
//     **/
//    public function it_can_have_default_presentables(): void
//    {
//        config(['presentable.defaults' => [
//            'default' => DefaultPresenter::class,
//        ]]);
//
//        $user = new User();
//
//        self::assertSame('default-presenter', $user->presentable()->default);
//    }
//}
