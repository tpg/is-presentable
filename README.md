# Simple model presenter for Laravel

[![Run Tests](https://github.com/tpg/is-presentable/actions/workflows/php.yml/badge.svg)](https://github.com/tpg/is-presentable/actions/workflows/php.yml)

This simple package can help you format a model's data so that it's presentable in a browser. For example, if you needed to print the creation date of a model in a view, and you did something like this:

```php
<p>{{ $model->created_at }}</p>
```

You'd get something like this:

```
<p>2021-12-09 03:04:22</p>
```

That's fine, but it's not great. What if you wanted to format it? Well, you could do:

```
<p>{{ $model->created_at->format('d F Y H:i a') }}</p>
```

That works nicely, but what if you need to use the same format in a whole lot of places. That gets frustrating. You could create a model accessor, which would work just fine, but then it feels like it's litering up your model with presentation data. That's where IsPresentable comes in.

## Installation

Install in your Laravel app:

```
composer require thepublicgood/is-presentable
```

## Usage

Version 2 brings a whole new approach to adding presentables to your models. The old version 1 way of using `presentable` methods still works, though.

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

Now create a new presenter class for the attribute you'd like to present. Make sure to extend the `Presenter` class:

```php
<?php

namespace App\Http\Presenters;

use App\Models\User;
use TPG\IsPresentable\Presenter;

class CreatedAtPresenter extends Presenter
{
    public function render(User $user): ?string
    {
        return $user->created_at?->format('d F Y H:i a');
    }
}
```

Now assign this presenter class to the attribute name in your model's `$presenters` array:

```php
use App\Http\Presenters\CreatedAtPresenter;

class User extends Model {
    use IsPresentable;
    
    protected $presenters = [
        'created_at' => CreatedAtPresenter::class,
    ];
    
    // ...
}
```

now you'll have access to the presentable data like this:

```
$user->presentable()->created_at;
```

It can be really useful to create presenters as classes like this as they are reusable. A `created_at` column is fairly standard on Laravel models, so you can use the same class to present that data on any model now. No need to write a whole new class.

IsPresentable will also extend the `toArray()` method and add an array of the rendered data to the result. This is useful if you need to access your presenters in a JavaScript front-end. A `presentable` array containing all the formatted data will be added. For example, `$user->toArray()` would result in something like:

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

If you're using something like Vue, and casting the array as a JSON object (Laravel should do that for you), then you could get to the same `created_at` presenter like this:

```
{{ user.presentable.created_at }}
```

### Using `presentable` methods
If you don't to create classes or you're adding just one presenter to a model that will not be used elsewhere, you can create simple "accessor" methods directly on the model class by prefixing them with the word `presentable`. As an example, a `User` might need a `username` that is calculated on the fly. We can write a "presentable" method like this:

```php
public function presentableUsername(): string
{
    return Str::slug($this->name);  
}
```

To make this a little neater, you can create traits for your presentable methods and use the `IsPresentable` trait inside your presentable traits:

```php
trait UserPresenter
{
    use IsPresentable;
    
    public function presentableUsername(): string
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

## Hiding presenters
Sometimes you won't want to include presenters when the model is cast to an array. You can do this by implementing the `IsHidden` interface in your presenter class:

```php
class UserPresenter extends Presenter implements IsHidden
{
    // ..
}
```

You will still have access to presenter in PHP, but it will not be included in the result when casting to an array.

## Dealing with missing data
If you're using class presenters, IsPresentable will automatically fail gracefully if the attribute you are trying to present has no data. For example:

```php 
public function render(User $user): string
{
    return $user->username;
}
```

You would expect that to fail if the `username` attribute was `null`, since the method MUST return a string. However, in this case, IsPresentable will simply return an empty string for you.

The same is true for presenter methods on the model class:

```php
public function presentableUsername(): string
{
    return $user->username;
}
```

This will also return an empty string if `username` doesn't exist.

## Configuration
There is a single solitary configuration option. You can change the key that is used when casting to an array. To publish the configuration file, run the followin Artisan command:

```
php ./artisan vendor:publish --provider=TPG\IsPresentable\IsPresentableServiceProvider
```

This will place a `presentable.php` configuration file in your `config` directory. To change the array key, update the `key` property:

```php
return [

    'key' => 'presentation',

];
```

Now you can you presentable data with:

```javascript
const username = user.presentation.username;
```
