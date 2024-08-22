# Naming Collisions

## Introduction

When developing plugins for WordPress, it's important to be aware of naming collisions. 

A naming collision occurs when two or more functions, variables, classes, or constants have the same name.

To understand this, let's look at an example of defining a custom function in a plugin, that's going to be used in a WordPress site.

## Example

Before you start, make sure to enable the WordPress debugging mode in your `wp-config.php` file, especially the `WP_DEBUG_LOG` constant.

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Next, create plugin directory in your `wp-content/plugins` directory called `wp-conflict`. Inside that create the main plugin filed called `wp-conflict.php` and add the following code:

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

This error occurs because the `get_option()` function is already defined in WordPress core, in the `wp-includes/option.php` file on line 78. 

By trying to define the same function in your plugin, you are causing a naming collision, as there can only be one function with the same name in the global namespace.

This can happen with any variables, classes, and constants you create inside the global namespace.

There are a couple of ways to avoid naming collisions.

### Prefixes

The first option to consider is to prefix any code with a unique identifier. As the developer, you can determine what that identifier is, and the use it throughout your code.

In this example, the plugin is called `WP Conflict`, so you can use a prefix like `wp_conflict_`:

```php
function wp_conflict_function_name() {
    return 'Conflict';
}
```

If you try to activate the plugin now, it will activate successfully, as the prefix makes your function name unique.

When defining a prefix, it's a good idea to make it as unique to your plugin as possible, to avoid conflicts with other plugins that may use the same prefix.

One way to do this is to use a combination of the plugin developer's name, or an abbreviation of the name, and the plugin name as the prefix:

```php
function jb_wp_conflict_get_option(){
    return 'Conflict';
}
```

Alternatively, if you're developing a plugin for a company, use the company name as the prefix:

```php
function acme_wp_conflict_get_option(){
    return 'Conflict';
}
```

Whatever prefix you choose, make sure it's unique to your plugin, and use it consistently throughout your code.

```php
define( 'ACME_WP_CONFLCT_VERSION', 1.0.0 );
$acme_wp_conflict_option = 'value';
function acme_wp_conflict_function_name() {
    return 'Conflict';
}
```

One thing to note is that naming conflicts only occur in the global namespace. 

For example, if you define a variable inside a function, the variable name is scoped to the function, and so you don't need to prefix it:

```php
function acme_wp_conflict_function_name() {
    $option = 'Conflict';
    return $option;
}
```

### Namespaces

A slightly better way to prefix your code is to define a custom [namespace](https://www.php.net/manual/en/language.namespaces.definition.php) for your plugin code, using the PHP `namespace` keyword.

This allows you to avoid naming collisions by defining your functions and variables in a separate namespace:

```php
namespace ACME\WP_Conflict;

function get_option(){
    return 'Conflict';
}
```

When you need to call the function, you use the fully qualified name:

```php
echo WP_Conflict\get_option();
```

The advantage of namespaces is that they allow you to group related code together, even if it's structured in separate files.

Take a look at the following slightly updated version of the bookstore plugin from the Beginner Developer Learning Pathway:

```php
namespace WP_Learn\Bookstore\Custom_Post_Types;

function register_book_post_type() {
	$args = array(
		'labels'       => array(
			'name'          => 'Books',
			'singular_name' => 'Book',
			'menu_name'     => 'Books',
			'add_new'       => 'Add New Book',
			'add_new_item'  => 'Add New Book',
			'new_item'      => 'New Book',
			'edit_item'     => 'Edit Book',
			'view_item'     => 'View Book',
			'all_items'     => 'All Books',
		),
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
	);

	register_post_type( 'book', $args );
}

function register_genre_taxonomy() {
	$args = array(
		'labels'       => array(
			'name'          => 'Genres',
			'singular_name' => 'Genre',
			'edit_item'     => 'Edit Genre',
			'update_item'   => 'Update Genre',
			'add_new_item'  => 'Add New Genre',
			'new_item_name' => 'New Genre Name',
			'menu_name'     => 'Genre',
		),
		'hierarchical' => true,
		'rewrite'      => array( 'slug' => 'genre' ),
		'show_in_rest' => true,
	);

	register_taxonomy( 'genre', 'book', $args );
}

function add_isbn_to_quick_edit( $keys, $post ) {
	if ( 'book' === $post->post_type ) {
		$keys[] = 'isbn';
	}
	return $keys;
}
```

The `custom-post-types.php` file defines the custom post type and taxonomy registration functions in the `WP_Learn\Bookstore\Custom_Post_Types` namespace.

```php
<?php
namespace WP_Learn\Bookstore\Asset_Enqueing;
function enqueue_scripts() {
	if ( ! is_singular( 'book' ) ) {
		return;
	}
	wp_enqueue_style(
		'bookstore-style',
		plugins_url() . '/bookstore/bookstore.css'
	);
	wp_enqueue_script(
		'bookstyle-script',
		plugins_url() . '/bookstore/bookstore.js'
	);
}
```

The `asset-enqueing.php` file defines the asset enqueuing functions in the `WP_Learn\Bookstore\Asset_Enqueing` namespace.

```php
<?php
/**
 * Plugin Name: Bookstore
 * Description: A plugin to manage books
 * Version: 1.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

include plugin_dir_path( __FILE__) . '/custom-post-types.php';
include plugin_dir_path( __FILE__) . '/asset-enqueing.php';

add_action( 'init', 'WP_Learn\Bookstore\Custom_Post_Types\register_book_post_type' );
add_action( 'init', 'WP_Learn\Bookstore\Custom_Post_Types\register_genre_taxonomy' );
add_filter( 'postmeta_form_keys', 'WP_Learn\Bookstore\Custom_Post_Types\add_isbn_to_quick_edit', 10, 2 );

add_action( 'wp_enqueue_scripts', 'WP_Learn\Bookstore\Asset_Enqueing\enqueue_scripts' );
```

The main plugin file includes the custom post type and asset enqueuing files and hooks the functions to the appropriate WordPress actions and filters. Notice how the fully qualified namespace is used as the callback function.

## Global namespace

One thing to note about using namespaces, is what happens when you don't specifically use the fully qualified namespace.

For example in the updated bookstore plugin, the `register_book_post_type()` function calls the `register_post_type()` function. 

This function is defined in the core of WordPress, inside the `wp-includes/post.php` file.

If you scroll to the top of that file, you'll see that no specific namespace is defined. Therefore, this function exists in the global namespace.

This means that when you call it from the `register_book_post_type()` function, you don't need to use the fully qualified namespace.

According to the [PHP documentation](https://www.php.net/manual/en/language.namespaces.global.php), if a specific namespace is not defined, all class and function definitions are placed into the global space.

However, you can use the \ (backslash) character to specify that you want to use the function from the global namespace:

```php
<?php

namespace WP_Learn\Bookstore\Custom_Post_Types;

function register_book_post_type() {
	$args = array(
		'labels'       => array(
			'name'          => 'Books',
			'singular_name' => 'Book',
			'menu_name'     => 'Books',
			'add_new'       => 'Add New Book',
			'add_new_item'  => 'Add New Book',
			'new_item'      => 'New Book',
			'edit_item'     => 'Edit Book',
			'view_item'     => 'View Book',
			'all_items'     => 'All Books',
		),
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
	);

	\register_post_type( 'book', $args );
}
```

This is a useful habit to get into, especially when it comes to creating new instances of classes that are defined in the global namespace, which we will dive further into in the next section.

## Classes

You can also make use classes to encapsulate your functions and variables. 

This allows you to group related functions and variables together and help to avoid naming collisions.

Going back to the earlier WP Conflict example, you could define a class called `WP_Conflict` and define your functions and variables as static methods and properties of the class:

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

This probably doesn't make sense for simple code like this, but let's refactor the bookstore plugin to use classes:

```php

if you look at the PHP documentation on [Using namespaces](https://www.php.net/manual/en/language.namespaces.fallback.php) it states:

> Inside a namespace, when PHP encounters an unqualified name in a class name, function or constant context, it resolves these with different priorities. Class names always resolve to the current namespace name. 
