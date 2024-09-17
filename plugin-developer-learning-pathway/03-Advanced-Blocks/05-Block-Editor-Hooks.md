# Block Editor Hooks

Hooks are one of the fundamental features of WordPress. They allow you to "hook" into the WordPress core, and execute your own code at specific points in the WordPress lifecycle.

The Block Editor has its own set of hooks that allow you to interact with the editor, and modify its behavior. 

This lesson will introduce you to some different hooks available in the Block Editor, and how to use them to extend the editor's functionality.

## The @wordpress/hooks package

While action and filter hooks have historically only been available in PHP, with the fact that the Block Editor is built using JavScript, there are now also a new set of JavaScript hooks. 

These JavaScript action and filter hooks allow you to hook into different aspects of the editor, and make changes to the editor's behavior or the blocks themselves.

JavaScript hooks are registered using the `addAction` and `addFilter` functions of the `@wordpress/hooks` [package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-hooks/).

Similar to the PHP versions, you pass the hook name and the callback function to these functions. 

The one notable difference is that you also have to pass the namespace of the hook. The namespace is a unique identifier for the callback function, and it helps to prevent naming collision conflicts.

Let's look at an example to demonstrate how to use a JavaScript hook.

## The blocks.registerBlockType filter

For all the block examples you've seen so far, you've used the `registerBlockType` function from the `@wordpress/blocks` package to register a block in JavaScript. 

Every block starts by registering a new block type definition using this function.

```js
/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * @see ./save.js
	 */
	save,
} );
```

When the code that [processes all registered block's runs](https://github.com/WordPress/gutenberg/blob/f82bc32212f2708851bd10e3c2d803994cd3cb3c/packages/blocks/src/store/process-block-type.js#L105), any callback functions hooked into the `blocks.registerBlockType` filter hook are run, and should return a modified block settings object.

```
    const settings = applyFilters(
        'blocks.registerBlockType',
        blockType,
        name,
        null
    );
```

This filter hook allows you to modify the block settings object before the block is registered.

To register a callback function to the `blocks.registerBlockType` filter, you use the `addFilter` function from the `@wordpress/hooks` package.

Let's create a plugin to enqueue a JavaScript file, to register the filter. If you've already completed the Block Variations lesson, the code is very similar.

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
 * Text Domain:       wp-learn-block-hooks
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
		    'wp-hooks',
        ),
	);
}
```

Notice that you need to specify the `wp-hooks` dependency to make sure your block variation code only loads once the `@wordpress/hooks` package is available. This is because you're going to use the `addFilter` function from the `@wordpress/hooks` package in the JavaScript file.

Now, create a `block-hooks.js` file in the plugin directory and start with an IIFE (Immediately Invoked Function Expression) to prevent any variables from this code leaking into the global scope:

```js
( function(){
    
} )()
```

Next, use the `addFilter` function to register a callback on the `blocks.registerBlockType` filter hook:

```js
( function(){
    wp.hooks.addFilter(
        'blocks.registerBlockType',
        'wp-learn-block-hooks/list-block-description',
        addListBlockDescription
    );
} )()
```

Notice how you specify a unique namespac, before the callback function `addListBlockDescription`. The namespace is defined by the developer, and should be unique to your plugin.

Now, let's create the `addListBlockDescription` callback function, to do something:

```js
    function addListBlockDescription( settings, name ) {
        if ( name === 'core/list' ) {
            settings.description = 'This is a list block';
        }
        return settings;
    }
```

Here, the `addListBlockDescription` callback function receives the block's settings object and name of the block as parameters. 

It then checks if the block being registered is a list block, and if it is, it changes the description property of the block's settings object.

Finally, because this is a filter, it needs to return something, in this case it needs to return the modified settings object.

To test this, don't activate the plugin yet, but instead, edit a post or page, and search for the list block from the block inserter.

If you hover over the list block, you should see the description "Create a bulleted or numbered list" displayed.

Now, activate the plugin

With the plugin activated, you should see the list block description change to "This is a list block".

## Block Editor Hooks documentation

The Block Editor handbook has a section dedicated to [Block Editor Hooks](https://developer.wordpress.org/block-editor/reference-guides/filters/), which contains different pages for the different types of hooks available.

The [Block Filters](https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters) page contains a list of all the filters available that allow you to modify the behavior of existing blocks, with section modifying blocks during block registration, modifying blocks on the front end, changing the behaviour of blocks in the Block Editor, removing blocks, hiding blocks, and managing block categories.

[Editor Hooks](https://developer.wordpress.org/block-editor/reference-guides/filters/editor-filters/) documents all the hooks that allow you to modify the editor experience, including modifying the editor settings, disabling the block directory and pattern directory features, extending Editor features, controlling REST API data for the editor, and logging Editor errors.

The [i18n Filters](https://developer.wordpress.org/block-editor/reference-guides/filters/i18n-filters/) page contains a list of filters that allow you to modify the internationalization functions used in the editor.

[Parser Filters](https://developer.wordpress.org/block-editor/reference-guides/filters/parser-filters/) documents all the filters that allow you to modify the block parser, which is used to convert the block data stored in memory into the block markup that is stored in the post content in the database.

[Autocomplete](https://developer.wordpress.org/block-editor/reference-guides/components/autocomplete/) is a component in the @wordpress/block-editor package that provides a way to add autocompleters to the editor. 

If you've ever used the @username functionally in the block editor, this is powered by an Autocomplete component. 

The [Autocomplete](https://developer.wordpress.org/block-editor/reference-guides/filters/autocomplete-filters/) filters page documents how you can add your own autocompleters to the editor.

Finally, the Global Styles Filters page documents the filters added to WordPress in version 6.1 that allow developers to modify the global settings and styles of a block theme as defined by the `theme.json` file.

All of these pages include detailed examples of how to use these hooks, and how they can be used to extend the Block Editor's functionality.