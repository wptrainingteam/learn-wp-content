# Using the WP REST API

# Objectives

After completing this lesson, participants will be able to:

- Understand the WP REST API
- Understand the concepts of routes, endpoints and global parameters
- Use the WP REST API to fetch data from a WordPress site

# Prerequisite Skills

- Basic knowledge of WordPress
- Basic knowledge of JavaScript
- Basic knowledge of PHP

# Readiness Questions
- What is an API?
- What is REST?
- What is an HTTP method?

# Materials Needed

- A local WordPress install
- A text editor
- A browser
- A browser extension to format JSON data (optional)

# Notes for the presenter
- This lesson is intended to be delivered in a classroom setting, with a projector and a computer for each participant.
- If this is not possible, the lesson can be delivered remotely, using a screen sharing tool like Zoom.
- The lesson is intended to be delivered in a 1 hour session.
- The lesson is intended to be delivered by a presenter with experience in WordPress, JavaScript and PHP.

# Lesson Outline
- Introduction
- What does REST API mean
- Routes & Endpoints
- Global Parameters
- Pagination and Ordering
- Authentication
- Replacing admin-ajax with REST API endpoints

# Exercises

 - Browse to the /wp-json/ URI of your local WordPress install, and view the JSON data returned.
 - Browse to the /wp-json/wp/v2/posts URI of your local WordPress install, and view the JSON data returned.
 - Add the _fields global parameter to the /wp-json/wp/v2/posts route, and specify the fields you want to return in the response.
 - Add the per_page global parameter to the /wp-json/wp/v2/posts route, and specify the number of posts you want to return in the response.
 - Add the order global parameter to the /wp-json/wp/v2/posts route, and specify the order you want the posts returned in.
 - Add the orderby global parameter to the /wp-json/wp/v2/posts route, and specify the field you want to order the posts by.   

# Assessment

There should be one assement item (or more) for each objective listed above. Each assessment item should support an objective; there should be none that don't.

1. What is an API?
    - A. An application programming interface
    - B. A set of functions that allow applications to interact with each other
    - C. A software architectural style that describes a uniform interface between physically separate components
2. What is REST?
    - A. A set of functions that allow applications to interact with each other
    - B. A software architectural style that describes a uniform interface between physically separate components
    - C. A set of functions that allow applications to interact with each other
3. What is an HTTP method?
    - A. The type of request that's made whenever you interact with anything on the web
4. What is a route?
    - A. A URI which can be mapped to different HTTP methods
    - B. The type of request that's made whenever you interact with anything on the web
    - C. A set of functions that allow applications to interact with each other
5. What is an endpoint?
    - A. The type of request that's made whenever you interact with anything on the web
    - B. A URI which can be mapped to different HTTP methods
    - C. The mapping of an individual HTTP method to a route

# Additional Resources

- [WP REST API Handbook](https://developer.wordpress.org/rest-api/)
- [WP REST API Reference](https://developer.wordpress.org/rest-api/reference/)

# Example

## Introduction

Hey there, and welcome to Learn WordPress. 

In this tutorial, you're going to learn about the WordPress REST API, and how to use it in your projects. 

You will learn about key WP REST API concepts like routes, endpoints and global parameters, as well as how to use it in place of something like admin-ajax.

## Description

The WordPress REST API provides an interface for applications to interact with a WordPress site. These applications could be WordPress plugins, themes, or custom applications that need to access WordPress site data.

One of the most well known implementations of the WP REST API is the Block Editor, which is a JavaScript application that interacts with WordPress data through the REST API.

## What does REST API mean

An API is an application programming interface. It's a set of functions that allow applications to interact with each other. WordPress has many different APIs, the REST API is just one of them.

REST stands for [REpresentational State Transfer](https://en.wikipedia.org/wiki/Representational_state_transfer), which is a software architectural style that describes a uniform interface between physically separate components. 

At it's core, the WordPress REST API provides REST endpoints (URIs) which represent the posts, pages, taxonomies, and other built-in WordPress data types. Your code can send and receive data as JavaScript Object Notation (aka JSON) to these endpoints to query, modify, and create content on your site.

Let's dive into some concepts of REST to understand them better.

## Routes & Endpoints

In the context of the WordPress REST API, a route is a URI which can be mapped to different HTTP methods. 

An HTTP method is the type of request that's made whenever you interact with anything on the web. For example, when you browse to a URL on the web, a GET request is made to the server to request the data. 

When you submit a form, a POST request is made, which passes the submitted form data to the web server.

The mapping of an individual HTTP method to a route is known as an endpoint. Let's look at some examples of routes and endpoints.

If you open a browser, and go to the /wp-json/ URI of your local WordPress install, you will be making a GET request to that URI.  

The data returned is a JSON response showing what routes are available, and what endpoints are available within each route. 

By default, your browser will display the JSON data in its raw data format. To convert it to a more readable format, you can use a browser extension like [JSON Formatter](https://chrome.google.com/webstore/detail/json-formatter/bcjindcccaagfpapjjmafapmmgkkhgoa) for Chrome, [Basic JSON Formatter](https://addons.mozilla.org/en-US/firefox/addon/basic-json-formatter/) for Firefox, or [JSON Peep](https://apps.apple.com/us/app/json-peep-for-safari/id1458969831?mt=12) for Safari.

In this example /wp-json/ is a route, and when that route receives a GET request it's handled by the endpoint which displays the data. This data is what is known as the index for the WordPress REST API. 

By contrast, the /wp-json/wp/v2/posts route offers a GET endpoint which returns a list of posts, but also a POST endpoint. If you are an authenticated user, and you submit the right data via a POST request to the /wp-json/wp/v2/posts route, that request is handled by the endpoint which creates new posts.

Typically, the same route (in this case /wp-json/wp/v2/posts) will have different endpoints for different HTTP methods, including GET for fetching data, POST for creating data and DELETE for deleting data.

## Global Parameters 

The WP REST API includes a number of global parameters which control how the API handles the request/response handling. These operate at a layer above the actual resources themselves, and are available on all resources.

Global parameters are implemented on REST API routes as query string parameters. Query strings start with a ? and are followed by a series of key=value pairs, separated by &.

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

In this example code, the plugin which adds a menu page to the WordPress dashboard. The page has a button and a textarea, and when the button is clicked, an ajax request is made to get the most recent posts, and display their titles in the textarea.

Besides the functionality to render the admin page, this is what the code looks like to implement Ajax. First, in the wp_learn_rest_enqueue_script function, you need to use wp_localize_script to pass the ajax_url to the wp_learn_ajax object, so that it's available when the script is loaded.

Note that in this example, there's no implementation of a nonce for security, but it is recommended for a production plugin.

```
/**
 * Enqueue the main plugin JavaScript file
 */
add_action( 'admin_enqueue_scripts', 'wp_learn_rest_enqueue_script' );
function wp_learn_rest_enqueue_script() {
	wp_register_script(
		'wp-learn-rest-api',
		plugin_dir_url( __FILE__ ) . 'wp-learn-rest-api.js',
		array( 'jquery' ),
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
```

Then, you'd need to the callback function to process the ajax request, by hooking into a wp_ajax_ action hook

```
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

On the JavaScript side, some JavaScript would needed to handle the click event on the button, and make the Ajax request. Typically this is usually handled using some jQuery:

```
/**
 * JQuery to handle the Ajax request
 */
jQuery( document ).ready(
    function ( $ ) {
        const loadPostsButton = $( '#wp-learn-ajax-button' );
        if ( loadPostsButton ) {
            loadPostsButton.on(
                'click',
                function ( event ) {
                    $.post(
                        wp_learn_ajax.ajax_url,
                        {
                            'action': 'learn_fetch_posts',
                        },
                        function ( posts ) {
                            const textarea = $( '#wp-learn-posts' );
                            posts.forEach( function ( post ) {
                                textarea.append( post.post_title + '\n' )
                            } );
                        },
                    )
                },
            );
        }
    },
);
```

So what would this look like if you were using the WP REST API.

First of all, you don't need to pass the ajax_url anywhere, so we can remove that code from wp_learn_rest_enqueue_script. We also won't be processing the ajax request, so we can remove the wp_learn_ajax_fetch_posts function and associated hook. Finally, we can remove the button, and add one to load the posts via the REST API

WordPress ships with a [Backbone JavaScript Client](https://developer.wordpress.org/rest-api/using-the-rest-api/backbone-javascript-client/) for making direct requests to the WP REST API. This provides an interface for the WP REST API by providing Backbone Models and Collections for all endpoints exposed through the API. 

To ensure that your code is able to use the Backbone.js client, you merely need to update your plugin's dependency from `jquery` to `wp-api`.

This will ensure that your plugin's JavaScript code is only loaded after the REST API JavaScript client is loaded, so you can use it in your plugin.

In the JavaScript file, first you can delete the jQuery/Ajax related code. 

Than you'll want to start by registering a click event handler for the new button.

```
const loadPostsByRestButton = document.getElementById( 'wp-learn-rest-api-button' );
if ( loadPostsByRestButton ) {
    loadPostsButton.addEventListener( 'click', function () {
      // do something
    } );
}
```

Then, in the event handler function, you can use the WP API client, by accessing it from the global `wp` object, to create a new collection of posts

```
const allPosts = new wp.api.collections.Posts();
```

At this point, allPosts is simply an empty collection, so you will need to fetch the posts, by calling the `fetch` method on the collection.

```
   allPosts.fetch();
``` 

The fetch method returns a promise, so you can chain the `done` method to handle the response, and implement a callback function which will accept the response from the API request. You can specify a `posts` argument in this callback function, to accept the response from the API request

```
    const posts = allPosts.fetch().done(
        function ( posts ) {
            // do something with posts
        }
    );
```

Now you can simply loop through the `posts` object using `forEach` [method](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach), and access each post individually.

```
    const posts = allPosts.fetch().done(
        function ( posts ) {
            posts.forEach( function ( post ) {
              // do something with post
            } );
        }
    );
```

Finally, you can add the post title to the textarea. First you need to create an instance of the textarea before the forEach loop, and then append the post title to the textarea's value property inside the forEach loop.

```
    const posts = allPosts.fetch().done(
        function ( posts ) {
            const textarea = document.getElementById( 'wp-learn-posts' );
            posts.forEach( function ( post ) {
              textarea.value += post.title.rendered + '\n'
            } );
        }
    );
```

Your final code will look something like this.

```
const loadPostsByRestButton = document.getElementById( 'wp-learn-rest-api-button' );
if ( loadPostsByRestButton ) {
    loadPostsButton.addEventListener( 'click', function () {
        const allPosts = new wp.api.collections.Posts();
        allPosts.fetch().done(
          function ( posts ) {
            const textarea = document.getElementById( 'wp-learn-posts' );
            posts.forEach( function ( post ) {
              textarea.value += post.title.rendered + '\n'
            } );
        }
    );
    } );
}
```

The great advantage of using the WP REST API and the Backbone.js JavaScript client, is that you can use the same client over and over again. For example, if you wanted to fetch users, you could simply create a new collection of users, and fetch them. To do this via admin-ajax, you'd either need a new admin_ajax handler function, or update your existing function to accept a parameter to determine what to fetch.

The other advantage is that the API JavaScript client handles nonce checking for you. With admin-ajax, you have to specify this yourself and check it in your handler function. So using the REST API and the JavaScript client, your requests are more secure.

This tutorial just scratches the surface of what is possible with the WP REST API. To learn more about how it works, how to use it and how to extend it, check out the [WP REST API Handbook](https://developer.wordpress.org/rest-api/).

Happy coding