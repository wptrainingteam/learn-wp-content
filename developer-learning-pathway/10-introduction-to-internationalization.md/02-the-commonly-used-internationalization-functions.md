# The Commonly Used Internationalization Functions

## Introduction (0:00)

In order to get started on internationalizing your code, you need to understand the functions that WordPress provides to help you with this process. 

In this lesson, you will learn about the most commonly used internationalization functions in WordPress, and how to use them in your code.

## How to internationalize your code (0:18)

Whenever you find yourself writing a string of text that will be displayed to the user, you should use the [WordPress i18n functions](https://developer.wordpress.org/apis/internationalization/internationalization-functions/) to make sure it can be translated. 

There are a number of i18n functions available, with each performing a different task related to internationalization.

The most commonly used i18n function is the `__()` function. This function takes a string of text and returns the translated version of that string. If no translation is available, it returns the original string.

You will also notice that this function, and most other i18n functions, takes a second parameter. This parameter is used to specify the text domain. 

A text domain is a unique identifier for your plugin or theme. It is used to make sure that the correct translations are used.

Both themes and plugins have a Text Domain field in their header. This is used to specify the text domain for the theme or plugin.

Whenever you use a translation function, you should always include the text domain as the second parameter.

```php
__( 'Some Text', 'my-textdomain' );
```

To see how this works, let's take a look at an example:

Start by browsing to the [Beginner Developer Learning Pathway repository](https://github.com/wptrainingteam/beginner-developer/) on GitHub and download the [Internationalization Theme](https://github.com/wptrainingteam/beginner-developer/raw/main/internationalization.zip) zip file.

Then install the theme on your WordPress site, and browse to it in your code editor.

In the `functions.php` file a JavaScript file is enqueued in the context of the WordPress dashboard, It also registers a submenu item and in the Appearance menu, which renders a theme settings page.

```php
<?php
/**
 * Enqueue theme.js file in the dashboard.
 */
add_action( 'admin_enqueue_scripts', 'internationalization_enqueue_scripts' );
function internationalization_enqueue_scripts() {
	wp_enqueue_script(
		'internationalization-theme-js',
		get_stylesheet_directory_uri() . '/assets/theme.js',
		array(),
		'1.0.0',
		true
	);
}

/**
 * Create a submenu item under the "Appearance" menu.
 */
add_action( 'admin_menu', 'internationalization_add_submenu_page' );
function internationalization_add_submenu_page() {
	add_submenu_page(
		'themes.php',
		'Internationalization',
		'Internationalization',
		'manage_options',
		'internationalization',
		'internationalization_display_page'
	);
}

/**
 * Render the page for the submenu item.
 */
function internationalization_display_page() {
	?>
	<div class="wrap">
		<h1>Internationalization Settings</h1>
		<p>This is a settings page for the Internationalization theme</p>
		<button id="internationalization-settings-button" class="button button-primary">Alert</button>
	</div>
	<?php
}
```

If you browse to the settings page, it contains a button, which when clicked shows an alert.

This alert is handled in the JavaScript file for the theme.

```js
/**
 * Add event listener to the button
 */
document.querySelector( '#internationalization-settings-button' ).addEventListener( 'click', function(){
    alert( 'Settings button clicked' );
} );
```

In this code, you have a number of English text strings that need to be internationalized.

The first step is to internationalize the text strings in the PHP code. To do this, you can wrap text strings in the `__()` function and specify the text domain.

Start by checking the Text Domain for the theme. For theme's this is in the style.css file, and for plugins it is in the main plugin file.

In this case, the text domain is `internationalization`. If this theme didn't have a text domain, you would need to specify one.

Then, update the text strings in the `internationalization_add_submenu_page()` function.

```php
/**
 * Create an admin submenu item under the "Appearance" menu.
 */
add_action( 'admin_menu', 'internationalization_add_submenu_page' );
function internationalization_add_submenu_page() {
	add_submenu_page(
		'themes.php',
		__( 'Internationalization', 'internationalization' ),
		__( 'Internationalization', 'internationalization' ),
		'manage_options',
		'internationalization',
		'internationalization_display_page'
	);
}
```

You can do the same for the text strings in the `internationalization_display_page()` function. 

```php
/**
 * Render the page for the submenu item.
 */
function internationalization_display_page() {
	?>
	<div class="wrap">
		<h1><?php echo __( 'Internationalization Settings', 'internationalization' ); ?></h1>
		<p><?php echo __( 'This is a settings page for the Internationalization theme', 'internationalization' ); ?></p>
		<button id="internationalization-settings-button" class="button button-primary"><?php echo __( 'Alert', 'internationalization' ); ?></button>
	</div>
	<?php
}
```

WordPress also contains a shorthand function to echo a translatable string. This function is the `_e()` [function](https://developer.wordpress.org/reference/functions/_e/). It both internationalizes and then echoes the string. You can use this function to simplify your code.

```php
/**
 * Render the page for the submenu item.
 */
function internationalization_display_page() {
	?>
	<div class="wrap">
		<h1><?php _e( 'Internationalization Settings', 'internationalization' ); ?></h1>
		<p><?php _e( 'This is a settings page for the Internationalization theme', 'internationalization' ); ?></p>
		<button id="internationalization-settings-button" class="button button-primary"><?php echo __( 'Alert', 'internationalization' ); ?></button>
	</div>
	<?php
}
```

Next, you need to internationalize the text strings in the JavaScript file. 

To do this, there is a JavaScript equivalent to the PHP `__()` function, which is available in the `wp.i18n` object on the WordPress frontend. 

To ensure that you can use this function, you need to update your `internationalization_enqueue_scripts()` function to require the `wp-i18n` package as a dependency. This will ensure that your JavaScript code is only loaded when the `wp-i18n` package loaded and the `wp.i18n` object is available.

```php
/**
 * Enqueue theme.js file in the dashboard.
 */
add_action( 'admin_enqueue_scripts', 'internationalization_enqueue_scripts' );
function internationalization_enqueue_scripts() {
	wp_enqueue_script(
		'internationalization-theme-js',
		get_stylesheet_directory_uri() . '/assets/theme.js',
		array( 'wp-i18n' ),
		'1.0.0',
		true
	);
}
```

Then, you need to call the wp_set_script_translations function for the script you want to translate. This function takes the handle of the script and the text domain as parameters. This will load the translations for the script.

```php
wp_set_script_translations( 'internationalization-theme-js', 'internationalization' );
```

With this done, you can then set up and use the `__()` function from the `wp.i18n` object to translate the text string in your JavaScript file.

```js
const __ = wp.i18n.__;
/**
 * Add event listener to the button
 */
document.querySelector( '#internationalization-settings-button' ).addEventListener( 'click', function(){
    alert( wp.i18n.__( 'Settings button clicked', 'internationalization' ) );
} );
```

If you refresh the page, you won't immediately see any changes, and all functionality will still work as expected.

However, if someone wanted to, they could generate the English language file for the theme, based on all the internationalized text strings, to allow for translation.

## Internationalization functions in block development (7:45)

If you are developing a block for the block editor, you can also use the JavaScript translation functions in your block's JavaScript to internationalize the text strings in your block. 

All you need to do to use it is to import the relevant functions from the `@wordpress/i18n` [package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/).

```js
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
```

## Further reading (8:01)

This lesson only covers a handful of the available internationalization functions. For more information on all the available functions, check out the [Internationalization](https://developer.wordpress.org/apis/internationalization/) section in the Common APIs handbook of the WordPress developer resources.

## YouTube chapters

0:00 Introduction
0:18 How to internationalize your code
7:45 Internationalization functions in block development
8:01 Further reading