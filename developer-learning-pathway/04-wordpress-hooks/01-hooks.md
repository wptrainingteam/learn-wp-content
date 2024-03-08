# WordPress Hooks

One of the most regularly used developer features in WordPress is its Hooks system.

Hooks are what make WordPress so extendable, and allow you to build anything on the foundation of WordPress, from a blog to an online ecommerce platform.

Let's dive into what hooks are, how they work, and how you can use them in your WordPress themes and plugins.

## What are hooks?

Hooks allow your theme or plugin code to interact with or modify the execution of a WordPress request at specific, predefined spots.

There are two types of hooks, action hooks, and filter hooks. These are more commonly known as actions and filters.

To understand how hooks work, let's look at how a hook is defined in WordPress core.

## How hooks work

In the WordPress front end request lesson, you learned about the `wp-settings.php` file, and how this file sets up the WordPress environment.

If you scroll down to about line 643 of this file, you will see the following line of code:

```php
do_action( 'init' );
```

Here the `do_action` function defines an action hook, with the hook name `init`.

You can read more about this hook, in the [init hook documentation](https://developer.wordpress.org/reference/hooks/init/)

As a developer, you can hook into this action, and run your own code when this `init` action is fired.

It's essentially like being able to add your own code inside the `wp-settings.php` file at that point, but without actually modifying the core file.

## How you can use hooks

You can use hooks in your WordPress themes and plugins to add your own functionality to WordPress, or to modify the default functionality.

To see this in action, let's create a simple example of how you can use a filter hook to modify the content of a post.

To do this, you're going to create a small plugin. Don't worry if you've never worked with plugins before, but do check out the Introduction to plugins module to learn more about them.

For now, in your code editor, browse to your `wp-content/plugins` directory, and create a new file called `wp-learn-hooks.php`.

Then, add the following code to this file:

```php
<?php
/**
 * Plugin Name: WP Learn Hooks
 * Description: A simple plugin to demonstrate how to use hooks in WordPress.
 * Version: 1.0
 */

add_filter( 'the_content', 'wp_learn_amend_content' );

function wp_learn_amend_content( $content ) {
    return $content . '<p>Thanks for reading!</p>';
}
```

Now, browse to the WordPress admin of your local WordPress installation, go to Plugins, and activate your new plugin.

Then, browse to the front end of your site, and view any post or page. In this example, I'm going to view the sample page.

You will see that the content of the page now has the text "Thanks for reading!" added to the end of it.

If you deactivate the plugin, the text will disappear.

## Conclusion

This is a simple example of how you can use a filter hook to modify the content of a post. 

Don't worry if none of this makes sense right now, as we will cover this in more detail in the lessons on Action Hooks and Filter Hooks.

## YouTube Chapters

0:00 Introduction
0:24 What are hooks?
0:47 How hooks work
2:00 How you can use hooks
3:00 Conclusion