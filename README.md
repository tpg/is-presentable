# IsPresentable for Laravel


[![Tests](https://github.com/tpg/is-presentable/actions/workflows/php.yml/badge.svg?branch=2.x)](https://github.com/tpg/is-presentable/actions/workflows/php.yml)

IsPresentable is a simple package to help you format you Laravel model's data so that it's presentable in a browser. For example, if you needed to print the creation date of a model in a view, and you wrote:

```php
<p>{{ $model->created_at }}</p>
```

You'd get output that looks a little this this:

```
<p>2021-12-09 03:04:22</p>
```

That's fine, but it's not great. What if you wanted to format it? Well, you could do:

```
<p>{{ $model->created_at->format('d F Y H:i a') }}</p>
```

Laravel automatically hands the `created_at` timestamp to `Carbon` so this actually works nicely. But what if you need to use the same format in a whole lot of places. Now it gets frustrating. You could create a model accessor, which would work just fine, but then it feels like it's litering up your model with presentation data. And do you add the same accessor to all your models? That's where IsPresenter comes in.

## Installation

As always, install into your Laravel app using Composer:

```
composer require thepublicgood/is-presentable=^2.0
```

## Usage

> Version 2 brings a whole new approach to adding presentables to your models. The old version 1 way of using `presentable` methods still works fine and is backward compatible.

### Using `presentable` classes

First add the `IsPresentable` trait to your model class:
```php
<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;
use TPG\IsPresentable\Traits\IsPresentable;

class User extends Model {
    use IsPresentable;
    
    // ...
}
```

Now create a new presenter class for the attribute you'd like to present. Make sure to extend the `Presentable` class. The model you are presenting will be injected as the `model` class attribute so you can reference it with `$this->model`. Here's a simple `CreatedAtPresentable` class:

```php
<?php

namespace App\Http\Presenters;

use App\Models\User;
use TPG\IsPresentable\Presentable;

class CreatedAtPresentable extends Presentable
{
    public function render(): string
    {
        return $this->model->created_at->format('d F Y H:i a');
    }
}
```

Now you can assign this presentable class to the attribute name in your model's `$presentables` array:

```php
use App\Http\Presenters\CreatedAtPresentable;

class User extends Model {
    use IsPresentable;
    
    protected $presentables = [
        'created_at' => CreatedAtPresentable::class,
    ];
    
    // ...
}
```

This will give you access to the rendered data like this:

```
$user->presentable()->created_at;
```

It can be really useful to create presentables as classes like this as they are reusable. A `created_at` column is fairly standard on Laravel models, so you can use the same class to format that data on any model now. No need to write another presentable for each model. Just add it to the `$presentables` array wherever you need it.

### Using `presentable` methods

If you don't want/need to create presentable classes, or you're adding just one presenter to a model that will not be used elsewhere, you can create simple "accessor" methods directly on the model class by prefixing them with the word `presentable`. As an example, a `User` might need a `username` that is calculated on the fly. We can write a "presentable" method on the model class like this:

```php
public function presentableUsername(): ?string
{
    return Str::slug($this->name);  
}
```

To make this a little neater, you could create traits for your presentable methods and include the `IsPresentable` trait inside your OWN traits:

```php
trait UserPresenter
{
    use IsPresentable;
    
    public function presentableUsername(): ?string
    {
        // ...
    }
}
```

Then in your `User` model class, use just the `UserPresenter` trait:

```php
class User extends Authenticatable
{
    use UserPresenter;
    // ...
}
```

This allows for a bit of reusability as the traits can be used by multiple models.

## Using with JavaScript

The `IsPresentable` trait will also extend the `toArray()` method and add the rendered data to the result. This is useful if you need to access your presenters in a JavaScript front-end. A `presentable` sub-array containing all the formatted data will be added to the resulting array. For example, `$user->toArray()` would result in something like:

```json
{
    "id": 1,
    "name": "Marquardt Morissette",
    "email": "user@example.com",
    "password": "password",
    "created_at": "2021-03-08T20:49:43.000000Z",
    "updated_at": "2021-03-08T20:49:43.000000Z",
    "presentable": {
        "created_at": "08 March 2021 08:49 pm"
    }
}
```

If you're using a front-end framework like Vue, and the array gets cast as a JSON object, then you could get to the same formatted `created_at` property like this:

```
{{ user.presentable.created_at }}
```

## Advanced uses

### Hiding presenters

Sometimes you won't want to include presenters when the model is cast to an array. You can do this by implementing the `IsHidden` interface in your presentable class:

```php
class UsernamePresentable extends Presenter implements IsHidden
{
    // ..
}
```

You'll still have access to the presentable in your Laravel app through the `presentable()` method, but it will no longer show up when the model is cast to an array.

### Accessing the attribute name

The presenter class has access to the attribute name that you set as the key in your `$presentables` array. For example, you could have the following presentables set up on your `User` model class:

```php
class User extends Model
{
    protected $presentables = [
        'created_at' => DatePresentable::class,
        'updated_at' => DatePresentable::class,
    ];
```

Instead of creating two separate presentable classes for each attribute, we can access the name of the attribute we're presenting via `$this->attribute` in the presentable class:

```php
class DatePresenter extends Presentable
{
    public function render(): string|null
    {
        return $this->model->{$this->attribute};
    }
}
```

This can be very useful if we need to display ALL dates and times in a consistent way.

### Passing data into presenters

Using presentable classes, it's possible to pass arbitrary data in, which can be used to alter how the presentable reacts. You can do this by making a small change to the `$presentables` attribute on the model class. Instead of passing a string class path, you can pass a simple array with the first element being the class path and the second being the data you want to pass in:

```php
class User extends Model
{
    use IsPresentable;
    
    $presentables = [
        'created_at' => [            
            DatePresenter::class,
            'd F Y'
        ],
    ];
}
```

In your presenter class, the second element can be accessed with `$this->option`:

```php
class DatePresenter extends Presentable
{
    public function render(): string|null
    {
        return $this->model->{$this->attribute}->format($this->option);
    }
}
```

Options don't have to be strings, you could pass an array of options:

```php
class User extends Model
{
    use IsPresentable;
    
    protected $presentables = [
        'created_at' => [
            DatePresentable::class,
            [
                'Africa/Johannesburg',
                'd F Y',
            ],
        ],
    ];
```

This gives you quite a lot of power over how the models attributes could be presented.

You can also move this entire configuration into a `getPresentables` method if you don't wish to use the `$presentables` array:

```php
class User extends Model
{
    use IsPresentable;
    
    public function getPresentables(): array
    {
        return [
            'created_at' => DatePresentable::class,
        ];
    }
```

## Default presentables
Sometimes it can be useful to speficy a default set of presentable classes that will be automatically used for all model classes that use the `IsPresentable` trait. You can add defaults into the `presentable.php` configuration file. First, publish the configutation file using Artisan:

```shell
php artisan vendor:publish --provider=TPG\IsPresentable\IsPresentableServiceProvider
```

Now you can add your default presentable classes to the `default` array. You are free to use all the same functionality as if you were adding them directly to model classes:

```php
return [
    'defaults' => [
        'created_at' => [
            DatePresentable::class,
            [
                'Africa/Johannesburg',
                'd F Y',
            ],
        ],
    ],
],
```

You don't need to add anything to your `$presentables` array. Simply include the `IsPresentable` trait, and a `created_at` presentable attribute will be included by default.

## Testing

Tests can be run using PHPUnit:

```shell
vendor/bin/phpunit
```

## Credits

- [Warrick Bayman](https://github.com/warrickbayman)

## Changelog

All API changes are documented in the [CHANGELOG](CHANGELOG.md) file.

## License

IsPresentable is licensed in The MIT License. Please see [LICENSE](LICENSE.md) for more details.
