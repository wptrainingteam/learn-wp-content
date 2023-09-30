# Common APIs - Settings

## Learning Objectives

Upon completion of this lesson the participant will be able to:

- Describe what the Settings API is
- Describe what the Settings API should be used for
- Use the Settings API to add a new setting to an existing settings page
- Use the Settings API to create a new setting pages, and move the setting to the new page
- Add an error message to the setting
- Describe the advantages and disadvantages of using the Settings API

## Outline

1. Introduction
2. What is the Settings API?
3. What should the Settings API be used for?
4. Using the Settings API to add new settings to existing settings pages
5. Using the Settings API to create new settings pages
6. Add settings error messages
7. Advantages of using the Settings API
8. Disadvantages of using the Settings API
9. Summary

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, we'll be looking at the Settings API. You will learn what the Settings API is, what it's used for, and how to use it to build settings pages for your WordPress themes and plugins.

## What is the Settings API?

The Settings API, added in WordPress 2.7, allows admin pages containing settings forms to be managed semi-automatically. It lets you define settings pages, sections within those pages and fields within the sections.

New settings pages can be registered along with sections and fields inside them. Existing settings pages can also be added to by registering new settings sections or fields inside of them.

The Settings API uses the Options API under the hood to store the Settings in the wp_options table. All the same field type rules for options apply when using the Settings API.

https://developer.wordpress.org/reference/functions/get_option/

- the options table stores data as a text string
- arrays and objects are stored as a serialized string
- false returns string(0) ""
- true returns string(1) "1"
- 0 returns string(1) "0"
- 1 returns string(1) "1"
- '0' returns string(1) "0"
- '1' returns string(1) "1"
- null returns string(0) ""

https://wordpress.tv/2023/09/22/common-apis-options/

NOTE: When using the Settings API, the form posts to wp-admin/options.php which provides fairly strict capabilities checking. Users will need manage_options capability (and in MultiSite will have to be a Super Admin) to submit the form.

## What should the Settings API be used for?

The Settings API is typically used in one of two days

- Add new settings to existing settings pages
- Create new settings pages for plugins or themes

## Using the Settings API to add new settings to existing settings pages

To understand how the Settings API works, let's look at a plugin that adds a new setting to the General Settings page.


```php
<?php
/**
 * Plugin Name: WP Learn Settings
 * Version: 1.0.0
 * Description: A plugin to learn how to use the Settings API
 */
```

```php
/**
 * Add all your sections, fields and settings during admin_init
 */
add_action( 'admin_init', 'wp_learn_settings_api_init' );
function wp_learn_settings_api_init() {
	// Add the section to general settings so we can add our fields to it
	add_settings_section(
		'wp_learn_setting_section',
		'Example settings section in General Settings',
		'wp_learn_setting_section_callback_function',
		'general'
	);

	// Add the field with the names and function to use for our new settings, put it in our new section
	add_settings_field(
		'wp_learn_setting_name',
		'Example setting Name',
		'wp_learn_setting_callback_function',
		'general',
		'wp_learn_setting_section'
	);

	// Register our setting so that $_POST handling is done for us and
	// our callback function just has to echo the field
	register_setting( 'general', 'wp_learn_setting_name' );
} 

/**
 * Settings section callback function
 *
 * This function is needed if we added a new section. This function
 * will be run at the start of our section
 */
function wp_learn_setting_section_callback_function() {
	echo '<p>Intro text for our settings section</p>';
}

/*
 * Callback function for our example setting
 *
 * creates a checkbox true/false option. Other types are surely possible
 */

function wp_learn_setting_callback_function() {
	echo '<input name="wp_learn_setting_name" id="wp_learn_setting_name" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'wp_learn_setting_name' ), false ) . ' />';
	echo '<label for="wp_learn_setting_name">Explanation text</label>';
}
```

- `add_settings_section()` - Adds a new section to a settings page
- `add_settings_field()` - Adds a new field to a settings section
- `register_setting()` - Registers a new setting to be handled when the form is submitted
- `checked` - Checks if the current value of the setting is equal to the value passed in, and if so, adds the `checked` attribute to the input field

Notice that the setting callback function echos HTML for the input fields. This is because there is currently no Fields API, where you could do something like this:.

```php
echo settings_field( 'wp_learn_setting_name', 'Example setting Name', 'checkbox' );
```

And it would output the HTML for the checkbox. Instead, you have to do it yourself.

There is work underway to add a Fields API to WordPress, which may replace the settings API, or merely be an addition to it. You can read more about it [here](https://github.com/sc0ttkclark/wordpress-fields-api).

## Using the Settings API to create new settings pages

First, you need to add a new sub menu item in the Settings menu. You can do this using the `add_submenu_page()` function. To add a new submenu page, you should first hook a callback function into the `admin_menu` action hook. This callback function will then call the `add_submenu_page()` function to create the new page

```php
/**
 * Add a menu item to create a custom settings page
 */

// Add settings page to menu.
add_action( 'admin_menu', 'wp_learn_add_submenu_page' );
function wp_learn_add_submenu_page(){
	/**
	 * Add a new sub menu page for my plugin settings
	 */
	add_submenu_page(
		'options-general.php',
		'WP Learn Settings',
		'WP Learn Settings',
		'manage_options',
		'wp_learn_settings',
		'wp_learn_settings_page'
	);
}

function wp_learn_settings_page(){
	?>
	<div class="wrap">
		<h2>WP Learn Settings</h2>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'wp_learn_settings' );
			do_settings_sections( 'wp_learn_settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
```

- `add_submenu_page()` - Adds a new submenu page. The page is added to which ever top level page is specified in the first parameter. In this case, we are adding it to the `options-general.php` page, which is the Settings page. `manage_options` is the capability required to view the page. `wp_learn_settings` is the slug for the page, and `wp_learn_settings_page` is the function that will be called to render the page.
- `settings_fields()` - Outputs the hidden fields required by the Settings API. The parameter is the slug of the page you are adding the settings to.
- `do_settings_sections()` - Outputs the sections, fields and settings for the page. The parameter is the slug of the page you defined in the `add_submenu_page` function call.
- `submit_button()` - Outputs the submit button for the form.

Notice the use of the div with a class of `wrap` around the form. By using this you get the same look and feel as the other settings pages in the admin area.

Now, you can take the settings you have already registered for the General Settings page, and use them on your new settings page, by replacing everywhere you refer to the `general` settings page, with the slug of your new settings page, `wp_learn_settings`.

Notice that when you save the settings, they are saved to the `wp_options` table with the option name of `wp_learn_setting`. 

Also notice that while the form is posting to the `options.php` page, you are redirected back to your custom settings page. This is because of the fields output by the `settings_fields()` function.

## Add settings error messages

It is also possible to add settings error messages for settings fields, using the `add_settings_error()` function. This function needs to be called inside the `sanitize_callback` function which can be defined in the arguments array passed to `register_setting`.

```php
	register_setting(
		'wp_learn_settings',
		'wp_learn_setting_name',
		array(
			'type'              => 'boolean',
			'sanitize_callback' => 'wp_learn_setting_sanitize_callback_function'
		)
	);
	
	function wp_learn_setting_sanitize_callback_function( $input ){
	if ( $input == 0 ) {
		add_settings_error(
			'wp_learn_setting_name',
			'wp_learn_setting_name_error',
			'Please check the check box.',
			'error'
		);
    }
    return $input;
}
```

Notice how you pass the setting name slug as the first parameter, and then a unique error slug as the second parameter. The third parameter is the error message, and the fourth parameter is the error type. The error type can be `error`, `updated` or `notice`.

## Advantages of using the Settings API
- Settings pages are automatically validated and sanitized
- Settings pages are automatically saved to the database
- Settings pages are automatically displayed in the admin area

## Disadvantages of using the Settings API
- No real control over UI/look and feel
- You have to register each setting section, field, and setting for it to work
- No fields API, so you have to write your own HTML for the input fields
- Can be hard to debug if things go wrong.

https://github.com/hlashbrooke/WordPress-Plugin-Template

## Summary

And that wraps up this tutorial on the Settings API. To read more about it and the functions it provides, check out the [Settings API documentation](https://developer.wordpress.org/apis/settings/) at developer.wordpress.org.

Happy coding!    