<?php

declare(strict_types=1);

namespace TPG\Tests;

class IsHiddenTest extends TestCase
{
    /**
     * @test
     **/
    public function it_will_exclude_presenters_marked_as_hidden_from_array(): void
    {
        $user = new User([
            'name' => 'Test User',
        ]);

        self::assertSame('hidden', $user->presentable()->hidden);
        $this->assertArrayNotHasKey('hidden', $user->toArray()['presentable']);
    }
}
