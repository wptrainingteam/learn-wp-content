# WordPress REST API - Custom Routes & Endpoints

https://workpress.test/wp-json/wp/v2/

## Objectives

Upon completion of this lesson the participant will be able to:

1. Create a custom table to store form submissions
2. Register a custom WP REST API route to fetch submissions
3. Register a custom WP REST API route to post submissions
4. Register a custom WP REST API endpoint to fetch a single submission

## Outline

- Introduction
- Creating a custom table to store form submissions
- Registering a custom WP REST API route to fetch submissions
- Registering a custom WP REST API route to post submissions
- Creating a custom WP REST API endpoint to fetch a single submission

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn how to extend the WP REST API by creating your own simple routes and endpoints.

You will learn how to use the register_rest_route function to register routes, and how to create specific endpoints by setting the route method. You will also learn how it's possible to specify route arguments, and how to use them fetch specific data.

# Creating a custom table to store form submissions

Let's consider a requirement to build a simple submissions form plugin, which allows a name and email field to be captured via the WP REST API. The plugin should allow for a form-submissions REST API route, which has three endpoints:

1. One to fetch all form submissions
2. One to post a form submission
3. One to fetch a specific form submission

To start, because you're only storing a few simple fields, you might create a custom table to store the form submissions. 

The custom table code might look something like this:

```php
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

Once the plugin is activated, this will create the form_submissions table in the database, with the two fields.

# Registering a custom WP REST API route to fetch submissions

To register a custom WP REST API route you can use the register_rest_route function. This function requires the following parameters:

1. The namespace - this is the portion of the route URL that comes before the route itself. For example, in the route /wp/v2/posts, the namespace is wp/v2.
2. The route - this is the portion of the route URL that comes after the namespace. For example, in the route /wp/v2/posts, the route is posts.
3. The route arguments - this is an array of arguments that specify the route method, and any other arguments that you want to pass to the callback function.

register_rest_route should only be called when the rest_api_init action is fired. This ensures that the route is only registered when the WP REST API is loaded.

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
			'callback' => 'wp_learn_rest_get_form_submissions',
			'permission_callback' => '__return_true'
		)
	);
```

1. The namespace is wp-learn-form-submissions-api/v1
2. The route is /form-submissions/
3. The route arguments specify the route method as GET, and the callback function as wp_learn_rest_get_form_submissions. It also specifies a permission callback function, which is used to check if the user has permission to access the route. In this case, we're using the built-in __return_true function, which returns true, so anyone can access the route.

Once the route is created, you'll need to create the callback function, wp_learn_rest_get_form_submissions. This function will be called when the route is accessed, and it will return the form submissions.

```php
/**
 * GET callback for the wp-learn-form-submissions-api/v1/form-submission route
 *
 * @return array|object|stdClass[]|null
 */
function wp_learn_rest_get_form_submissions() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$results = $wpdb->get_results( "SELECT * FROM $table_name" );

	return $results;
}
```

This function uses the global $wpdb variable to access the database, and then uses the get_results function to fetch all the rows from the form_submissions table. It then returns the results as an array. Because you've registered this callback against a WP REST API route using register_rest_route, the results will be returned as a JSON object. 

To test this, access the form_submissions table using your favourite database tool, and add a few rows of data. Then open a REST API testing tool like Postman, and create a new request to test the route.

```
GET https://workpress.test/wp-json/wp-learn-form-submissions-api/v1/form-submissions
```

You should see the results returned as a JSON object.

# Registering a custom WP REST API route to post submissions

Now that you can fetch form submissions, you can create a route to create them. To do this, you'll register a route to use the POST method, and create a callback function to handle the POST request.

```php
	/**
	 * POST
	 */
	register_rest_route(
		'wp-learn-form-submissions-api/v1',
		'/form-submission/',
		array(
			'methods'  => 'POST',
			'callback' => 'wp_learn_rest_create_form_submission',
			'permission_callback' => '__return_true'
		)
	);
```

As you can see, the namespace and route are the same, but the method is set to POST, and a different callback function is specified. This callback function will be called when the route is accessed using the POST method.

The other thing to notice is the use of the $request parameter. This is a WP_REST_Request object, which contains all the data that was sent to the route. In this case, the name and email fields will be available in the $request object.

```php
/**
 * POST callback for the wp-learn-form-submissions-api/v1/form-submission route
 *
 * @param $request
 *
 * @return void
 */
function wp_learn_rest_create_form_submission( $request ){
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

The callback function uses the global $wpdb variable in order to access the database, and then uses the insert function to insert a new row into the form_submissions table. It then returns the number of rows inserted.

Note that in this example there's no validating of the inputs. For more information on validating inputs, check out the Introduction to securely developing plugins tutorial at Learn.WordPress.org.

To test this, create a new POST request in your API testing tool, and submit the following data as a JSON object in the request body:

```json
{
    "name": "John Doe",
    "email": "jon@gmail.com"
}
```

You should see the number of rows inserted returned as a JSON object.

Notice how you didn't need to pass any authentication credentials to this route. This is because you set the permission_callback to __return_true. This means that anyone can access the route, and create a new form submission. If you want to restrict access to the route, you can specify a permissions_callback function, and use the current_user_can function to check if the user has the required permissions. 

## Creating a custom WP REST API endpoint to fetch a single submission

The last requirement for your custom route was a route that could fetch a single submission. One way you could do this is to set up the wp_learn_rest_get_form_submissions to also accept the $request parameter, and then check if an id value is passed in the request body.

A better solution is to use something called path variables. You've seen these before in the Using the WP REST API tutorial, were we discussed global variables. Path variables are similar, but they're used to pass data to a route.

To create a path variable, you add a placeholder to the route, and then specify the name of the placeholder in the route arguments. For example, if you wanted to fetch a single submission, you could create a route like this:

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

The key thing to notice here is the use of the (?P<id>\d+) placeholder. 

It uses the following format: `?P<{name}>{regex pattern}`

1. name is the name of the placeholder. This will be used to access the value in the $request object eg $request['{name}']
2. regex pattern is the regular expression pattern that the value should match. In this case, \d+ means that the value should be a number.

This tells the route that the id value will be passed to the route and that it should be a number. The id value will then be available in the $request object.

Now you just need to create the wp_learn_rest_get_form_submission function, which could look something like this:

```php
function wp_learn_rest_get_form_submission( $request ) {
	$id = $request['id'];
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$results = $wpdb->get_results( "SELECT * FROM $table_name WHERE id = $id" );

	return $results[0];
}
```

To test this, create a new GET request in your API testing tool, and add the id value to the end of the route.

```
https://workpress.test/wp-json/wp-learn-form-submissions-api/v1/form-submission/1
```

You should see the results returned as a JSON object.

Play around with this a little to test it, create a few more form submissions, use the GET route to fetch them, and then use the GET single route to fetch a single submission by id.

This has been a fairly brief introcution to what you can acheive using register_rest_route. For more information and examples, check out the [Extending the REST API](https://developer.wordpress.org/rest-api/extending-the-rest-api/) chapter of the WP REST API handbook on developer.wordpress.org, specufucally the [Adding Custom Endpoints](https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/) and [Routes and Endpoints](https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/) sections.

Happy coding