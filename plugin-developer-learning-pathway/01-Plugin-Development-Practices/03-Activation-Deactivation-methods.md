# Activation and Deactivation methods

## Introduction

Depending on your plugin's functionality, you may need to perform certain tasks when the plugin is either activated or deactivated. 

In this lesson you're going to learn about two methods that WordPress provides to implement these tasks.

## Example code

In order to see these in action, let's take the Extra Option plugin from the Naming Collisions lesson. To keep things simple we'll use the prefxied option.

If you don't have this code you can create it by creating a plugin in your local WordPress installation using the following code.

```php
<?php
/**
 * Plugin Name: WP Learn Extra Content
 * Version: 1.0.0
 */

add_action( 'admin_init', 'wp_learn_extra_content_add_option' );

function wp_learn_extra_content_add_option() {
	add_settings_field( 'extra_option', 'Extra Option', 'wp_learn_extra_content_extra_option_field', 'general' );
	register_setting( 'general', 'wp_learn_extra_content_extra_option' );
}

function wp_learn_extra_content_extra_option_field() {
	echo '<input name="wp_learn_extra_content_extra_option" id="wp_learn_extra_content_extra_option" type="text" value="' . get_option( 'wp_learn_extra_content_extra_option' ) . '" />';
}

add_filter( 'the_content', 'wp_learn_extra_content_add_extra_option' );
function wp_learn_extra_content_add_extra_option( $content ) {
	$extra_option = get_option( 'wp_learn_extra_content_extra_option' );
	if ( ! $extra_option ) {
		new WP_Error( 'wp_learn_extra_content_extra_option', 'Extra content is empty.' );

		return $content;
	}
	$content .= '<p>' . $extra_option . '</p>';

	return $content;
}
```

Alternatively, you can download the plugin from the [GitHub repository](https://github.com/wptrainingteam/plugin-developer/blob/trunk/wp-learn-extra-content.zip), and install and activate it via your WordPress dashboard.



## Activation Method

In order to perform tasks when the plugin is activated, you can use the `register_activation_hook` function. 

This function takes two parameters: a path to the main plugin file and the function to be executed when the plugin is activated.

If you are calling this function from inside the plugin file, you can use the `__FILE__` constant to get the path to the file.

```php
register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_plugin' );
```

WordPress provides two methods, `register_activation_hook` and `register_deactivation_hook`, to handle these tasks.