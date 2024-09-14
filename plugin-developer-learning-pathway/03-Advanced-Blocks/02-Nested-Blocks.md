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

Nested blocks are created by using the `InnerBlocks` [component](https://github.com/WordPress/gutenberg/blob/33e2fadeebe32a64a946106488120b6f559d3deb/packages/block-editor/src/components/inner-blocks/README.md) of the `@wordpress/block-editor` package.

The `InnerBlocks` component allows you to create a block that can contain other blocks.

Let's look at an example of how to use this component.

If you followed the "An introduction to developing WordPress blocks" module in the Beginner Developer Learning Pathway, you will have installed `node.js` and `npm`, and used `create-block` to scaffold a new block plugin.

If you didn't follow that module, or you don't have the required software installed, please follow the [Setting up your block development environment](https://learn.wordpress.org/lesson/setting-up-your-block-development-environment/) lesson for all the details.

Either way, start by opening your terminal, switching to the `plugins` directory of a local WordPress install, and scaffolding a new block plugin using `create-block`.

```bash
cd /path/to/wordpress/wp-content/plugins
npx @wordpress/create-block@latest wp-learn-inner-blocks
```

This will scaffold your new block code.

Before you continue, the scaffolded block code includes some default background and color styles that you may want to remove. You can do this by updating the `src/style.scss` file, and either commenting out or removing the `background-color` and `color` styles.

```scss
.wp-block-create-block-wp-learn-inner-blocks {
  //background-color: #21759b;
  //color: #fff;
  padding: 2px;
}
```

Now, open the `src/edit.js` file.

To make use of the `InnerBlocks` component, you need to import it from the `@wordpress/block-editor` package. So start by updating the list of components you're importing at the top of the file.

```js
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
```

Next, update the JSX that the `Edit` component is returning, changing the top level block wrapper element to a `div`, and adding the `InnerBlocks` component after the text.

```js
<div { ...useBlockProps() }>
	{ __(
		'Wp Learn Inner Blocks – hello from the editor!',
		'wp-learn-inner-blocks'
	) }
    <InnerBlocks />
</div>
```

If you haven't already, start the development server by running `npm start` from the terminal in the `wp-learn-inner-blocks` plugin directory.

```js
cd wp-learn-inner-blocks
npm start
```

Then, activate the plugin in the WordPress admin, and add the block to a post or page.

Notice how this block includes the text "Type / to choose a block" and a placeholder for a block that can be added, just below the text.

You can now add any number of blocks to your `InnerBlocks` block, and they will be displayed in the block in the editor.

## Block save

With the `InnerBlocks` component, you can also define the block's output in the save function.

To do this, you can return `InnerBlocks.Content` in your save function. This will automatically be replaced with the content of the nested blocks when the blocks save function is called.

Open the `src/save.js` file, and update the `save` function to return the `InnerBlocks.Content` component.

First, again, you need to import the `InnerBlocks` component.

```js
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
```

Then, update the `save` function to also use a div and return `InnerBlocks.Content`.

```js
<div { ...useBlockProps.save() }>
	{ 'Wp Learn Inner Blocks – hello from the saved content!' }
	<InnerBlocks.Content />
</div>
```

Now, when edit and save the post or page, the content of the nested blocks will be saved and displayed on the front end.

## Allowing only specific blocks to be nested

By default, the `InnerBlocks` component allows any registered block to be added to it. You can restrict the blocks that can be added by using the `allowedBlock`s property of `InnerBlocks`.

This can be done in one of two ways.

You can pass an array of block names to the `allowedBlocks` property of the component, which will allow only the specified blocks to be nested within `InnerBlocks`.

```js
<InnerBlocks allowedBlocks={ [ 'core/heading', 'core/paragraph' ] } />
```

Alternatively, you can specify this in the block settings, by using the `allowedBlocks` property of block metadata, for example via the `block.json` file. 

```json
  	"allowedBlocks": [
      "core/heading",
	  "core/paragraph"
	],
```

Either way, by specifying the allowed blocks, you can control which blocks can be added to your block.

Your requirements will determine which method you use.

Using the `allowedBlocks` property of the `InnerBlocks` component is generally used when you want the allowed blocks to be changed dynamically, based on the block's attributes.

Using the `allowedBlocks` property of the block metadata is generally used when you want to restrict the allowed blocks to a fixed set of blocks that doesn't change.

## Block template

One of the main features of the `InnerBlocks` component is the ability to define a template for the block.

This allows you to define a set of blocks that are automatically added to the block when it is first inserted into the editor.

To do this, you can use the template property of `InnerBlocks`, which accepts an array of block items.

Each block items requires the name of the block and an object that specifies the attributes each block.

For example, to define a template that includes an image, heading, and paragraph block, you can use the following code:

```js
<InnerBlocks
	template={ [
		[ 'core/image', {} ],
		[ 'core/heading', { placeholder: 'Book Title' } ],
		[ 'core/paragraph', { placeholder: 'Summary' } ],
	] }
/>
```

Update your `InnerBlocks` component to include this template, and when you add the block to a post or page, the specified blocks will be automatically added to your block.

## Setting a default block

It is also possible to set the default block that is added to the `InnerBlocks` component when a user clicks on the block inserter, by using the `defaultBlock` and `directInsert` properties of `InnerBlocks`.

`defaultBlock` accepts an object that has a `name` property (the name of the block) and an `attributes` property (the attributes of the block). `directInsert` must be set to true to enable this feature.

```js
<InnerBlocks
    defaultBlock={
        { name: 'core/paragraph', attributes: { content: 'Lorem ipsum...' } }
    }
    directInsert={true}
/>
```

For example, this will automatically add a paragraph block with the text "Lorem ipsum..." when a user clicks the block inserter.

## More examples and further reading

For more examples of how to use the `InnerBlocks` component, and further reading, see the [Guide on Nested Blocks](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/nested-blocks-inner-blocks/) in the Block Editor handbook.

You can also find the full documentation for the `InnerBlocks` component in the [package reference for the @wordpress/block-editor](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#innerblocks) which links to the [documenation for the InnerBlocks component](https://github.com/WordPress/gutenberg/blob/HEAD/packages/block-editor/src/components/inner-blocks/README.md) in the Gutenberg code repository on GitHub.
