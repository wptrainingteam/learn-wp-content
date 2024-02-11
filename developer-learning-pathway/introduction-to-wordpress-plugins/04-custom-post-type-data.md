# Using post meta

Sometimes, you might not need a custom post type, but you might need to store additional information about a post. 

Fortunately WordPress has a feature called post meta, which allows you to store additional information about a post.

Let's look at how you can use post meta on a post, or custom post type.

## What is post meta

Post meta is a way to store additional information about a post in the WordPress database. 

Post meta is stored in the `postmeta` table as key-value pairs. If you look at the `postmeta` table in the WordPress database, you'll see that it has four columns: `meta_id`, `post_id`, `meta_key`, and `meta_value`.

`post_id` is the ID of the post that the metadata is associated with. `meta_key` is the name of the metadata field, and `meta_value` is it's value.

## Adding post meta

You can add post meta to a post using the `add_post_meta` function. This function takes three parameters: the ID of the post, the name of the meta data, and the value of the meta data.

So lets say you wanted to add a post meta field to a post with the ID of 1, and you wanted to store the location where the post was written as `London`. 

You could use the following code:

```php
    add_post_meta( 1, 'location', 'London' );
```

Adding post meta via code is one way, but a better way would be to enable site administrators to add post meta via the WordPress admin interface.

## The Custom Fields panel

One way to do this is the Custom Fields panel, which is a feature of the WordPress admin interface that allows site administrators to add meta data to a post or custom post type.

To enable the Custom Fields panel, need to edit the settings for the edit view of the post type.

To do this, click on the editor's Options icon, select Preferences, click Panels, and enable the Custom Fields toggle.

This will refresh the editor, and you'll see the Custom Fields panel at the bottom of the screen.

Here you can add a new custom field, and give it a name and a value.

This panel uses the `add_post_meta` function to add the meta data to the post.

## Pre-populated field names for the Custom Fields panel

It is also possible to populate the Name field of the Custom Fields panel with a list of predefined meta fields.

To do this, you need to hook into the `postmeta_form_keys filter`, and add the names of the meta fields that you want to display in the Custom Fields panel.

The postmeta_form_keys filter is fired before the HTML of the Custom Fields panel is rendered, and passes in two parameters: an array of meta keys, and the post object.

If no other keys are defined, a query will be run to fetch the keys of any existing metadata for the post.

So by updating the array of meta keys, you can add meta field keys to the Custom Fields panel to make it easier for your users to add the correct meta data.

Here's an example of how you could do this:

```php
add_filter('postmeta_form_keys', 'bookstore_add_isbn_to_quick_edit', 10, 2);
function bookstore_add_isbn_to_quick_edit($keys, $post) {
	if ($post->post_type === 'book') {
		$keys[] = 'isbn';
	}
	return $keys;
}
```

In this case, if the post type is book, it will add the `isbn` meta field to the Custom Fields panel.

If you create or edit a book, with the Custom Fields panel enabled, you'll see the `isbn` field available to add meta data to the post.

Another way to allow site administrators to add post meta is to use custom meta boxes. 

Working with custom meta boxes however also requires a good understanding of developing with security in mind, but for now you can read about them in the [Custom Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/) page in the Plugin developer handbook.