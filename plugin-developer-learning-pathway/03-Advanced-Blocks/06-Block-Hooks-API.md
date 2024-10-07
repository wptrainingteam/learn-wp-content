# Block Hooks API

Not to be confused with Block Editor action and filter hooks, the Block editor also has something called the Block Hooks API

This API provides a way to automatically insert your block next to all instances of a specific block in block-based templates, template parts, and patterns of a WordPress block theme.

In this lesson, you'll learn about the specific requirements needed in order to use the Block Hooks API, and how to use it to automatically insert a block next to all instances of the target block.

## Requirements

In order to use the Block Hooks API, you need to create a dynamic block, not a static one.

A [dynamic block](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/) is one where the block's structure and functionality is built on the fly using PHP when the block is rendered on the front end.

In the lesson on [Static vs Dynamic Blocks](https://learn.wordpress.org/lesson/static-vs-dynamic-blocks/), you learned how to convert a static block to a dynamic one, by creating a `render.php` file to generate the block's output when a front-end request is made for a post or page that includes the block.

This `render.php` file is configured in the block metadata in the `block.json` file, and the block itself doesn't therefore need a `save` function.

If you don't have a dynamic block handy, you can download it from the WordPress Training Team's [GitHub repository](https://github.com/wptrainingteam/plugin-developer/blob/trunk/dynamic-copyright-date-block.1.0.0.zip).

Once you have the block installed, navigate to the block directory, install the dependencies, and build the block:

```
cd dynamic-copyright-date-block
npm install
npm run build
```

Once the plugin is active you be able to add the block to a post or page, that displays a Copyright text and date wherever you need it.

You will see that the block code contains a `render.php` file in the `src` directory, which handles the block's output.

## Registering a Block Hook

To register a block hook you set the `blockHooks` property on the block metadata. This can be done in a few ways, but for the Dynamic Copyright Block it can be added to the `block.json` file.

Open the `src/block.json` file in the `dynamic-copyright-date-block` directory, and add the following code to the block metadata:

```
"blockHooks": {
      "core/post-content": "after"
    },
```

This code registers a block hook for this block that will insert the block after all instances of the core Post Content block. In this example, the Post Content block is the target block.

It uses a JSON object with the block name as the key, and the position as the value.

With this in place, run the build step again

Then browse to any post or page on the front end.

You should see the Dynamic Copyright Date block automatically inserted after the core Post Content block.

## Multiple Block Hooks and Block Hook positions

You can add multiple block hooks to a target block by adding more key-value pairs to the `blockHooks` object.

For example, to add a block before the core Post Content block, you can add another key-value pair to the `blockHooks` object:

```
"blockHooks": {
      "core/post-content": "after",
      "core/post-content": "before"
    },
```

You'll notice that the value for the second key-value pair is `before`, which is the another of the possible positions you can set for a block hook.

There are a total of four positions you can set for a block hook:

- `before` – inject before the target block.
- `after` – inject after the target block.
- `firstChild` – inject before the first inner block of the target container block.
- `lastChild` – inject after the last inner block of the target container block.


The `firstChild` and `lastChild` positions are useful when the target block contains multiple inner blocks, for example, the Query Loop block.

## Alternative ways to register a block hook

Creating a block hook is not just limited to setting it in the `block.json` file.

You can also hook into the `block_type_metadata` [filter](https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#block_type_metadata) to add a block hook to a block's metadata using PHP.

```
add_filter( 'block_type_metadata', 'dynamic_block_type_metadata' );
function dynamic_block_type_metadata( $metadata ) {
	if ('dynamic-copyright-date/copyright-date-block' !== $metadata['name']) {
		return $metadata;
	}
	$metadata['blockHooks'] = [
		'core/post-content' => 'after',
	];
	return $metadata;
};
```

Here, the `dynamic_block_type_metadata` callback function accepts the block metadata array as an argument, and checks if the name property is the one you want to add the block hook to.

If it is, it adds the block hook to the block metadata.

This is useful if you want to create a block hook for a block that is not part of your plugin.

There are other filters, like the `hooked_block_types` [filter](https://developer.wordpress.org/reference/hooks/hooked_block_types/) and `hooked_block` [filter](https://developer.wordpress.org/reference/hooks/hooked_block/) that allow you to modify the block hook settings. There's even a variant of the hooked_block filter that allows you to [modify the block hook settings for a specific block](https://developer.wordpress.org/reference/hooks/hooked_block_hooked_block_type/).

## Block hook limitations

It's important to note that these hooked blocks will only be implemented at the theme level of a WordPress site. So the hooked blocks are only inserted into templates, template parts, and patterns.

For example, if you edit a post or page, you'll notice that the hooked block doesn't appear in the content area, but it does when you preview or view the content on the front end.

Additionally, as discussed, block hooks only work for dynamic blocks, not static blocks. This is because the blocks save function will not have been called when the block is inserted, meaning the block's output will not be available to be inserted into the content.

## Further reading

There's a short introduction to the Block Hooks API in the *Metadata in block.json reference guide* if scroll down to the [Block Hooks section](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#block-hooks).

The official documentation for how to use Block Hooks can be found in the Block [Registration](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/) reference guide, under *blockHooks*.

Another great resource is the developer blog post titled [Exploring the Block Hooks API in WordPress 6.5](https://developer.wordpress.org/news/2024/03/25/exploring-the-block-hooks-api-in-wordpress-6-5/), which provides an overview of the Block Hooks API and how to use it.

