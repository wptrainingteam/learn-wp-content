# Common APIs - HTTP Request API

## Learning Objectives

## Outline

1. Introduction
2. What is the HTTP Request API?
3. Test Bed plugin
4. Fetching data using wp_remote_get
5. Debugging your HTTP requests
6. Sending data using wp_remote_post
7. Performance
8. Advanced
9. Conclusion

## Introduction

Hey there, and welcome to Learn WordPress. 

In this tutorial, you're going to learn about the WordPress HTTP Request API.

You will learn about the HTTP Request API, how to use it to fetch and send data from a WordPress site to and from an external API, as well as some additional considerations around performance, more advanced options, and authentication.

## What is the HTTP Request API?

## Let's talk about words

API: Application Programming Interface, a set of functions and procedures that allow one application to interact with another.
HTTP: Hypertext Transfer Protocol, an application protocol for distributed, collaborative, hypermedia information systems.
HTTP API: A set of functions and procedures that allow one application to interact with another using the HTTP protocol.
REST: Representational State Transfer, a software architectural style that defines a set of constraints to be used for creating Web services.
HTTP REST API: A set of functions and procedures that allow one application to interact with another using the HTTP protocol and the REST architectural style (more commonly called a REST API).
HTTP Request: A request for the server to do something. Requests usually have a method (GET, POST, PUT, DELETE, etc), a URL, and headers.
HTTP Response: A response from the server. Responses usually have a status code (200, 404, 500, etc), headers, and a body.
HTTP Client: A program that makes HTTP requests. Browsers are HTTP clients, as are programs like cURL and Postman.
 
## Test Bed plugin

```php
<?php
/**
 * Plugin Name:     WP Learn HTTP API
 * Description:     Learning about the WP HTTP API
 * Version:         0.0.2
 */

/**
 * Create an admin page to show the form submissions
 */
add_action( 'admin_menu', 'wp_learn_http_submenu', 11 );
function wp_learn_http_submenu() {
	add_menu_page(
		esc_html__( 'WP Learn HTTP', 'wp_learn' ),
		esc_html__( 'WP Learn HTTP', 'wp_learn' ),
		'manage_options',
		'wp_learn_admin',
		'wp_learn_render_http_admin_page',
		'dashicons-admin-tools'
	);
}

/**
 * Render the form submissions admin page
 */
function wp_learn_render_http_admin_page(){
	?>
	<div class="wrap" id="wp_learn_admin">
		<h1>Products</h1>
	</div>
	<?php
	do_action('wp_learn_load_products_hook');
}

/**
 * Load the products from the API
 */
add_action( 'wp_learn_load_products_hook', 'wp_learn_products_data' );
function wp_learn_products_data(){

}
```

## Fetching data using wp_remote_get

```php
	$response = wp_remote_get( 'https://fakestoreapi.com/products' );

	$status_code = wp_remote_retrieve_response_code( $response );

	if ( is_wp_error( $response ) ) {
		die( esc_html( $response->get_error_message() ) );
	} elseif ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
		die( esc_html( "HTTP Error $status_code \n" ) );
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body );

    echo '<div class="wrap" id="wp_learn_admin">';
    foreach ($data as $product) {
	    echo '<h2>' . $product->title . '<h2>';
	    echo '<p>' . $product->price . '<p>';
	    echo '<p>' . $product->description . '<p>';
	    echo '<hr>';
    }
    echo '</div>';
```


## Debugging your HTTP requests

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', '/home/ubuntu/wp-local-env/sites/workpress/logs/debug' . date( 'Y-m-d' ) . '.log' );
```

```php
$data = array( 'response' => $response );
```

```php
error_log( print_r( $data, true ) );
```

## Sending data using wp_remote_post

```php
	$args = array(
		'body' => array(
			'title'       => 'test product',
			'price'       => 13.5,
			'description' => 'lorem ipsum set',
			'image'       => 'https://i.pravatar.cc',
			'category'    => 'electronic'
		),
	);

	$response = wp_remote_post( 'https://fakestoreapi.com/products' );

	$status_code = wp_remote_retrieve_response_code( $response );

	if ( is_wp_error( $response ) ) {
		die( esc_html( $response->get_error_message() ) );
	} elseif ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
		die( esc_html( "HTTP Error $status_code \n" ) );
	}

	$body = wp_remote_retrieve_body( $response );

	$data = json_decode( $body );

	error_log(print_r($data, true));

    echo '<div class="wrap" id="wp_learn_admin">';
	    echo '<p>Product ID ' . $data->id . ' added<p>';
	    echo '<hr>';
    echo '</div>';
```

## Performance

## Advanced

### Other methods

### Request Arguments

### Headers

## Authentication

## Conclusion

And that wraps up this tutorial on the WordPress Metadata API. For more details on the Metadata API, check out the [Metadata API](https://developer.wordpress.org/apis/metadata/) section of the WordPress developer documentation under Common APIs.

Happy coding