# Common APIs - Options

## Learning Objectives

Upon completion of this lesson the participant will be able to:

Describe what the Options API is
Explain what the Options API should be used for
Describe how to use the Options API
Explain how different data types are stored in the options table
Explain what protected options

## Outline

1. Introduction
2. What is the Options API?
3. What should the Options API be used for?
4. How to use the Options API
    1. `add_option()`
    2. `get_option()`
    3. `update_option()`
    4. `delete_option()`
5. A note on data types
6. A note on protected options and permissions
7. Summary

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, we'll be looking at the Options API. You will learn what the Options API is, what it's used for, and how to use it to store and retrieve options in the WordPress database.

## What is the Options API?

The Options API is a set of functions that allow you to store and retrieve options in the WordPress database.

These options are stored in the `options` table in the database and are stored as key/value pairs.

Many of these options are created during the WordPress installation process, and control various aspects of a WordPress installation.

The options table is where all the settings in the Settings screens of the WordPress dashboard are stored, things like General Settings, Reading Settings, Writing Settings etc. Each of the settings you see on any of these screens is stored as an option in the options table.

In fact, the Settings screens are powered by two core APIs: the Settings API, and the Options API. The Settings API is used to create the user interfaces for these screens, and the Options API is used to store and retrieve the settings data.

## What should the Options API be used for?

Besides storing WordPress settings data, the `options` table can also be used to store other data that is used by WordPress plugins and themes.

For example, in the Seriously Simple Podcasting plugin for WordPress, the plugin uses the Options API to store the settings for your podcast, including settings that control how the podcast episode player is displayed, and settings that control how the podcast feed is generated. Any time you need to store data that is used by your plugin or theme to determine how that plugin or theme functions, you can use the Options API to store that data.

## How to use the Options API

The Options API is a set of four core functions that allow you to store and retrieve options in the WordPress database.

- `add_option()` - Adds a new option to the options table in the WordPress database.
- `get_option()` - Retrieves the value of an option from the options table in the WordPress database.
- `update_option()` - Updates the value of an option in the options table in the WordPress database.
- `delete_option()` - Deletes an option from the options table in the WordPress database.

Additionally, there are also four additional functions that perform the same actions, but on sites on a WordPress Multisite network.

- `add_site_option()`
- `get_site_option()`
- `update_site_option()`
- `delete_site_option()`

For the purposes of this tutorial, we will focus on the four core functions, and not the multisite functions.

### `add_option()`

The `add_option()` function is used to add a new option to the options table in the WordPress database. It takes four parameters:

- `$option_name` - The name of the option to add.
- `$option_value` - The value of the option to add.
- `$deprecated` - Optional. Not used.
- `$autoload` - Optional. Whether to load the option when WordPress starts up. Default is `yes`.

In its simplest form, the `add_option()` function can be used like this:

```php
add_option( 'my_option', 'my_option_value' );
```

And this will add the option to the options table in the database.

It is also possible to add an option with a more complex value, such as an array or an object. For example:

```php
$my_option_array = array(
    'option_1' => 'value_1',
    'option_2' => 'value_2',
    'option_3' => 'value_3',
);
add_option( 'my_option_array', $my_option_array );
```

When this is added to the options table, it will be stored as a serialized array.

The autoload parameter is used to determine whether the option should be loaded when WordPress starts up.

If you take a look at the options table, you'll see it has an autoload field, and many of the options are marked yes

This is used by the wp_load_alloptions() [function](https://developer.wordpress.org/reference/functions/wp_load_alloptions/).

wp_load_alloptions() is called early on in any WordPress request, which fetches and caches all options in the wp_options table where the autoload value is yes. This means that any future calls to get_option() will get the option value from the cached version, instead of running an additional MySQL query to fetch the option value from the database.

### `get_option()`

The `get_option()` function is used to retrieve the value of an option from the options table in the WordPress database.

It takes two parameters:

- `$option_name` - The name of the option to retrieve.
- `$default` - Optional. The default value to return if the option does not exist. Default is `false`.

In its simplest form, the `get_option()` function can be used like this:

```php
$my_option = get_option( 'my_option' );
```

This will retrieve the value of the option from the options table in the database.

If the option does not exist, then the default value will be returned. If no default value is specified, then `false` will be returned.

```php
$my_missing_option = get_option( 'my_missing_option', 'my_missing_option' );
```

Using the default value is useful if you want to make use of this option for a specific purpose, but in a case where the user might not have set a value for the option yet. For example, in the Seriously Simple Podcasting plugin, the plugin has a setting with controls where the media player is displayed, called Media player position. The options are either Above the content, or Below the content.

When the user installs the plugin, the default value for this option is set to Above the content. However, for this to work, the plugin would need to add this option to the database when the plugin is activated, and then retrieve the value of this option when the player is rendered.

However, by using the default value for 'above' when the option is fetched using get_option(), the plugin doesn't have to write that option to the database on installation, and the player will still be displayed above the content. It also means that if that option is removed from the table by mistake, the player will still be displayed somewhere at least, based on the default value, instead of not being displayed at all.

Notice also that using get_option to retrieve an option that is stored as a serialised array will return the data as an array, so you don't have to worry about converting it back. WordPress will handle this for you.

```php
$my_option_array = get_option( 'my_option_array' );
```

### A note on data types

When storing data in the options table, it is important to note that the value of the option is stored as a string. This means that if you want to a boolean value (like true or false) or some other value that can't be serialized (like an array or object can), it's going to implement some PHP type casting to store the value. So for example, true and false will be stored as "1" and "0" and null will be stored as an empty string.

It's therefore a good idea to always store string values of what you want to store as your options, so "true" and "false" or "yes" and "no", and then check against those string values in your code.

### `update_option()`

The `update_option()` function is used to update the value of an option in the options table in the WordPress database. It takes three parameters:

- `$option_name` - The name of the option to update.
- `$option_value` - The new value of the option.
- `$autoload` - Optional. Whether to load the option when WordPress starts up. Default is `yes`.

In its simplest form, the `update_option()` function can be used like this:

```php
update_option( 'my_option', 'my_new_option_value' );
```

This will update the value of the option in the options table in the database.

Note that if you try to update an option that does not exist, then the option will be created. So you can use `update_option()` to add an option to the database as well. Many developers will use update_option and get_option with the default value to add an option to the database if it doesn't exist, and then retrieve the value of that option, or use it's default if it does not exist.



## `delete_option()`

Finally `delete_option()` function is used to delete an option from the options table in the WordPress database. It takes one parameter:
- `$option_name` - The name of the option to delete.

In its simplest form, the `delete_option()` function can be used like this:

```php
delete_option( 'my_option' );
```

This will delete the option from the options table in the database.

## A note on protected options and permissions

There are a number of options that are protected WordPress options, meaning they are required for WordPress to function. These options cannot be added, updated or deleted using the Options API functions, as checks are put in place to prevent this from happening.

Additionally, there are no checks in place for add_option, update_option and delete_option to check whether the user has the correct permissions to perform these actions. Therefore, it is always a good idea to perform a capability check before performing any of these actions, using the current_user_can() function. For more information on how to use User Roles and Capabilities, check out [this tutorial on Learn WordPress](https://learn.wordpress.org/tutorial/developing-with-user-roles-and-capabilities/).

## Summary

And that wraps up this tutorial on the Options API. To read more about it and the functions it provides, check out the [Options API documentation](https://developer.wordpress.org/apis/handbook/options-api/) at developer.wordpress.org.

Happy coding!    