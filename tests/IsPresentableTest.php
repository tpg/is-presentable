<?php

declare(strict_types=1);

namespace TPG\Tests;

use Illuminate\Database\Eloquent\Model;
use TPG\IsPresentable\PresentableCollection;
use TPG\IsPresentable\Traits\IsPresentable;

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

        self::assertSame('presentable-test', $user->presentable()->test);
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
                'created_at' => now()->format('d F Y H:i a'),
                'test' => 'presentable-test',
            ],
        ], $user->toArray());
    }

    /**
     * @test
     **/
    public function class_presenters_can_have_options(): void
    {
        $user = new class extends Model
        {
            use IsPresentable;

            protected $guarded = [];

            public function isPresentableCollection(): PresentableCollection
            {
                return (new PresentableCollection())
                    ->add('options', OptionPresenter::class, ['option 1', 'option 2']);
            }
        };

        $this->assertSame('option 1 + option 2', $user->presentable()->options);
    }

    /**
     * @test
     **/
    public function class_presenters_can_use_the_attribute(): void
    {
        $user = new class extends Model
        {
            use IsPresentable;

            public function isPresentableCollection(): PresentableCollection
            {
                return (new PresentableCollection())
                    ->add('name', AttributePresenter::class)
                    ->add('age', AttributePresenter::class);
            }

            public function getNameAttribute(): string
            {
                return 'slim';
            }

            public function getAgeAttribute(): string
            {
                return 'sixty';
            }
        };

        $this->assertSame([
            'name' => 'slim',
            'age' => 'sixty',
        ], $user->toArray()['presentable']);
    }

    /**
     * @test
     **/
    public function it_can_have_a_different_array_key(): void
    {
        $user = new User([
            'name' => 'Test User',
        ]);

        config(['presentable.key' => 'test-key']);

        self::assertSame([
            'name' => 'Test User',
            'test-key' => [
                'created_at' => now()->format('d F Y H:i a'),
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

        self::assertNull($user->presentable()->nothing);
    }
}
