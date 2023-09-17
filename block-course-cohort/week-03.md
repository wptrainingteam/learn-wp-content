# TOC
- Welcome & My Reading List plugin requirements
- Scaffolding the new the block, register_post_type, and some metadata changes
- register_block_type and registerBlockType
- Creating some placeholder data and a basic loop
- Styling your block
- Adding block supports
- Activity
- Wrap up

# Welcome & My Reading List plugin requirements

## Welcome

## My Reading List plugin requirements

# Scaffolding the new the block, metadata changes, register_post_type

## Scaffolding the new the block

To begin, you'll use create-block to scaffold your new block plugin.

In your terminal, navigate to your plugins directory:

```bash
cd ~/path-to-sites/local-site/wp-content/plugins
```

Then, scaffold your new block plugin, passing in the name or slug of the plugin you want to create:

```bash
npx @wordpress/create-block@latest my-reading-list
```

As before, this will scaffold the new block in the `my-reading-list` directory, ready for development.

## Plugin Header and block metadata changes

Now would be a good time to customise the scaffolded plugin file. While the scaffolded code is a good starting point, it requires a few changes to make it unique for your plugin.

The first set of changes are to the plugin header file. The header file should be unique for every plugin you develop, so you need to replace some of the scaffolded values with your own.

Open the `my-reading-list.php` file in your code editor and change the plugin header details for Author, Description, and Version. At the same time, change the @package name to match your plugin

```php
/**
 * Plugin Name:       My Reading List
 * Description:       Create a list of books to be rendered in a dynamic block.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.0.1
 * Author:            Jonathan Bossenger
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-reading-list
 *
 * @package           my-reading-list
 */
```

Next, you'll need to make a few changes to the block's metadata. Those changes happen in the `block.json` file, inside the `src` directory.

First, change the `name` property. 

```json
    "name": "create-block/my-reading-list",
```

The format of this property's value is namespace/blockname, and it's using the namespace 'create-block' and the slug you passed to the create-block tool when you generated this code. So change it to something that is more unique, and also matches this plugin and block better.

```json
	"name": "my-reading-list/reading-list-block",
```

Then, change the version and description. Usually the version is the same as the plugin version, but you can change it to whatever you want. For the description, think about a description for the specific block you're about to develop

```json
    "version": "0.0.1",
    "description": "Displays a list of books for a reading list.",
```

## Registering the book custom post type

Next, you'll need to register the book custom post type. Add the following code below the plugin header.

```php
/**
 * Register a book custom post type
 */
add_action( 'init', 'my_reading_list_register_book_post_type' );
function my_reading_list_register_book_post_type() {
	register_post_type(
		'book',
		array(
			'labels'       => array(
				'name'          => 'Books',
				'singular_name' => 'Book',
			),
			'public'       => true,
			'has_archive'  => true,
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest' => true
		) 
	);
}
```

This code is hooking into the `init` action, and registering a new custom post type called `book`. 

Knowing what the `register_post_type` function does is outside the scope of what you need to learn to develop blocks, so all you need to know for now is that this is what will allow you to add books to the WordPress site in the admin.

> [!NOTE]
>  You can read more about the `register_post_type` function in the [WordPress developer documentation](https://developer.wordpress.org/reference/functions/register_post_type/).

The last change you should make now is related to the code that registers the block type. 

The current code implementation looks like this:

```php
function create_block_my_reading_list_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_my_reading_list_block_init' );
```

Please update it to look like this:

```php
add_action( 'init', 'my_reading_list_reading_list_block_init' );
function my_reading_list_reading_list_block_init() {
	register_block_type( __DIR__ . '/build' );
}
```

- the function name has been changed to `my_reading_list_reading_list_block_init`
- the add_action function call which hooks the `my_reading_list_reading_list_block_init` function into the `init` action has been moved above the function definition, which makes it easier to read.

These changes are not strictly necessary, but they make your code slightly easier to read. You'll learn more about this in the next lesson.

With all those changes made, you should now be able to activate the plugin in your WordPress dashboard.

Once activated, add a few books to the list of books in the admin dashboard. Remember to add a title, some content, and a featured image. You'll need this data to test your block during development.

## register_block_type and registerBlockType

Generally, a block plugin is made up of two parts:

- the PHP code in the main plugin file, in this case `my-reading-list.php`
- the JavaScript code in the main source JavaScript file, in this case `src/index.js`

On the PHP side, the register_block_type function is used to register the block type when WordPress is loaded. It's basically saying, "here is some block code that can be used to render a block". 

If you read the [documentation for this function](https://developer.wordpress.org/reference/functions/register_block_type/), you'll see that the first parameter you pass to the function can be one of 4 different things:

```
Block type name including namespace, or alternatively a path to the JSON file with metadata definition for the block, or a path to the folder where the block.json file is located, or a complete WP_Block_Type instance.
```

In the scaffolded code, the first parameter is a path to the folder where the block.json file is located. This is the recommended way to register a block type.

This function then loads the information from the block.json file, and registers the block type with WordPress.

Inside the block.json file, notice that the main JavaScript file, index.js, is specified as the value for the `editorScript` property. 

```js
"editorScript": "file:./index.js",
```

This tells WordPress that the JavaScript code that will be used to render the block in the editor is in the index.js file.

If you open that file, and scroll to the bottom, it uses the function registerBlockType to register the block type for the editor.

```js
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

This function is loaded once the editor is loaded, and will determine what code is run when a user tries to add your block to their editor instance.

If you look at [the documentation for this function](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-blocks/#registerblocktype), the first parameter is the name of the block, which is the value of the `name` property in the block.json file. It reads this name from the metadata variable, which is created by importing the json object from the block.json file, higher up in the index.js file.

```js
import metadata from './block.json';
```

The second parameter is an object that contains two properties, `edit` and `save`. 

```js
{
	edit: Edit, 
    save,
}
```

These properties are the names of two functions that are also imported from the edit.js and save.js files respectively, just below the metadata import.

```js
import Edit from './edit';
import save from './save';
```

Using the [import statement](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import) in this way is a way of separating reusable bits of code into separate files, making development easier. You will learn more about this later on in this course.

For now, it's important to understand the differences between the PHP register_block_type function the JavaScript registerBlockType function.

- register_block_type is a PHP function that tells WordPress "hey, here's some block code you can use when the editor loads"
- registerBlockType is a JavaScript function that is called when a user tries to add your block in their editor instance

> [!NOTE]
> For the rest of this course, you'll be working primarily in the files that make up your block source code in the src directory. If you ever have to make a change in PHP, you'll be doing it in the main plugin file.

# Creating some placeholder data and a basic loop

Once you have your block ready for development, it would be a good idea to create some placeholder book data to work with, and then create a basic loop to display that data. You will learn how to connect the block to the books custom post type later, but for now you can just create some placeholder data to represent the books. 

To do this, you can create a JavaScript array of objects, where each object represents a book.

```php
const books = [
    {
        id: 1,
        post_title: 'The Fellowship of the Ring',
        post_content: 'When Mr. Bilbo Baggins of Bag End announced that he would shortly be celebrating his eleventy-first birthday with a party of special magnificence, there was much talk and excitement in Hobbiton.',
        post_featured_image: 'https://source.unsplash.com/featured/?book',
    },
    {
        id: 2,
        post_title: 'The Two Towers',
        post_content: 'Frodo and Sam lay side by side, peering through the ferns. The path was now almost due west, and it went gently downwards. The sun was shining brightly, and the grass under the fluttering wind-flags was sweet and soft, as if it had been mown.',
        post_featured_image: 'https://source.unsplash.com/featured/?book',
    },
    {
        id: 3,
        post_title: 'The Return of the King',
        post_content: 'Pippin looked out from the shelter of Gandalf\'s cloak. He wondered if he was awake or still sleeping, still in the swift-moving dream in which he had been wrapped so long since the great ride began. The dark world was rushing by and the wind sang loudly in his ears.',
        post_featured_image: 'https://source.unsplash.com/featured/?book',
    }
];   
```

Then, you could use this data to create a basic loop that will display the book data in the block. 

```js
books.map( ( book ) => (
	// output the book data
) ) }
```

The first place to implement this would be in your Edit component. The Edit component is the code that runs when the block is rendered in the editor. In the scaffolded block, the Edit component code is in the edit.js file in the src directory.

