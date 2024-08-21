# Development Best Practices

## Introduction

## Naming Collisions

Take a look at the following PHP code snippet:

```php
$variable = 'value';
function function_name() {
    // code
}
```

By default, anything you define (variables, functions, constants, classes) exists in what's called the global namespace. 

What this means is that if another plugin tries to define the same variable or function, you will get what is known as a naming collision.

To understand this, let's look at an example.

First, make sure to enable the WordPress debugging mode in your `wp-config.php` file, especially the WP_DEBUG_LOG constant. 

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Next, create plugin file in your wp-content/plugins directory called wp-conflict.php and add the following code:

```php
<?php
/*
Plugin Name: WP Conflict
Version: 1.0.0
*/
function get_option(){
	return 'Conflict';
}
```

Save the file, log into your WordPress admin area, and try to activate the plugin.

You will get an error message that says:

```
Plugin could not be activated because it triggered a fatal error.
```

If you check the debug log, you will see the following error message:

```
PHP Fatal error:  Cannot redeclare get_option() (previously declared in /path/to/your/wordpress/install/wp-includes/option.php:78)
```

This error occurs because the `get_option()` function is already defined in WordPress core, in the `wp-includes/option.php` file on line 78. By trying to define the same function in your plugin, you are causing a naming collision, as there can only be one function with the same name in the global namespace.

There are a couple of ways to avoid naming collisions.

### Prefixes

Prefix your functions and variables with a unique name. In this example, the plugin is called `WP Conflict`, so you can prefix your functions and variables that exist in the global namespace with `wp_conflict_`:

```php
function wp_conflict_get_option(){
    return 'Conflict';
}
```

When you need to call the function, you can use the full function name:

```php
echo wp_conflict_get_option();
```

### Check for Existing Implementations

Before defining a function or variable, check if it already exists. You can use the `function_exists()` function to check if a function is already defined:

```php
if ( ! function_exists( 'wp_conflict_get_option' ) ) {
    function wp_conflict_get_option(){
        return 'Conflict';
    }
}
```



2. Use classes to encapsulate your functions and variables. This allows you to group related functions and variables together and avoid naming collisions. 

For example, you can define a class called `WP_Conflict` and define your functions and variables as static methods and properties of the class:

```php
class WP_Conflict {
    public static function get_option(){
        return 'Conflict';
    }
}
```

When you need to call class methods, you can use the `::` operator:

```php
echo WP_Conflict::get_option();
```

3. Use the PHP `namespace` keyword to define your functions and variables in a specific namespace. This allows you to avoid naming collisions by defining your functions and variables in a separate namespace:

```php
namespace WP_Conflict;

function get_option(){
    return 'Conflict';
}
```

When you need to call the function, you can use the fully qualified name:

```php
echo WP_Conflict\get_option();
```

## File Organization

## Plugin Architecture

