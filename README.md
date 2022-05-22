# *Anything!* 💫 [WIP]

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ajthinking/anything.svg?style=flat-square)](https://packagist.org/packages/ajthinking/anything)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ajthinking/anything/run-tests?label=tests)](https://github.com/ajthinking/anything/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ajthinking/anything/Check%20&%20fix%20styling?label=code%20style)](https://github.com/ajthinking/anything/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ajthinking/anything.svg?style=flat-square)](https://packagist.org/packages/ajthinking/anything)

Imagine public API:s and make it so - a TDD and sketch toy for Laravel projects :star_struck:


## Installation

```bash
composer require ajthinking/anything --dev
```


## Usage
First, we need to turn *anything mode* **on**:
```bash
php artisan anything:on
```
Then, flesh out your idea and execute it in a console context. Lets say we want to build a git client:
```php
// This class does not exist, yet
App\Support\Git::status()
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
    public function status()
    {
        return $this;
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

If referenced statically like above we will also create a register a facade:

```php
class SomeFacade {
	// TODO
}
```

Finally, it also creates a test file:

```php
it('can test', function() {
	// TODO
})
```
## TODO
- [ ] Ensure existing App files are loaded if they do exist
- [x] Prevent from crashing when pest is installed
- [ ] Find out why Pest does not respect the passed Kernel
- [ ] Allow anything mode to modify CreatesApplication
- [ ] Consider if setting facadeNamespace = 'App' essentially does the same thing as the AliasLoader
- [ ] Instead of all guard clauses in AliasLoader, restrict to a configured namespace to create in
- [ ] Implement the FacadeGenerator
- [ ] Implement the TestGenerator

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
