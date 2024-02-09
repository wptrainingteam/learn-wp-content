# Working with Hooks

Besides simply registering a callback function to an action hook, there are a few other things you need to know about working with hooks in WordPress.

In this lesson you'll learn about hook priority, hook parameters, and hook order.

## Hook Priority

Let's start with hook priority.

If you take a look at the [documentation](https://developer.wordpress.org/reference/functions/add_action/) for `add_action` you will see two additional function parameters, after the hook name and callback function.

The third parameter is the hook priority, which is an integer which defaults to 10. This means if you register an action in your code without specifying a priority, it will be registered with a priority of 10.

The `add_filter` [function](https://developer.wordpress.org/reference/functions/add_filter/) also has a priority parameter, which works in the same way.

Hook priority allows you to determine the order in which your hook callback is run, relative to any other hook callbacks that may be registered on the given hook, either by WordPress core, or other themes or plugins.

Hooks run in numerical order of priority, starting at 1. It’s usually safe to leave the priority to the default of 10, unless you specifically want to change the order in which your callback function is run.

For example, in our `after_setup_theme` action example, you may want to make sure that the registered callback function is only run after any callbacks registered by WordPress core are run. Because WordPress core registers any hook callbacks with the default priority of 10, if you specify a priority of 11, you can make sure my callback function is run after any core callbacks have completed.

```php
add_action( 'after_setup_theme', 'wp_learn_setup_theme', 11 );
```

Alternatively, if you want to make sure the callback is run before WordPress core’s, you would set a lower priority, say 9.

```php
add_action( 'after_setup_theme', 'wp_learn_setup_theme', 9 );
```

Often you might see callbacks being registered with a high priority, like 99, or 9999.

```php
add_action( 'after_setup_theme', 'wp_learn_setup_theme', 9999 );
```

This is because the plugin or theme developer wants to be sure that this callback is run after all other callback functions. However, one can never know at what priority other third party plugins or themes might register their callbacks.

## Hook Parameters

The fourth parameter in the `add_action` and `add_filter` functions is the number of accepted arguments the callback function can accept.

https://developer.wordpress.org/reference/functions/add_filter/
https://developer.wordpress.org/reference/functions/add_action/

In order to better understand how this works, let’s take a look at a content related filter hook, `get_the_excerpt`.

The filter is defined in the `wp-includes/post-template.php` file on line 492

```php
return apply_filters( 'get_the_excerpt', $post->post_excerpt, $post );
```

This filter is defined in the `get_the_excerpt` function, which in turn is called from the `the_excerpt` function. 

This function is typically used when displaying search results, where it’s useful to show the post excerpt and not the post content.

The `apply_filters` function is registering the filter hook with two variables that are associated with the filter, the `$post_excerpt` string variable and the `$post` object.

When defining a filter hook using `apply_filters`, any number of possible arguments can be added. However, the number passed to the accepted arguments parameter determines how many of them are passed to the hook callback function.

If you look at the documentation for `apply_filters` you will see the default value for number of accepted arguments is 1, which means that if you don’t specify a value for this parameter, the first argument will be available to be passed to the callback function.

In the `get_the_excerpt` filter, there are two possible variables to be accepted. If you register the callback without setting the number of arguments, only the first will be available in the callback function, in this case the `post_excerpt`.

```php
add_filter( 'get_the_excerpt', 'wp_learn_amend_the_excerpt', 10 );
function wp_learn_amend_the_excerpt( $post_excerpt ) {
    // do something with the $post_excerpt
}
```

In order to accept more of the available arguments, you need to specify the number of arguments to accept.

```php
add_filter( 'get_the_excerpt', 'wp_learn_amend_the_excerpt', 10, 2 );
```

Then you can use those arguments in your callback function

```php
function wp_learn_amend_the_excerpt( $post_excerpt, $post ) {

}
```

Being able to determine which arguments you need for your callback function, and setting the number in the hook registration is a valuable skill. 

For example, let’s say you just wanted to add a simple piece of text after the excerpt. In that case, you would only need the `$post_excerpt`, so you can leave out the accepted arguments setting.

```php
add_filter( 'get_the_excerpt', 'wp_learn_amend_the_excerpt', 11 );
function wp_learn_amend_the_excerpt( $post_excerpt ) {
    $additional_content = '<p>Verified by Search Engine</p>';
    $post_excerpt       = $post_excerpt . $additional_content;

	return $post_excerpt;
}
```

Notice how the priority has also been updated. You might do this because you want to make sure this text is amended after any core filter callbacks assigned to this filter have been run.

Let’s take a look at that in our search results.

However, what if you wanted to include something from the post, say the post title? In this case you would update your callback to accept both arguments from the hook to get the post title from the $post object.

```php
add_filter( 'get_the_excerpt', 'wp_learn_amend_the_excerpt', 11, 2 );
function wp_learn_amend_the_excerpt( $post_excerpt, $post ) {
    $additional_content = '<p>'. $post->post_title . ' Verified by Search Engine</p>';
    $post_excerpt       = $post_excerpt . $additional_content;

	return $post_excerpt;
}
```

Let’s see what this looks like in our updated search results.

You will notice that this code is using the same variable names for the arguments being passed to the callback, in this case `$post_excerpt` and `$post`.

This is not a requirement, and you can call them anything you want when you register your callback function, but it does make it easier to remember what each variable is for if you name them the same.

## Hook Order

Depending on your specific requirements, you may first need to determine which action or filter is the correct one to use.

Fortunately, the WordPress Developer documentation a page on Hooks in the Common APIs section, which contains a list of all action and filter hooks, and in the order that they are run during different requests on a WordPress site.

You can use this list to check when the hook you want to use is run, and then use this information to determine if it’s the correct hook for your requirements.

## YouTube chapters
    
(00:00) Introduction
(00:10) Hook priority
(01:00) Hook parameters
(02:00) Hook order
