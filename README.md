# Simple model presenter for Laravel

[![Run Tests](https://github.com/tpg/is-presentable/actions/workflows/php.yml/badge.svg)](https://github.com/tpg/is-presentable/actions/workflows/php.yml)

## Installation

IsPresentable can be installed via Composer:

```
composer require thepublicgood/is-presentable
```

## Usage

To make a model presentable, simply add the `IsPresentable` trait:

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

You can now create new presentable methods by prefixing them with `presentable`. As an example, our `User` might need
a `username` that is calculated on the fly. We can write a presenter like this:

```php
public function presentableUsername(): string
{
    return Str::slug($this->name);  
}
```

We now have access to the presenter on any user model like this:

```php
return $user->presentable()->username;
```

The `IsPresentable` trait will also extend the `toArray()` method on the model meaning that any presentable values will
also be included when parsing the model to an array or JSON object. This is particularly handy when using tools like
Interia, or you need to pass the model as part of an API response.

For example, `$user->toArray()` would result in something like:

```json
{
    "id": 1,
    "name": "Marquardt Morissette",
    "email": "user@example.com",
    "password": "password",
    "created_at": "2021-03-08T20:49:43.000000Z",
    "updated_at": "2021-03-08T20:49:43.000000Z",
    "presentable": {
        "username": "marquardt-morissette"
    }
}
```

In a JavaScript app you can access that `username` with:

```javascript
const username = user.presentable.username;
```
