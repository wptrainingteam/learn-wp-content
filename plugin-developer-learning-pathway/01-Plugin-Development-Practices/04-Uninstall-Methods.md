# Uninstall methods

## Introduction

The ability to run clean up tasks when a plugin is removed from a WordPress install is a useful piece of functionality to include in your plugins. 

It allows you as the developer to make sure your plugin doesn't leave any unwanted data behind when it is uninstalled.

This lesson will look at the types of activities you can perform when a plugin is uninstalled, and the two methods you can use to trigger those activities.

## Data clean up scenarios

A plugin is considered uninstalled if a user has deactivated the plugin, and then clicks the delete link under the plugin in the WordPress Admin.

As you learned in the lesson on Activation and Deactivation methods, you can use the `register_deactivation_hook` function to perform tasks when the plugin is deactivated.

Developers will sometimes use the deactivation method to remove data from the database when the plugin is deactivated. 

However, this is not recommended as the user may want to reactivate the plugin at a later date and expect the data to still be there.

The WordPress developer documentation on [Uninstall methods](https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/) has a handy comparison table, which shows under what conditions tasks should be performed during deactivation vs the plugin being uninstalled.

This is a good set of guidelines to keep in mind, however you should always consider the individual use case and requirements of the plugin's functionality.

A better solution is to trigger some form of user interaction to confirm the removal of data when the plugin is either deactivated or uninstalled.

This gives your plugin users control over whether the data is removed or not.

## Uninstall method: `register_uninstall_hook`

Similar to the `register_deactivation_hook` function,  the `register_uninstall_hook` function allows you to specify a callback function, which is run when the plugin is uninstalled.

To see this in action, you can update the Extra Content plugin from the Activation and Deactivation methods lesson. If you didn't take that lesson or you don't have that plugin, you create it using the following code:

```php
<?php
/**
 * Plugin Name: WP Learn Extra Content
 * Version: 1.0.0
 */

register_activation_hook( __FILE__, 'wp_learn_extra_content_activation' );
function wp_learn_extra_content_activation() {
	add_option( 'wp_learn_extra_content_extra_option', 'Default extra content' );
}

add_action( 'admin_init', 'wp_learn_extra_content_add_option' );
function wp_learn_extra_content_add_option() {
	add_settings_field( 'extra_option', 'Extra Option', 'wp_learn_extra_content_extra_option_field', 'general' );
	register_setting( 'general', 'wp_learn_extra_content_extra_option' );
}

function wp_learn_extra_content_extra_option_field() {
	echo '<input name="wp_learn_extra_content_extra_option" id="wp_learn_extra_content_extra_option" type="text" value="' . esc_html( get_option( 'wp_learn_extra_content_extra_option' ) ) . '" />';
}

add_filter( 'the_content', 'wp_learn_extra_content_add_extra_option' );
function wp_learn_extra_content_add_extra_option( $content ) {
	$extra_option = get_option( 'wp_learn_extra_content_extra_option' );
	if ( ! $extra_option ) {
		new WP_Error( 'wp_learn_extra_content_extra_option', 'Extra content is empty.' );

		return $content;
	}
	$content .= '<p>' . esc_html( $extra_option ) . '</p>';

	return $content;
}

register_deactivation_hook( __FILE__, 'wp_learn_extra_content_deactivation' );
function wp_learn_extra_content_deactivation() {
	delete_option( 'wp_learn_extra_content_extra_option' );
}
```

Alternatively, you can download the plugin from the Training team's [GitHub repository](https://github.com/wptrainingteam/plugin-developer/blob/trunk/wp-learn-extra-content.1.0.0.zip).

Open the plugin file, and you'll see the `register_deactivation_hook` function that removes the option from the database when the plugin is deactivated.

```php
register_deactivation_hook( __FILE__, 'wp_learn_extra_content_deactivation' );
function wp_learn_extra_content_deactivation() {
	delete_option( 'wp_learn_extra_content_extra_option' );
}
```

You can simply replace this with the `register_uninstall_hook` function, and update the function callback to `wp_learn_extra_content_uninstall`.

```php
register_uninstall_hook( __FILE__, 'wp_learn_extra_content_uninstall' );
function wp_learn_extra_content_uninstall() {
	delete_option( 'wp_learn_extra_content_extra_option' );
}
```

Now, the `wp_learn_extra_content_uninstall` function will only run when the plugin is uninstalled, not on deactivation.

You can test this, by activating the plugin. If you look in the options table in the database, you'll see the `wp_learn_extra_content_extra_option` option has been added.

Now deactivate the plugin. Then check the options table again, and you'll see the option is still there

Finally, delete the plugin. Then verify that the option has been removed.

## Uninstall method: `uninstall.php`

The second method to run tasks when a plugin is uninstalled is to use an `uninstall.php` file in the root of your plugin directory.

This file is automatically run when the plugin is deleted from the WordPress Admin.

Go head and create this file in the root of the Extra Content plugin directory.

```
mkdir wp-learn-extra-content/uninstall.php
```

To use this method, you need to make sure that this file is only run when the plugin is uninstalled. You can do this by checking the `WP_UNINSTALL_PLUGIN` constant.

```php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}
```

This way, if anything else tries to run the file, it will end script execution if it's not being run by a WordPress uninstall process.

Then, you can just add the specific tasks you want to run when the plugin is uninstalled.

```php
delete_option( 'wp_learn_extra_content_extra_option' );
```

Notice how you don't need to worry about hooking into the uninstallation process, as WordPress will automatically run this file when the plugin is deleted.

To test this, clean up the main plugin file, by removing the `register_uninstall_hook` function.

Then, activate the plugin, and check the options table to see the option has been added.

Now, deactivate the plugin, and check the options table again to see the option is still there.

Finally, delete the plugin, and verify that the option has been removed.

## register_uninstall_hook vs uninstall.php

Generally it's recommended ot use the `uninstall.php` file to run tasks when a plugin is uninstalled. This is because when WordPress deletes a plugin, it will automatically try to locate and run this file.

If this file is not present, it will then look for a callback function registered with the `register_uninstall_hook` function to run. This means it has to load your entire main plugin file to find the callback function.

By defaulting to using `uninstall.php`, you can reduce the overhead and any possible risks associated with loading your main plugin file to perform cleanup tasks when the plugin is uninstalled.

It also allows you to contain all your uninstallation tasks in a single file, which can make it easier to manage and maintain.