# WordPress REST API - Custom Routes & Endpoints

## Introduction

While the default routes and endpoints provided by the WP REST API should cover most common use cases, there may be times when you need to create your own custom routes and endpoints to fetch or manipulate data in a specific way.

In this lesson, you're going to learn how to extend the WP REST API by creating your own routes and endpoints.

You will learn how to register custom routes, and how to create specific endpoints by setting the route method. 

You will also learn how it's possible to specify route arguments, and how to use them fetch specific data.

# A custom table to store form submissions

For the purposes of this lesson, you're going use with a custom table to store form submissions. 

This is a common use case for custom routes and endpoints, as it allows you to store data that doesn't fit neatly into the default WordPress post types.

Your plugin will create a custom table to store form submissions, and then create custom routes to fetch and create form submissions.

Your plugin code to create the custom table might look something like this:

```php
/**
 * Plugin Name: WP Learn - Form Submissions
 * Description: A plugin to demonstrate creating custom routes and endpoints in the WP REST API.
 * Version: 1.0.0 
 */
register_activation_hook( __FILE__, 'wp_learn_setup_table' );
function wp_learn_setup_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name varchar (100) NOT NULL,
	  email varchar (100) NOT NULL,
	  PRIMARY KEY  (id)
	)";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
```

Here, in a function hooked into the plugin activation hook, you're using the global `$wpdb` object and the `dbDelta()` function to create a new table in the WordPress database called `form_submissions`. The table has three fields: `id`, `name`, and `email`. The id field is set to auto-increment, and is the primary key.

If this is the first time you're seeing custom tables, don't worry too much about what it's doing, but make sure to check out the [Creating Custom Tables](https://learn.wordpress.org) lesson later which explains this in more detail.

For now, activate the plugin. 

Then use your database tool to check and make sure it created the `form_submissions` table in the database, with the three fields.

## Registering a custom WP REST API route to fetch submissions

Now consider a requirement to build a simple form submissions plugin, which allows a name and email field to be captured via the WP REST API. 

The plugin should allow for a `form-submissions` REST API route, which has three endpoints:

1. One to fetch all form submissions
2. One to create a form submission
3. One to fetch a specific form submission

# Registering a custom WP REST API route to fetch submissions

To register a custom WP REST API route you can use the [register_rest_route](https://developer.wordpress.org/reference/functions/register_rest_route/) function. This function requires the following parameters:

1. The namespace - this is the portion of the route URL that comes before the route itself. For example, in the route `/wp/v2/posts`, the namespace is `wp/v2`.
2. The route - this is the portion of the route URL that comes after the namespace. For example, in the route `/wp/v2/posts`, the route is `posts`.
3. The route arguments - this is an array of arguments that specify the route method, and any other arguments that you want to pass to the callback function.

`register_rest_route` should only be called when the `rest_api_init` action is fired. 

This ensures that the route is only registered when the WP REST API is loaded.

```php
/**
 * Register the REST API wp-learn-form-submissions-api/v1/form-submission routes
 */
add_action( 'rest_api_init', 'wp_learn_register_routes' );
function wp_learn_register_routes() {
    // Register the routes
}
```

Let's look at what the code might look like to register a custom route to fetch all form submissions:

```php
	register_rest_route(
		'wp-learn-form-submissions-api/v1',
		'/form-submissions/',
		array(
			'methods'  => 'GET',
			'callback' => 'wp_learn_get_form_submissions',
			'permission_callback' => '__return_true'
		)
	);
```

1. The namespace is `wp-learn-form-submissions-api/v1`
2. The route is `/form-submissions/`
3. The route arguments specify the route method as GET, and the callback function as `wp_learn_get_form_submissions`. It also specifies a permission callback function, which is used to check if the user has permission to access the route. In this case, we're using the built-in `__return_true` function, which returns `true`, so anyone can access the route.

Once the route is created, you'll need to create the callback function, `wp_learn_rest_get_form_submissions`. This function will be called when the route is requested, and it will return the form submissions.

```php
/**
 * GET callback for the wp-learn-form-submissions-api/v1/form-submission route
 *
 * @return array|object|stdClass[]|null
 */
function wp_learn_get_form_submissions() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$results = $wpdb->get_results( "SELECT * FROM $table_name" );

	return $results;
}
```

This function uses the global `$wpdb` variable to access the database, and then uses the `get_results` method to fetch all the rows from the `form_submissions` table, based on the SQL query. It then returns the results as an array.

Because you've registered this callback against a WP REST API route using `register_rest_route`, the array will automatically be sent in the REST API response as a JSON object.

To test this, use your database tool and add a few rows of data to the table.

Then open a REST API testing tool like [Postman](https://www.postman.com/), and create a new request to test the route.

```
GET https://workpress.test/wp-json/wp-learn-form-submissions-api/v1/form-submissions
```

You should see the results returned as a JSON object.

# Registering a custom WP REST API route to post submissions

Now that you can fetch form submissions, you can register a route to create them. To do this, you'll register a new route to use the POST method, and specify a callback function which will handle the POST request.

```php
	/**
	 * POST
	 */
	register_rest_route(
		'wp-learn-form-submissions-api/v1',
		'/form-submission/',
		array(
			'methods'  => 'POST',
			'callback' => 'wp_learn_create_form_submission',
			'permission_callback' => '__return_true'
		)
	);
```

As you can see, the namespace and route are the same, but the method is set to POST, and a different callback function is specified. This callback function will be called when the route is accessed using the POST method, and could look like this:

```php
/**
 * POST callback for the wp-learn-form-submissions-api/v1/form-submission route
 *
 * @param $request
 *
 * @return void
 */
function wp_learn_create_form_submission( $request ){
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$rows = $wpdb->insert(
		$table_name,
		array(
			'name' => $request['name'],
    		'email' => $request['email'],
        )
    );

	return $rows;
}
```

The callback function accepts a single parameter which is a `WP_REST_Request` object and contains all the data that was sent to the route.

In this case, the `name` and `email` fields will be available in the `$request` object, which can be used to create the form submission.

Again, the callback function uses the global `$wpdb` variable in order to access the database, and then uses its `insert` method to insert a new row into the `form_submissions` table, using the name and email fields sent to the route.

Notice how the code accesses the name and email properties using an array-like syntax. This is because the `WP_REST_Request` object implements a PHP interface called [ArrayAccess](ArrayAccess), which allows you to access object properties using array syntax.

Finally, it returns the number of rows inserted.

Note that in this example there's no validation of the name and email inputs, which is a security vulnerability. 

For more information on how to validate inputs, review the [Securely developing plugins and themes](https://learn.wordpress.org/lesson/securely-developing-plugins-and-themes/) lesson in the Beginner Developer course.

Note also that in this example the callback function does not check if the fields have been sent in the request, or if they contain data. For the purposes of this example, we'll assume they will always been passed.

To test this, create a new `POST` request in your API testing tool, and submit the name and email data as a JSON object in the request body:

```json
{
    "name": "John Doe",
    "email": "jon@gmail.com"
}
```

When you save and send the request you should see the number of rows inserted returned in the response.

Notice how you didn't need to pass any authentication credentials to this route. This is because you set the permission_callback to `__return_true`. This means that anyone can currently create a POST request to the route, and create a new form submission.

If you want to restrict access to the route, you can specify a permissions_callback function.

```php
'permission_callback' => 'wp_learn_require_permissions'
```

Then you can use the `current_user_can` function to check if the user has the required permissions.

```php
function wp_learn_require_permissions() {
	return current_user_can( 'edit_posts' );
}
```

In this case this checks for a valid user, with permissions to edit posts.

Try testing this by submitting the POST request using the route.

You should see an authentication error, which means that you don't have permission to access the route.

Now, set up an Application Password for your current user, configure it in Postman under Authorization, and try the request again.

It should work this time, and you'll see the number of rows inserted in the response. 

To verify this, check the `form_submissions` table in your database, to make sure the data has been inserted.

## Creating a custom WP REST API endpoint to fetch a single submission

The last requirement for your custom API route was an endpoint that could fetch a single submission. One way you could do this is to set up the `wp_learn_get_form_submissions` callback to also accept the `$request` parameter, and then check if an `id` value is passed in the request body.

A better solution is to use something called path variables. You've seen REST API variables before in the Using the WP REST API tutorial, where global variables were discussed. Path variables are similar, but they're used to pass data to a route.

To implement a path variable, you add the path variable to the route name when you register the route. For example, if you wanted to implement a path variable for the id field to fetch a single submission, you could create a route like this:

```php
	/**
	 * GET single
	 */
	register_rest_route(
		'wp-learn-form-submissions-api/v1',
		'/form-submission/(?P<id>\d+)',
		array(
			'methods'  => 'GET',
			'callback' => 'wp_learn_rest_get_form_submission',
			'permission_callback' => '__return_true'
		)
	);
```

They key thing to not is the format of the path variable:

`?P<{name}>{regex-pattern}`

1. The start of the path variable is a query string using an upper case P as the query parameter
2. name is the name of the placeholder. This will be used to create the property on the $request object, to access the path variable value
2. regex-pattern is the regular expression pattern that the value should match. In this case, \d+ means that the value should be a number.

This tells the route that a value will be passed to the route and that it should be a numeric. This numeric value will then set up as a property with the name 'id' on the request object.

Now you just need to create the `wp_learn_get_form_submission` function, to get the `form_submission` based on the id, which could look something like this:

```php
function wp_learn_rest_get_form_submission( $id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = $id" );

	return $results[0];
}
```

As you can see in the first line of this callback function, it's expecting the id to be passed in the route.

It then uses that id to retrieve the specific record from the form_submissions table

Because you have set up this path variable, you only need to append the id value at the end of the route URI when you make the request. The path variable will handle getting the value, and setting it up inside the id property on the request object.

To test this, create a new GET request in your API testing tool, and add the id value to the end of the route.

```
https://workpress.test/wp-json/wp-learn-form-submissions-api/v1/form-submission/1
```

You should see the results returned as a JSON object.

Play around with this a little to test it, create a few more form submissions, use the GET route to fetch them, and then use the GET single route to fetch a single submission by id.

## Further reading

For more information and examples of custom REST API routes and endpoints, check out the [Extending the REST API](https://developer.wordpress.org/rest-api/extending-the-rest-api/) chapter of the WP REST API handbook, specifically the [Adding Custom Endpoints](https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/) and [Routes and Endpoints](https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/) sections.

## Full example code

```php
<?php
/**
 * Plugin Name: WP Learn - Form Submissions
 * Description: A plugin to demonstrate creating custom routes and endpoints in the WP REST API.
 * Version: 1.0.0
 *
 * @package wp-learn-form-submissions
 */

register_activation_hook( __FILE__, 'wp_learn_setup_table' );
/**
 * Create custom form_submissions table
 */
function wp_learn_setup_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name varchar (100) NOT NULL,
	  email varchar (100) NOT NULL,
	  PRIMARY KEY  (id)
	)";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

add_action( 'rest_api_init', 'wp_learn_register_routes' );
/**
 * Register the REST API wp-learn-form-submissions-api/v1/form-submission routes
 */
function wp_learn_register_routes() {

	// GET route.
	register_rest_route(
		'wp-learn-form-submissions-api/v1',
		'/form-submissions/',
		array(
			'methods'             => 'GET',
			'callback'            => 'wp_learn_get_form_submissions',
			'permission_callback' => '__return_true',
		)
	);

	// POST route.
	register_rest_route(
		'wp-learn-form-submissions-api/v1',
		'/form-submission/',
		array(
			'methods'             => 'POST',
			'callback'            => 'wp_learn_create_form_submission',
			'permission_callback' => 'wp_learn_require_permissions',
		)
	);

	// GET by id route.
	register_rest_route(
		'wp-learn-form-submissions-api/v1',
		'/form-submission/(?P<id>\d+)',
		array(
			'methods'             => 'GET',
			'callback'            => 'wp_learn_rest_get_form_submission',
			'permission_callback' => '__return_true',
		)
	);
}

/**
 * GET callback for the wp-learn-form-submissions-api/v1/form-submission route
 *
 * @return array|object|stdClass[]|null
 */
function wp_learn_get_form_submissions() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$results = $wpdb->get_results(
		$wpdb->prepare( 'SELECT * FROM %i', $table_name )
	);

	return $results;
}

/**
 * GET callback for the wp-learn-form-submissions-api/v1/form-submission/<id> route
 *
 * @param integer $id The form submission id.
 * @return array
 */
function wp_learn_rest_get_form_submission( $id ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	error_log(
		$wpdb->prepare( 'SELECT * FROM %i WHERE id = %d', $table_name, $id )
    );

	$results = $wpdb->get_results(
		$wpdb->prepare( 'SELECT * FROM %i WHERE id = %d', $table_name, $id )
	);

	return $results[0];
}

/**
 * Permissions callback for POST route
 *
 * @return boolean
 */
function wp_learn_require_permissions() {
	return current_user_can( 'edit_posts' );
}

/**
 * POST callback for the wp-learn-form-submissions-api/v1/form-submission route
 *
 * @param array $request Request paramters.
 *
 * @return array
 */
function wp_learn_create_form_submission( $request ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$rows = $wpdb->insert(
		$table_name,
		array(
			'name'  => $request['name'],
			'email' => $request['email'],
		)
	);

	return $rows;
}
```