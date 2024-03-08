# Action Hooks

There are two types of WordPress hooks, action hooks, and filter hooks. These are more commonly known as actions and filters. 

In this video we’ll focus on actions, but check out the filters lesson for more information on filter hooks.

## What are action hooks

As their name states, actions allow you to perform some action at a specific point during the execution of a WordPress request. 

To better explain this, let’s look at an example.

When developing a WordPress theme, it's possible to enable support for different post formats.

You can read more about [post formats](https://developer.wordpress.org/advanced-administration/wordpress/post-formats/) in the advanced administration section of the WordPress developer documentation. 

Post formats are a way to allow users with access to create posts on a WordPress side to chose from a predefined list of formats. Depending on the chosen post format, a different template layout can be rendered, displaying the post in a different format.

To enable post formats, the documentation indicates you need to use the `add_theme_support` function, and recommends that this be registered via the `after_setup_theme` action hook.

This hook is defined in the `wp-settings.php` file, after the theme is loaded.

```php
do_action( 'after_setup_theme' );
```

Here the do_action function defines the action hook, with the hook name `after_setup_theme`.

We can also read more about this hook, [in the reference page for this hook](https://developer.wordpress.org/reference/hooks/after_setup_theme/) in the developer reference.

Here we see that this hook is fired during each page load, after the theme is initialized, and is used to perform basic setup, registration, and initialization actions for a theme.

## Using action hooks

In order to make use of an action, you register a function in your code to a pre-existing action hook, which is known as a callback function.

To register your callback function on an action you use the WordPress `add_action` [function](https://developer.wordpress.org/reference/functions/add_action/).

You will need to pass the hook name and the name of your callback function as arguments to the add_action function.

Let’s take a look at what this looks like in a theme’s `functions.php` file. 

In your code editor, navigate to your currently active theme’s `functions.php` file, and open it.

If your theme doesn’t have a `functions.php` file, you can create one in the root of your theme directory. Just make sure it's named functions.php, and has the opening PHP tag at the top of the file.

Then, add the following code to your `functions.php` file to hook a callback function into the `after_setup_theme` action hook.

```php
add_action( 'after_setup_theme', 'wp_learn_setup_theme');
```

Next you need to define the callback function.

To do, use the PHP function syntax, with the name of the function you want to define.

````php
function wp_learn_theme_setup() {
    
}
````

Then add the `add_theme_support` function call inside the callback function. For this example, you can copy the code from the Post formats documentation.

````php
function wp_learn_theme_setup() {
    add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );
}
````

With this code in your active them, if you create a new Post now in your WordPress dashboard, you’ll see the Post Formats select box appear in the post editor, and you can select the required Post Format. 

Here you can see the two post formats you enabled in your callback function.

As you learned from this example you can use actions to perform something, either enabling some already existing feature, or adding something to the request execution.

## YouTube chapters

00:00 Introduction
00:10 What are action hooks
00:30 Using action hooks
