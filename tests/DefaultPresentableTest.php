<?php

declare(strict_types=1);

namespace TPG\Tests;

class DefaultPresentableTest extends TestCase
{
    /**
     * @test
     **/
    public function it_can_have_default_presentables(): void
    {
        config(['presentable.defaults' => [
            'default' => DefaultPresenter::class,
        ]]);

        $user = new User();

        self::assertSame('default-presenter', $user->presentable()->default);
    }
}
