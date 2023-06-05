<?php

declare(strict_types=1);

use TPG\IsPresentable\Tests\User;

it('will exclude presenters marked as hidden', function () {
    $user = new User([
        'name' => 'Test User',
    ]);

    expect($user->presentable()->hidden)->toBe('hidden')
        ->and(array_key_exists('hidden', $user->toArray()))->toBeFalse();
});

//namespace TPG\IsPresentable\Tests;
//
//class IsHiddenTest extends TestCase
//{
//    /**
//     * @test
//     **/
//    public function it_will_exclude_presenters_marked_as_hidden_from_array(): void
//    {
//        $user = new User([
//            'name' => 'Test User',
//        ]);
//
//        self::assertSame('hidden', $user->presentable()->hidden);
//        $this->assertArrayNotHasKey('hidden', $user->toArray()['presentable']);
//    }
//}
