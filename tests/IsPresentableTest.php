<?php

declare(strict_types=1);

namespace TPG\Tests;

class IsPresentableTest extends TestCase
{
    /**
     * @test
     **/
    public function models_can_have_presentable_methods(): void
    {
        $user = new User([
            'name' => 'Test User',
        ]);

        self::assertSame('presentable-test', $user->present()->test);
    }

    /**
     * @test
     **/
    public function presentables_are_included_in_the_model_array(): void
    {
        $user = new User([
            'name' => 'Test User',
        ]);

        self::assertSame([
            'name' => 'Test User',
            'presentable' => [
                'test' => 'presentable-test',
            ],
        ], $user->toArray());
    }

    /**
     * @test
     **/
    public function it_will_return_null_if_presentable_method_doesnt_exist()
    {
        $user = new User([
            'name' => 'Test User',
        ]);

        self::assertNull($user->present()->nothing);
    }
}
