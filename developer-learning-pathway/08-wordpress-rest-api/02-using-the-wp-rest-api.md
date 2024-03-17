# Using the WP REST API

## Introduction

In the introductory lesson, you learned about the WordPress REST API, and how it allows you to interact with your WordPress site using HTTP requests.

In this lesson, you'll learn how to use the WP REST API to interact with the data in your WordPress site. 

You'll discover two internal options for making REST API requests, and then use a GET request to retrieve some public custom post type data, to display on your website.

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

Let's say you want to add a page in your WordPress dashboard to fetches the list of books, and displays a list of book titles and permalinks a comma separated list.

To start, you might add an admin submenu page to the Books menu, using the `admin_menu` hook, and the `add_submenu_page` function. 

```php
add_action( 'admin_menu', 'bookstore_add_booklist_submenu', 11 );
function bookstore_add_booklist_submenu() {
	add_submenu_page(
		'edit.php?post_type=book',
		'Book List',
		'Book List',
		'edit_posts',
		'book-list',
		'bookstore_render_booklist'
	);
}

function bookstore_render_booklist() {
	?>
	<div class="wrap" id="bookstore-booklist-admin">
		<h1>Actions</h1>
		<button id="bookstore-load-books">Load Books</button>
		<button id="bookstore-clear-books">Clear Posts</button>
		<h2>Books</h2>
		<textarea id="bookstore-booklist" cols="125" rows="15"></textarea>
	</div>
	<?php
}
```

You can copy this code, and paste it into the main plugin file. If you browse to the dashboard, and click on the Books menu, you will see a new submenu page called Book List.

Clicking on that link will take you to a page with two buttons, and a textarea.

Now you could add functionality to the `bookstore_render_booklist` function which fetches the book list via PHP and make the button trigger a page refresh. 

However, for a smoother user experience, you'd like to use JavaScript and the REST API to fetch the book list asynchronously and populate the book list, without having to wait for a full page refresh.

## Enqueing the admin JavaScript

In the Introduction to WordPress plugins module, you learned how to enqueue a JavaScript file in your plugin. Because this functionality is being added to the admin dashboard, you need to set up a separate `wp_enqueue_script` function call hooked into the `admin_enqueue_scripts` hook, so that the JavaScript file is only loaded in the admin dashboard.

First, create a new JavaScript file in the plugin directory, called `admin_bookstore.js`.

Then, add the following code to the main plugin file, to enqueue the JavaScript file in the dashboard

```php
add_action( 'admin_enqueue_scripts', 'bookstore_admin_enqueue_scripts' );
function bookstore_admin_enqueue_scripts() {
	wp_enqueue_script(
		'bookstyle-script',
		plugins_url() . '/bookstore/admin_bookstore.js',
		array(),
		'1.0.0',
		true
	);
}
```

Notice that this code not only enqueues the JavaScript file, but also specifies and empty dependencies array, a version number, and that it should be enqueued in the footer of the HTML page, by setting the last argument to `true`.

You can read more about these arguments in the [wp_enqueue_script](https://developer.wordpress.org/reference/functions/wp_enqueue_script/) function reference.

You could test that it's enqueued correctly by adding a single alert to the `admin_bookstore.js` file, and refreshing the admin page.

```js
alert( 'Hello from the Book store admin' );
```

Once you're sure it's working, you can remove that line form the file.

## Option 1: Using the Backbone.js client

Since the REST API was added to WordPress it has included a [Backbone.js](https://backbonejs.org/) [REST API JavaScript Client](https://developer.wordpress.org/rest-api/using-the-rest-api/backbone-javascript-client/) for making direct requests to the WP REST API. 

It provides an interface for using the WP REST API by providing Models and Collections for all endpoints exposed through the API.

To ensure that your JavaScript code is able to use the REST API client, you need to add it as a dependancy to your enqueued JavaScript.

The third argument for `wp_enqueue_script` is an array of any dependencies, and you can add `wp-api` as a dependency to your `wp_enqueue_script` function call.

```php
	wp_enqueue_script(
		'bookstyle-script',
		plugins_url() . '/bookstore/admin_bookstore.js',
		array( 'wp-api' ),
		'1.0.0',
		true
	);
```

This will ensure that your plugin's JavaScript code is only loaded after the REST API JavaScript client is loaded, so you can use it in your plugin.

You'll want to start by registering a click event handler for the new button.

```
const loadBooksByRestButton = document.getElementById( 'bookstore-load-books' );
if ( loadBooksByRestButton ) {
    loadBooksByRestButton.addEventListener( 'click', function () {
        //do somthing
    } );
}
```

Then, in the event handler function, you can use the WP API client, by accessing it from the global `wp` object, to create a new collection of books

```
    const allBooks = new wp.api.collections.Books();
```

At this point, allBooks is simply an empty collection, so you will need to fetch the posts, by calling the `fetch` method on the collection.

```
   allBooks.fetch();
``` 

The fetch method returns a promise, so you can chain the `done` method to handle the response, and implement a callback function which will accept the response from the API request. You can specify a `books` argument in this callback function, to accept the response from the API request

```
    const books = allBooks.fetch().done(
        function ( books ) {
            // do something with books
        }
    );
```

Now you can simply loop through the `books` object using `forEach` [method](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach), and access each book individually.

```
    const books = allBooks.fetch().done(
        function ( books ) {
            books.forEach( function ( book ) {
              // do something with book
            } );
        }
    );
```

Finally, you can add the book title and permalink to the textarea. 

First you need to create an instance of the textarea before the forEach loop, and then append the values to the textarea's value property inside the forEach loop.

```
        const books = allBooks.fetch().done(
            function ( books ) {
                const textarea = document.getElementById( 'bookstore-booklist' );
                books.forEach( function ( book ) {
                    textarea.value += book.title.rendered + ',' + book.link + ',\n'
                });
            }
        );
```

Your final code will look something like this.

```
const loadBooksByRestButton = document.getElementById( 'bookstore-load-books' );
if ( loadBooksByRestButton ) {
    loadBooksByRestButton.addEventListener( 'click', function () {
        const allBooks = new wp.api.collections.Books();
        const books = allBooks.fetch().done(
            function ( books ) {
                const textarea = document.getElementById( 'bookstore-booklist' );
                books.forEach( function ( book ) {
                    textarea.value += book.title.rendered + ',' + book.link + ',\n'
                });
            }
        );
    });
}
```

## Option 2: Using @wordpress/fetch-api

Since the inclusion of the Block Editor in WordPress 5.0, the `@wordpress/fetch-api` package has also been made available to make REST API requests.

This package is a wrapper around the browser `fetch` [API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API), and provides a more modern and flexible way to make requests to the REST API.

To make use of the fetch API you can update your plugin's JavaScript dependancies from `wp-api` to `wp-api-fetch`.

```php
    wp_enqueue_script(
        'bookstyle-script',
        plugins_url() . '/bookstore/admin_bookstore.js',
        array( 'wp-api-fetch' ),
        '1.0.0',
        true
    );
```

Then, in your bookstore.js file, you can replace the Backbone code with an implementation using the fetch API.

```
        wp.apiFetch( { path: '/wp/v2/books' } ).then( ( books ) => {
            // do something with books
        } );
```

Nothing how you pass the path to the books endpoint as an object to the `wp.apiFetch` function, and then chain the `then` method to handle the response. 

This is similar to use of the `done` method in the Backbone example, in that it waits for the request to the REST API to complete, and then returns the result from.

You can then loop through the books object and append the title and link to the textarea, just as you did with the Backbone example.

```
        wp.apiFetch( { path: '/wp/v2/books' } ).then( ( books ) => {
            const textarea = document.getElementById( 'bookstore-booklist' );
            books.forEach( function ( book ) {
                textarea.value += book.title.rendered + ',' + book.link + ',\n'
            } );
        } );
```

Here's what your final code would look like

```js
const loadBooksByRestButton = document.getElementById( 'bookstore-load-books' );
if ( loadBooksByRestButton ) {
    loadBooksByRestButton.addEventListener( 'click', function () {
        wp.apiFetch( { path: '/wp/v2/books' } ).then( ( books ) => {
            const textarea = document.getElementById( 'bookstore-booklist' );
            books.forEach( function ( book ) {
                textarea.value += book.title.rendered + ',' + book.link + ',\n'
            });
        } );
    });
}
```

## Option 3: Using @wordpress/core-data

If you're developing blocks, there is also a Core Data module available, to access data from the REST API.

Core Data is meant to simplify access to and manipulation of core WordPress entities. It registers its own store and provides a number of selectors which resolve data from the WordPress REST API automatically, along with dispatching action creators to manipulate data.

Let's look at how you can use the Core Data module in a block to fetch the books from the REST API.

First, using the create-block tool you learned about in the Introduction to block development module, create a new block called `bookstore-block`.

```bash
cd wp-local-env/sites/learn/wp-content/plugins
npx @wordpress/create-block bookstore-block
```

Then, inside the block's edit.js file, import the `useSelect` hook from the `@wordpress/data` package, as well as the store from the `@wordpress/core-data` package.

```js
import { useSelect } from '@wordpress/data';
import { store as bookStore } from '@wordpress/core-data';
``` 

Then you can use these to fetch the books from the REST API.

```js
	const books = useSelect(
		select =>
			select( bookStore ).getEntityRecords( 'postType', 'book' ),
		[]
	);

    if ( ! books ) {
        <p { ...useBlockProps() }></p>
    }
```

`useSelect` is a hook that allows you to retrieving data from registered selectors. 

`useSelect` accepts a callback function as it's first argument, where you make use of the `bookStore` store's `getEntityRecords` selector to retrieve the books from the REST API.

Finally, you can update the component to loop through the books object and output the book title and link.

```js
	return (
    <div { ...useBlockProps() }>
        { books.map( ( book ) => (
            <p>
                <a href={ book.link }>{book.title.rendered}</a>
            </p>
        ) ) }
    </div>
);
```

Now, run the block build step, activate the plugin, and add the bookstore block to a post or page.