<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use TPG\IsPresentable\Tests\AttributePresenter;
use TPG\IsPresentable\Tests\OptionPresenter;
use TPG\IsPresentable\Tests\User;
use TPG\IsPresentable\Traits\IsPresentable;

test('models can have presentable methods', function () {
    $user = new User([
        'name' => 'Test User',
    ]);

    expect($user->presentable()->test)->toBe('presentable-test');
});

test('presentables are included in the model array', function () {
    $user = new User([
        'name' => 'Test User',
    ]);

    expect($user->toArray())->toBe([
        'name' => 'Test User',
        'presentable' => [
            'created_at' => now()->format('d F Y H:i a'),
            'test' => 'presentable-test',
        ],
    ]);
});

test('class presenters can have options', function () {
    $user = new class extends Model
    {
        use IsPresentable;

        protected $guarded = [];

        protected array $presentables = [
            'options' => [OptionPresenter::class, ['option 1', 'option 2']],
        ];
    };

    expect($user->presentable()->options)->toBe('option 1 + option 2');
});

test('class presenters can use the attribute', function () {
    $user = new class extends Model
    {
        use IsPresentable;

        protected array $presentables = [
            'name' => AttributePresenter::class,
            'age' => AttributePresenter::class,
        ];

        public function getNameAttribute(): string
        {
            return 'slim';
        }

        public function getAgeAttribute(): string
        {
            return 'sixty';
        }
    };

    expect($user->toArray()['presentable'])->toBe([
        'name' => 'slim',
        'age' => 'sixty',
    ]);
});

test('it can have a different array key', function () {
    $user = new User([
        'name' => 'Test User',
    ]);

    config(['presentable.key' => 'test-key']);

    expect($user->toArray())->toBe([
        'name' => 'Test User',
        'test-key' => [
            'created_at' => now()->format('d F Y H:i a'),
            'test' => 'presentable-test',
        ],
    ]);
});

test('it will return null if presentable method doesnt exist', function () {
    $user = new User([
        'name' => 'Test User',
    ]);

    expect($user->presentable()->nothing)->toBeNull();
});

test('presentables can be cast as an array', function () {
    $user = new User([
        'name' => 'Test User',
    ]);

    expect($user->present->toArray())->toBe([
        'created_at' => now()->format('d F Y H:i a'),
        'hidden' => 'hidden',
        'test' => 'presentable-test',
    ]);
});

//namespace TPG\IsPresentable\Tests;
//
//use Illuminate\Database\Eloquent\Model;
//use TPG\IsPresentable\Traits\IsPresentable;
//
//class IsPresentableTest extends TestCase
//{
//    /**
//     * @test
//     **/
//    public function models_can_have_presentable_methods(): void
//    {
//        $user = new User([
//            'name' => 'Test User',
//        ]);
//
//        self::assertSame('presentable-test', $user->presentable()->test);
//    }
//
//    /**
//     * @test
//     **/
//    public function presentables_are_included_in_the_model_array(): void
//    {
//        $user = new User([
//            'name' => 'Test User',
//        ]);
//
//        self::assertSame([
//            'name' => 'Test User',
//            'presentable' => [
//                'created_at' => now()->format('d F Y H:i a'),
//                'test' => 'presentable-test',
//            ],
//        ], $user->toArray());
//    }
//
//    /**
//     * @test
//     **/
//    public function class_presenters_can_have_options(): void
//    {
//        $user = new class extends Model
//        {
//            use IsPresentable;
//
//            protected $guarded = [];
//
//            protected array $presentables = [
//                'options' => [OptionPresenter::class, ['option 1', 'option 2']],
//            ];
//        };
//
//        $this->assertSame('option 1 + option 2', $user->presentable()->options);
//    }
//
//    /**
//     * @test
//     **/
//    public function class_presenters_can_use_the_attribute(): void
//    {
//        $user = new class extends Model
//        {
//            use IsPresentable;
//
//            protected array $presentables = [
//                'name' => AttributePresenter::class,
//                'age' => AttributePresenter::class,
//            ];
//
//            public function getNameAttribute(): string
//            {
//                return 'slim';
//            }
//
//            public function getAgeAttribute(): string
//            {
//                return 'sixty';
//            }
//        };
//
//        $this->assertSame([
//            'name' => 'slim',
//            'age' => 'sixty',
//        ], $user->toArray()['presentable']);
//    }
//
//    /**
//     * @test
//     **/
//    public function it_can_have_a_different_array_key(): void
//    {
//        $user = new User([
//            'name' => 'Test User',
//        ]);
//
//        config(['presentable.key' => 'test-key']);
//
//        self::assertSame([
//            'name' => 'Test User',
//            'test-key' => [
//                'created_at' => now()->format('d F Y H:i a'),
//                'test' => 'presentable-test',
//            ],
//        ], $user->toArray());
//    }
//
//    /**
//     * @test
//     **/
//    public function it_will_return_null_if_presentable_method_doesnt_exist()
//    {
//        $user = new User([
//            'name' => 'Test User',
//        ]);
//
//        self::assertNull($user->presentable()->nothing);
//    }
//
//    /**
//     * @test
//     **/
//    public function presentables_can_be_cast_as_an_array(): void
//    {
//        $user = new User([
//            'name' => 'Test User',
//        ]);
//
//        self::assertSame([
//            'created_at' => now()->format('d F Y H:i a'),
//            'hidden' => 'hidden',
//            'test' => 'presentable-test',
//        ], $user->presentable()->toArray());
//    }
//}
