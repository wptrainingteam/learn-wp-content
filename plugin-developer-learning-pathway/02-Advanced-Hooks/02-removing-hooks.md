# Removing Hooks

## Introduction

While the WordPress hooks system makes WordPress very extendable, it can sometimes create incompatibilities with your plugin’s execution.

In this lesson you will learn about removing hook callback functions.

Example

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
	$post = get_post();
	if ( ! is_page( $post ) ) {
		return $content;
	}
	$year = date( 'Y' );

	return $content . "<p>&copy; $year</p>";
}
```

You receive a support request from a user who is using your plugin, complaining that there is some other text being added to the end of the page content that is not related to your plugin. 

You investigate and find that another plugin is adding some text to the end of the page content based on an Extra Option setting. The plugin user wants only the copyright text to be displayed on pages, but to retain the other plugin’s functionality on all other post types.

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

You realize that the other plugin is adding the extra text to the end of the page content using the same filter hook as your plugin.

In that situation there is little we can do except to somehow remove the incompatible callback function hooked into the filter.

## Removing a callback from a hook

To remove a callback, we have two functions based on the hook type.

Filter

In the case of a filter, we can use the function remove_filter.

To understand further how this works, let's take an example.

Imagine I got the function my_callback as a callback from the filter my_filter with priority 12.

function my_callback() {

}

add_filter('my_filter', 'my_callback', 12);
To remove that callback, I will have to call the function remove_filter with the following arguments:
remove_filter('my_filter', 'my_callback', 12);
Action
In the case of an action, we can use the function remove_action.
To understand further how this works, let's take an example.
Imagine I got the function my_callback as a callback from the action my_action with priority 12.
function my_callback() {

}

add_filter('my_action', 'my_callback', 12);
To remove that callback, I will have to call the function remove_action with the following arguments:
remove_action('my_action', 'my_callback', 12);
Removing all callbacks for a hook
First, I need to warn you doing this is not recommended because it can remove important hooks for certain plugins or themes, and it is more recommendable to remove only the callback which creates an incompatibility.
If you want to remove all callbacks from an action, you can use the method remove_all_actions:
remove_all_actions('my_action');
For a filter you can use remove_all_filters:
remove_all_filters('my_filter');
It is also important to note that these methods should not be called from inside the hook you want to remove otherwise this would result in an infinite loop.

