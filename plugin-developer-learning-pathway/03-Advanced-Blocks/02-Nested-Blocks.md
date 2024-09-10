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

The InnerBlocks component allows you to define a block that can contain other blocks.

Let's take a look at what this might look like.

If you followed the "An introduction to developing WordPress blocks" module in the Beginner Developer Learning Pathway, you'll remember that we created a simple block called the Copyright Date block.

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

Now, update that markup to replace the Copyright text with an InnerBlocks component.

```js
<p { ...useBlockProps() }>
	<InnerBlocks />
	© { startingYear } - { currentYear }
</p>
```

If you haven't already, start the development server by running `npm start` in the plugin directory.

Now, when you add the Copyright Date block to a post or page.