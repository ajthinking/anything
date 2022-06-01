# *Anything!* 💫

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ajthinking/anything.svg?style=flat-square)](https://packagist.org/packages/ajthinking/anything)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ajthinking/anything/run-tests?label=tests)](https://github.com/ajthinking/anything/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ajthinking/anything/Check%20&%20fix%20styling?label=code%20style)](https://github.com/ajthinking/anything/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ajthinking/anything.svg?style=flat-square)](https://packagist.org/packages/ajthinking/anything)

Imagine public API:s and make it so - a TDD and sketch toy for Laravel projects :star_struck:


## Getting started

Install and enable like so:
```bash
composer require ajthinking/anything --dev
php artisan anything:on
```

Then, flesh out your idea and execute it in a console context. Lets say we want to build a git client:
```php
// This class does not exist, yet
App\Support\Git::client()
  ->pull()
  ->add('*')
  ->nah()
  ->wip()
  ->unwip()
  ->commit('message')
  ->push()
  ->build() // <-- creates the class!
```
The last method call `->build()` will create this class for you along with all the method stubs:

```php
<?php

namespace App\Support;

class Git
{
    public static function client()
    {
        return new static();
    }
    
    public function pull()
    {
        return $this;
    }
    
    public function add()
    {
        return $this;
    }

	// ...
}
```

When referenced statically like above the first call will typically spawn a static method. However, if the class name contains `Facades\` lets say `App\Facades\Zonda` we will instead make it an instance method and create a facade next to it:

```php
<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Zonda extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'App\\Zonda';
    }
}
```

Finally, make sure to clean up by discarding the change in `bootstrap/app.php` with git, or by running
`php artisan anything:off`

## Gotchas
This experiment comes with some limitations. 

* `anything:on/off` commands it makes a little intrusion in your `bootstrap/app.php` to temporary swap out the console kernel. It assumes you have not made any major modifications to this file.
* it will only work for classes in the `App` namespace
* method arguments are currently ignored
* assumes everything returns `$this`
* when using in tests, make sure your test case uses `CreatesApplication`. This might not always be the case for unit test setups

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
