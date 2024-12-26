# WordPress REST API - Custom Routes & Endpoints

## Introduction

While the default routes and endpoints provided by the WP REST API should cover most common use cases, there may be times when you need to create your own custom routes and endpoints to fetch or manipulate data in a specific way.

In this lesson, you're going to learn how to extend the WP REST API by creating your own routes and endpoints.

You will learn how to register custom routes, and how to create specific endpoints by setting the route method. 

You will also learn how it's possible to specify route arguments, and how to use them fetch specific data.

# A custom table to store book reviews

For the purposes of this lesson, you're going to create a plugin that stores and displays book reviews. 

To start build a plugin that registers a book custom post type.

```php
<?php
/**
 * Plugin Name: WP Learn Book Reviews
 * Description: Creates a book custom post type, with a reviews custom table.
 * Version: 1.0.0
 * License: GPL2
 *
 * @package WP_Learn_Book_Reviews
 */

add_action( 'init', 'wp_learn_register_book_post_type' );
/**
 * Register the book custom post type.
 */
function wp_learn_register_book_post_type() {
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

	register_post_type( 'book', $args );
}
```

Next, you're going to create a custom table to store the book reviews.

```php
register_activation_hook( __FILE__, 'wp_learn_setup_book_reviews_table' );
/**
 * Create the book reviews table.
 */
function wp_learn_setup_book_reviews_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$wpdb->prefix}book_reviews (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      book_slug text NOT NULL,
      review_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
      email text NOT NULL,
      review_text text NOT NULL,
      star_rating tinyint(1) NOT NULL,
      PRIMARY KEY (id)
      ) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
```

This is a common use case for custom routes and endpoints, as it allows you to work with data that doesn't fit neatly into the default WordPress post types.

Your plugin will create a custom table to store book reviews, and then create custom routes to fetch and create form submissions.

Here, in a function hooked into the plugin activation hook, you're using the global `$wpdb` object and the `dbDelta()` function to create a new table in the WordPress database called `book_reviews`. The table has six fields: `id`, `book_slug`, `review_time`, `email`, `review_text` and `star_rating`. The id field is set to auto-increment, and is the primary key.

If this is the first time you're seeing custom tables, don't worry too much about what it's doing, but make sure to check out the [Creating Custom Tables](https://learn.wordpress.org) lesson later which explains this in more detail.

For now, activate the plugin. 

Then use your database tool to check and make sure it created the `book_reviews` table in the database, with the six fields.

At the same time, create a couple of books, and make a note of each of their slugs, as you'll need that when you test creating a few reviews.

## Registering custom WP REST API routes

The typical requirements for building out this plugin would be to create a custom route which has three endpoints:

1. One to create a review
2. One to fetch all reviews
3. One to fetch a specific review

# Registering a custom WP REST API route to create a review

To register a custom WP REST API route you can use the [register_rest_route](https://developer.wordpress.org/reference/functions/register_rest_route/) function. This function requires the following parameters:

1. The namespace - this is the portion of the route URL that comes before the route itself. For example, in the route `/wp/v2/posts`, the namespace is `wp/v2`.
2. The route - this is the portion of the route URL that comes after the namespace. For example, in the route `/wp/v2/posts`, the route is `posts`.
3. The route arguments - this is an array of arguments that specify the route method, and any other arguments that you want to pass to the callback function.

`register_rest_route` should only be called when the `rest_api_init` action is fired. 

This ensures that the route is only registered when the WP REST API is loaded.

```php
add_action( 'rest_api_init', 'wp_learn_register_routes' );
/**
 * Register the REST API wp-learn-book-reviews/v1/book-review routes
 */
function wp_learn_register_routes() {
    // Register the routes
}
```

Let's look at what the code might look like to register a custom route to create a review:

```php
	register_rest_route(
		'wp-learn-book-reviews/v1',
		'/book-reviews',
		array(
			'methods'  => 'POST',
			'callback' => 'wp_learn_create_book_review',
			'permission_callback' => '__return_true'
		)
	);
```

1. The namespace is `wp-learn-book-reviews/v1`
2. The route is `/book-reviews`
3. The route arguments specify the route method as POST, and the callback function as `wp_learn_create_book_review`. It also specifies a permission callback function, which is used to check if the user has permission to access the route. For now, it's using the built-in `__return_true` function, which returns `true`, so anyone can access the route.

With the route registered, you'll need to create the callback function, `wp_learn_create_book_review`. This function will be called when the route is accessed using the POST method, and it will create a new review.

```php
/**
 * Callback for the wp-learn-book-reviews/v1/book-review POST route
 *
 * @param WP_REST_Request $request The request object.
 *
 * @return int The number of rows inserted.
 */
function wp_learn_create_book_review( $request ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'book_reviews';

	$book_slug   = sanitize_text_field( $request['book_slug'] );
	$email       = sanitize_email( $request['email'] );
	$review_text = sanitize_textarea_field( $request['review_text'] );
	$star_rating = intval( $request['star_rating'] );

	$rows = $wpdb->insert(
		$table_name,
		array(
			'book_slug'   => $book_slug,
			'email'       => $email,
			'review_text' => $review_text,
			'star_rating' => $star_rating,
		)
	);

	return $rows;
}
```

The callback function accepts a single parameter which is a `WP_REST_Request` object and contains all the data that was sent to the route.

The function then uses the global `$wpdb` variable in order to access the database, and calls its `insert` method to insert a new row into the `book_reviews` table, using the data passed in the `$request` object.

In this case, the `book_slug`, `email`, `review_text` and `star_rating` fields should be available in the `$request` object, which can be used to create the review.

Before inserting the data, you should first validate the inputs, to prevent any security vulnerabilities. For more information on how to validate inputs, review the [Securely developing plugins and themes](https://learn.wordpress.org/lesson/securely-developing-plugins-and-themes/) lesson in the Beginner Developer course.

Notice how you're accessing the properties on the `$request` object using an array-like syntax. This is because the `WP_REST_Request` object implements a PHP interface called [ArrayAccess](https://www.php.net/manual/en/class.arrayaccess.php), which allows you to access object properties using the array syntax.

Finally, this function returns the number of rows inserted.

Then open a REST API testing tool like [Postman](https://www.postman.com/), and create a new request to test the route.

To do this, create a new `POST` request to the `wp-learn-book-reviews/v1/book-review` route, and submit the review data as a JSON object in the request body:

```
https://learn.test/wp-json/wp-learn-book-reviews/v1/book-reviews
```

```json
{
    "book_slug": "meditations",
    "email": "jondoe@gmail.com",
    "review_text": "This is a great book!",
    "star_rating": 5
}
```

When you save and send the request you should see the number of rows inserted returned in the response. 

If you browse the `book_reviews` table in your database, you should see the new review data has been inserted.

## Basic authentication for custom WP REST API routes

Notice how you didn't need to pass any authentication credentials to this route. This is because you set the permission_callback to `__return_true`. This means that anyone can currently create a POST request to the route, and create a new review.

Ideally, you would want to restrict this in some way. To restrict access to the route, you can specify a `permissions_callback` function.

```php
'permission_callback' => 'wp_learn_require_permissions'
```

Then you can create the callback function which will check if the user has the required permissions.

```php
function wp_learn_require_permissions() {
    // Check if the user has the required permissions
}
```

There are a number of ways to check if a user has the required permissions, depending on the authorisation process. 

One simple way to do this is to check for a valid user on the site, with permissions to edit posts.

```php
function wp_learn_require_permissions() {
	return current_user_can( 'edit_posts' );
}
```

Try testing this out by submitting the POST request using the route.

You should see an authentication error, which means that you don't have permission to access the route.

Now, set up an Application Password for your current user, configure the username and application password in Postman as the Basic Auth type under Authorization, and then try the request again.

It should work this time, and you'll see the number of rows inserted in the response.

To verify this, check the `book_reviews` table in your database, to make sure the data has been inserted.

## Creating a custom WP REST API endpoint to fetch all reviews

The next requirement for your custom API route is an endpoint that can fetch all reviews. You can create this in the same way as you created the create review route, by registering a new route with the GET method, and then creating a callback function to fetch the reviews.

```php
	register_rest_route(
		'wp-learn-book-reviews/v1',
		'/book-reviews',
		array(
			'methods'             => 'GET',
			'callback'            => 'wp_learn_get_book_reviews',
			'permission_callback' => '__return_true',
		)
	);
```

The namespace for the route can be the same, `wp-learn-book-reviews/v1`, but it would be useful to have a different route name, such as `/book-reviews`.

The route arguments specify the route method as GET, and the callback function as `wp_learn_get_form_submissions`. 

In this case it's OK to leave the `permission_callback` to `__return_true` function to make the route publicly available, but you can always specify a custom `permission_callback` if you want to lock down the route.

Once the route is created, you'll need to create the callback function, `wp_learn_get_book_reviews`. This function will be called when the route is requested, and it will return the reviews.

```php
/**
 * Callback for the wp-learn-book-reviews/v1/book-reviews GET route
 *
 * @return array|object|null
 */
function wp_learn_get_book_reviews() {
	global $wpdb;

	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}book_reviews" );

	return $results;
}
```

Again, this function uses the global `$wpdb` variable to access the database, and then uses the `get_results` method to fetch all the rows from the `book_reviews` table, based on the SQL query. It then returns the results as an array.

Because you've registered this callback against a WP REST API route using `register_rest_route`, the array will automatically be sent in the REST API response as a JSON object.

To test this, open your API testing tool, and create a GET request to the route:

```
https://learn.test/wp-json/wp-learn-book-reviews/v1/book-reviews
```

You should see the results returned as a JSON object.

## Creating a custom WP REST API endpoint to fetch a single review

The last requirement for your custom API route was an endpoint that could fetch a single review. One way you could do this is to set up the `wp_learn_get_book_reviews` callback to also accept the `$request` parameter, and then check if an `id` value is passed in the request body.

A better solution is to use something called path variables. You've seen REST API variables before in the Using the WP REST API lesson, where global variables were discussed. Path variables are similar, but they're used to pass data to a route.

To implement a path variable, you add the path variable to the route name when you register the route. For example, if you wanted to implement a path variable for the id field to fetch a single review, you could create a route like this:

```php
	/**
	 * GET single
	 */
	register_rest_route(
		'wp-learn-book-reviews/v1',
		'/book-reviews/(?P<id>\d+)',
		array(
			'methods'             => 'GET',
			'callback'            => 'wp_learn_get_review',
			'permission_callback' => 'wp_learn_require_permissions',
		)
	);
```

They key thing to not is the format of the path variable:

`?P<{name}>{regex-pattern}`

1. The start of the path variable is a query string using an upper case P as the query parameter
2. name is the name of the placeholder. This will be used to create the property on the `$request` object, to access the path variable value
2. `regex-pattern` is the regular expression pattern that the value should match. In this case, `\d+` means that the value should be a number.

This tells the route that a value will be passed to the route and that it should be a numeric. This numeric value will then set up as a property with the name 'id' on the request object.

Now you just need to create the `wp_learn_get_review` function, to get the `review` based on the id, which could look something like this:

```php
/**
 * Callback for the wp-learn-book-reviews/v1/book-reviews GET route
 *
 * @param object $request The request object.
 *
 * @return mixed|stdClass
 */
function wp_learn_get_review( $request ) {
	global $wpdb;
	$id = $request['id'];

	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}book_reviews WHERE id = %d",
			$id
		)
	);

	return $results[0];
}
```

As you can see in the first line of this callback function, it's expecting the `id` to be passed to the request object in the route.

It then uses that `id` to retrieve the specific record from the `book_reviews` table

Because you have set up this path variable, you only need to append the id value at the end of the route URI when you make the request. The path variable will handle getting the value, and setting it up inside the id property on the request object.

To test this, create a new GET request in your API testing tool, and add the id value to the end of the route.

```
https://learn.test/wp-json/wp-learn-book-reviews/v1/book-reviews/1
```

You should see the results returned as a JSON object.

Play around with this a little to test it, create a few more reviews, use the GET route to fetch them, and then use the GET single route to fetch a single review by id.

## Further reading

For more information and examples of custom REST API routes and endpoints, check out the [Extending the REST API](https://developer.wordpress.org/rest-api/extending-the-rest-api/) chapter of the WP REST API handbook, specifically the [Adding Custom Endpoints](https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/) and [Routes and Endpoints](https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/) sections.