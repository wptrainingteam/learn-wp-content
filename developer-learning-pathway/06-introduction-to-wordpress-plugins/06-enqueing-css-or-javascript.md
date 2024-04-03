# Enqueuing CSS or JavaScript

## Introduction

Because plugins don't control the look and feel of a WordPress site, if you need to make use of custom CSS or JavaScript, you need to follow a process called enqueuing. 

In order to add custom CSS or JavaScript, a WordPress plugin needs a way to add script or style tags to the HTML being rendered at any time.

Fortunately, WordPress allows plugin developers to enqueue their plugin CSS or JavaScript, so that it is added in the right place in the HTML of any post or page request.

In this lesson you'll learn how to enqueue custom CSS and JavaScript, on either the front end, or the admin dashboard.

## Enqueuing CSS

As with most WordPress functionality, to start you need to register a callback function on a specific hook, and use the callback to enqueue your scripts or styles.

The correct hook to use is the [wp_enqueue_scripts](https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/) action hook. As you can see from the documentation, despite the hook's name, it is used for enqueuing both scripts and styles.

So start by registering the callback to the hook, and creating the callback function.

```php
add_action( 'wp_enqueue_scripts', 'bookstore_enqueue_scripts' );
function bookstore_enqueue_scripts() {
    
}
```

You can now enqueue you custom CSS or JavaScript. 

Let's target the Book title on any given book on the front end and make it red.

Go ahead and create an empty `bookstore.css` file in your bookstore plugin directory to be enqueued by your plugin.

Inside that file, add the following code:

```css
.single-book h1 {
    color: red;
}
```

Any time a single book is rendered, it will add the `single-book` class to the body element of the HTML page, and so this code will change the colour of any `h1` tag inside the body tag to red.

Now that you've created the CSS file, you need to enqueue it inside the `bookstore_enqueue_scripts()` function. 

You do this using the wp_enqueue_style [function](https://developer.wordpress.org/reference/functions/wp_enqueue_style/).

At minimum, you need to pass at least the first two arguments to the function
- the handle, a unique name for the stylesheet that's used when the stylesheet is added to the HTML
- the src, which is the full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.

For a plugin, you can use the `plugins_url` function to get the url of the plugins directory, and then concatenate that with the path to your css file.

```php
add_action( 'wp_enqueue_scripts', 'bookstore_enqueue_scripts' );
function bookstore_enqueue_scripts() {
	wp_enqueue_style(
		'bookstore-style',
		plugins_url() . '/bookstore/bookstore.css',
	);
}
```

During a WordPress request, this will add the stylesheet handle and URL to a `wp_styles` object. 

When the HTML to be rendered is generated, and WordPress is ready to output the `head` tag, it will loop through each stylesheet in the wp_styles object, and output an HTML style element, applying the handle as the element's id attribute, and the src as the element's href attribute. 

With this CSS file enqueued, go ahead and browse to the single book view of any book you've added, and you should notice that the h1 element is red.

## Enqueuing JavaScript

You can also enqueue JavaScript files from your plugin, using the same wp_enqueue_scripts action hook callback. 

The only difference is that instead of using wp_enqueue_style, you can use wp_enqueue_script

Similar to wp_enqueue_style, you pass a unique handle and src arguments to wp_enqueue_script for it to enqueue you JavaScript file.

First, create a bookstore.js file in the bookstore directory, and add a simple JavaScript alert to it.

```js
alert('Hello from the book store');
```

Now, update the bookstore_enqueue_scripts to enqueue the bookstore.js file using wp_enqueue_script.

```php
add_action( 'wp_enqueue_scripts', 'bookstore_enqueue_scripts' );
function bookstore_enqueue_scripts() {
	wp_enqueue_style(
		'bookstore-style',
		plugins_url() . '/bookstore/bookstore.css',
	);
	wp_enqueue_script(
		'bookstore-script',
		plugins_url() . '/bookstore/bookstore.js',
	);
}
```

As with stylesheets, wp_enqueue_script will add the script handle and URL to a wp_scripts object, and output an HTML script element for each one, using the handle in the id attribute and the URL in the src attribute. 

With this script enqueued, browse to the single book view of any book you've added, and you should see the alert pop up on the page.

## Enqueuing on the admin dashboard

You can also enqueue styles and scripts on the admin dashboard, using the admin_enqueue_scripts [action](https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/) instead of the wp_enqueue_scripts action.

```php
add_action( 'admin_enqueue_scripts', 'bookstore_admin_enqueue_scripts' );
function bookstore_admin_enqueue_scripts() {
    
}
```

Once you've registered the callback function on the hook, the process of enqueuing scripts and styles is the same as for the front end using wp_enqueue_style and wp_enqueue_script.

## Selective Enqueuing

In the examples in the lesson, the bookstore CSS and JavaScript is enqueued on every page of the site. This is not ideal. In the case of the CSS for example, it's specifically targeting h1 elements on the single book view, so you don't need to enqueue the CSS for anything other than books.

It is possible to perform selective enqueuing, where you determine the specific scenario when the file should be enqueued. 

For example, in the case of the bookstore.css, you could use the get_post() function to get the current post object, and then check if the post type is book. 

If it isn't' then exit the function and don't enqueue the stylesheet or script file.

```php
add_action( 'wp_enqueue_scripts', 'bookstore_enqueue_scripts' );
function bookstore_enqueue_scripts() {
	$post = get_post();
	if ( 'book' !== $post->post_type ) {
		return;
	}
	wp_enqueue_style(
		'bookstore-style',
		plugins_url() . '/bookstore/bookstore.css',
	);
	wp_enqueue_script(
		'bookstore-script',
		plugins_url() . '/bookstore/bookstore.js',
	);
}
```

There are a number of other ways to make sure that your plugin stylesheet or script files are only loaded when needed. Doing this means that your plugin won't add any unnecessary overhead or loading times to any WordPress site it's installed on.

## YouTube chapters

0:00 Introduction
0:42 Enqueuing CSS
4:14 Enqueuing JavaScript
5:51 Enqueuing on the admin dashboard
6:11 Selective Enqueuing
