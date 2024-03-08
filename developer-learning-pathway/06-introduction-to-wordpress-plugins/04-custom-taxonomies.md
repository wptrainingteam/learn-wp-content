# Custom taxonomies

In WordPress, taxonomies are a way to group things together. In a default WordPress install, there are two types of registered taxonomies: categories and tags.

## Introduction

When developing a plugin that registers a custom post type, you can also register custom taxonomies.

This adds some additional flexibility to your plugin, as it allows your custom post types to be grouped independently from the default categories or tags.

Let's look at how this works, and what you need to do to register a custom taxonomy.

## Why use custom taxonomies?

You might think that the two custom taxonomies that come with WordPress are enough, but there are times when your plugin might need to group data in a different way.

For example, in a bookstore, you might want to group books by genre, such as fiction, non-fiction, science fiction, etc.

Additionally, when you register a custom taxonomy for a specific post type, that taxonomy will only be available to that post type, and will appear associated to the post type in the admin menu and the edit screens.

## Registering a custom taxonomy

Let's add a custom taxonomy to the bookstore plugin you were building in the previous lessons:

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
			'name' => 'Books',
			'singular_name' => 'Book',
		),
		'public' => true,
		'has_archive' => true,
		'show_in_rest' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
	);

	register_post_type( 'book', $args );
}
```

To register a custom taxonomy, you can use the `register_taxonomy` [function](https://developer.wordpress.org/reference/functions/register_taxonomy/) in a similar way to the `register_post_type` function.

This function requires you to pass in the name of the taxonomy, the post type that the taxonomy is associated with, and an array of arguments.

Similarly to the `register_post_type` function, the `register_taxonomy` function also needs to be hooked into the `init` action.

So start by registering the callback to the action.

```php
add_action( 'init', 'bookstore_register_genre_taxonomy' );
```

Then, you can create the `bookstore_register_genre_taxonomy` callback function, 

In this function create the arguments array, and call the `register_taxonomy` function, passing in the relevant arguments to create the taxonomy.

```php
function bookstore_register_genre_taxonomy() {
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
		'show_in_rest'           => true,
	);

	register_taxonomy( 'genre', 'book', $args );
}
```

You could also add this code to the `bookstore_register_book_post_type` function, so that the genre taxonomy is registered when the book post type is registered. 

Once this is added to your bookstore plugin, you'll see a new Genre menu item in the admin menu, and you'll be able to add genres to your books.

Additionally, once you've added some genres, you'll be able to select from those genres when you add or edit a book.

Just like with regular taxonomies, you will be able to browse the archive page for each taxonomy, and see all the books that are associated with that taxonomy.

## Further reading

To read more about custom taxonomies, and how to register them, visit the [Taxonomies](https://developer.wordpress.org/plugins/taxonomies/) page in the Plugin developer handbook.

It's also a good idea to read the full [register_taxonomy](https://developer.wordpress.org/reference/functions/register_taxonomy/) function reference page in the WordPress developer documentation, as it contains a full list of all the arguments that can be passed to the function, and what they do.

## YouTube chapters

0:00 Introduction
0:00 Why use custom taxonomies
0:00 Registering a custom taxonomy
0:00 Further reading