# Welcome to week 6

# The save function, and dynamic blocks

Now that you have your Edit component fully functional, it's time to implement the save function. The save function is responsible for saving the output of your block to the database, so that it can be rendered on the front end.

The code in the save function is usually a lot simpler than the code in the Edit function, because you don't need to worry about user interactions, or updating any data of the block. The primary goal of the save function is to return the final output of the block to be saved in the database.

## Adding the RichText component

Before we do that though, in the last module there was a [Block Attributes lesson](https://learn.wordpress.org/lesson/block-attributes/), in which you could optionally add a RichText component to your block, to make the paragraph text at the top of the block editable. 

If you didn't add that code to your block, you can add it now.

In the block.json file, add the content attribute to the attributes object:

```json
	"attributes": {
	"content": {
		"type": "string",
			"source": "html",
			"selector": "p",
			"default": "My Reading List"
	},
	"showContent": {
		"type": "boolean",
			"default": true
	},
	"showImage": {
		"type": "boolean",
			"default": true
	}
  },
````

In the Edit component, update the imports from the `@wordpress/block-editor` package to include the `RichText` component.

```js
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
```

Then, finally, remove the `<p>` tag and replace it with the `RichText` component implementation. 

```js
<RichText tagName="p" value={ attributes.content } onChange={ ( content ) => setAttributes( { content } ) } />
```

If you want a reminder of what this code does, go back and review the [Block Attributes lesson](https://learn.wordpress.org/lesson/block-attributes/) from the previous module.

## Implementing the save function

First, as you've added the RichText component to your block, you need to update the save to import RichText from the @wordpress/block-editor package.

```js
import { useBlockProps, RichText } from '@wordpress/block-editor';
```

Because the save function is merely saving the output, you don't need to re-run the `getEntityRecords` selector using the `useSelect` hook. Instead, you can just use the `select` function on the `core` store, and fetch the books that are already in the store.

So next, you can import the `select` function from the `@wordpress/data` package.

```js
import { select } from '@wordpress/data';
```

At the same time, you can import the BookList component, so we can reuse it in the save function

```js
import BookList from './components/BookList';
```

Now you can focus on the actual save function.

First, destruct the block props so you can access the attributes property.

```js
export default function save( { attributes } ) {
```

Then, inside the save function create the `books` variable and use the `select` function to fetch the books from the `core` store.

```js
const books = select( 'core' ).getEntityRecords( 'postType', 'book' );
```

Finally, update the return statement to return the markup for the block.

```js
    return (
	<div {...useBlockProps.save()}>
		<RichText.Content tagName="p" value={ attributes.content } />
		<BookList books={books} attributes={attributes} />
	</div>
);
```

You'll notice however there are a few differences between the Edit component and the Save function.

1. Instead of useBlockProps() you use useBlockProps.save()
2. Instead of RichText you use RichText.Content and you don't need any onChange handler
3. There are no InspectorControls, because those are only used in the editor

[Note] This is the main reason the Edit component is referred to as a component, vs the save function. A component does more than just return markup, whereas a function just returns markup. It's a small but important distinction.

Your updated save function should look like this:

```js
/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, RichText } from '@wordpress/block-editor';
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

	/*return (
		<p {...useBlockProps.save()}>
			{'My Reading List – hello from the saved content!'}
		</p>
	);*/

    return (
        <div {...useBlockProps.save()}>
            <RichText.Content tagName="p" value={ attributes.content } />
            <BookList books={books} attributes={attributes} />
        </div>
    );
}
```

Why not give this a try in the editor.

If you refresh the browser, and you had already added the block to the editor, you might see this error. This is perfectly normal. 

[Block error](/images/save-01.png)

This is because you've made a change to the save function code. When the block editor loads, it compares the output of the save function with the output that was saved in the database. If the output is different, the block will be marked as invalid, and you'll see an error in the editor.

If you click on the Attempt block recovery button, it will reload the code, and the error will go away.

[!INFO] Whenever you edit the code in your save function, it's useful to first remove the block from the post or page you're testing it on, save the content, then refresh the browser before adding the block again.

Do this now, and you shouldn't see any errors.

Now try and either preview, or publish the post or page, and view it. You should see the same output as you saw in the editor, only with the black border you defined in the front end style.

Well done, you've successfully finished your block! Or have you?

## Dynamic blocks

What would happen if you added a new book to your book custom post type. Your block would still be displaying the original list of books, because the save function saved the list of books at the time you added the block. What would be ideal is if you could somehow fetch the most recent list of books from the database when the block is rendered on the front end, so that the list of books is always up to date.

This is where [dynamic blocks](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/) come in. Dynamic blocks are blocks that fetch data from the database when they are rendered on the front end. This means that the output of the block is always up to date.

In order to make sure you think about all the different parts of your block, you also need to consider the block supports you added, as well as the settings you added to the block. 

1. You added the alignment support, and background and text colour supports
2. You added the `showContent` and `showImage` settings, making it possible to hide the content and image of the book

Now would be a good idea to set all of these settings to something other than the default, so you can see how they affect the output of the block. 

For example, set the alignment to "Align center", the background colour to "Cyan bluish gray", and the text colour to "Contrast". Then, turn the "Toggle Content" setting off. 

Switch back to the Code Editor view, and look at the block attributes stored in the block markup.

```json
{"showContent":false,"align":"center","backgroundColor":"cyan-bluish-gray","textColor":"contrast"}
```

This will come in handy later on.

Dynamic blocks use PHP to fetch the data from the database. To make use of dynamic blocks, you need to add a function as the `render_callback` property of the `register_block_type` function. 

You do this by adding an array as the second argument of the `register_block_type` function, and adding the `render_callback` key and the function to be called as the value.

```php
register_block_type( __DIR__ . '/build', array( 'render_callback' => 'my_reading_list_render_callback' ) );
```

This should go inside the `my_reading_list_register_block_type` function, just before the closing curly brace.

```php
function my_reading_list_reading_list_block_init() {
    register_block_type( __DIR__ . '/build', array( 'render_callback' => 'my_reading_list_render_callback' ) );
}
```

Then, you need to create the callback function, in this case `my_reading_list_render_callback`. This function will be responsible for fetching the books from the database, and returning the markup for the block, and it receives the attributes of the block as it's only parameter, which is an array of data.

```php
function my_reading_list_render_callback( $attributes ){

}
```

For now, let's just return the content of the $attributes variable, so you can see what it contains.

```php
function my_reading_list_render_callback( $attributes ){
    $output = '<pre>';
    $output .= print_r( $attributes, true );
    $output .= '<pre>';
    echo $output;
}
```

The last step is to update the save function. Generally, when using dynamic blocks, the save function can merely return `null`, because the output of the block is generated by the PHP function. And because it's returning null, you can remove everything else in the file, and all you need is this.

```js
export default function save() {
	return null;
}
```

Once the new block code is build, remove the block from the post or page you're working on, save the content, then refresh the browser before adding the block again.

Now, preview or view the post or page on the front end.

You should see the output of the $attributes variable, which is an array of all the attributes relevant to the block's output.

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

Notice that the attributes array contains the same attributes you defined in the block.json file, with whatever you set in the editor, and which you saw in the block markup in the code view. 

You can use this to determine what output to return.

Now, you can update the `my_reading_list_render_callback` function to fetch the books from the database, and return the markup for the block.

First, you need to fetch the books from the database. You can do this using the `get_posts` function, which is a WordPress function that fetches posts from the database. To return the list of books, you pass an array of arguments to the `get_posts` function, and you specify the post type as `book`.

```php
function my_reading_list_render_callback( $attributes ) {
	$args  = array(
		'post_type' => 'book',
	);
	$books = get_posts( $args );
		
}
```

Next, you need to get the parent container div attributes, using the `get_block_wrapper_attributes` function. This function essentially does the same thing as the `useBlockProps` hook in the Edit component, but it's the PHP equivalent.

```php
function my_reading_list_render_callback( $attributes ) {
	$args  = array(
		'post_type' => 'book',
	);
	$books = get_posts( $args );

    $wrapper_attributes = get_block_wrapper_attributes();	
}
```

There are multiple ways to return the block markup, but one way is to create an $output variable, concatenate the markup to the $output variable, and then return the $output variable.

```php
	$output  = '';
	$output  = sprintf( '<div %1$s>', $wrapper_attributes );
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

1. The sprintf function is a PHP function that allows you to insert variables into a string. In this case, you're inserting the $wrapper_attributes variable into the string, which is the markup for the parent div.
2. Then, you concatenate the `<p>` tag, and the content attribute of the block, which is the paragraph text at the top of the block.
3. Next, you loop through the books, and concatenate the markup for each book to the $output variable. This is similar to the `books.map` method you used in the Edit component.
4. Inside the loop, you check if the showImage attribute is true, and if it is, you concatenate the featured image of the book to the $output variable, and do the same for the showContent attribute, and the book content.

Last but not least, just as you did in the Edit component, you can return markup, in this case the $output variable.

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
	$output  = sprintf( '<div %1$s>', $wrapper_attributes );
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

However, if you add a new book to the book custom post type, and refresh the front end of the post or page, you should see the new book in the output.

## Block supports in dynamic blocks

