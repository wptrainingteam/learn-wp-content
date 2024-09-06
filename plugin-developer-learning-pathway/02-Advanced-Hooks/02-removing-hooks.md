<!-- Original script by Cyrille C: https://github.com/CrochetFeve0251 -->

# Removing Hooks

## Introduction

While the WordPress hooks system makes WordPress very extendable, it can sometimes create incompatibilities with your plugin’s execution.

In this lesson you will learn about removing hook callback functions.

## Example

Let’s say you are developing a plugin that will add some a copyright text string with a date to the end of every page on a WordPress site. Your plugin code might look something like this:

```php
<?php
/**
 * Plugin Name: Add Copyright
 * Description: Add Copyright with current year to all Pages
 * Version: 1.0
 * Author: Jon Doe
 */

namespace JonDoe\AddCopyright;

add_filter( 'the_content', __NAMESPACE__ . '\add_copyright' );
function add_copyright( $content ) {
	if ( ! is_page() ) {
		return $content;
	}
	$year = date( 'Y' );

	return $content . "<p>&copy; $year</p>";
}
```

You receive a support request from a user who is using your plugin, complaining that there is some other text being added to the end of the page content that is not related to your plugin.

You investigate and find that another plugin is adding some text to the end of the page content based on an Extra Option setting.

You realize that the other plugin is adding the extra text to the end of the page content using the same filter hook as your plugin.

```php
<?php
/*
Plugin Name: WP Learn Extra Content
Version: 1.0.0
*/

namespace WP_Learn\Extra_Content;

add_action('admin_init', __NAMESPACE__ . '\add_option');
function add_option() {
	add_settings_field('wp_learn_extra_option', 'Extra Option', __NAMESPACE__ . '\extra_option_field', 'general');
	register_setting('general', 'wp_learn_extra_option');
}
function extra_option_field() {
	echo '<input name="wp_learn_extra_option" id="wp_learn_extra_option" type="text" value="' . get_option('wp_learn_extra_option') . '" />';
}

add_filter( 'the_content', __NAMESPACE__ . '\add_extra_option' );
function add_extra_option( $content ) {
	$extra_option = get_option('wp_learn_extra_option');
	if ( ! $extra_option ) {
		new \WP_Error( 'wp_learn_extra_option', 'Extra content is empty.' );
		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';
	return $content;
}
```

The plugin user wants only the copyright text to be displayed on pages, but to retain the other plugin’s functionality on all other post types.

In that situation there is little you can do except to somehow remove the incompatible callback function hooked into the same filter.

## Removing a callback from a hook

Fortunately, WordPress allows you to remove a hooked callback, depending on the hook type.

### Filter

In the case of a filter, you can use the `remove_filter()` [function](https://developer.wordpress.org/reference/functions/remove_filter/).

In order to remove a callback hooked into a filter, we call the function, passing the filter name and the callback function name to remove.

```php
remove_filter( 'the_content', 'WP_Learn\Extra_Content\add_extra_option' );
```

If the callback function was added with a priority, you can also pass the priority as the third argument.

```php
remove_filter( 'the_content', 'WP_Learn\Extra_Content\add_extra_option', 10 );
```

In this specific case, the copyright text should only be displayed on pages, so you should only remove the other plugin’s callback function when the content being rendered is actually page.

```php
if ( ! is_page() ) {
    return;
}
remove_filter( 'the_content', 'WP_Learn\Extra_Content\add_extra_option' );
````

Ideally, you should also remove any callbacks at the right point in request execution. In this specific case, a good place to remove the callback would be at the `wp` action hook, which is executed after the `query_posts()` function, which sets up the query loop.

```php
add_action( 'wp', 'custom_remove_extra_option_hook' );
function custom_remove_extra_option_hook() {
	if ( ! is_page() ) {
		return;
	}
        remove_filter( 'the_content', 'WP_Learn\Extra_Content\add_extra_option' );
}
```

This code could be added to a custom plugin, or a child theme’s `functions.php` file.

### Action

In the case of an action, you can use the `remove_action()` [function](https://developer.wordpress.org/reference/functions/remove_action/).

As with removing a filter, you call the function, passing the action name and the callback function name to remove.

```php
remove_action( 'admin_init', 'hooked_callback_function' );
```

Again, if the callback function was added with a priority, you can also pass the priority as the third argument.

```php
remove_action( 'admin_init', 'hooked_callback_function', 10 );
```

## Removing all callbacks for a hook

It is also possible to remove all callbacks for a specific hook.

Doing this is not generally recommended as it can remove important hooks for certain plugins or themes, and it is better to remove only specific hook callbacks which create incompatibilities.

If you do want to remove all callbacks from an action, you can use the `remove_all_actions()` [function](https://developer.wordpress.org/reference/functions/remove_all_actions/).

To remove all callbacks from a filter you can use the `remove_all_filters()` [function](https://developer.wordpress.org/reference/functions/remove_all_filters/):

For both functions you pass the hook name as the first argument, and optionally the hook priority as the second.

It is also important to note that these functions should not be called from inside the hook you want to remove the callbacks from, otherwise this would result in an infinite loop.