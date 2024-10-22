# Development Best Practices

## Introduction

As your plugin's functionality evolves, it is important to maintain a clean and organized codebase. 

This will help you and other developers to easily understand and maintain the code. 

In this lesson, we will discuss some best practices that you can follow to maintain a well-organized plugin.

## Conditional Loading of assets

As discussed in the [Enqueuing CSS and JavaScript lesson](https://learn.wordpress.org/lesson/enqueuing-css-or-javascript/) when developing a plugin that loads CSS or JavaScript assets, you may need to load certain scripts or stylesheets only on specific pages. 

There are a number of ways to achieve this, depending on the context.

For example, if you only need enqueue CSS or JavaScript in the admin area, you can use the `admin_enqueue_scripts` action hook.

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_admin_enqueue_scripts' );
function wp_learn_admin_enqueue_scripts() {
    // Load admin scripts and styles
}
```

To take it a step further, if you only need the asset on a specific admin page, you can use the `get_current_screen()` function to check the current screen ID. For example, to load an asset only on the General Settings page:

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_admin_enqueue_scripts' );
function wp_learn_admin_enqueue_scripts() {
    $screen = get_current_screen();
    if ( 'options-general' === $screen->id ) {
        // load assets only on the General Settings page
    }
}
```

You can perform similar checks on the front end.

For example, to only load an asset for any post type, you can use the `is_singular()` function:

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_enqueue_scripts' );
function wp_learn_enqueue_scripts() {
    if ( is_singular() ) {
        // Load assets only for single posts 
    }
}
```

Additionally, you can check for specific post-types:

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_enqueue_scripts' );
function wp_learn_enqueue_scripts() {
    if ( is_singular( 'book' ) ) {
        // Load assets only for single books 
    }
}
```

The Theme Developer Handbook has a great page on [Conditional Tags](https://developer.wordpress.org/themes/basics/conditional-tags/) in WordPress that you can refer to for more information.

## Determining Path and URL values

Developing a WordPress plugin often requires you to reference files located within a WordPress installation, usually located in the `wp-content` directory. This includes any Media Library files, plugin assets, or active theme related files.

While a default WordPress installation will have a predictable file structure, it is important to remember that users can change the location of the `wp-content` directory, as well as rename it.

Therefore, you can never assume that plugins will be in `wp-content/plugins`, uploads will be in `wp-content/uploads`, or that themes will be in `wp-content/themes`.

Fortunately, WordPress provides a number of functions to help you determine the correct path and URL values  for these locations.


## File Organization

Generally, the root level of your plugin directory should contain your main plugin file and, optionally, your uninstall.php file. 

It's a good idea to store any other plugin files into subdirectories whenever possible.

Here's an example of a well-organized plugin directory structure:

```
/plugin-name/
     /admin/
          /js/
          /css/
          /images/
     /includes/
     /languages/
     /public/
          /js/
          /css/
          /images/
     plugin-name.php
     uninstall.php
```

In this example structure, all plugin translation files are stored in the `languages` directory, any additional PHP files are stored in the `includes` directory, and all admin-related assets (JavaScript, CSS, or images) are stored in the `admin` directory. Any public facing assets (JavaScript, CSS, or images) are stored in the `public` directory.

Here's another example, taking from a real-world web agency's engineering practices:

```
/plugin-name/
    /assets/
        /css/
        /images/
        /js/
        /svg/
    /includes/
        /classes/
    /languages/
    plugin-name.php
    uninstall.php    
```

In this example, all assets are stored in the `assets` directory, and any PHP classes are stored separately in the `includes/classes` directory.

Neither of these structures is a requirement, but they are good examples of how you can organize your plugin files. You should choose a structure that makes sense for your plugin and stick with it.

