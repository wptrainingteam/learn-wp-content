# Block Bindings

Block Bindings are a new feature in WordPress that provide a way to connect a post type's custom fields to WordPress Core blocks.

With Block Bindings, as long as you register your custom fields correctly, theme developers can connect them to core blocks in theme templates. 

Let's look at what's required to use Block Bindings, and how to connect core blocks to custom fields in the editor.

## Registering custom fields

To start, create a plugin which will register a book custom post type.

```php
<?php
/**
 * Plugin Name: WP Learn Post Meta
 * Description: A plugin to demonstrate adding post meta in WordPress.
 * Version: 1.0.0
 * License: GPL2
 *
 * @package WP_Learn_Post_Meta
 */

add_action( 'init', 'wp_learn_register_book_post_type' );

function wp_learn_register_book_post_type() {
	$args = array(
		'labels'       => array(
			'name'          => 'Books',
			'singular_name' => 'Book',
			'menu_name'     => 'Books',
			'add_new'       => 'Add New Book',
			'add_new_item'  => 'Add New Book',
			'new_item'      => 'New Book',
			'edit_item'     => 'Edit Book',
			'view_item'     => 'View Book',
			'all_items'     => 'All Books',
		),
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'rest_base'    => 'books',
		'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
	);

	register_post_type( 'book', $args );
}
```

Now, add a custom field to the book post type for the ISBN field.

```php
	register_meta(
        'post',
        'isbn',
        array(
            'single'         => true,
            'type'           => 'string',
            'default'        => '',
            'show_in_rest'   => true,
            'object_subtype' => 'book',
        )
    );
```

This code uses the `register_meta()` function to register a custom field for the book post type. 

The `show_in_rest` parameter is set to `true` to make the custom field available in the REST API.

You could also use the `register_post_meta()` function, which is a wrapper for `register_meta()` but with a slightly simpler set of parameters.

```php
    register_post_meta(
		'book',
		'isbn',
		array(
			'single'       => true,
			'type'         => 'string',
			'default'      => '',
			'show_in_rest' => true,
		)
	);
```

Either way, with your post meta registered, it will be available to be bound to one of the supported blocks.

To make life easier when adding your books, use the `postmeta_form_keys` filter to add the `isbn` field to the Custom Fields panel in the WordPress admin.

```php
add_filter( 'postmeta_form_keys', 'bookstore_add_isbn_to_quick_edit', 10, 2 );
function bookstore_add_isbn_to_quick_edit( $keys, $post ) {
	if ( $post->post_type === 'book' ) {
		$keys[] = 'isbn';
	}
	return $keys;
}
```

With this plugin installed and active, you can add a book, and add the ISBN value to your book in the Custom Fields panel.

## Block Bindings API

The [Block Bindings API was first introduced in WordPress 6.5](https://make.wordpress.org/core/2024/03/06/new-feature-the-block-bindings-api/), and provided a way to connect custom fields on a post or post type to specific core blocks.

Initially the API supports the following core blocks, with a specific set of supported attributes per block:

- Image: url, alt, title attributes
- Paragraph: content attribute
- Heading: content attribute
- Button: url, text, linkTarget, rel attributes

Binding these core blocks to custom fields required theme developers to hardcode the bindings in their theme templates.

```html
<!-- wp:paragraph {
	"metadata":{
		"bindings":{
			"content":{
				"source":"core/post-meta",
				"args":{
					"key":"isbn"
				}
			}
		}
	}
} -->
<p></p>
<!-- /wp:paragraph -->
```

## Block Bindings UI 

In WordPress 6.7, [the Block Bindings UI was introduced](https://make.wordpress.org/core/2024/10/21/block-bindings-improvements-to-the-editor-experience-in-6-7/) to provide a way to connect custom fields to core blocks in the block editor. This meant that theme developers no longer needed to hardcode the bindings in their theme templates.

## Adding custom fields 

To start, make sure you have a block theme installed. In this example, we're using the Twenty Twenty-Five theme.

Open the Site Editor, and add a new template for all books.

Next, add a paragraph somewhere for the ISBN number. In this example we'll add a row block below the title, with two paragraph blocks. The first paragraph block will display the word `ISBN:`, and the second paragraph will be bound to the `isbn` custom field.

If you open the settings panel for the second paragraph block, you will see an Attributes panel. Click on the `+` Attributes options button, and select the supported `content` attribute.

Next, click on the content attribute in the Attributes panel, and you will see that you can select the source of the content, in this case `isbn`.

In the template, the post meta key `isbn` appears, indicating that this is the custom field that the paragraph block is bound to.

Save the template, and navigate to your list of books.

Select any book, and click to view it on the front end.

You will see that the new book template is loaded, and the ISBN field is pulled from the custom field and displayed in the paragraph block.

## Further reading

For more information in this new WordPress feature, take a look at the following two posts on the WordPress core development blog:
- [New Feature: The Block Bindings API](https://make.wordpress.org/core/2024/03/06/new-feature-the-block-bindings-api/)
- [Block Bindings: Improvements to the Editor Experience in 6.7](https://make.wordpress.org/core/2024/10/21/block-bindings-improvements-to-the-editor-experience-in-6-7/)