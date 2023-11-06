# Welcome to week 6

# The save function, and dynamic blocks

Now that you have your `Edit` component fully functional, it's time to implement the `save` function. The `save` function is responsible for saving the output of your block to the database, so that it can be rendered on the front end.

The code in the `save` function is usually a lot simpler than the code in the `Edit` function, because you don't need to worry about user interactions, or updating any data of the block. The primary goal of the `save` function is to return the final output of the block to be saved in the database.

## Removing the RichText component

Before we do that though, in the last module there was a [Block Attributes lesson](https://learn.wordpress.org/lesson/block-attributes/), in which you could optionally add a `RichText` component to your block, to make the paragraph text at the top of the block editable. 

If you added that code to your block, now would be a good idea to remove it.

[NOTE] If you didn't add this code, you can skip this step.

In the `block.json` file, remove the `content` attribute from the `attributes` object, so only showContent and showImage remain:

```json
	"attributes": {
	"showContent": {
		"type": "boolean",
			"default": true
	},
	"showImage": {
		"type": "boolean",
			"default": true
	}
  },
```

In the `Edit` component, remove the RichText import from the `@wordpress/block-editor` package, so only useBlockProps and InspectorControls remain.

```js
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
```

Then, finally, remove the  `RichText` component from the Edit component and replace it with the original `p` tag. 

```js
<p>{__( 'My Reading List – hello from the editor!', 'my-reading-list') }</p>
```

## Implementing the save function

Because the `save` function is merely returning the output of the block, you don't need to re-run the `getEntityRecords` selector using the `useSelect` hook. 

Instead, you can just use the `select` function on the `core` store, and fetch the books that are already in the store.

So next, you can import the `select` function from the `@wordpress/data` package.

```js
import { select } from '@wordpress/data';
```

At the same time, you can import the `BookList` component, so we can reuse it in the `save` function.

```js
import BookList from './components/BookList';
```

Now you can focus on the actual `save` function.

First, destruct the block `props` so you can access the `attributes` property.

```js
export default function save( { attributes } ) {
```

Then, inside the `save` function create the `books` variable and use `select` to fetch the books from the `core` store.

```js
const books = select( 'core' ).getEntityRecords( 'postType', 'book' );
```

Finally, update the `return` statement to return the markup for the block.

```js
    return (
        <div {...useBlockProps.save()}>
			<p>{__( 'My Reading List – hello from the saved content!', 'my-reading-list' )}</p>
            <BookList books={books} attributes={attributes} />
        </div>
    );
```

You'll notice there are a few differences between the return in the `Edit` component and the `save` function.

1. Instead of `useBlockProps()` you use `useBlockProps.save()`. This will return the subset of the block attributes needed for the `save` function.
2. There are no `InspectorControls`, because those are only needed in the editor.

Your updated save function should look like this:

```js
/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';
import { select } from '@wordpress/data';

import BookList from './components/BookList';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */
export default function save( { attributes } ) {

	const books = select( 'core' ).getEntityRecords( 'postType', 'book' );

	return (
		<div {...useBlockProps.save()}>
			<p>{__( 'My Reading List – hello from the saved content!', 'my-reading-list' )}</p>
			<BookList books={books} attributes={attributes} />
		</div>
	);
}
```

Why not give this a try in the editor.

If you refresh the browser, and you had already added the block to the editor, you might see this error. This is perfectly normal. 

[Block error](/images/save-01.png)

This is because you've made a change to the `save` function code. When the block editor loads, it compares the output of the `save` function with the output that was saved in the database. If the output is different, the block will be marked as invalid, and you'll see an error in the editor.

If you click on the "Attempt block recovery" button, it will reload the block, and the error will go away.

[!INFO] Whenever you edit the code in your `save` function, it's useful to first remove the block from the post or page you're testing it on, save the content, and then refresh the browser before adding the block again.

Do this now, and you shouldn't see any errors.

Now try and either preview, or publish the post or page, and view it. You should see the same output as you saw in the editor, only with the black border you defined in the front end style.

Well done, you've successfully finished your block! 

Or have you?

## Dynamic blocks

What would happen if you added a new book to your book custom post type? Your saved block content would only include original list of books, because the `save` function saved the list of books at the time you added the block. This is what is known as a "static block".

What would be ideal is if you could somehow fetch the most recent list of books from the database when the block is rendered on the front end, so that the list of books is always up-to-date.

This is where [dynamic blocks](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/) come in. Dynamic blocks are blocks that fetch data from the database when they are rendered on the front end. This means that the output of the block is always up-to-date.

When converting a block from a static block to a dynamic one, you also need to consider any block supports you added, as well as the attributes and controls you added to the block. 

1. You added the alignment support, and background and text colour supports
2. You added the `showContent` and `showImage` attributes, making it possible to hide the content and image of the book

Now would be a good idea to set all of these settings to something other than the default, so you can see how they affect the output of the block. 

For example, set the alignment to "Align center", the background colour to "Cyan bluish gray", and the text colour to "Contrast". Then, turn the "Toggle Content" setting off. 

In the block editor, switch to the "Code Editor" view, and look at the block attributes stored in the block markup.

```json
{"showContent":false,"align":"center","backgroundColor":"cyan-bluish-gray","textColor":"contrast"}
```

This will come in handy later on.

## Adding dynamic block support

Dynamic blocks use PHP to fetch the data from the database. To make your block dynamic, you need to add a function as the `render_callback` property of the `register_block_type` function. 

You do this by adding an array as the second argument of the `register_block_type` function, and adding the `render_callback` key and the function to be called as the value.

```php
register_block_type( __DIR__ . '/build', array( 'render_callback' => 'my_reading_list_render_callback' ) );
```

Update your `my_reading_list_register_block_type` function, with this new `register_block_type` call.

```php
function my_reading_list_reading_list_block_init() {
    register_block_type( __DIR__ . '/build', array( 'render_callback' => 'my_reading_list_render_callback' ) );
}
```

Then, you need to create the callback function, in this case `my_reading_list_render_callback`. This function will be responsible for fetching the books from the database, and returning the markup for the block. It receives the attributes of the block as it's only parameter, which is an array of attribute properties and their value.

```php
function my_reading_list_render_callback( $attributes ){

}
```

For now, let's just return the content of the `$attributes` variable, so you can see what it contains.

```php
function my_reading_list_render_callback( $attributes ){
    $output = '<pre>';
    $output .= print_r( $attributes, true );
    $output .= '<pre>';
    return $output;
}
```

The last step is to update the save function. Generally, when making the block dynamic, the save function can merely return `null`, because the output of the block is generated by the PHP function. And because it's returning `null`, you can remove everything else in the file, and all you need is this.

```js
export default function save() {
	return null;
}
```

[INFO] There's an even better way to do this, which is to remove the `save.js` file altogether, but you'll learn about that in a future lesson. 

Once the new block code is built, remove the block from the post or page you're working on, save the content, then refresh the browser before adding the block again.

Now, preview or view the post or page on the front end.

You should see the output of the `$attributes` variable, which is an array of all the attributes relevant to the block's output.

[Block output](/images/save-02.png)

```php
Array
(
    [showContent] => 
    [align] => center
    [backgroundColor] => cyan-bluish-gray
    [textColor] => contrast
    [content] => My Reading List
    [showImage] => 1
)
```

Notice that the attributes array contains the same attributes you defined in the `block.json` file, with whatever you set in the editor, and which you saw in the block markup in the code view. 

You can use this to determine what output to return.

Now, you can update the `my_reading_list_render_callback` function to fetch the books from the database, and return the markup for the block.

If you take a look at your `Edit` component, there are a few things you need to do in the `my_reading_list_render_callback` function. 

You need to fetch the books from the database. You can do this using the `get_posts` [function](https://developer.wordpress.org/reference/functions/get_posts/), which is a WordPress function that fetches posts from the database. To return the list of books, you pass an array of arguments to the `get_posts` function, and you specify the post type as `book`.

```php
	$args  = array(
		'post_type' => 'book',
	);
	$books = get_posts( $args );
```

Next, you need to get the parent container `div` attributes. You can do this using the `get_block_wrapper_attributes` [function](https://developer.wordpress.org/reference/functions/get_block_wrapper_attributes/). This function essentially does the same thing as the `useBlockProps` hook in the `Edit` component, but it's the PHP equivalent.

```php
    $wrapper_attributes = get_block_wrapper_attributes();	
}
```
If you've never used PHP before, there are multiple ways to handle generating the block markup, but one way is to create an `$output` variable, and add the markup as text strings to the `$output` variable.

```php
	$output  = '';
	$output .= sprintf( '<div %1$s>', $wrapper_attributes );
	$output .= '<p>' . $attributes['content'] . '</p>';

	foreach ( $books as $book ) {
		$output .= '<div>';
		$output .= '<h2>' . $book->post_title . '</h2>';
		if ( $attributes['showImage'] ) {
			$output .= get_the_post_thumbnail( $book->ID );
		}
		if ( $attributes['showContent'] ) {
			$output .= $book->post_content;
		}
		$output .= '</div>';
	}

	$output .= '</div>';
```

1. The `sprintf` [function](https://www.php.net/manual/en/function.sprintf.php) is a PHP function that allows you to insert variables into a string. In this case, you're inserting the `$wrapper_attributes` variable into the string, which is the markup for the parent `div`.
2. Then, you [concatenate](https://www.php.net/manual/en/language.operators.string.php) the `<p>` tag, and the `content` attribute of the block, which is the paragraph text at the top of the block.
3. Next, you loop through the `$books`, and concatenate the markup for each book to the `$output` variable. This is similar to the `books.map` method you used in the `Edit` component.
4. Inside the loop, you check if the `showImage` attribute is `true`, and if it is, you concatenate the featured image of the book to the $output variable, and do the same for the `showContent` attribute, and the book content.
5. Notice how you can use the `get_the_post_thumbnail` WordPress [function](https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/) here, to fetch the image for the book. 

Last but not least, just as you did in the `Edit` component, you can return markup, in this case the `$output` variable.

```php
return $output;
```

Your final `my_reading_list_render_callback` function should look like this:

```php
function my_reading_list_render_callback( $attributes ) {
	$args  = array(
		'post_type' => 'book',
	);
	$books = get_posts( $args );

	$wrapper_attributes = get_block_wrapper_attributes();

	$output  = '';
	$output .= sprintf( '<div %1$s>', $wrapper_attributes );
	$output .= '<p>' . $attributes['content'] . '</p>';

	foreach ( $books as $book ) {
		$output .= '<div>';
		$output .= '<h2>' . $book->post_title . '</h2>';
		if ( $attributes['showImage'] ) {
			$output .= get_the_post_thumbnail( $book->ID );
		}
		if ( $attributes['showContent'] ) {
			$output .= $book->post_content;
		}
		$output .= '</div>';
	}

	$output .= '</div>';

	return $output;
}
```

Now, refresh the browser, remove the block from the post or page you're working on, save the content, then refresh the browser before adding the block again. Then, preview or view the post or page on the front end.

You should see the correct output on the front end as before.

[Block output](/images/save-03.png)

However, if you add a new book to the book custom post type, and refresh the front end of the post or page, you should see the new book included in the output.

[Block output](/images/save-04.png)

Your final step is to stop the npm development server, and build the final version of your block.

Hit **Ctrl + C** in the terminal to stop the npm development server.

Then, run the build command to build the final version of your block.

```bash
npm run build
```

Congratulations! You've successfully completed the My Reading List block.

# Dynamic blocks

There's another reason why this block makes more sense as a dynamic block and not a static block, and it's related to how blocks work.

Before you converted the block save function to return null, and implemented the render_callback function, if you had added the block to a post or page, saved the page, and then refreshed it, while you had Dev Tools open, you might have seen this error.

[Block error](/images/save-error-01.png)

Take a look at the top of the error being reported:

```
Content generated by `save` function:

<div class="wp-block-my-reading-list-reading-list-block"><p>My Reading List</p></div>

Content retrieved from post body:

<div class="wp-block-my-reading-list-reading-list-block"><p>My Reading List</p><div><h2>Make It Stick</h2><img src="https://learnpress.test/wp-content/uploads/2023/09/41AwfdbjUGL._SL500_-300x300.jpg"/><div>
<p><strong>The Science of Successful Learning</strong></p>
```

Why does this happen?

If you remember when you implemented the book data into the `Edit` component using the `@wordpress/core-data` package, you used the `useSelect` hook when fetching the books from the database. In that lesson, it was explained that:

> `useSelect` will ensure that the `getEntityRecords` selector is re-run when the data is available in the store.
> This is because the data is fetched asynchronously, and you want to make sure the block content is rendered when the data is available.

When the editor loads with a block already added to the editor, the block editor compares the markup returned by the save function against what's stored in the database. This means every time a block loads in the editor, it runs the save function to compare that markup. If the markup is different, the block is marked as invalid, and you see the error in the editor.

In this case, because you're using the `useSelect` hook to re-run the `getEntityRecords` selector once the data is available in the store in the `Edit` component, those books are not available in the store only when block validation check is done. 

There are ways you could rewrite the code to allow the books to be available in the store when the block validation check is done, but in this case it just makes more sense to convert this to a dynamic block.

## Streamlining the current save function

Before you do that though, there's one more thing you can do to streamline the current save function. Currently your save looks like this 

```js
export default function save() {
	return null;
}
```

However, you can remove the `save.js` file altogether, and just return `null` for the save property of the `registerBlockType` call in the `index.js` file.

```js
/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * @see ./save.js
	 */
	save: () => {
		return null;
	},
});
```

The only time you would not retun null for the save property, is if you wanted to use [InnerBlocks](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/nested-blocks-inner-blocks/). InnerBlocks is a component that allows you to next blocks inside other blocks. This is great if you wanted to use pre-existing blocks (like the RichText block), to add functionality to your custom block.

[NOTE] Diving into InnerBlocks is outside the scope of this course, but there's another "introduction to block development" course on Learn WordPress that covers the use of InnerBlocks, if you're interested.

# Diving into the render_callback

# Preparing your block plugin for distribution

# Wrap Up