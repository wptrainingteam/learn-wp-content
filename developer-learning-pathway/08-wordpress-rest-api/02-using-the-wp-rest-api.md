# Using the WP REST API

## Introduction

In the introductory lesson, you learned about the WordPress REST API, and how it allows you to interact with your WordPress site using HTTP requests.

In this lesson, you'll learn how to use the WP REST API to interact with the data in your WordPress site. 

You'll discover three options for making REST API requests, and then use a GET request to retrieve some public custom post type data, to display on your website.

## The Bookstore plugin

If you completed the Introduction to WordPress plugins module, you built a plugin that registers a custom post type called `book`. 

If you skipped that module, you can download main plugin code from the [GitHub repository](https://github.com/wptrainingteam/beginner-developer) by clicking on the [Bookstore plugin link](https://github.com/wptrainingteam/beginner-developer/raw/main/bookstore.1.0.zip).

Once you have the plugin installed and activated, open the main plugin file in your code editor.

You will notice that one of the arguments passed to the `register_post_type` function is `show_in_rest`. 

```php
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
```

This argument is set to `true`, which means that the custom post type is available in the REST API.

This means that if you browse to the `wp-json/wp/v2/book` route, you will see the custom post type data in the response.

If you take a look at the `register_post_type` [function reference](https://developer.wordpress.org/reference/functions/register_post_type/), you'll see a few additional arguments that can be used to control the REST API response.

For example, you can change the `rest_base` argument to change the route that the custom post type data is available at.

Given that you would expect to be able to fetch more than one book from the book route, it would be a good idea to change the rest_base to `books`.

Doing this will allow you to make a GET request to the `wp-json/wp/v2/books` route, to retrieve all the books in the response.

## Making REST API requests

Let's say that you've added a simple admin page to your Book

