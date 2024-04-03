# Building your first block

## Introduction

Once you've scaffolded your block using `create-block`, you can start tweaking the code to meet your requirements. 

Let's dive into what this might look like for the Copyright Date Block you scaffolded in the previous lesson.

## Clean up

To start, you can clean up any scaffolded code you don't need. 

Navigate to the `copyright-date-block/src` directory and remove the `view.js` file. At the same time, remove the `viewScript` property from the block.json. Make sure not to leave a trailing comma at the end of the last property in the block.json file.

## The main plugin file

Now open the `copyright-date-block.php` file in the root of the plugin directory. 

There's not a lot to be changed in this file, except maybe the `@package` annotation in the plugin header. This defaults to `create-block`, and you might want to change it to something more specific to your plugin. 

For the purposes of this lesson, let's change it to `copyright-date`.

You can also improve the code that registers the block, by moving the hook registration above the `init` hook's callback, and simplifying the callback function name. 

```php
add_action( 'init', 'copyright_date_copyright_date_block_block_init' );
function copyright_date_copyright_date_block_block_init() {
	register_block_type( __DIR__ . '/build' );
}
```

This makes this code a little easier to read and understand.

As you can see the code uses the `register_block_type` function to register the block, which use the metadata from the `block.json` file.

Therefore, now would be a good time to review the `block.json` file.

## Block Metadata

The `block.json` file contains the block metadata, in a JSON format.

```json
{
	"$schema": "https://schemas.wp.org/trunk/block.json",
	"apiVersion": 3,
	"name": "create-block/copyright-date-block",
	"version": "0.1.0",
	"title": "Copyright Date Block",
	"category": "widgets",
	"icon": "smiley",
	"description": "Example block scaffolded with Create Block tool.",
	"example": {},
	"supports": {
		"html": false
	},
	"textdomain": "copyright-date-block",
	"editorScript": "file:./index.js",
	"editorStyle": "file:./index.css",
	"style": "file:./style-index.css"
}

```

JSON stands for [JavaScript Object Notation](https://developer.mozilla.org/en-US/docs/Learn/JavaScript/Objects/JSON), and it's a lightweight data format that is easy for humans to read and write and easy for machines to parse and generate.

JSON is made up of key-value pairs, and each value can also be a nested JSON object.

To modify the scaffolded block metadata for your block, you should change at least the values of the following properties:
 - update the `name`. In this case you can replace `create-block` with the same value you used for the package value in the plugin header, `copyright-date`.
 - update the `icon`. For now, change the `icon` value to `calendar`. This icon comes from the [Gutenberg Icon Library](https://wordpress.github.io/gutenberg/?path=/story/icons-icon--library).
 - update the `description`, to make it more specific to your block.

Your `block.json` file should look something like this:

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "copyright-date/copyright-date-block",
  "version": "0.1.0",
  "title": "Copyright Date Block",
  "category": "widgets",
  "icon": "calendar",
  "description": "A Copyright Date block.",
  "example": {},
  "supports": {
    "html": false
  },
  "textdomain": "copyright-date-block",
  "editorScript": "file:./index.js",
  "editorStyle": "file:./index.css",
  "style": "file:./style-index.css"
}
```

## The block's main JavaScript file

The `index.js` file in the `src` directory is the main JavaScript file for your block. Mostly you won't need to change much in this file, as it's already set up to register your block for the block editor.

At the top of the file you'll see the following line:

```js
import { registerBlockType } from '@wordpress/blocks';
```

The JavaScript [import](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import) declaration is used to import functionality (variables, functions, objects) from somewhere else.

In this case, the code is importing the `registerBlockType` function from the `@wordpress/blocks` package, which is the package that powers the Block Editor.

Next it imports the `style.scss` file, which is the file that contains the block's styles. 

Then it imports the `Edit` component. This component is exported from the `edit.js` file.

After that it imports the `save` function. This function is exported from the `save.js` file.

Last but not least, it imports the JSON object from the `block.json` file as the `metadata` variable. 

Finally, it uses the `registerBlockType` function to register the block, and passes in two variables, the block name, and a block configuration object of block properties. 

Inside the properties object, the `edit` property is set to the value of the `Edit` component, and the `save` property is set to the value of the `save` function. Because the `save` property and the imported `save` function have the same name, it uses a shorthand syntax to set the property value.

## Your first build

Now that you've updated some block code, you can build your block for the first time.

To build your block, open a terminal and navigate to the root of your block plugin directory. Then run the following command:

```bash
npm run build
```

This will scan through the contents of your `src` directory, and compile the files from that directory into the `build` directory. 

If the `build` directory doesn't exist, it will be created. 

Whenever you make changes to your block code, you'll need to run this command again to update the build directory.

Optionally, there is a `npm run start` command that will start a development server that watches for changes to the files in the `src` directory and automatically builds them into the `build` directory. 

```bash
npm run start
```

This is useful for when you're actively developing your block.

Whichever option you use, if you open your WordPress dashboard, create a new Post, and add your block, you should see the block in the block inserter.

You'll notice that the icon has changed, and the description is more specific to your block.

## Additional resources

You can read more the block metadata fields in the [Metadata in block.json](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/) section of the Block Editor Handbook, as well as the development platform and build processes in the [Development platform](https://developer.wordpress.org/block-editor/how-to-guides/platform/) and the [Working with Javascript for the Block Editor](https://developer.wordpress.org/block-editor/getting-started/fundamentals/javascript-in-the-block-editor/) pages.

## YouTube chapters

0:00 Introduction
0:14 Clean up
0:45 The main plugin file
1:55 Block Metadata
3:12 The block's main JavaScript file
4:45 Your first build
6:15 Additional resources