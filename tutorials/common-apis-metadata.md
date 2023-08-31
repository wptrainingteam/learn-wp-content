# Common APIs - Metadata

## Learning Objectives

Upon completion of this lesson the participant will be able to:
Describe what metadata is and how it works
Access metadata in the WordPress dashboard as Custom Fields
Use the WordPress Metadata API to interact with metadata
Use the metadata wrapper functions for posts, users, and comments

## Outline

1. Introduction
2. What is metadata in WordPress
3. How to access metadata in the WordPress dashboard
4. How to use the WordPress Metadata API to interact with metadata
5. Metadata wrapper functions for posts, users, and comments
6. Conclusion

## Introduction

Hey there, and welcome to Learn WordPress. 

In this tutorial, you're going to learn about the WordPress Metadata API.

You will learn what the metadata is in the context of a WordPress site, how it is useful, and how to access and interact with the different metadata types.

## What is metadata in WordPress

Metadata is data about data. For the three main data types in a WordPress site, posts, users, and comments, metadata is additional information that is stored alongside the main data for a given data object.

The columns in the wp_posts table are set by the table schema, and do not change, unless additional columns are added. So the post_title columns will stay the post_title columns, and will store the post title.

But let's say you want to store some additional information about a post. For example, for each post, you want to store the location where the post was written, and display it when the post is displayed. In order to do this, you would have to either add a new column to the wp_posts table, or create a new table to store this information.

This is where metadata comes in. Instead of adding a new column to the wp_posts table, you can use the WordPress Metadata API to store the location information in the wp_postmeta table, and associate it with the post. 

In the wp_postmeta table, each row has a post_id column, which is the ID of the post that the metadata is associated with. The metadata is stored in the table as a key/value pair, where the meta_key column stores the name of the additional field, and the meta_value column stores the value.

## How to access metadata in the WordPress dashboard

To make this process a little easier, WordPress provides a Custom Fields meta box in the edit screen, which allows you to add metadata to a post, without having to write any code. This is one of th reasons metadata is also commonly called Custom Fields. 

As the name suggests, the Custom Fields meta box is an area that displays all of the metadata associated with a given data type. It is possible to [add custom meta boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/) to the edit screen, but that is beyond the scope of this tutorial. 

### Enabling the Custom Fields meta box

In the classic editor, you can enable the Custom Fields meta box by clicking on the Screen Options tab in the top right corner of the screen, and checking the Custom Fields checkbox.

In the block editor, you can enable the Custom Fields meta box by clicking on the three dots in the top right corner of the screen, clicking Preferences, Panels and enabling the Custom Fields toggle. The modal will ask you to reload the page to view the Custom fields.

You will need to do this for any data type that you want to add metadata to.

### Adding, updating, and deleting metadata in the Custom Fields meta box

Once enabled you can add a new custom field, by proving the field name (which will be stored as the meta_key) and the field value (which will be stored as the meta_value).

Start by adding a custom field with the name "location" and the value "Los Angeles". Then click the Add Custom Field button. 

Now take a look at the wp_postmeta table in the database. You should see a new row with the post_id of the post you added the custom field to, a meta_key of "location", and a meta_value of "Los Angeles".

It's also possible to add more than one record of metadata to a data object, with the same key. So you could add another custom field with the name "location" and the value "California". 

And if you inspect the wp_postmeta table again, you'll see that there are now two rows with the same post_id and meta_key, but different meta_values.

You can also update a custom field, let's say you want to change the first one to "San Diego".

Finally, you can delete a custom field from the Custom Fields meta box, which will delete it from the wp_postmeta table.

In this case, let's delete the first custom fields you added for "location" and "San Diego".

And if you check the wp_postmeta table again, you'll see that the that row has been deleted.

What makes all this possible is the WordPress Metadata API.

## How to use the WordPress Metadata API to interact with metadata

The four core functions of the WordPress Metadata API are:

- `add_metadata()` - allows you to add a new metadata entry for a specific object
- `update_metadata()` - allows you to update an existing metadata entry for a specific object
- `get_metadata()` - allows you to retrieve an existing metadata entry for a specific object
- `delete_metadata()` - allows you to delete an existing metadata entry for a specific object

### Example test bed

In order to demonstrate how these functions work, create a wp-test.php file in the root of your local WordPress install, and include the following code:

```php
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Learn WordPress Metadata API</title>
</head>
<body style="margin: 2em;">
	<h1>Learn WordPress Database</h1>
	<div>

	<?php
	// Load the WordPress library.
	require_once __DIR__ . '/wp-load.php';

	$data = "Hello WordPress Testing!";

	echo $data;

	?>

	</div>
</body>
```

This will allow you to run arbitrary PHP code in the context of your WordPress site, but without triggering the entire WordPress request.

Then browse to this file in your browser, and you should see the text "Hello WordPress Testing!".

```php
https://learn.wordpress.test/wp-test.php
```

### Adding or updating metadata

To add a new metadata entry, you can use the `add_metadata()` function. This function has four require parameters:

- `$meta_type` - the type of object that the metadata is being added to. This can be 'post', 'comment', 'term', or 'user'
- `$object_id` - the ID of the object that the metadata is being added to
- `$meta_key` - the name of the metadata field
- `$meta_value` - the value of the metadata field

The function returns the meta_id of the new metadata entry, or false if the metadata could not be added.

So to programmatically add the location metadata to the post with the ID of 1, you would use the following code:

```php
	$meta_data_id = add_metadata( 'post', 1, 'location', 'Los Angeles' );
	if ( ! $meta_data_id ) {
		echo 'Could not add metadata';
	} else {
		echo 'Metadata added successfully';
	}
```

`add_metadata()` also has a fifth optional parameter, `unique`, which defaults to false. If set to true, the metadata will only be added if there is no existing metadata entry with the same meta_key and meta_value. 

To update an existing metadata entry, you can use the `update_metadata()` function, which functions in a similar way to `add_metadata()`. This function has four require parameters:

- `$meta_type` - the type of object that the metadata is being updated for. This can be 'post', 'comment', 'term', or 'user'
- `$object_id` - the ID of the object that the metadata is being updated for
- `$meta_key` - the name of the metadata field
- `$meta_value` - the new value of the metadata field

So if you wanted to update the meta key you just added, you could use the following code:

```php
    $meta_data_id = update_metadata( 'post', 1, 'location', 'San Diego' );
    if ( ! $meta_data_id ) {
        echo 'Could not update metadata';
    } else {
        echo 'Metadata updated successfully';
    }
```

If you take a look in the database, you'll see the metadata entry has been updated.

`update_metadata()` also has a fifth optional parameter, `$prev_value`, which allows you to specify the current value of the metadata field, and will only update the metadata if the current value matches the value of `$prev_value`.

So for the last update you could have done something like this:

```php
    $meta_data_id = update_metadata( 'post', 1, 'location', 'San Diego', 'Los Angeles' );
    if ( ! $meta_data_id ) {
        echo 'Could not update metadata';
    } else {
        echo 'Metadata updated successfully';
    }
```

And it would only have worked if the value being updated was Los Angeles

### Fetching metadata

You can use the get_metadata() function to retrieve metadata for a given object. The `get_metadata()` function has two required parameters:
- `$meta_type` - the type of object that the metadata is being retrieved for. This can be 'post', 'comment', 'term', or 'user'
- `$object_id` - the ID of the object that the metadata is being retrieved for

It also has additional optional parameters that allow you to filter the metadata that is returned. These are:
- `$meta_key` - the name of the metadata field to retrieve
- `$single` - whether to return a single value or an array of values. Defaults to false

To understand how this works, try using the `get_metadata()` function to retrieve the metadata for the post with ID of 1, and display it on screen using the print_r() function.

```php
    $meta_data = get_metadata( 'post', 1 );
    echo '<pre>' . print_r( $meta_data, true ) . '</pre>';
```

Notice how by passing only the object type and the ID, it returns all meta data associated to that post. 

Now include a specific meta_key, say location

```php
    $meta_data = get_metadata( 'post', 1, 'location' );
    echo '<pre>' . print_r( $meta_data, true ) . '</pre>';
```

Notice that the returned data is an array, with the two location values. Now set the `$single` parameter to true

```php
    $meta_data = get_metadata( 'post', 1, 'location', true );
    echo '<pre>' . print_r( $meta_data, true ) . '</pre>';
``` 

And it only returns the first location value.

By default, get_metadata will always return an array of values, even if only one value for the given meta_key exists. So even if you are only storing a single key/value pair, if you want to return a single value, you need to set the `$single` parameter to true.

### Deleting metadata

Finally, you can use the `delete_metadata()` function to delete metadata for a given object. The `delete_metadata()` function has three required parameters:
- `$meta_type` - the type of object that the metadata is being deleted for. This can be 'post', 'comment', 'term', or 'user'
- `$object_id` - the ID of the object that the metadata is being deleted for
- `$meta_key` - the name of the metadata field to delete

It also has an additional optional parameter, `$meta_value`, which allows you to specify the value of the metadata field to delete. If this parameter is not specified, all metadata entries with the given meta_key will be deleted.

So for example, if you just wanted to delete the second location value, you could use the following code:

```php
    $meta_data_id = delete_metadata( 'post', 1, 'location', 'California' );
    if ( ! $meta_data_id ) {
        echo 'Could not delete metadata';
    } else {
        echo 'Metadata deleted successfully';
    }
```

But if you wanted to delete all location values, you could use the following code:

```php
    $meta_data_id = delete_metadata( 'post', 1, 'location' );
    if ( ! $meta_data_id ) {
        echo 'Could not delete metadata';
    } else {
        echo 'Metadata deleted successfully';
    }
```

## Metadata wrapper functions for posts, users, and comments

In addition to the core functions, the WordPress Metadata API also provides wrapper functions for the three main data types in a WordPress site, posts, users, and comments. These wrappers provide a more convenient way to interact with the metadata for these data types.

As an example, the post metadata wrapper functions are:
- `add_post_meta()`
- `update_post_meta()`
- `get_post_meta()`
- `delete_post_meta()`

If you compare the function signatures for these functions with the core functions, you'll notice that they are identical, except that the `$meta_type` parameter is not required, as it is assumed to be 'post'. This is the same for all of the metadata wrapper functions. Knowing that these wrappers exist can save you some time when working with metadata if you know the data type you are working with. 

This is especially useful when creating custom meta boxes in the edit screen for specific object types, or for rendering metadata for specific object types on the front end. 

## Conclusion

And that wraps up this tutorial on the WordPress Metadata API. For more details on the Metadata API, check out the [Metadata API](https://developer.wordpress.org/apis/metadata/) section of the WordPress developer documentation under Common APIs.

Happy coding