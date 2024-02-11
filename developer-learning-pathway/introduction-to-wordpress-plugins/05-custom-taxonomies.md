# Custom taxonomies

Taxonomies are a way to group things together. In a default WordPress install, there are two types of registered taxonomies: categories and tags.

When developing a plugin that registers a custom post type, you can also register custom taxonomies, which brings some additional flexibility to your plugin.

Let's look at how this works, and what you need to do to register a custom taxonomy.

## Why use custom taxonomies?

You might think that the two custom taxonomies that come with WordPress are enough, but there are times when your plugin might need to group data in a different way.

For example, in a bookstore, you might want to group books by genre, such as fiction, non-fiction, or science fiction.

Additionally, when you register a custom taxonomy for a specific post type, that taxonomy will only be available to that post type, and will appear associated to the post type in the admin menu.

## Registering a custom taxonomy

Let's review the bookstore plugin we've been building in the previous lessons:

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

add_filter('postmeta_form_keys', 'bookstore_add_isbn_to_quick_edit', 10, 2);
function bookstore_add_isbn_to_quick_edit($keys, $post) {
	if ($post->post_type === 'book') {
		$keys[] = 'isbn';
	}
	return $keys;
}
```

To register a custom taxonomy, you can use the `register_taxonomy` [function](https://developer.wordpress.org/reference/functions/register_taxonomy/) in a similar way to the register_post_type function.

This function requires you to pass in the name of the taxonomy, the post type that the taxonomy is associated with, and an array of arguments.

Similarly to the `register_post_type` function, the `register_taxonomy` function also needs to be hooked into the `init` action.

```php
add_action( 'init', 'bookstore_register_genre_taxonomy' );
```

Then, you can create the `bookstore_register_genre_taxonomy` function, which will call the `register_taxonomy` function, passing in the relevant arguments to create the taxonomy.

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

Once this is added to your bookstore plugin, you'll see a new Genre menu item in the admin menu, and you'll be able to add genres to your books.

Additionally, once you've added some genres, you'll be able to select from those genres when you add or edit a book.

## Further reading

To read more about custom taxonomies, and how to register them, visit the [Taxonomies](https://developer.wordpress.org/plugins/taxonomies/) page in the Plugin developer handbook.

It's also a good idea to read the full [register_taxonomy](https://developer.wordpress.org/reference/functions/register_taxonomy/) function reference page in the WordPress developer documentation, as it contains a full list of all the arguments that can be passed to the function, and what they do.