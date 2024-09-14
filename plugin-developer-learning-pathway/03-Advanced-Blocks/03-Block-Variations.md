# Block Variations

When planning to create a block, one of the things to consider is whether the block needs to be built from scratch, or could it simply be an extension of an existing block.

This lesson introduces the concept of block variations, which are a way to create different versions of an existing block.

## What are Block Variations?

Block Variations are a way to create iterations of existing blocks without building entirely new blocks from scratch.

A block variation differs from the original block by a set of initial attributes or inner blocks. 

When you insert the block variation into the Editor, these attributes and/or inner blocks are applied.

Let's start by creating a block variation that applies a set of initial attributes to the core Heading block.

## Creating a Block Variation with initial attributes

To create a block variation, you need a way to enqueue a JavaScript file when the editor loads.

This can be achieved using the `enqueue_block_editor_assets` [action hook](https://developer.wordpress.org/reference/hooks/enqueue_block_editor_assets/).

This hook works in the same way as the `wp_enqueue_scripts` you might have learned about in the Enqueuing CSS or JavaScript lesson of the Beginner Developer Learning Pathway.

The difference is that `enqueue_block_editor_assets` is specifically for enqueuing assets when the block editor loads.

Then you're going to use the `registerBlockVariation` [function](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-blocks/#registerblockvariation) of the @wordpress/blocks package to register the block variation.

## Building the Custom Heading Block Variation

Start by creating a directory in your `wp-content/plugins` directory to store the block variation code.

```bash
mkdir -p wp-content/plugins/wp-learn-block-variations
```

Create the main plugin PHP file, `wp-learn-block-variations.php` in the newly created directory.

Then add the following code to set up the plugin header, and make sure the plugin code only runs in the WordPress environment:

```php
<?php
/**
 * Plugin Name:       WP Learn Block Variations
 * Description:       WP Learn Block Variations
 * Requires at least: 6.6
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-learn-block-variations
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
```

Next, you can hook a callback function to the `enqueue_block_editor_assets` action to enqueue the JavaScript file.

```php
add_action( 'enqueue_block_editor_assets', 'wp_learn_block_variations_editor_assets' );
function wp_learn_block_variations_editor_assets() {
	wp_enqueue_script(
		'wp-learn-block-variations-editor-script',
		plugin_dir_url( __FILE__ ) . 'block-variations.js',
		array(
		    'wp-blocks',
        ),
	);
}
```

Notice that you need to specify the `wp-blocks` dependency to make sure your block variation code only loads once the @wordpress/blocks package is available. This is because you're going to use the `wp.blocks.registerBlockVariation` function to register the block variation.

Now, create a `block-variations.js` file in the plugin directory and add the following code:

```js
(function(){
	wp.blocks.registerBlockVariation( 'core/heading', {
		name: 'wp-learn-block-variations/custom-heading',
		title: 'Custom Heading',
		attributes: {
			content: 'Custom Heading',
		}
	} )
})()
```

This code registers a block variation of the core Heading block. It sets the `name` to `wp-learn-block-variations/custom-heading`, the `title` to `Custom Heading`, and in the `attributes` object, sets the content of the heading to `Custom Heading`.

Now you can activate the plugin. 

Edit a post or page, and insert the Custom Heading block, either by clicking the block inserter icon and searching for custom or typing `/custom` in the Editor.

When you insert the Custom Heading block into the Editor, you should see that it defaults to having a value of `Custom Heading` but it retains all the other core heading block attributes and functionality.

## Creating a Block Variation with InnerBlocks

As discussed, block variations can also include inner blocks using the InnerBlocks component. This is useful if you want to extend an existing block to include additional blocks inside it, or to change the default InnerBlocks.

For example, the Query Loop block is a block that displays a list of posts. The blocks actual functionality is [determined by the InnerBlocks it contains](https://github.com/WordPress/gutenberg/blob/trunk/packages/block-library/src/query/index.js).

It contains a `post-template` block with its own set of attributes, and the `post-template` block uses InnerBlocks to display each post's content, by including the `post-title`, `post-excerpt`, and `post-date` blocks.

To create a block variation with InnerBlocks, you can use the same `wp.blocks.registerBlockVariation` function, but this time you need to include the `innerBlocks` property.

Let's add a block variation of the core Query Loop block that includes a paragraph block after the post-template, with some default content.

Just under the Custom Header block variation code in the `block-variations.js` file, add the following code:

```js
	wp.blocks.registerBlockVariation( 'core/query', {
		name: 'wp-learn-block-variations/custom-query',
		title: 'Custom Query',
		innerBlocks: [
			{
				name: 'core/post-template',
				attributes: {
					layout: {
						type: 'grid',
						columnCount: 2,
					},
				},
				innerBlocks: [
					{
						name: 'core/post-title',
					},
					{
						name: 'core/post-date',
					},
					{
						name: 'core/post-excerpt',
					},
				],
			},
			{
				name: 'core/paragraph',
				attributes: {
					content: 'Custom Query Block',
				},
			}
		],
	} );
```

You'll notice that the `innerBlocks` property is the same array of blocks that are defined in the core Query Loop. However, after the post-template block, you've added a paragraph block with the content `Custom Query Block`.

Now, insert the Custom Query block in a post or page, either by clicking the block inserter icon and searching for custom or typing `/custom` in the Editor.

When you insert the Custom Query block into the Editor, you should see that it defaults to the set of innerBlocks you defined, including the paragraph block with the content `Custom Query Block`.

## Block variation vs Custom Block

Block variations are a powerful way to extend existing blocks without having to build entirely new blocks from scratch. Therefore, if you have a need for a block that is similar to an existing block, but with some additional functionality or different default attributes, block variations are a good way to achieve this. 

However, if your block requirements are significantly different from an existing block, you may need to build a custom block from scratch.

## Further Reading

This lesson only scratches the surface of what's possible with block variations. 

For more information on block variations, see the [Block Variations](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-variations/) page of the Block API reference guide in the Block Editor handbook. 

It would also be a good idea to work through the [Extending the Query Loop Block](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/extending-the-query-loop-block/) tutorial in the Block Editor handbook to see how block variations can be used to extend the Query Loop block even further.