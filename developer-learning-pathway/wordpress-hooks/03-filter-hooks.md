# Filter Hooks

There are two types of WordPress hooks, actions, and filters.

In this video we’ll focus on filters, but check out the actions lesson for more information on action hooks.

## What are filter hooks

Filters allow you to modify, or filter, some data at a specific point, which will be used later on.

In order to make use of a filter, you register a function in your code to a pre-existing filter hook, which is known as a callback function.

To better explain this, let’s look at a filter called `the_content`.

This filter is defined in the `wp-includes/post-template.php` file, inside the function which is used in theme templates whenever the template needs to render any post or page content.

Inside that function, we see this code

```php
$content = apply_filters( 'the_content', $content );
```

Here the `apply_filters` function defines the filter hook, with the hook name of the_content.

You will notice that a `$content` variable is passed as an argument of `apply_filters` and that the value of `apply_filters` is assigned back to a variable, in this case, again, the `$content` variable.

If we look a little higher in this function, the `$content` variable is assigned the value of the `get_the_content` function, which is a WordPress core function that retrieves the value of the `post_content` field for the current post or page in the posts table.

So the `apply_filters` function registers the filter hook, passes the value of `$content` at this point in the code execution to any callback functions registered on this hook, and requires the updated value to be passed back.

## Using filter hooks

To register your callback function on a filter you use the WordPress add_filter [function](https://developer.wordpress.org/reference/functions/add_filter/).

You will need to pass the hook name and the name of your callback function as arguments to the `add_filter` function.

Let’s take a look at what this looks like in a theme’s functions.php file.

In your code editor, navigate to your currently active theme’s `functions.php` file, and open it.

If your theme doesn’t have a `functions.php` file, you can create one in the root of your theme directory. Just make sure it's named functions.php, and has the opening PHP tag at the top of the file.

Then, add the following code to your `functions.php` file to hook a callback function into the `the_content` filter hook.

```php
add_filter( 'the_content', 'wp_learn_amend_content' );
```

Then, create the callback function, using the PHP function syntax, which accepts the relevant argument from the filter.

```php
function wp_learn_amend_content( $content ) {
    // do some things that update $content
    return $content;
}
```

You don’t have to name the argument the same as the variable name passed from the filter, but it does make it easier if you do.

Notice that you have to return the updated data. This is so that the original variable being updated from the `apply_filters` call gets the update data.

For example, let’s say you wanted to add something to the end of the content of each post, you could append it to the `$content` variable like this.

```php
add_filter( 'the_content', 'wp_learn_amend_content' );
function wp_learn_amend_content( $content ) {
    $additional_content = '<!-- wp:paragraph --><p>Filtered through <i>the_content</i></p><!-- /wp:paragraph -->';
    $content            = $content . $additional_content;

	return $content;
}
```

In this example you're adding some text in a paragraph block, which renders at the bottom of each post on the front end. 

It’s very important to always return something back from a filter callback, ideally the modified content of the variable passed to the filter. Not returning something will cause a fatal error on your WordPress site.

Let’s take a look at what that looks like in our WordPress site.

If you view any post or page on the front end, you will see the text "Filtered through the_content" at the bottom of the content.

So, as you can see from this example, filter hooks allow you to modify certain pieces of data at a specific point in the code execution.

## YouTube chapters

00:00 Introduction
00:10 What are filter hooks
00:30 Using filter hooks
