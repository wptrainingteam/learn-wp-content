<!-- Original script by Cyrille C: https://github.com/CrochetFeve0251 -->

# Determining the correct hook

## Introduction

At the time of creating this lesson, WordPress Core has more than 3000 available hooks.

As you might guess, there is a hook for everything, so one problem you may often encounter is which is the correct hook to use for a given set of functionality.

In this lesson, you will learn how to find the right hook for your needs as well as some of the more important hooks to be aware of. 

## Finding the correct hook type

The first choice to make is whether you're going to need an action or a filter hook. 

As a reminder, actions allow you to perform some action at a specific point during the execution of a request, while filters allow you to modify, or filter, some data at a specific point, which will be used later on.

You will need to determine whether you just need to trigger something, or if you need to modify some data.

## Finding a hook in the WordPress documentation 

There are a couple of places you can find a list of available hooks in the WordPress documentation.

There is a section dedicated to [Hooks](https://developer.wordpress.org/apis/hooks/) in the Common APIs handbook of the WordPress developer documentation. 

From there you can navigate to the Action Reference or Filter Reference, and then browse through the list of actions or filters run during a typical request.

While this list is not extensive, it is a good starting point to find the hook you need.

It is also possible to find a list of all WordPress hooks in the [WordPress code reference under Hooks](https://developer.wordpress.org/reference/hooks/).

Then from there, it is possible to either navigate manually or search for hooks using the search bar.

## Important hooks

Many of the available Core hooks are not regularly used (if it all) by plugin developers, while some are used more often than others.

Let's look at some of the most important hooks to remember.

### The plugins_loaded hook

[This hook](https://developer.wordpress.org/reference/hooks/plugins_loaded/) fires once all plugins are loaded.

This hook is the perfect one to use when you want to register any plugin initialization tasks, as it will ensure all other plugin code will be loaded.

This helps if you need to check for third party compatibilities or if your plugin relies on another plugin's logic to execute.

### The init hook

[This hook](https://developer.wordpress.org/reference/hooks/init/) is executed when the core of WordPress is loaded.

This is usually the hook used to configure the core functionality of your plugin such as registering custom post types, dashboard menus, or cron tasks.

A [similar hook](https://developer.wordpress.org/reference/hooks/admin_init/) called `admin_init` exists to register core functionality, but only for WordPress admin pages.

### The wp_enqueue_scripts hook

When you need to enqueue CSS style files or JavaScript files, this [is the hook to use](https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/).

Inside the hook callback function you can use the `wp_enqueue_style` and `wp_enqueue_script` functions to enqueue styles and scripts.

```php
function enqueue_assets() {
    wp_enqueue_style( 'my-theme', 'style.css', false );

    wp_enqueue_script( 'my-js', 'filename.js', false );
}

add_action('wp_enqueue_scripts', 'enqueue_assets');
```

## Hooks registered outside WordPress core?

If you need to extend the functionality of another plugin or theme, you have to rely on what hooks the plugin or theme developer has put in place. 

In many cases, there will be a limited set of hooks to make use of, and they may not even be documented correctly.

A good place to start is to search for any instances of  `do_action` and `apply_filters` inside their codebase to find any hooks available.

Additionally, some developers will have documentation on their site which includes all the relevant hooks you can use.
