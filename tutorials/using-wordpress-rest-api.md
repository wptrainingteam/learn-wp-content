# Using the WordPress REST API

## Outline

- Key Concepts
- Global Parameters
- Pagination
- Ordering results
- Backbone.js

- Where to go for more information
- 
## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn all about the WordPress REST API.

The WordPress REST API provides an interface for applications to interact with a WordPress site. These applications could be WordPress plugins, themes, or custom applications that need to access WordPress site data.

One of the most well known implementations of the WP REST API is the Block Editor, which is a JavaScript application that interacts with WordPress data through the REST API.

## What does REST API mean

An API is an application programming interface. It's a set of functions that allow applications to interact with each other. WordPress has many different APIs, the REST API is just one of them.

REST stands for REpresentational State Transfer which is a set of concepts for modeling and accessing your application’s data as interrelated objects and collections. 

At it's core, the WordPress REST API provides REST endpoints (URLs) which represent the posts, pages, taxonomies, and other built-in WordPress data types. Your application can send and receive data as JavaScript Object Notation (aka JSON) to these endpoints to query, modify and create content on your site.

Let's dive into some of these concepts to understand them better.

## Routes & Endpoints

In the context of the WordPress REST API, a route is a URI which can be mapped to different HTTP methods. 

An HTTP method is the type of request that's made whenever you interact with anything on the web. For example, when you browse to a URL on the web, a GET request is made to the server to request the data. When you submit a form, a POST request is made, which passes the submitted form data to the web server.

The mapping of an individual HTTP method to a route is known as an endpoint. Let's look at some examples of routes and endpoints.

If you open a browser, and go to the /wp-json/ URI of your local WordPress install, you will be making a GET request to that URI.  

The data returned is a JSON response showing what routes are available, and what endpoints are available within each route. 

In this example /wp-json/ is a route, and when that route receives a GET request it's handled by the endpoint which displays the data. This data is what is known as the index for the WordPress REST API. 

By contrast, the /wp-json/wp/v2/posts route offers a GET endpoint which returns a list of posts, but also a POST endpoint. If you are an authenticated user, and you submit the right data via a POST request to the /wp-json/wp/v2/posts route, that request is handled by the endpoint which creates new posts.

Typically, the same route (in this case /wp-json/wp/v2/posts) will have different endpoints for different HTTP methods, including GET (fetching data), POST (creating data) and DELETE (deleting data) HTTP methods.

## Global Parameters 

The WP REST API includes a number of global parameters which control how the API handles the request/response handling. These operate at a layer above the actual resources themselves, and are available on all resources.

Global parameters are implemetned on REST API routes as query string parameters. Query strings start with a ? and are followed by a series of key=value pairs, separated by &.

Take a look at the /wp-json/wp/v2/posts route you looked at earlier, by requesting the route in a browser, thereby activating the GET endpoint. As you can see, the default is to return all available fields for a post.

However, if you update the route by adding the _fields global parameter, and then specify the fields you want to return in the response as a comma delimited list. 

```
wp-json/wp/v2/posts?_fields=author,id,excerpt,title,link
```

If you make a second GET request, by refreshing the browser, only the fields you have requested to be returned in the response are available.

## Pagination and Ordering

The WP REST API also supports pagination and ordering of results.

Pagination is handled by the per_page, page and offset parameters. 

For example, you can update the wp-json/wp/v2/posts route to return only 5 posts per page, by adding the per_page parameter to the route.

```
wp-json/wp/v2/posts?_fields=author,id,excerpt,title,link&per_page=5
```

If you make a new GET request be refreshing the page, only the first 5 posts are returned.

It's also possible to order the results, using the order and order_by parameters.

For example, you can update the wp-json/wp/v2/posts route to order by post title, in descending order.

``` 
wp-json/wp/v2/posts?_fields=author,id,excerpt,title,link&per_page=5&orderby=title&order=asc
```

## Authentication

By default, the WP REST API uses the same cookie based Authentication method as logging into the WP dashboard. So for REST API endpoints that require a valid user, if the authentication cookie is present, the request is authenticated.

However, it is also possible to application passwords, JSON Web Tokens, and OAuth 1.0a to authenticate requests. We will cover these authentication methods in a future tutorial.

## Replacing admin-ajax with REST API endpoints

One of the biggest benefits of using the REST API in your plugins is that it can replace your admin-ajax requests, using less code, and making your requests more secure. To see this in action, let's take a look at a simplified example.

In this example, we have a plugin which adds a menu page to the WordPress dashboard. The page has a button and a textarea, and when the button is clicked, an ajax request is made to get the most recent posts, and display their titles in the textarea.

Here is the PHP code to manage all this.

```
<?php
/**
 * Plugin Name:     WP Learn REST API
 * Description:     Learning about the WP REST API
 */

/**
 * Create an admin page to show the form submissions
 */
add_action( 'admin_menu', 'wp_learn_rest_submenu', 11 );
function wp_learn_rest_submenu() {
	add_menu_page(
		esc_html__( 'WP Learn Admin Page', 'wp_learn' ),
		esc_html__( 'WP Learn Admin Page', 'wp_learn' ),
		'manage_options',
		'wp_learn_admin',
		'wp_learn_rest_render_admin_page',
		'dashicons-admin-tools'
	);
}

/**
 * Render the form submissions admin page
 */
function wp_learn_rest_render_admin_page(){
	?>
    <div class="wrap" id="wp_learn_admin">
        <h1>Admin</h1>
        <button id="wp-learn-ajax-button">Load Posts via admin-ajax</button>
        <button id="wp-learn-rest-api-button">Load Posts via REST</button>
        <button id="wp-learn-clear-posts">Clear Posts</button>
        <h2>Posts</h2>
        <textarea id="wp-learn-posts" cols="125" rows="15"></textarea>
    </div>
	<?php
}

/**
 * Enqueue the main plugin JavaScript file
 */
add_action( 'admin_enqueue_scripts', 'wp_learn_rest_enqueue_script' );
function wp_learn_rest_enqueue_script() {
	wp_register_script(
		'wp-learn-rest-api',
		plugin_dir_url( __FILE__ ) . 'wp-learn-rest-api.js',
		array( 'wp-api' ),
		time(),
		true
	);
	wp_enqueue_script( 'wp-learn-rest-api' );

    /**
     * Localize the script with the Ajax url to handle ajax requests
     */
	wp_localize_script(
		'wp-learn-rest-api',
		'wp_learn_ajax',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);
}

/**
 * Handles the learn_fetch_posts AJAX request.
 */
add_action( 'wp_ajax_learn_fetch_posts', 'wp_learn_ajax_fetch_posts' );
function wp_learn_ajax_fetch_posts() {
	$posts = get_posts();
    wp_send_json($posts);
	wp_die(); // All ajax handlers die when finished
}
```

