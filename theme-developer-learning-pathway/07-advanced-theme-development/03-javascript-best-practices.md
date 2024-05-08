# JavaScript Best Practices

## Introduction

JavaScript is a programming language that is used to add interactivity to web pages. Theme developers will often use JavaScript to provide interactivity, animation or other enhancements to their themes.

While using JavaScript can help to make your theme more engaging and interactive, it can also introduce potential issues if not used correctly.

In this lesson, you'll learn about some best practices for using JavaScript in your WordPress theme

You will learn about using third-party JavaScript libraries, some best practices to follow when writing JavaScript, whether you should use jQuery, and where to find more information.

## JavaScript libraries

If you need to use any third-party JavaScript libraries in your theme, make sure to check whether it's already available via the WordPress install.

WordPress includes several JavaScript libraries that you can use. These libraries are included with WordPress, and are available for you to use in your theme.

A common mistake made by beginning theme developers is to bundle their theme with their own version of the library. 

However, this may conflict with plugins that enqueue the version bundled with WordPress.

You can find a full list of the default Scripts and JavaScript Libraries included and registered by WordPress in the [wp_enqueue_scripts](https://developer.wordpress.org/reference/functions/wp_enqueue_script/#default-scripts-and-js-libraries-included-and-registered-by-wordpress) function reference.

Make your theme compatible with the version of your favorite library included with WordPress.

## Writing JavaScript

JavaScript is growing in popularity on the web developers, and over the years the language has improved to be able to a variety of tasks. This means that for more common tasks, you may not need to use a JavaScript library at all, and can just write plain JavaScript.

Here are some things to consider when writing your JavaScript:

1. Try to avoid using global variables.

Global variables are available throughout the entirety of your code, regardless of scope. This means you can access and modify these variables from anywhere in your code, whether inside or outside of functions.

```php
let greeting = "Hello, World!";
function greet() {
  console.log(greeting);
}
greet(); // Outputs: "Hello, World!"
```

To avoid using global variables, you can use a number of alternatives, but the most straightforward is to use an Immediately Invoked Function Expression (IIFE). This allows you to define variables in a local scope, preventing them from polluting the global namespace.

```js
(function() {
	var greeting = "Hello, World!";
	console.log(greeting);
})(); // Outputs: "Hello, World!"
```

2. Keep your JavaScript unobtrusive

Make sure your JavaScript doesn't interfere with the content of the page or produce unnecessary errors. This means that your JavaScript should be separate from your HTML, and should not rely on specific elements or classes in your HTML.

For example, if you need to add a click event to a button, you should use the `addEventListener()` method to add the event listener, rather than using the `onclick` attribute in the HTML. Additionally, you should check that the element exists on the page before adding the event listener.

```js
// HTML
(function() {
	let button = document.getElementById('myButton');
	if (button) {
        button.addEventListener('click', function() {
            alert('Button clicked!');
        });
	}
})(); 
```

3. Use a coding standard. 

Using a coding standard can help to ensure that your code is consistent and easy to read. WordPress has a https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/ that you can use to ensure that your code is consistent with the rest of the WordPress codebase.

4. Validate and Lint Your Code

Use a linter to check your code for errors and potential issues. This can help to catch bugs early, and ensure that your code is consistent and easy to read.

ESLint is a popular linter for JavaScript, and can be used to check your code for errors and potential issues. It possible to configure your theme to use EsLint to check your JavaScript code via the @wordpress/scripts package.

5. Ensure your theme works without JavaScript

Ensure your site still works without JavaScript first – then add JavaScript to provide additional capabilities. This is a form of [Progressive Enhancement](https://developer.mozilla.org/en-US/docs/Glossary/Progressive_Enhancement), which is a strategy that puts emphasis on web content first, allowing everyone to access the basic content and functionality of a web page.

6. Asset loading

Use [Lazy loading](https://developer.mozilla.org/en-US/docs/Web/Performance/Lazy_loading) for assets that aren’t immediately required. To do this, identify resources that are not critical for the content and load these only when needed.

## jQuery

jQuery is a JavaScript library that saw an increased use in the early days of web development. However, with the improvements in JavaScript, it is often no longer necessary to use jQuery for many common tasks.

Don’t use jQuery if you don’t need to — [You might not need jQuery](https://youmightnotneedjquery.com/) is a great site that shows you how to do some common tasks with plain JavaScript.

For example, if you need to select an element by its ID, you can use the `document.getElementById()` method in plain JavaScript.

```js
// jQuery
$( "#myElement" );

// Plain JavaScript
document.getElementById( 'myElement' );
```

Another good example is if you need to make an AJAX request, you can use the `fetch()` method in plain JavaScript, or better yet, the WordPress [apiFetch](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/) package.

```js
// jQuery
$.ajax({
  url: 'https://example.com//wp/v2/posts',
  success: function( data ) {
	  console.log( data );
  }
});

// Plain JavaScript
fetch('https://example.com//wp/v2/posts')
  .then(data => console.log(data));


// apiFetch
apiFetch( { path: '/wp/v2/posts' } ).then( posts => {
    console.log( posts );
} );
```

If you must use jQuery in your theme, you should use the version of jQuery that is included with WordPress.

When enqueuing your theme's scripts, you should specify jQuery as a dependency, by including it in the dependencies array.

```php
add_action( 'wp_enqueue_scripts', 'my_theme_scripts' );
function my_theme_scripts() {
    wp_enqueue_script( 'my-script', get_template_directory_uri() . '/js/my-script.js', array( 'jquery' ), '1.0', true );
}

```

This will ensure that jQuery is loaded before your script is loaded, and uses the version included with WordPress.

Because the copy of jQuery included in WordPress loads in [noConflict()](https://api.jquery.com/jQuery.noConflict/) mode, you should also wrap your code in an Immediately Invoked Function Expression, or IIFE.

```javascript
( function( $ ) {
    // Your jQuery code goes here
} )( jQuery );
```

This prevents the use of the `$` variable by other JavaScript libraries from conflicting with your jQuery code.

## Further reading

For these, and other JavaScript best practices when developing themes, make sure to read the [JavaScript Best Practices Handbook](https://developer.wordpress.org/themes/advanced-topics/javascript-best-practices) page under the Advanced Topics section of the WordPress Developer Handbook.