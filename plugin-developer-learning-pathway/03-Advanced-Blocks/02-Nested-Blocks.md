# Nested Blocks

## Introduction

One of the benefits of developing blocks is the ability to nest blocks within other blocks. 

This allows you to support more complex user requirements by combining multiple blocks together.

Let's take a look at how nested blocks work, and how you can create them in your own block plugins.

## Nested blocks in the Comments block

The Comments block is a good example of a block that uses nested blocks. 

When you add a Comments block to a theme template in the Site Editor, you'll see that it automatically includes other blocks, the Comments Title block, Comments Template, Comments Pagination and Comments Form.

These blocks are all nested within the Comments block, and are displayed in the Site Editor as a single block.

## Creating nested blocks

Nested blocks are created by using the InnerBlocks component of the @wordpress/block-editor package.

The InnerBlocks component allows you to create a block that can contain other blocks.

Let's look at an example of how to use this component.

If you followed the "An introduction to developing WordPress blocks" module in the Beginner Developer Learning Pathway, you'll remember that you created a simple block called the Copyright Date block.

If you didn't follow that module, you can download the plugin archive for the Copyright Date block from the [Plugin Developer code examples repository](https://github.com/wptrainingteam/plugin-developer/blob/trunk/copyright-date-block.0.1.0.zip), and install it on your local WordPress site.

Once you've installed the plugin, navigate to the plugin's directory in your terminal and run `npm install` to install the necessary dependencies.

Now, open the `src/edit.js` file in your code editor, and take a look at the Edit component, specifically the markup rendered by the block.

```js
<p { ...useBlockProps() }>
	{ __(
		'Copyright',
		'copyright-date-block'
	) }
	© { startingYear } - { currentYear }
</p>
```

For this example, let\s update the block to use the InnerBlocks component to allow other blocks to be nested within it.

First you need to import the InnerBlocks component from the @wordpress/block-editor package. 

You can do this by simply adding it to the list of components being imported.

```js
import { InspectorControls, InnerBlocks, useBlockProps } from '@wordpress/block-editor';
```

Now, update the Edit component, replacing the translatable string with the InnerBlocks component.

```js
<p { ...useBlockProps() }>
	<InnerBlocks />
	© { startingYear } - { currentYear }
</p>
```

If you haven't already, start the development server by running `npm start` in the plugin directory.

Now add the Copyright Date block to a post or page. 

Notice how the block includes the text "Type / to choose a block" and a placeholder for the block that can be added, just above the date.

You can now add any number of blocks to the Copyright Date block, and they will be displayed within the block in the editor.

Note that you can only use the InnerBlocks component once in a single block.

## Allowing only specific blocks to be nested

By default, the InnerBlocks component allows any block to be nested within it. You can restrict the blocks that can be nested by using the allowedBlocks property of InnerBlocks.

This can be done in one of two ways.

You can pass an array of block names to the allowedBlocks property of the component, which will allow only the specified blocks to be nested within InnerBlocks.

```js
<InnerBlocks allowedBlocks={ [ 'core/paragraph', 'core/image' ] } />
```

Alternatively, you can specify this in the block settings, by using the allowedBlocks property of block metadata, for example via the block.json file

```json
  	"allowedBlocks": [
	  "core/paragraph",
	  "core/heading"
	],
```

## Orientation

## Setting a default block

## Block template
