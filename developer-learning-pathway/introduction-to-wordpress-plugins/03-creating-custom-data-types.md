# Creating custom post types

One of the more common use cases for developing a custom plugin is to take advantage of the WordPress Post API to create custom post types.

## Building an online bookstore

Let's say you're building an online bookstore, and you want to store data about the books that are for sale.

The WordPress core Post API allows you to register custom post types. These custom post types extend the core `post` data type, which is stored in the `posts` table in the WordPress database.

An example of a custom post type is the `page` data type, which is registered by WordPress core. 

Using the WordPress POST API, you could create a custom post type to store information about books.

When you register a custom post type correctly, WordPress will automatically create a new admin menu item for your custom post type, and admin pages to manage the data.

## Custom post types

To register a custom post type, you use the WordPress `register_post_type` [function](https://developer.wordpress.org/reference/functions/register_post_type/). 

As you can see from the function reference page in the WordPress developer documentation, this function takes two parameters: the name of the custom post type, and an array of arguments that define the custom post type.

Parameters are the names of the variables that you define in the function definition. In this case the parameters are the name, as a string variable, and an array of arguments.

When you use the function, you pass in the values for these parameters. These values are called arguments.

Here's an example of creating the arguments array, and calling the `register_post_type`, passing in the relevant arguments, to create the custom post type for books.

```php
$args = array(
    'labels' => array(
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
    'public' => true,
    'has_archive' => true,
    'show_in_rest' => true,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
);
    
register_post_type( 'book', $args );
```

Let's look at how you could implement this code in a plugin.

## Implementing the book custom post type

To start create a new plugin in the same way that you learned in the What is a plugin lesson.

Add a directory in the `wp-content/plugins` directory called `bookstore`. 

Then create a new PHP file called `bookstore.php` inside that directory. 

Inside the file, add the opening PHP tag, and the plugin header to the top of the file, as well as the `ABSPATH` check to prevent direct access to the file.

```php
<?php
/**
 * Plugin Name: Bookstore
 * Description: A plugin to manage books
 * Version: 1.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
```

Next, create a new function called `bookstore_register_book_post_type` that will register the custom post type. Create the arguments array, and then call the `register_post_type` function, passing in the relevant arguments to create the custom post type for books.

```php
function bookstore_register_book_post_type() {
     $args = array(
        'labels' => array(
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
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
    );

    register_post_type( 'book', $args );
}
```

Notice the use of the `bookstore_` prefix in the function name. 

This is an example if prefixing function names to avoid conflicts with other plugins or themes. 

You can read more about this, and other methods to prevent function conflicts in the [Plugin best practices](https://developer.wordpress.org/plugins/plugin-basics/best-practices/#avoid-naming-collisions) page in the Plugin developer handbook.

The last step is to call the `bookstore_register_book_post_type` function. You need to hook into the `init` action to do this. 

If you skipped the module on WordPress hooks, hooks that they are a way to run code at specific points in the WordPress request lifecycle.

```php
add_action( 'init', 'bookstore_register_book_post_type' );
```

Your final code should look like this:

```php
<?php
/**
 * Plugin Name: Bookstore
 * Description: A plugin to manage books
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'init', 'bookstore_register_book_post_type' );
function bookstore_register_book_post_type() {
    $args = array(
        'labels' => array(
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
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
    );

    register_post_type( 'book', $args );
}
```

Now, browse to your WordPress dashboard and activate the plugin. 

You should see a new menu item called "Books" in the admin menu with the ability to add new books to your book custom post type. 

If you browse to the `wp_posts` table in the WordPress database, you'll see a new row with the `post_type` column set to `book`, containing the new book data.

## Custom Post types and performance

It's important to note that registering too many custom post types can have a performance impact on a WordPress site. 

This is because the custom post type registration process is executed on every site request, even if the custom post type is not being used on that page. 

So be careful of overusing custom post types and consider when you should use them vs creating custom tables to store custom data.

You can learn more about creating and using custom tables in the [Creating Tables with Plugins](https://developer.wordpress.org/plugins/plugin-basics/creating-tables-with-plugins/) page in the Plugin developer handbook.

You will also learn more about this in the plugin developer learning pathway

## Further reading

To read more about custom post types, and how to register them, visit the [Post Types](https://developer.wordpress.org/plugins/post-types/) page in the Plugin developer handbook.

It's also a good idea to read the full [register_post_type](https://developer.wordpress.org/reference/functions/register_post_type/) function reference page in the WordPress developer documentation, as it contains a full list of all the arguments that can be passed to the function, and what they do.

## YouTube chapters

(0:00) Building an online bookstore
(0:00) Custom post types
(0:00) Implementing the book custom post type
(0:00) Custom Post types and performance
(0:00) Further reading