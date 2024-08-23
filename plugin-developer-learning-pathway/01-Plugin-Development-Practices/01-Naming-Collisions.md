# Naming Collisions

## Introduction

When developing plugins for WordPress, it's important to be aware of naming collisions. 

A naming collision occurs when two or more functions, variables, classes, or constants have the same name in the same namespace.

To understand what this means, let's look at an example.

## Enable debugging

Before you start, make sure to enable the WordPress debugging mode in your `wp-config.php` file, especially the `WP_DEBUG_LOG` constant.

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

You're intentionally going to write some code that will cause an error, and you want to be able to see the error message in the debug log.

Next, create plugin directory in your `wp-content/plugins` directory called `extra-content`. Inside that create the main plugin filed called `extra-content.php` and add the following code:

```php
<?php
/*
Plugin Name: Extra Content
Version: 1.0.0
Description: Add extra content to the end of the post content.
Author: WP Learn
*/

add_action( 'admin_init', 'add_option' );
function add_option() {
	add_settings_field( 'extra_option', 'Extra Option', 'extra_option_field', 'general' );
	register_setting( 'general', 'extra_option' );
}

function extra_option_field() {
	echo '<input name="extra_option" id="extra_option" type="text" value="' . get_option( 'extra_option' ) . '" />';
}

add_filter( 'the_content', 'add_extra_option' );
function add_extra_option( $content ) {
	$extra_option = get_option( 'extra_option' );
	if ( ! $extra_option ) {
		new WP_Error( 'extra_option', 'Extra content is empty.' );

		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';

	return $content;
}
```

This code is makes use of the WordPress [Options](https://developer.wordpress.org/apis/options/) and [Settings](https://developer.wordpress.org/apis/settings/) APIs. 

It adds a field to the General Settings page in the WordPress admin area called Extra Option, where an admin user can enter some content.

When the settings are saved, the content will be saved as an option in the WordPress database, with the key `extra_option`.

It then hooks into the `the_content` filter, and appends the content of the `extra_option` option to the end of the post content.

If the `extra_option` option is empty, it will log an error, and return the original content.

Save the file, log into your WordPress admin area, and try to activate the plugin.

You will get an error message that says:

```
Plugin could not be activated because it triggered a fatal error.
```

If you check the debug log, you will see the following error message:

```
PHP Fatal error:  Cannot redeclare add_option()
```

This error occurs because the `add_option()` function is already defined in WordPress core, in the `wp-includes/option.php` file. 

By trying to define the same function in your main plugin file, you are causing a naming collision. 

When using the function keyword to define these two functions, they are both defined in the global namespace, and so they conflict with each other.

This can happen with any variables, classes, and constants you create inside the global namespace.

Now let's look at some ways to avoid naming collisions, and the pros and cons of each approach.

### Prefixes

The first option is to prefix any plugin code with a unique identifier. As the developer, you can determine what that identifier is, and the use it throughout your code.

In this example, the plugin is called `Extra Content`, so you can use a prefix like `extra_content_`:

```php
add_action( 'admin_init', 'extra_content_add_option' );
function extra_content_add_option() {
	add_settings_field( 'extra_option', 'Extra Option', 'extra_option_field', 'general' );
	register_setting( 'general', 'extra_option' );
}
```

If you try to activate the plugin now, it will activate successfully, as the prefix makes your function name unique.

You will then be able to add some content to the Extra Option field in the General Settings, and see it displayed at the end of the post content.

When defining a prefix, it's a good idea to make it as unique to your plugin as possible, to avoid conflicts with other plugins that may use the same prefix.

One way to do this is to use a combination of the plugin author, or an abbreviation of the name, and the plugin name as the prefix:

```php
add_action( 'admin_init', 'wp_learn_extra_content_add_option' );
function wp_learn_extra_content_add_option() {
	add_settings_field( 'extra_option', 'Extra Option', 'extra_option_field', 'general' );
	register_setting( 'general', 'extra_option' );
}
```

Whatever prefix you choose, make sure it's unique to your plugin, and use it consistently throughout your code.

```php
add_action( 'admin_init', 'wp_learn_extra_content_add_option' );
function wp_learn_extra_content_add_option() {
	add_settings_field( 'extra_option', 'Extra Option', 'wp_learn_extra_content_extra_option_field', 'general' );
	register_setting( 'general', 'wp_learn_extra_content_extra_option' );
}

function wp_learn_extra_content_extra_option_field() {
	echo '<input name="wp_learn_extra_content_extra_option" id="wp_learn_extra_content_extra_option" type="text" value="' . get_option( 'wp_learn_extra_content_extra_option' ) . '" />';
}

add_filter( 'the_content', 'wp_learn_extra_content_add_extra_option' );
function wp_learn_extra_content_add_extra_option( $content ) {
	$extra_option = get_option( 'wp_learn_extra_content_extra_option' );
	if ( ! $extra_option ) {
		new WP_Error( 'wp_learn_extra_content_extra_option', 'Extra content is empty.' );

		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';

	return $content;
}
```

One thing to note is that naming conflicts only occur in the global namespace. 

For example, if you define a variable inside a function, the variable name is scoped to the function, and so you don't need to prefix it.

### Namespaces

A slightly better way to prefix your code is to define a custom [namespace](https://www.php.net/manual/en/language.namespaces.definition.php) for your plugin code, using the PHP `namespace` keyword.

This allows you to avoid naming collisions by defining your functions and variables in a separate namespace:

```php
namespace WP_Learn\Extra_Content;

add_action('admin_init', 'WP_Learn\Extra_Content\add_option');
function add_option() {
	add_settings_field('extra_option', 'Extra Option', 'WP_Learn\Extra_Content\extra_option_field', 'general');
	register_setting('general', 'extra_option');
}
function extra_option_field() {
	echo '<input name="extra_option" id="extra_option" type="text" value="' . get_option('extra_option') . '" />';
}

add_filter( 'the_content', 'WP_Learn\Extra_Content\add_extra_option' );
function add_extra_option( $content ) {
	$extra_option = get_option('extra_option');
	if ( ! $extra_option ) {
		new WP_Error( 'extra_option', 'Extra content is empty.' );
		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';
	return $content;
}
```

Notice that when you need to call the function, or in this case pass the function as a callback to a hook, you use the fully qualified name, which includes the namespace.

But notice what happens when you delete the content of the Extra Option field in the General Settings, save the settings, and navigate to a post or page on your site.

This has to do with how PHP resolves classes differently when used inside a namespace.

## Global namespace resolution

You still have the WordPress debugging options on, so take a look at what's being logged to the debug.log file.

```
PHP Fatal error:  Uncaught Error: Class "WP_Learn\Extra_Content\WP_Error" not found
```

This error is happening because the `WP_Error` class is not being found. It worked before you added the namespace, so what's going on?

If you look at the PHP documentation on [Using namespaces](https://www.php.net/manual/en/language.namespaces.fallback.php) it states:

> Inside a namespace, when PHP encounters an unqualified name in a class name, function or constant context, it resolves these with different priorities. Class names always resolve to the current namespace name.

What this means is that all the WordPress core functions you're using in this code are being resolved to the global namespace by default, but the use of the `WP_Error` class is being resolved to the current namespace.

WP_Error is a WordPress core class, so it exists in the global namespace.

When using namespaces, you can use a backslash character in front of any class names or function calls to tell PHP the class or function exists in the global namespace.

```php
namespace WP_Learn\Extra_Content;

add_action('admin_init', 'WP_Learn\Extra_Content\add_option');
function add_option() {
	add_settings_field('extra_option', 'Extra Option', 'WP_Learn\Extra_Content\extra_option_field', 'general');
	register_setting('general', 'extra_option');
}
function extra_option_field() {
	echo '<input name="extra_option" id="extra_option" type="text" value="' . get_option('extra_option') . '" />';
}

add_filter( 'the_content', 'WP_Learn\Extra_Content\add_extra_option' );
function add_extra_option( $content ) {
	$extra_option = get_option('extra_option');
	if ( ! $extra_option ) {
		new \WP_Error( 'extra_option', 'Extra content is empty.' );
		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';
	return $content;
}
```

Adding this will fix the namespace resolution issue, and you should now be able to navigate to a post or page on your site without any errors.

While you must prefix any core WordPress classes with the backslash character to ensure they are resolved in the global namespace, you can also optionally use the backslash before function calls. It's not required, because the PHP parser will do this automatically, but it helps you remember which functions belong to which namespace. 

```php
namespace WP_Learn\Extra_Content;

\add_action( 'admin_init', 'WP_Learn\Extra_Content\add_option' );
function add_option() {
	\add_settings_field( 'extra_option', 'Extra Option', 'WP_Learn\Extra_Content\extra_option_field', 'general' );
	\register_setting( 'general', 'extra_option' );
}

function extra_option_field() {
	echo '<input name="extra_option" id="extra_option" type="text" value="' . \get_option( 'extra_option' ) . '" />';
}

\add_filter( 'the_content', 'WP_Learn\Extra_Content\add_extra_option' );
function add_extra_option( $content ) {
	$extra_option = \get_option( 'extra_option' );
	if ( ! $extra_option ) {
		new \WP_Error( 'extra_option', 'Extra content is empty.' );

		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';

	return $content;
}
```

Namespaces also make it easier to move your code into separate files, and then include those files in your main plugin file.

For example, you could move all the functions into a file called `functions.php` that's namespaced under `WP_Learn\Extra_Content\Functions`:

```php
<?php
namespace WP_Learn\Extra_Content\Functions;

function add_option() {
	\add_settings_field( 'extra_option', 'Extra Option', 'WP_Learn\Extra_Content\Functions\extra_option_field', 'general' );
	\register_setting( 'general', 'extra_option' );
}

function extra_option_field() {
	echo '<input name="extra_option" id="extra_option" type="text" value="' . \get_option( 'extra_option' ) . '" />';
}

function add_extra_option( $content ) {
	$extra_option = \get_option( 'extra_option' );
	if ( ! $extra_option ) {
		new \WP_Error( 'extra_option', 'Extra content is empty.' );

		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';

	return $content;
}
```

You could then include this file in your main plugin file, and pass the fully qualified function names to the hooks:

```php
require plugin_dir_path( __FILE__ ) . 'functions.php';

\add_action( 'admin_init', 'WP_Learn\Extra_Content\Functions\add_option' );
\add_filter( 'the_content', 'WP_Learn\Extra_Content\Functions\add_extra_option' );
```

## Classes

You can also make use classes to encapsulate your functions and variables. 

This allows you to group related functions and variables together and help to avoid naming collisions.

So for example, you could define a class called `WP_Learn_Extra_Content` and define your functions and variables as methods and properties of the class:

```php
class WP_Learn_Extra_Content {

	public function init() {
		add_action( 'admin_init', array( $this, 'add_option' ) );
		add_filter( 'the_content', array( $this, 'add_extra_option' )  );
	}
	public function add_option() {
		add_settings_field('extra_option', 'Extra Option', array( $this, 'extra_option_field' ), 'general');
		register_setting('general', 'extra_option');
	}

	public function extra_option_field() {
		echo '<input name="extra_option" id="extra_option" type="text" value="' . get_option('extra_option') . '" />';
	}
	
	public function add_extra_option( $content ) {
		$extra_option = get_option('extra_option');
		if ( ! $extra_option ) {
			new WP_Error( 'extra_option', 'Extra content is empty.' );
			return $content;
		}
		$content .= '<p>' . $extra_option . '</p>';
		return $content;
	}
	
}
```

You'll notice that the hook callbacks have changed to use an array with the class instance and the method name. This is because the callbacks are now methods of the class.

Then all you need to do is create an instance of the class and call the `init()` method:

```php
$wp_learn_extra_content = new WP_Learn_Extra_Content();
$wp_learn_extra_content->init();
```

This provides a cleaner way to structure your code, and helps to avoid naming collisions.

As with namespaces, using classes allows you to move common functionality into different files. For example, you can create a separate file for the class:

```php
<?php

class WP_Learn_Extra_Content {

	public function init() {
		add_action( 'admin_init', array( $this, 'add_option' ) );
		add_filter( 'the_content', array( $this, 'add_extra_option' ) );
	}

	public function add_option() {
		add_settings_field( 'extra_option', 'Extra Option', array( $this, 'extra_option_field' ), 'general' );
		register_setting( 'general', 'extra_option' );
	}

	public function extra_option_field() {
		echo '<input name="extra_option" id="extra_option" type="text" value="' . get_option( 'extra_option' ) . '" />';
	}

	public function add_extra_option( $content ) {
		$extra_option = get_option( 'extra_option' );
		if ( ! $extra_option ) {
			new WP_Error( 'extra_option', 'Extra content is empty.' );

			return $content;
		}
		$content .= '<p>' . $extra_option . '</p>';

		return $content;
	}
}
```

Then in your main plugin file, you can include the class file and create the instance of the class:

```php
require plugin_dir_path( __FILE__ ) . 'class-extra-content.php';

$wp_learn_extra_content = new WP_Learn_Extra_Content();
$wp_learn_extra_content->init();
```

Finally, you can even combine namespaces and classes to create a more organized and structured plugin, with less chance of naming collisions. For example you could move the class into folder called `classes` and namespace it under `WP_Learn\Classes`:

```php
<?php

namespace WP_Learn\Classes;
class Extra_Content {

	public function init() {
		add_action( 'admin_init', array( $this, 'add_option' ) );
		add_filter( 'the_content', array( $this, 'add_extra_option' ) );
	}

	public function add_option() {
		add_settings_field( 'extra_option', 'Extra Option', array( $this, 'extra_option_field' ), 'general' );
		register_setting( 'general', 'extra_option' );
	}

	public function extra_option_field() {
		echo '<input name="extra_option" id="extra_option" type="text" value="' . get_option( 'extra_option' ) . '" />';
	}

	public function add_extra_option( $content ) {
		$extra_option = get_option( 'extra_option' );
		if ( ! $extra_option ) {
			new WP_Error( 'extra_option', 'Extra content is empty.' );

			return $content;
		}
		$content .= '<p>' . $extra_option . '</p>';

		return $content;
	}
}
```

Then in your main plugin file, you can include the class file and create the instance of the class:

```php
require plugin_dir_path( __FILE__ ) . 'classes/class-extra-content.php';

use WP_Learn\Classes\Extra_Content;

$wp_learn_extra_content = new Extra_Content();
$wp_learn_extra_content->init();
```

Notice the use of the `use` keyword to import the class from its namespace, so that you can register an instance of the Extra_Content class, without needing to use the fully qualified name.

## Avoiding naming collisions in the database

There's one more type of naming collision to be aware of, and that's when storing options to the database.

When using any of the Options or Settings API functions that are related to storing data to the database with key value pairs like `register_setting`, `add_option`, and `get_option`, you need to be careful about the names you use.

In the example above, the option name is `extra_option`, which is a very generic name, and could easily conflict with other plugins or themes that use the same name.

In this case it's always a good idea to follow the prefixing convention, and prefix your setting and option names with a unique identifier:

```php
<?php

namespace WP_Learn\Classes;
class Extra_Content {

	public function init() {
		add_action( 'admin_init', array( $this, 'add_option' ) );
		add_filter( 'the_content', array( $this, 'add_extra_option' ) );
	}

	public function add_option() {
		add_settings_field( 'wp_learn_extra_option', 'Extra Option', array( $this, 'extra_option_field' ), 'general' );
		register_setting( 'general', 'wp_learn_extra_option' );
	}

	public function extra_option_field() {
		echo '<input name="wp_learn_extra_option" id="wp_learn_extra_option" type="text" value="' . get_option( 'wp_learn_extra_option' ) . '" />';
	}

	public function add_extra_option( $content ) {
		$extra_option = get_option( 'wp_learn_extra_option' );
		if ( ! $extra_option ) {
			new WP_Error( 'wp_learn_extra_option', 'Extra content is empty.' );

			return $content;
		}
		$content .= '<p>' . $extra_option . '</p>';

		return $content;
	}
}
```

## Further reading

To read more about the different ways to avoid naming collisions, take a look at the [Avoiding Naming Collisions section](https://developer.wordpress.org/plugins/plugin-basics/best-practices/#avoid-naming-collisions) in the page on Best Practices for plugin development in the Plugin Developer handbook. You can also read about the recommended way to [declare namespaces]((https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/#namespace-declarations)) in the WordPress Coding Standards documentation. 

For more information about using the Options and Settings APIs, take a look at the [Options API](https://developer.wordpress.org/apis/options/) and [Settings API](https://developer.wordpress.org/apis/settings/) pages of the Common APIs Handbook.