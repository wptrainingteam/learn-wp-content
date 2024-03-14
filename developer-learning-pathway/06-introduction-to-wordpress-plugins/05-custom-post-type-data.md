# Custom post type data

## Introduction

Sometimes, the default custom post type fields might not be enough, and you need to store additional data on a custom post type. 

Fortunately WordPress supports something called post metadata, which allows you to store additional information about a post.

Let's look at how you can use post metadata on a post, or custom post type.

## What is post metadata

Post metadata is a way to store additional information about a post in the WordPress database. 

Post metadata is stored in the `postmeta` table as key-value pairs. If you look at the `postmeta` table in the WordPress database, you'll see that it has four columns: `meta_id`, `post_id`, `meta_key`, and `meta_value`.

`post_id` is the ID of the post that the metadata is associated with. `meta_key` is the name of the metadata field, and `meta_value` is its value.

## Adding post metadata

You can add post meta to a post using the `add_post_meta` [function](https://developer.wordpress.org/reference/functions/add_post_meta/). This function takes three parameters: the ID of the post, the name of the metadata, and the value of the metadata.

So lets say you wanted to add a post meta field to a post with the ID of 1, and you wanted to store the location where the post was written as `London`. 

You could use the following code:

```php
    add_post_meta( 1, 'location', 'London' );
```

There are other functions for working with post meta, such as `update_post_meta`, `delete_post_meta`, and `get_post_meta`.

You can read more about these, and how to use them in plugins in the [Metadata](https://developer.wordpress.org/plugins/metadata/) page in the Plugin developer handbook.

## The Custom Fields panel

Adding post metadata via code is one way, but it is also possible to enable site administrators to add post metadata via the WordPress admin interface.

One way to do this is the Custom Fields panel for the post type.

To enable the Custom Fields panel, you will first need to make sure your custom post type supports metadata. 

To do this, you need to add or update the supports' argument, to include support for `custom-fields`

Then, when editing a custom post type, click on the editor's Options icon, select Preferences, click Panels, and enable the Custom Fields toggle.

This will refresh the editor, and you'll see the Custom Fields panel at the bottom of the screen.

Here you can add a new custom field, and give it a name and a value.

For example, if you wanted to add the ISBN number of a book, you could add a new custom field with the name `isbn`, and the value of the ISBN number.

This panel uses the `add_post_meta` function to add the metadata to the post.

## Pre-populated field names for the Custom Fields panel

It is also possible to populate the Name field of the Custom Fields panel with a list of predefined meta fields.

To do this, you need to hook into the `postmeta_form_keys` [filter](https://developer.wordpress.org/reference/hooks/postmeta_form_keys/), and add the names of the meta fields that you want to display in the Custom Fields panel.

The `postmeta_form_keys` filter is fired before the HTML of the Custom Fields panel is rendered, and passes in two parameters: an array of meta keys, and the post object.

If no other keys are defined, a query will be run to fetch the keys of any existing metadata for the post.

So by updating the array of meta keys, you can add meta field keys to the Custom Fields panel to make it easier for your users to add the correct metadata.

Here's an example of how you could do this:

```php
add_filter( 'postmeta_form_keys', 'bookstore_add_isbn_to_quick_edit', 10, 2 );
function bookstore_add_isbn_to_quick_edit( $keys, $post ) {
	if ( $post->post_type === 'book' ) {
		$keys[] = 'isbn';
	}
	return $keys;
}
```

You register a callback funtion onto the `postmeta_form_keys` filter, and set your accepted arguments to 2, to accept both arguments from the filter. 

Then, check if the post's type is a book, and if it is, add the `isbn` option to the `$keys` array, and then return that array.

If the post type is book, it will add the `isbn` meta field to the Custom Fields panel.

If you create or edit a book, with the Custom Fields panel enabled, you'll see the `isbn` field available to add metadata to the post.

## Custom Meta Boxes

Another way to allow site administrators to add post meta is to use custom meta boxes.

Working with custom meta boxes however also requires a good understanding of developing with security in mind, but for now you can read about them in the [Custom Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/) page in the Plugin developer handbook.

You will learn how to work with meta boxes in the plugin developer learning pathway.

## YouTube chapters

0:00 Introduction
0:23 What is post meta
0:57 Adding post meta
1:41 The Custom Fields panel
2:55 Pre-populated field names for the Custom Fields panel
4:22 Custom Meta Boxes

