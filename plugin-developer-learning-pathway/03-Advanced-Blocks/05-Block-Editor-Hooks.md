# Block Editor Hooks

Hooks are one of the fundamental features of WordPress. They allow you to "hook" into the WordPress core, and execute your own code at specific points in the WordPress lifecycle.

The Block Editor has its own set of hooks that allow you to interact with the editor, and modify its behavior. 

This lesson will introduce you to some different hooks available in the Block Editor, and how to use them to extend the editor's functionality.

## The @wordpress/hooks package

While action and filter hooks have historically only been available in PHP, with the fact that the Block Editor is built using JavScript, there are now also a new set of JavaScript hooks. 

These JavaScript action and filter hooks allow you to hook into different aspects of the editor, and make changes to the editor's behavior or the blocks themselves.

JavaScript hooks are registered using the `addAction` and `addFilter` functions of the `@wordpress/hooks` package.

Similar to the PHP versions, you can pass the hook name and the callback function to these functions. 

The one notable difference is that you also have to pass the namespace of the hook.

Let's create an example to demonstrate how to use a JavaScript hook.

## The registerBlockType filter

For all the block examples you've seen so far, you've used the `registerBlockType` function to register a block in JavaScript. Every block starts by registering a new block type definition using this function.

The `registerBlockType` function has a filter hook of the same name that allows you to modify the block settings before the block is registered.

To register a callback function to the `registerBlockType` filter, you use the `addFilter` function from the `@wordpress/hooks` package.

To demonstrate how to use the `registerBlockType` filter, let's create a plugin to enqueue a javascript file, to register the filter.

Start by creating a directory in your `wp-content/plugins` directory to store the block hooks code.

```bash
mkdir -p wp-content/plugins/wp-learn-block-hooks
```

Create the main plugin PHP file, `wp-learn-block-hooks.php` in the newly created directory.

Then add the following code to set up the plugin header, and make sure the plugin code only runs in the WordPress environment:

```php
<?php
/**
 * Plugin Name:       WP Learn Block Hooks
 * Description:       WP Learn Block Hooks
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
add_action( 'enqueue_block_editor_assets', 'wp_learn_block_hooks_editor_assets' );
function wp_learn_block_hooks_editor_assets() {
	wp_enqueue_script(
		'wp-learn-block-hooks-editor-script',
		plugin_dir_url( __FILE__ ) . 'block-hooks.js',
		array(
		    'wp-blocks',
        ),
	);
}
```

Notice that you need to specify the `wp-blocks` dependency to make sure your block variation code only loads once the @wordpress/blocks package is available. This is because you're going to use the `wp.blocks.registerBlockType` function to register the block variation.

Now, create a `block-hooks.js` file in the plugin directory and start with an IIFE (Immediately Invoked Function Expression) to prevent any variables from this code leaking into the global scope:

```js
( function(){
    
} )()
```

Next, use the `addFilter` function to register a callback on the `registerBlockType` filter hook:

```js
( function(){
    wp.hooks.addFilter(
        'blocks.registerBlockType',
        'wp-learn-block-hooks/list-block-description',
        addListBlockDescription
    );
} )()
```

Notice how the namespace is specified, before the callback function `addListBlockDescription`.

Now, let's create the `addListBlockDescription` callback function, to do something:

```js
    function addListBlockDescription( settings, name ) {
        if ( name === 'core/list' ) {
            settings.description = 'This is a list block';
        }
        return settings;
    }
```

The `addListBlockDescription` callback function receives the settings object and name of the block as parameters. 

It then checks if the block being registered is a list block, and if it is, it changes the description property of the block's settings object.


