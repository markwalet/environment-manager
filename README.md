# Laravel environment manager
A Laravel package that helps you edit the .env file programmatically.

## Installation
You can install this package with composer.

```shell
composer require markwalet/environment-manager
```

Although this package is written for Laravel projects, the package can also be used for other frameworks or vanilla PHP projects.

Laravel 5.5 used Package auto-discovery, so you don't have to register the service provider. If you want to register the service provider manually, add the following line to your `config/app.php` file:

```php
MarkWalet\EnvironmentManager\EnvironmentManagerServiceProvider::class
```

## Usage
You can get the environment manager by resolving it with the app Laravel service container. This will give you a singleton `MarkWalet\EnvironmentManager\Environment` class with the right dependencies.
```php
$environment = app(Environment::class);
```

You can also manually set up an `Environment` class when you are not using Laravel.

Once you have a environment instance you can add lines to the environment:

```php
$environment->add("FOO", "Bar")->after("OTHER_KEY");
```

If you don't specify a location for the new value, the value will be added at the end of the file.

You can also update environment variables:

```php
$environment->update("EXISTING_KEY", "updatedValue");
```

This will replace the original value of `EXISTING_KEY` with `updatedValue`.

All values will be automatically formatted to a valid environment value.

### Mutating multiple lines
You can use the `mutate()` method when you want to apply multiple changes to the file. The syntax looks a lot like the syntax in Laravel migrations:

```php
/**
 * Original content: 
 *
 * TEST1=value1
 * TEST2=value2
 * TEST3=value3
 */
 
$environment->mutate(function(EnvironmentBuilder $builder){
	$builder->add('TEST4', 'escaped value');
	$builder->update('TEST2', 'updated')->after('TEST3');
	$builder->delete('TEST1');
});

/**
 * New content: 
 *
 * TEST3=value3
 * TEST2=updated
 * TEST4="escaped value"
 */
```

### Available methods
Below yo8u will find every available method and its underlying class. These methods can be called on the `Environment` class itself and the `EnvironmentBuilder` you get when mutating the environment.

Method  |  Returns
------------- | -------------
`add(string $key, $value = null)` | [Addition](src/Changes/Addition.php)
`create(string $key, $value = null)` | [Addition](src/Changes/Addition.php)
`set(string $key, $value = null)` | [Update](src/Changes/Update.php)
`update(string $key, $value = null)` | [Update](src/Changes/Update.php)
`move(string $key)` | [Move](src/Changes/Move.php)
`delete(string $key)` | [Delete](src/Changes/Delete.php)
`unset(string $key)` | [Delete](src/Changes/Delete.php)

## Extending the builder
You can also extend the builder with your own implementation of the `Change` class. In the example below we are making a class that can increment a value quickly:

```php
use MarkWalet\EnvironmentManager\Changes\Change;

class Increment extends Change
{
    use HasKey;

    function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Apply the pending change to the given content.
     *
     * @param $content
     *
     * @return mixed
     */
    public function apply(string $content): string
    {
        $search = '/'.$this->getKey().'=(.*)/';
        preg_match($search, $content, $matches);
        $value = $matches[1];

        $replacement = $this->getKey().'='.($value + 1);

        return preg_replace($search, $replacement, $content);
    }
}

$environment->extend('increment', Increment::class);
```

After we extended the builder we can call it through the environment object:

```php
/**
 * Original content: 
 *
 * TEST1=value1
 * INCREMENT=56
 */
$environment->increment('INCREMENT');



/**
 * New content: 
 *
 * TEST1=value1
 * INCREMENT=57
 */
```