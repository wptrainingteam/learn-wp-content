<!-- Original script by Cyrille C: https://github.com/CrochetFeve0251 -->

# Introduction

As a plugin developer, you’ll probably try to cover all possible use cases when building your plugin’s functionality.

However, all websites have unique requirements, and it’s impossible to satisfy all of them.

This sometimes means your plugin will end up being really close to what a potential user needs but might need one or two minor additions that would make it a perfect solution.

Fortunately, there is a way to allow your plugin’s users to extend its functionality without editing the plugin code.

In this lesson, you will learn about custom hooks, the perfect way to enable your users to customize your plugin to their needs.

## Custom hooks

Custom hooks are just like regular hooks you’ve already learned about, which are defined by WordPress Core.

The only differnce is that you are behind the wheel. 

You can create either custom action hooks or custom filter hooks through your plugin code, to enable other users or developers the ability to extend the functionality of your plugin.

## Why use custom hooks?

Custom hooks are a great way to keep focus on your plugin’s main functionality.

They make it possible for your plugin user to customize your plugin behavior to meet their needs and implement their own features.

They also allow you to avoid having to worry about making your plugin compatible every single possible external integration.

As custom hooks give users a way to implement their own custom scenarios themselves they will often report their solution back to you when they find one, which you can share with the rest of your users.

For the rest of the examples in this lesson, download, install, and activate version 1.0.1 of the [bookstore plugin](https://github.com/wptrainingteam/beginner-developer) from the Training Team's GitHub repository on your local WordPress installation. This plugin is part of the Beginner Developer Learning Pathway.

## Creating a custom action

You would often create custom actions to trigger before something specific is going to happen or after something specific has happened.

To do this, you call the `do_action` [function](https://developer.wordpress.org/reference/functions/do_action/), passing it the name of the action as a parameter.

Open the bookstore.php file in the bookstore plugin and find the `bookstore_render_booklist` function. 

This function that renders the bookstore plugins custom Sub Menu page in the WordPress dashboard.

Let's say you want to make it possible for someone to add their own HTML content to that function. You can do this by adding action hooks using the `do_action` function.

```php
function bookstore_render_booklist() {
	do_action( 'bookstore_before_render_booklist' );
	?>
	<div class="wrap" id="bookstore-booklist-admin">
		<h1>Actions</h1>
		<button id="bookstore-load-books">Load Books</button>
		<button id="bookstore-fetch-books">Fetch Books</button>
		<h2>Books</h2>
		<textarea id="bookstore-booklist" cols="125" rows="15"></textarea>
	</div>
    <div style="width:50%;">
        <h2>Add Book</h2>
        <form>
            <div>
                <label for="bookstore-book-title">Book Title</label>
                <input type="text" id="bookstore-book-title" placeholder="Title">
            </div>
            <div>
                <label for="bookstore-book-content">Book Content</label>
                <textarea id="bookstore-book-content" cols="100" rows="10"></textarea>
            </div>
            <div>
                <input type="button" id="bookstore-submit-book" value="Add">
            </div>
        </form>
    </div>

	<?php
	do_action( 'bookstore_after_render_booklist' );
}
```

This way, other developer can hook into the `before_bookstore_render_booklist` and `after_bookstore_render_booklist` actions to add their own content to the bookstore plugin's Sub Menu page.

## Creating a custom filter

Custom filters are a way to allow someone to change the value of something you define in your code.

It allows you to make a specific decision for how your code functions for plugin users, but also allow more experienced users the ability to extend that decision to suit their requirements.

To create a custom filter, you call the `apply_filters` [function](https://developer.wordpress.org/reference/functions/apply_filters/) with the name of the filter as the first parameter and the default value the filter is applied to as the second parameter.

Two good places to add filters to the bookstore plugin are just before the custom post type and custom taxonomy are registered.

```php
add_action( 'init', 'bookstore_register_book_post_type' );
function bookstore_register_book_post_type() {
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
		'rest_base'    => 'books',
		'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
	);
	
	apply_filters( 'bookstore_register_post_type', $args );
	
	register_post_type( 'book', $args );
}
```

```php
add_action( 'init', 'bookstore_register_genre_taxonomy' );
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
		'show_in_rest' => true,
	);

    apply_filters( 'bookstore_register_genre_taxonomy', $args );

	register_taxonomy( 'genre', 'book', $args );
}
```


With this in place, someone could hook into either of these filter, and add or remove values form the relevant $args array that is used to creat the custom post type or custom taxonomy.

## Action and filter arguments

In the lesson on [Working with hooks](https://learn.wordpress.org/lesson/working-with-hooks/)

Both the `do_action` and `apply_filters` function signatures accept an unlimited number of optional, additional arguments.

These arguments are passed to the callback function when the action or filter is triggered.

You can use these arguments to pass data to the callback function, such as the context in which the action or filter is triggered.

For example, you could pass the current value of is_admin() as a "context" argument to the `bookstore_before_render_booklist` action:

```php
function bookstore_render_booklist() {
    $is_admin = is_admin();
	do_action( 'bookstore_before_render_booklist', $is_admin );
	?>
	<div class="wrap" id="bookstore-booklist-admin">
		<h1>Actions</h1>
		<button id="bookstore-load-books">Load Books</button>
		<button id="bookstore-fetch-books">Fetch Books</button>
		<h2>Books</h2>
		<textarea id="bookstore-booklist" cols="125" rows="15"></textarea>
	</div>
    <div style="width:50%;">
        <h2>Add Book</h2>
        <form>
            <div>
                <label for="bookstore-book-title">Book Title</label>
                <input type="text" id="bookstore-book-title" placeholder="Title">
            </div>
            <div>
                <label for="bookstore-book-content">Book Content</label>
                <textarea id="bookstore-book-content" cols="100" rows="10"></textarea>
            </div>
            <div>
                <input type="button" id="bookstore-submit-book" value="Add">
            </div>
        </form>
    </div>

	<?php
	do_action( 'bookstore_after_render_booklist', $is_admin );
}
```

Any callback function hooked into the `bookstore_before_render_booklist` or `bookstore_after_render_booklist` action will now receive the value of is_admin() as an argument.

```
add_action( 'bookstore_before_render_booklist', 'my_callback', 10, 1 );
function my_callback( $is_admin ) {
    //
}
```

## Naming conflicts

In the lesson on Naming Collisions, you learned how to avoid naming conflicts in the global namespace. This is also true when creating custom hooks.

## Conflict between plugins

When creating custom hooks, you should always prefix your hook names with a unique identifier, ideally the same one used elsewhere in your plugin:

```php
do_action( 'bookstore_before_render_booklist' );
do_action( 'bookstore_after_render_booklist' );
```

That way, if someone else creates another hook with a similar name, it won't conflict with yours, as each one will be prefixed with its own unique identifier.

### Conflicts between filters and actions

Under the hood, actions and filters are functionally the same, the main difference being that actions don’t return a value and filters do.

For that reason, you should always use unique names for each hook. Don’t create an action and a filter with the same name.

```php
// This is wrong
do_action('my_hook');
apply_filters('my_hook', $some_variable)
```

Doing this will result in conflicts with callback functions registered to the action or filter, as it means the callback will run both when the action and filter are triggered.

Depending on whether you hook a callback into the action or filter, it may also cause errors in the code execution.

## Further reading

You can read more about custom hooks along with some examples in the WordPress Plugin Developer Handbook section on  [custom hooks](https://developer.wordpress.org/plugins/hooks/custom-hooks/).