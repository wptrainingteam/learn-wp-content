# Plugin requirements

Now that you know what a plugin is, let's explore what you need to create one.

## Creating your first plugin

The minimum requirements for a valid WordPress plugin are at least one PHP file, the main plugin file, with an opening PHP tag.

Inside the main plugin file, the first piece of code should be the Plugin Header, which is a PHP comment block. At minimum, it should contain a field for the plugin name. 

To create your first plugin, navigate to the `wp-content/plugins` directory, and create a single PHP file called `example-plugin.php`.

```
cd wp-content/plugins
touch example-plugin.php
```

Inside the file, make sure to open the PHP tags, so that the server can execute the PHP code.

```php
<?php
```

Now, add the following code to the top of the file, just below the opening PHP tag.

```php
/**
 * Plugin Name: Example Plugin
 */
```

This is known as the Plugin Header, and is written using a version of PHP's comment syntax called a DocBlock. 

To read more about comments in PHP, take a look at the [Comments](https://www.php.net/manual/en/language.basic-syntax.comments.php) page in the Basic Syntax section of the PHP manual.

With your first plugin created, you can now browse to the Plugins page in the WordPress dashboard, and you will see your plugin available and ready to be activated.

This plugin doesn't do anything yet, but it's ready to have functionality added to it. 

## How WordPress identifies and stores active plugins

Once a plugin is activated, it is added to the list of active plugins stored in a serialised array in the options table. You can find this array by running the following SQL query in phpMyAdmin. 

```sql
SELECT * FROM `wp_options` WHERE `option_name` LIKE 'active_plugins'
```

Notice how it stores the filename of the PHP file. This is also known as the plugin slug, and is how WordPress identifies your plugin during execution.

If you move your main plugin file inside a directory, the plugin slug changes to include the directory name. 

Create a new directory in `wp-content/plugins` called `example-plugin`, and move your plugin file into that directory.

If you browse to the Plugins page in the WordPress dashboard, you'll see the plugin is no longer active, because the slug has changed. 

You'll also notice a warning at the top of the Plugins page:

```
The plugin example-plugin.php has been deactivated due to an error: Plugin file does not exist.
```

Now activate the plugin. 

Then go back and a look at the list of active plugins in the `wp_options table`, and see how the new slug is added to the serialised array, which includes the directory name.

## Plugin header fields

While the plugin name field is the basic requirement for a valid plugin, there are additional fields available for you to add to the plugin header. 

It's generally recommended to also add a description and version to the plugin header. This allows users to get a bit more information about your plugin, and it looks better when displayed in the plugins list. 

```php
/**
 * Plugin Name: Example Plugin
 * Description: This is an example plugin for the WordPress developer pathway.
 * Version: 1.0
 */
```

You can see the full list of plugin header fields in the [Header Requirements](https://developer.wordpress.org/plugins/plugin-basics/header-requirements/) page in the Plugin developer handbook.

## Plugin best practices

The plugin handbook also contains a section on common [best practices](https://developer.wordpress.org/plugins/plugin-basics/best-practices/) when developing plugins. 

One of these suggestions is to include a check to ensure that the plugin code is only executed when part of a WordPress request.

```php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
```

What this code does is check if the ABSPATH constant is defined, which is a WordPress specific constant. If it's not defined, then exit the code execution of the plugin. 

This way, if someone tries to browse to the main plugin file in a browser directly, none of the PHP code in the plugin will be executed, preventing any security risks. 

You can read more about this and other suggestions in the [Best Practices](https://developer.wordpress.org/plugins/plugin-basics/best-practices/) page in the Plugin developer handbook.

## YouTube chapters

0:00 Creating your first plugin
0:00 How WordPress identifies and stores active plugins
0:00 Plugin header fields
0:00 Plugin best practices