# Examining the state of your PHP code 

When you're debugging PHP code, it can be helpful to examine the state of your code at a specific point in time. This can help you understand what's happening in your code and identify any issues that may be causing problems.

Let's look at some ways you can examine the state of your PHP code using built-in PHP functions.

## Using `error_log()` to log messages

As discussed in the lesson on the built-in WordPress debugging options, you can use the `error_log()` function to log messages to the WordPress `debug.log` file. 

This can be useful for debugging purposes, as it allows you to see what's happening in your code without displaying it on the screen.

The `error_log()` function is not specific to WordPress, it's a PHP function that you can use in any PHP code to log messages to the PHP error log file configured on the server. 

However, once you enable the WordPress specific debugging options in your `wp-config.php` file, anything passed to `error_log()` will be logged to the WordPress `debug.log` file. 

```php
error_log( $some_variable );
``` 

This will log the value of `$some_variable` to the `debug.log` file, so you can see what it contains at that point in your code.

The benefit of using `error_log()` is that it allows you to log messages to a file without displaying them on the screen. If you display them on screen, especially in a production environment, you risk exposing sensitive information to users. 

It's also sometimes quicker to be able to see the output in a log file, rather than having to search through a long list of output on the screen.

## Using `print_r()`

Another useful function for examining the state of your PHP code is `print_r()`. This function outputs the value of a variable, array, or object in a human-readable format, so you can see what it contains.

Take a look at this example PHP code.

```php
$some_array = array( 'apple', 'banana', 'cherry' );
print_r( $some_array );
```

This code will output the following to the screen:

```
Array ( [0] => apple [1] => banana [2] => cherry )
```

Developers will often wrap `print_r()` in `<pre>` tags to make the output easier to read.

```php
echo '<pre>';
print_r( $some_array );
echo '</pre>';
```

This will output the following to the screen:

```
Array
(
    [0] => apple
    [1] => banana
    [2] => cherry
)
```

Notice that by default `print_r()` outputs the value of the variable, but does not return it. If you want to use the output of `print_r()` with `error_log()`, you need to set the second parameter to `true`.

```php
error_log( print_r( $some_array, true ) );
```

This will log the output of the `print_r` call to the `debug.log` file, so you can see what `$some_array` contains at that point in your code.

## Using `var_dump()`

The `var_dump()` function is another useful function for examining the state of your PHP code. This function outputs the value of a variable, array, or object in a human-readable format, along with additional information about the variable type and length.

Take a look at this example PHP code.

```php
$some_array = array( 'apple', 'banana', 'cherry' );
echo '<pre>';
var_dump( $some_array );
echo '</pre>';
```

This code will output the following to the screen:

```
array(3) {
  [0]=>
  string(5) "apple"
  [1]=>
  string(6) "banana"
  [2]=>
  string(6) "cherry"
}
```

Notice that `var_dump()` outputs the value of the variable, along with additional information about the variable type and length. This can be useful for debugging purposes, as it provides more detailed information about the variable than `print_r()`.

Unlike `print_r()`, `var_dump()` does not have an option to return the output to a variable, so you can't use it directly with `error_log()`. 

However, you can use something called output buffering to capture the output of `var_dump()` and then log it to the `debug.log` file.

```php
$some_array = array( 'apple', 'banana', 'cherry' );
ob_start();
var_dump( $some_array );
$output = ob_get_clean();
error_log( $output );
```

Developers will often use this type of code in a special logging function, to be able to use both `var_dump` and `error_log` together.

```php
function log_var_dump( $variable ) {
    ob_start();
    var_dump( $variable );
    $output = ob_get_clean();
    error_log( $output );
}
```

## Further reading

For more information on the functions mentioned in this lesson, check out the following pages in the PHP documentation:

- [PHP error_log() function](https://www.php.net/manual/en/function.error-log.php)
- [PHP print_r() function](https://www.php.net/manual/en/function.print-r.php)
- [PHP var_dump() function](https://www.php.net/manual/en/function.var-dump.php)

## YouTube chapters

 Using `error_log
 Using `print_r
 Using `var_dump
0:00 