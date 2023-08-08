# Common APIs - Internationalization

## Learning Objectives

Upon completion of this lesson the participant will be able to:

1. 

## Outline

1. Introduction

## Introduction

Hey there, and welcome to Learn WordPress. 

In this tutorial, you're going to learn about the Internationalization in your WordPress plugins and themes. 

You will learn what internationalization is, what it isnt, why it's important, and how to implement it in your WordPress code.

## What is Internationalization?

Internationalization is the process of developing your application in a way it can easily be translated into other languages. Internationalization is often abbreviated as i18n (because there are 18 letters between the letters i and n).

WordPress is used all over the world, by people who speak many different languages. Therefore, any text strings in WordPress need to be coded so that they can be easily translated into other languages. The process of making sure your text strings can be translation is called Internationalization while the process of translating and adapting the strings to a specific location is called Localization. 

While localization is outside the scope of this tutorial, you can [more about it here](https://developer.wordpress.org/apis/internationalization/localization/).

As a developer, you may not be able to provide localization for all your users; however, using the i18n functions and tools to create specific files, a translator can successfully localize your code without needing to modify the source code itself.

## What Internationalization is not

Internationalization is not the same as making sure your content is available in multiple languages on the front end of your website. This is more commonly referred to as making sure your content is multilingual or translated. Because the content is stored in the database, it's better to have a fully translated copy of your site for each language you want to support. This can be achieved using plugins like [TranslatePress](https://wordpress.org/plugins/translatepress-multilingual/), [Polylang](https://wordpress.org/plugins/polylang/), or [WeGlot](https://wordpress.org/plugins/weglot). 

## How to internationalize your code

Whenever you find yourself writing a string of text that will be displayed to the user, you should use the [WordPress i18n functions](https://developer.wordpress.org/apis/internationalization/internationalization-functions/) to make sure it can be translated.

Let's look at the most basic i18n function: `__()`. This function takes a string of text and returns the translated version of that string. If no translation is available, it returns the original string. 

You will also notice that this function, and most other i18n functions, takes a second parameter. This parameter is used to specify the text domain. A text domain is a unique identifier for your plugin or theme. It is used to make sure that the correct translation files are loaded.

The text domain is also used to create the translation files. The final translation files are stored in the `languages` folder of your plugin or theme. The file name is the text domain with the `.mo` extension. For example, if your text domain is `my-textdomain`, the translation file will be `my-textdomain.mo`.

```php
__( 'Some Text', 'my-textdomain' );
```

To see how this works, let's take a look at an example. Let's say you've created a cloned version of the Twenty Twenty-Three theme, with a functions.php file that enqueues a JavaScript file for the theme and adds a settings page to the Appearance menu. 

```php
/**
 * Enqueue theme.js file in the dashboard.
 */
add_action( 'admin_enqueue_scripts', 'twentytwentythreeclone_enqueue_scripts' );
function twentytwentythreeclone_enqueue_scripts() {
	wp_enqueue_script(
		'twentytwentythreeclone-theme-js',
		get_stylesheet_directory_uri() . '/assets/theme.js',
		array( 'wp-i18n' ),
		'1.0.0',
		true
	);
	wp_set_script_translations('twentytwentythreeclone-theme-js', 'twentytwentythreeclone');
}


/**
 * Create an admin submenu item under the "Appearance" menu.
 */
add_action( 'admin_menu', 'twentytwentythreeclone_add_submenu_page' );
function twentytwentythreeclone_add_submenu_page() {
	add_submenu_page(
		'themes.php', // parent slug
		'Twenty Twenty Three Clone', // page title
		'Twenty Twenty Three Clone', // menu title
		'manage_options', // capability
		'twentytwentythreeclone', // slug
		'twentytwentythreeclone_display_page' // callback
	);
}

/**
 * Render the page for the submenu item.
 */
function twentytwentythreeclone_display_page() {
	?>
	<div class="wrap">
		<h1>Twenty Twenty Three Clone Settings</h1>
		<p>This is a settings page for the Twenty Twenty Three Clone theme</p>
        <button id="twentytwentythreeclone-settings-button" class="button button-primary">Alert</button>
	</div>
	<?php
}
```

The settings page contains a button, which when clicked shows an alert. This is handled in the JavaScript file for the theme.

```js
/**
 * Add event listener to the button
 */
document.querySelector( '#twentytwentythreeclone-settings-button' ).addEventListener( 'click', settingsButtonClick );
function settingsButtonClick() {
	alert( 'Settings button clicked' );
}
```

In this code, you have a number of English text strings that need to be made translatable.

```php
<?php

/**
 * Enqueue theme.js file in the dashboard.
 */
add_action( 'admin_enqueue_scripts', 'twentytwentythreeclone_enqueue_scripts' );
function twentytwentythreeclone_enqueue_scripts() {
	wp_enqueue_script(
		'twentytwentythreeclone-theme-js',
		get_stylesheet_directory_uri() . '/assets/theme.js',
		array( 'wp-i18n' ),
		'1.0.0',
		true
	);
	wp_set_script_translations('twentytwentythreeclone-theme-js', 'twentytwentythreeclone');
}

/**
 * Create an admin submenu item under the "Appearance" menu.
 */
add_action( 'admin_menu', 'twentytwentythreeclone_add_submenu_page' );
function twentytwentythreeclone_add_submenu_page() {
	add_submenu_page(
		'themes.php', // parent slug
		__( 'Twenty Twenty Three Clone', 'twentytwentythreeclone' ), // page title
		__( 'Twenty Twenty Three Clone', 'twentytwentythreeclone' ), // menu title
		'manage_options', // capability
		'twentytwentythreeclone', // slug
		'twentytwentythreeclone_display_page' // callback
	);
}

/**
 * Render the page for the submenu item.
 */
function twentytwentythreeclone_display_page() {
	?>
    <div class="wrap">
        <h1><?php _e( 'Twenty Twenty Three Clone Settings', 'twentytwentythreeclone' ); ?></h1>
        <p><?php _e( 'This is a settings page for the Twenty Twenty Three Clone theme', '__' ); ?></p>
        <button id="twentytwentythreeclone-settings-button" class="button button-primary"><?php _e( 'Alert', 'twentytwentythreeclone' ); ?></button>
    </div>
	<?php
}
```

```js
/**
 * Add event listener to the button
 */
document.querySelector( '#twentytwentythreeclone-settings-button' ).addEventListener( 'click', settingsButtonClick );

function settingsButtonClick() {
	alert( __( 'Settings button clicked', 'twentytwentythreeclone' ) );
}
```

## How test your Internationalization functions

Once you've set up your code to use the i18n functions, you can test it by generating a POT file. This is a file a translater would use to create the translation files. You can generate a POT file using the [WP CLI](https://developer.wordpress.org/cli/commands/i18n/make-pot/).

```bash
wp i18n make-pot path/to/your-plugin-directory
```

This will scan the code in your plugin or theme, find any translatable strings, and place them in the POT file. 

## Conclusion

Happy coding