# TOC
- Welcome & My Reading List plugin requirements
- Scaffolding the new the block, register_post_type, and some metadata changes
- register_block_type and registerBlockType
- Creating some placeholder data and a basic loop
- Styling your block
- Adding block supports
- Wrap up

# Welcome & My Reading List plugin requirements

## Welcome

Welcome to week 3 of the Learn WordPress Developing your first block course cohort.

For the first two weeks of this cohort, you focused on getting your development environment set up, and learning to use the create block tool. In my opinion, this is the most important part of learning to develop blocks, so well done for getting this far.

With the necessary tools and environment set up, you can now focus on learning to develop blocks.

Block development is a wide-reaching topic. There is as much to learn as their is learning to develop a custom WordPress plugin or theme. It's honestly just not possible to learn everything in six short weeks!

Therefore, for the next 4 weeks, I'm going to try and give you an introduction to the most important parts, while giving you some resources to continue learning after the course is over.

## My Reading List plugin requirements

Last week you learned about the My Reading List plugin that you'll be developing over the next few weeks. Now would be a good time to dive into the features you're going to be building in this plugin.

1. The plugin will register a custom post type called `book`. This custom post type will support a title, content, and a featured image.
2. The plugin will register a block called `My Reading List Block`. This block will be a dynamic block, and will display a list of books that have been added to the `book` custom post type.
3. When adding the block in the editor, it will be possible for the user to change the background colour of the block, change the text colour, and change the alignment of the block in the editor.
4. It will also be possible to set whether to display the book content, and whether to display the featured image.
5. Finally, when the block is rendered on the front end, it will display a black border around the list of books, and when it's displayed in the editor, with a red border.

These requirements are not all typical requirements for a block you might build, but they will help showcase the main concepts you need to learn.

I hope you're excited to get started!

# Scaffolding the new the block, metadata changes, register_post_type

## Scaffolding the new the block

To begin, you'll use create-block to scaffold your new block plugin.

In your terminal, navigate to your plugins' directory:

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

Then, change the version, title and description. Usually the version is the same as the plugin version, but you can change it to whatever you want. For the title and description, think about a title and description for the specific block you're about to develop. Title is what shows when the user selects the block from the inserter, so that's the most important change

```json
    "version": "0.0.1",
    "title": "My Reading List Block",
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

These changes are not strictly necessary, but they make your code slightly easier to read. You'll learn more about this in a future lesson.

With all those changes made, you should now be able to activate the plugin in your WordPress dashboard.

Once activated, add a few books to the list of books in the admin dashboard. Remember to add a title, some content, and a featured image. You'll need this data to test your block during development.

[Image of adding a new Book]

## register_block_type and registerBlockType

To build a working plugin, you need at least two parts:

- the PHP code in the main plugin file, in this case `my-reading-list.php`
- the JavaScript code in the main source JavaScript file, in this case `src/index.js`

On the PHP side, the register_block_type function is used to register the block type when WordPress is loaded. It's basically saying, "here is some block code that can be used to power a block". 

If you read the [documentation for this function](https://developer.wordpress.org/reference/functions/register_block_type/), you'll see that the first parameter you pass to the function can be one of 4 different things:

```
Block type name including namespace, or alternatively a path to the JSON file with metadata definition for the block, or a path to the folder where the block.json file is located, or a complete WP_Block_Type instance.
```

In the scaffolded code, the first parameter is a path to the folder where the `block.json` file is located. This is the recommended way to register a block type.

> [!NOTE]
> You'll notice that the path specified is the build directory, but you will be writing your block code in the src directory. This is because when you run the build step you learned last week, it compiles everything in the build directory, which is what is loaded when WordPress is running. 

This function then loads the information from the `block.json` file, and registers the block type.

Inside the `block.json` file, notice that the main JavaScript file, index.js, is specified as the value for the `editorScript` property. 

```js
"editorScript": "file:./index.js",
```

This tells WordPress "hey, this file is the JavaScript code that will be used to render the block in the editor".

If you open the index.js file in your src directory, and scroll to the bottom, it uses the `registerBlockType` JavaScript function to register the block type for the editor.

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

This function is loaded once the editor is loaded, and will determine how the block functions when a user tries to add your block to their editor instance.

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

These properties are the names of two functions that are also imported from the `edit.js` and `save.js` files respectively, just below the metadata import.

```js
import Edit from './edit';
import save from './save';
```

Using the [import statement](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import) in this way is a way of separating reusable bits of code into separate files, making development easier. You will learn more about this later on in this course.

If you open the `edit.js` file, you'll see that it exports a function called `Edit`.

```js
export default function Edit( props ) {
    // edit code
}
```

The same is true of the `save.js` file, which exports a function called `save`.

```js
export default function save( props ) {
    // save code
}
```

The `edit` property of the block will be all the code in the `Edit` function of the `edit.js` file, and this is the code that runs when the block is rendered in the editor. 

The `save` property of the block will be all the code in the `save` function of the `save.js` file, and runs when the block output is saved to the database, to be rendered on the front end.

> [!NOTE] note on the difference between Edit component and save function

You'll learn more about this later in this course, but for now, it's important to understand the differences between the PHP `register_block_type` function the JavaScript `registerBlockType` function.

- `register_block_type` is a PHP function that tells WordPress "hey, here's some block JavaScript code that makes up a block"
- `registerBlockType` is a JavaScript function that  is called when the editor is loaded, and controls what happens in the editor (`edit`) and what is saved to the database (`save`).

> [!NOTE]
> For the rest of this course, you'll be working primarily in the files that make up your block source code in the src directory. If you ever have to make a change in PHP, you'll be doing it in the main plugin file.

# Creating some placeholder data and a basic loop

How you develop a block is ultimately up to you, but one way to go about this project is to start with the `Edit` component, set up some placeholder book data, and then create a basic loop to display that data.

Once you have the `Edit` component functioning the way you want, you can focus on the `save` function, and then later on replace the placeholder data with actual data from the database.  

To create the placeholder data, you can create a JavaScript array of objects, where each object represents a book, and fill it with some hardcoded information.

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

> [!NOTE] The JavaScript map function is a way of looping through an array of data, and outputting something for each item in the array. You can read more about it in the [Mozilla Developer Network documentation](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/map).

The placeholder data can be added to the `Edit` component, just above the `return` statement. The `return` statement is the section of the component that determines what is output in the browser, so that's where you'll add the loop to display the book data

This is what your current `Edit` component looks like:

```js
export default function Edit() {
	return (
		<p { ...useBlockProps() }>
			{ __(
				'My Reading List – hello from the editor!',
				'my-reading-list'
			) }
		</p>
	);
}
```

Step 1 is to add the books array to the `Edit` component, just above the `return` statement.

```js
export default function Edit() {
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

	return (
		<p { ...useBlockProps() }>
			{ __(
				'My Reading List Block!',
				'my-reading-list'
			) }
		</p>
	);
}
```

Step 2 is to add the `books.map` loop to the `return` statement, replacing the existing text.

```js
export default function Edit() {
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

	return (
		<p { ...useBlockProps() }>
			{ __(
				'My Reading List Block!',
				'my-reading-list'
			) }
        
            { books.map( ( book ) => (
                book.post_title
            ) ) }
        
		</p>
	);
}
```

If you save the file, you can run the build step you learned in the last lesson to compile the changes.

```bash
npm run build
```

Then, if you refresh the editor, you should be able to add the block to a post or page, and see the output.

## Running the build development server

Before we go any further, you might have been thinking, "Do I have to run the build step every single time I make a change to the code". The answer is no.

In last week's lesson on the `package.json` file, you learned that there are two npm scripts defined in the package.json file, `build` and `start`. 

`npm start` is what you use during development. It starts a local running process (known as a file watcher) that watches the files in the `src` directory, and when changes are made to those files, it triggers the build step.

`npm run build` is what you use when you are ready to build your final version. It takes all the code in the `src` directory and creates the final deployable build of the block.

So during development, you can run the `start` script, and it will watch for changes and run the build step automatically. 

```bash
npm start
```

Any time you save changes to any of the files in the `src` directory, the build step will run, and the changes will be compiled. You can then refresh the editor if you have already added the block to a post or page, and see the changes.

This does sometimes cause errors in the block rendering, especially if you make changes to the `save` function, but you'll learn how to tackle that when we get there.

## Displaying the book data

You'll notice that your block output doesn't look great at the moment. This is because the test is just rendered inside a paragraph tag. Let's add some better HTML markup to the output being rendered by the Edit component.

```js
    return (
		<div { ...useBlockProps() }>
			{ __(
				'My Reading List Block!',
				'my-reading-list'
			) }
			<ul>
				{ books.map( ( book ) => (
					<li key={ book.id }>{ book.post_title }</li>
				) ) }
			</ul>
		</div>
    );
```

- the paragraph tag has been replaced with a div tag
- you're using an unordered list to display the books
- each book is displayed in a list item

> [!NOTE] The markup you have used in the Edit component looks like HTML, but it's actually JSX. You'll learn more about JSX in a future lesson, but for now, just know that it's a way of writing HTML-like code in JavaScript.

Once the build has run, refresh the editor, and you should see the output looking much better.

[Image of better output]

Once you have the `Edit` component displaying the data the way you want, you can move on to the `save` function. 

The `save` function is what determines what is saved to the database when the post or page is saved. You can use the same placeholder data and the same loop to display the data in the `save` function, with one main difference. 

```js
export default function save() {
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

	return (
		<div { ...useBlockProps.save() }>
			{ __(
				'My Reading List Content!',
				'my-reading-list'
			) }
			<ul>
				{ books.map( ( book ) => (
					<li key={ book.id }>{ book.post_title }</li>
				) ) }
			</ul>
		</div>
	);
}
```

Notice that in the return statement, the `useBlockProps` function is being chained with the `save` function, whereas in the Edit component it is not. `useBlockProps` is what's know as a React hook, and it is used in block development to add attributes to the HTML that is either rendered in the editor or saved to the database. You'll learn what `useBlockProps` does in a future lesson (as well as what the `...` means), but for now, just know that you need to use the `save` function of the `useBlockProps` hook in your block's `save` function.

Let the build step run, and refresh the editor. Then, preview the block on the front end, and you should see the same output as in the editor.

[Show front end rendering]

If you refreshed the editor that already had the block added to it before you updated the save function and ran the build step, you might see this error:

[Attempt block recovery error]

That's normal, and it's because the block has changed, and the editor is comparing the saved content vs the content generated by your updated save function. You can just usually just click the "Attempt block recovery" button, and the block will be updated.

# Styling your block

Up till now there have been no styles associated to your block, even though the `block.json` file defines the `editorStyle` and `style` properties, and the associated files. This is because you updated the block name, which affects the default class applied to the block when it's rendered.

Open up the `editor.scss` and `style.scss` files, and notice the class names that are being targeted:

```
.wp-block-create-block-my-reading-list
```

> These two files are [Syntactically Awesome Stylesheets](https://sass-lang.com/), also known as Sass files. Just like the new JSX format used to create blocks convert into regular JavaScript during the build step, Sass files convert into regular CSS. These files follow the new SCSS syntax, which you can learn more about on the Sass website. Fortunately, you can also just write plain CSS in Sass files, and that will work as well. 

Remember that you changed the `block.json` name property to `my-reading-list/reading-list-block`? This name is used to generate the default class name of the parent container for your block. This class name is applied to the `div` because you're using the `useBlockProps` hook. It uses the format `wp-block-{block-name}`, replacing the `/` with a `-`.

So if your `block.json` `name` property is 

```json
"my-reading-list/reading-list-block",
```

The class name will be 

```
wp-block-my-reading-list-reading-list-block
```

Update the `editor.scss` and `style.scss` files to target this class name instead of the class name generated. Then let the build step run, and refresh the editor.

Notice how the styles are applied. In both the editor and the front end the background-color, color and padding styles are applied to the block, and in the editor, the border style is applied to the block.

> [!NOTE] The CSS in the `editorStyle` file defined in the block.json file is used to apply styles to the block in the editor, and the CSS in the `style` file is used to apply styles to the block on the front end. However, if you inspect the block code in the editor, you'll see that both the style and editorStyle CSS is applied, and the editorStyle merely overrides the style file contents. 
> You can read more about this in the [documentation](https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-styles).

For the purposes of your block, you need to change the CSS so that it applies the red border in the editor, and the black border on the front end.

Update the `editor.scss` file to look like this:

```scss
.wp-block-my-reading-list-reading-list-block {  
    border: 5px solid #f00;
}
```

And update the `style.scss` file to look like this:

```scss
.wp-block-my-reading-list-reading-list-block {  
    border: 5px solid #000;
}
```

After the build step has run refresh the editor, and preview the block on the front end. you should see the borders applied correctly in both places.

# Adding block supports

The last change you'll be making this week is to enable the following functionality from the original requirements:

- When adding the block in the editor, it will be possible for the user to change the background colour of the block, change the text colour, and change the alignment of the block in the editor.

To enable this functionality, you can make use of something called Block Supports.

Block supports are a way of enabling or disabling certain functionality for a block. You can read more about block supports in the [documentation](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/block-supports-in-static-blocks/). Essentially, many blocks, including core blocks, will share a lot of similar functionality. Whether that is to change the background color, text color, or to add padding, margin or other customization options.

Blocks supports are a way of enabling or disabling functionality that already exists in core blocks on your custom block.

The beauty of block supports is that to enable them, you simply add them to the `supports` property of the `block.json` file.

Let's start by adding alignment support. Open the `block.json` file, and update the `supports` property to include `align` [support](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/#align).

```json
  "supports": {
	"html": false
	"align": true
  },
```

Refresh the editor, and you should now see the alignment options available in the block toolbar.

[Show alignment option]

Next, add the `color` [support](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-supports/#color), for both background and text color.

```json
  "supports": {
    "html": false
    "align": true,
    "color": {
        "background": true,
        "text": true
    }
  },
```

Refresh the editor and you should see the color options in the block editor sidebar.

[Color options]

Block supports are a great way to add functionality to your block, without having to write any additional code. 