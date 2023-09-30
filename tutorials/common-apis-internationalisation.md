# Common APIs - Internationalization

## Learning Objectives

Upon completion of this lesson the participant will be able to:

1. Explain what Internationalization is
2. Explain what Internationalization is not
3. Explain why Internationalization is important
4. Use the WordPress i18n functions to internationalize their code
5. Test their Internationalization functions

## Outline

1. Introduction
2. What is Internationalization?
3. What Internationalization is not
4. How to internationalize your code
5. How test your Internationalization functions
6. Conclusion

## Introduction

Hey there, and welcome to Learn WordPress. 

In this tutorial, you're going to learn about the Internationalization in your WordPress plugins and themes. 

You will learn what internationalization is, what it isn't, why it's important, and how to implement it in your WordPress code.

## What is Internationalization?

Internationalization is the process of developing your application in a way it can easily be translated into other languages. Internationalization is often abbreviated as i18n (because there are 18 letters between the letters i and n).

WordPress is used all over the world, by people who speak many languages. 

Therefore, any text strings in WordPress need to be coded so that they can be easily translated into other languages. 

The process of making sure your text strings can be translated is called Internationalization while the process of translating and adapting the strings to a specific location is called Localization. 

While localization is outside the scope of this tutorial, you can read more about it [in the Localization section](https://developer.wordpress.org/apis/internationalization/localization/) of the Common APIs handbook.

As a developer, you may not be able to provide localization for all your users; however, using the i18n functions and tools to create specific files, a translator can successfully localize your code without needing to modify the source code itself.

## What Internationalization is not

Internationalization is not the same as making sure your content is available in multiple languages on the front end of your website. This is more commonly referred to as making sure your content is multilingual or translated. Because the content is stored in the database, it's better to have a fully translated copy of your site for each language you want to support. This can be achieved using plugins like [TranslatePress](https://wordpress.org/plugins/translatepress-multilingual/), [Polylang](https://wordpress.org/plugins/polylang/), or [WeGlot](https://wordpress.org/plugins/weglot). 

## How to internationalize your code

Whenever you find yourself writing a string of text that will be displayed to the user, you should use the [WordPress i18n functions](https://developer.wordpress.org/apis/internationalization/internationalization-functions/) to make sure it can be translated. There are a number of i18n functions available, with each performing a different task related to internationalization.

Let's look at the most basic i18n function: the `__()` function. This function takes a string of text and returns the translated version of that string. If no translation is available, it returns the original string. 

You will also notice that this function, and most other i18n functions, takes a second parameter. This parameter is used to specify the text domain. A text domain is a unique identifier for your plugin or theme. It is used to make sure that the correct translation files are loaded.

The text domain is also used to create the translation files. The final translation files are stored in the `languages` folder of your plugin or theme. The file name is the text domain with the `.mo` extension. For example, if your text domain is `my-textdomain`, the translation file will be `my-textdomain.mo`.

```php
__( 'Some Text', 'my-textdomain' );
```

To see how this works, let's take a look at an example inside a child theme of Twenty Twenty Three. 

In the functions.php file a JavaScript file is enqueued in the context of the WordPress dashboard, It also registers a submenu item and in the Appearance menu, which renders a child theme settings page. 

```php
<?php
/**
 * Enqueue theme.js file in the dashboard.
 */
add_action( 'admin_enqueue_scripts', 'twentytwentythreechild_enqueue_scripts' );
function twentytwentythreechild_enqueue_scripts() {
	wp_enqueue_script(
		'twentytwentythreechild-theme-js',
		get_stylesheet_directory_uri() . '/assets/theme.js',
		array(),
		'1.0.0',
		true
	);
}

/**
 * Create a submenu item under the "Appearance" menu.
 */
add_action( 'admin_menu', 'twentytwentythreechild_add_submenu_page' );
function twentytwentythreechild_add_submenu_page() {
	add_submenu_page(
		'themes.php', 
		'Twenty Twenty Three Child', 
		'Twenty Twenty Three Child',
		'manage_options', 
		'twentytwentythreechild', 
		'twentytwentythreechild_display_page' 
	);
}

/**
 * Render the page for the submenu item.
 */
function twentytwentythreechild_display_page() {
	?>
	<div class="wrap">
		<h1>Twenty Twenty Three Child Settings</h1>
		<p>This is a settings page for the Twenty Twenty Three Child theme</p>
        <button id="twentytwentythreechild-settings-button" class="button button-primary">Alert</button>
	</div>
	<?php
}
```

The settings page contains a button, which when clicked shows an alert. 

This alert is handled in the JavaScript file for the theme.

```js
/**
 * Add event listener to the button
 */
document.querySelector( '#twentytwentythreechild-settings-button' ).addEventListener( 'click', function(){
	alert( 'Settings button clicked' );
} );
```

In this code, you have a number of English text strings that need to be made translatable.

The first step is to internationalize the text strings in the PHP code. To do this, you can wrap text strings in the `__()` function and specify the text domain. 

Start by updating the text strings in the `twentytwentythreechild_add_submenu_page()` function.

```php
/**
 * Create an admin submenu item under the "Appearance" menu.
 */
add_action( 'admin_menu', 'twentytwentythreechild_add_submenu_page' );
function twentytwentythreechild_add_submenu_page() {
	add_submenu_page(
		'themes.php', // parent slug
		__( 'Twenty Twenty Three Child', 'twentytwentythreechild' ), // page title
		__( 'Twenty Twenty Three Child', 'twentytwentythreechild' ), // menu title
		'manage_options', // capability
		'twentytwentythreechild', // slug
		'twentytwentythreechild_display_page' // callback
	);
}
```

You can do the same for the text strings in the `twentytwentythreechild_display_page()` function.

```php
/**
 * Render the page for the submenu item.
 */
function twentytwentythreechild_display_page() {
	?>
    <div class="wrap">
        <h1><?php echo __( 'Twenty Twenty Three Child Settings', 'twentytwentythreechild' ); ?></h1>
        <p><?php echo __( 'This is a settings page for the Twenty Twenty Three Child theme', '__' ); ?></p>
        <button id="twentytwentythreechild-settings-button" class="button button-primary"><?php echo __( 'Alert', 'twentytwentythreechild' ); ?></button>
    </div>
	<?php
}
```

WordPress also contains a shorthand function to echo a translatable string. This function is the `_e()` function. It both translates and echoes the string. You can use this function to simplify your code.

```php
function twentytwentythreechild_display_page() {
	?>
    <div class="wrap">
        <h1><?php _e( 'Twenty Twenty Three Child Settings', 'twentytwentythreechild' ); ?></h1>
        <p><?php _e( 'This is a settings page for the Twenty Twenty Three Child theme', '__' ); ?></p>
        <button id="twentytwentythreechild-settings-button" class="button button-primary"><?php _e( 'Alert', 'twentytwentythreechild' ); ?></button>
    </div>
	<?php
}
```

Next, you need to internationalize the text strings in the JavaScript file. To do this, there is a JavaScript equivalent to the PHP __() function, which is available in the `wp.i18n` object on the WordPress frontend. To ensure that you can use this function, you need to update your `twentytwentythreechild_enqueue_scripts()` function to require the `wp-i18n` package as a dependency. This will ensure that your JavaScript code is only loaded when the `wp-i18n` package loaded and the `wp.i18n` object is available.

```php
/**
 * Enqueue theme.js file in the dashboard.
 */
add_action( 'admin_enqueue_scripts', 'twentytwentythreechild_enqueue_scripts' );
function twentytwentythreechild_enqueue_scripts() {
	wp_enqueue_script(
		'twentytwentythreechild-theme-js',
		get_stylesheet_directory_uri() . '/assets/theme.js',
		array( 'wp-i18n' ),
		'1.0.0',
		true
	);
}
```

Then, you need to call the wp_set_script_translations function for the script you want to translate. This function takes the handle of the script and the text domain as parameters. This will load the translations for the script.

```php
wp_set_script_translations( 'twentytwentythreechild-theme-js', 'twentytwentythreechild' );
```

With this done, you can then use the `__()` function to translate the text string in your JavaScript file.

```js
/**
 * Add event listener to the button
 */
document.querySelector( '#twentytwentythreechild-settings-button' ).addEventListener( 'click', function(){
	alert( __( 'Settings button clicked', 'twentytwentythreechild' ) );
} );
```

## How test your Internationalization functions

Once you've set up your code to use the i18n functions, you can test it by generating a POT file. This is a file a translator would use to create the translation files. You can generate a POT file using the [WP CLI](https://developer.wordpress.org/cli/commands/i18n/make-pot/).

```bash
wp i18n make-pot path/to/your/plugin/or/theme
```

This will scan the code in your plugin or theme, find any translatable strings, and place them in the POT file. 

## Conclusion

This has been a brief introduction to Internationalisation in WordPress plugins or themes. For more information, make sure to read the [Internationalization section](https://developer.wordpress.org/apis/internationalization/) of the Common APIs handbook on the WordPress developer documentation site.

Happy coding