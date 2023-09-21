# TOC
- Welcome 
- Accessing the book data for your reading list
- var, let, and const
- JavaScript arrays and objects
- Understanding the map function
- More about JSX

# Welcome

Welcome to week 4 of the Learn WordPress Developing your first block course cohort.

You're doing great! You've made it to week 4. You're at the halfway mark, with just three weeks left of this course cohort.

Last week, you scaffolded your new block plugin using create-block, started making some changes to the main plugin file to enable the custom post type, learned how to add custom styles to your block, as well as how to add block supports to allow  users to edit specific things in the block in the editor.

This week you'll be working on accessing the book post type data and displaying it in your block. At the same time, you'll learn some more JavaScript and React concepts, including some more React hooks, accessing REST API data, and some additional JSX concepts.

This week you'll also start learning how to use your browsers built in developer tools to help you debug your code.

Good luck, and I look forward to seeing your progress.

# Accessing the book data for your reading list

Now would be a good time to think about adding the book data to your reading list block. Generally, in any JavaScript application, you'll need to access that data from an external source. 

In this case, you'll be accessing the book data from the WordPress REST API.

How the REST API works is beyond the scope of this course, but you can think of it as a way to access data from WordPress, but if you want to read more about it you can do so in the [official WordPress REST API handbook](https://developer.wordpress.org/rest-api/). There's also [this course on Learn WordPress](https://learn.wordpress.org/course/developing-with-the-wordpress-rest-api/) which explains how to work with and use the REST API.

For now, all you need to know is that the REST API is how you fetch data from the WordPress database, and the block editor has a built in way to access that data called the [@wordpress/core-data package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-core-data/).

Before you get into all that though, let's create some placeholder data for our books

## Placeholder data

Placeholder data is a way to create some data that you can use to test your code, without having to worry about accessing the real data until you need to. If you've ever built websites, you've probably used placeholder data or text like Lorem Ipsum before, to create pages or posts in order to demo your layout or designs to a client, before getting the actual content.

In this case, you'll be creating some placeholder data for your books, so that you can first focus on how the list of books should look and function in the editor, before replacing that placeholder data with real data from the REST API.

> [!Note] Developing with placeholder data is not a requirement, but it does make things easier if you're learning more than one new thing at a time. In this case, you're learning how to both render a list of books in your block, AND how to connect to the REST API to fetch those books. Using some placeholder data allows you to focus on one thing at a time.

To create the placeholder data, you can create a JavaScript array of objects, where each object represents a book, and fill it with some hardcoded information.

```php
const books = [
    {
        title: 'The Fellowship of the Ring',
        content: 'When Mr. Bilbo Baggins of Bag End announced that he would shortly be celebrating his eleventy-first birthday with a party of special magnificence, there was much talk and excitement in Hobbiton.',
        featured_image: 'https://picsum.photos/360/240',
    },
    {
        title: 'The Two Towers',
        content: 'Frodo and Sam lay side by side, peering through the ferns. The path was now almost due west, and it went gently downwards. The sun was shining brightly, and the grass under the fluttering wind-flags was sweet and soft, as if it had been mown.',
        featured_image: 'https://picsum.photos/360/240',
    },
    {
        title: 'The Return of the King',
        content: 'Pippin looked out from the shelter of Gandalf\'s cloak. He wondered if he was awake or still sleeping, still in the swift-moving dream in which he had been wrapped so long since the great ride began. The dark world was rushing by and the wind sang loudly in his ears.',
        featured_image: 'https://picsum.photos/360/240',
    }
];   
```

When creating placeholder data, it's useful to use services like [Placehold](https://placehold.co/) or [Lorem Picsum](https://picsum.photos/) to generate placeholder images.

You'll notice a few things about the books array:
 - it uses the `const` keyword to declare the local variable. This is because the books array is not going to change. If you were creating a variable where it's contents might change, you'd use the `let` keyword instead.
 - it uses square brackets `[]` to declare the array
 - it uses curly braces `{}` to declare each object in the array

Each object in the array has three properties: `title`, `content`, and `featured_image`. These are the same properties that you might expect from a list of books fetched from a REST API.

Creating placeholder data as an array of objects will more closely match the type of data structure you would expect from a REST API, which would return an  array of objects, where each object represents a book.
 
You can read more about the JavaScript `const` [statement](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/const) as well as [arrays](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array) and [objects](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/object) in the MDN Web docs.

## Rendering the placeholder data

### Preparing the Edit component to return the book markup

Before thinking about how to use this placeholder data in the Edit component, take a look at the Edit component's return statement.

```js
	return (
		<p {...useBlockProps()}>
			{__('My Reading List – hello from the editor!', 'my-reading-list')}
		</p>
	);
```

In the last module you learned that this code is JSX, which is an HTML like syntax. So while the `<p>` tags might look like typical HTML, you can see that there is some code inside the tags, which is wrapped in curly braces `{}`.  

JSX is a special React syntax extension for JavaScript that lets you write HTML-like markup inside a JavaScript file. 

You will learn more about how JSX works, and what the curly braces mean, later in this module. For now, notice that the code returned by the Edit component is wrapped in a `<p>` tag. This is known as the parent container, and any React component can only return a single parent container.

This means that if you wanted to add additional HTML to the Edit components render statement, it would be a good idea to change the `<p>` tag to a `<div>` tag, so that you can add additional HTML inside the `<div>` tag.

```js
    return (
        <div {...useBlockProps()}>
            {__('My Reading List – hello from the editor!', 'my-reading-list')}
        </div>
    );
```

While you're at it, you should also wrap the text in a paragraph tag.

```js
    return (
        <div {...useBlockProps()}>
            <p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
        </div>
    );
```

And now you can add some JSX markup for a single book

```js
    return (
        <div {...useBlockProps()}>
            <p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
            <div>
                <h2>Book title</h2>
                <img src="https://picsum.photos/360/240"/>
                <p>Book content</p>
            </div>
        </div>
    );
```

Let the development build server finish building, and then refresh the page in the browser. You should see the new markup rendered in the editor.

![A single book appears](/images/books-block-01.png)

Now, let's add the book placeholder data to the Edit Component.

```js
export default function Edit() {

    const books = [
        {
            title: 'The Fellowship of the Ring',
            content: 'When Mr. Bilbo Baggins of Bag End announced that he would shortly be celebrating his eleventy-first birthday with a party of special magnificence, there was much talk and excitement in Hobbiton.',
            featured_image: 'https://picsum.photos/360/240',
        },
        {
            title: 'The Two Towers',
            content: 'Frodo and Sam lay side by side, peering through the ferns. The path was now almost due west, and it went gently downwards. The sun was shining brightly, and the grass under the fluttering wind-flags was sweet and soft, as if it had been mown.',
            featured_image: 'https://picsum.photos/360/240',
        },
        {
            title: 'The Return of the King',
            content: 'Pippin looked out from the shelter of Gandalf\'s cloak. He wondered if he was awake or still sleeping, still in the swift-moving dream in which he had been wrapped so long since the great ride began. The dark world was rushing by and the wind sang loudly in his ears.',
            featured_image: 'https://picsum.photos/360/240',
        }
    ];   

	return (
		<div {...useBlockProps()}>
			<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			<div>
				<h2>Book title</h2>
				<img src="https://picsum.photos/640/480"/>
				<p>Book content</p>
			</div>
		</div>
	);
}
```

Notice that you add the placeholder data above the return statement. The return statement is generally only be used to return the output of the component, any other variables or functionality are usually added above the return statement.

Now you need a way to loop through the books array, and render the book data for each book in the array, with the defined markup.

You can use the JavaScript `map` [method](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/map) to loop through the array, and render the fields for each book.

```js
books.map( ( book ) => (
	// do something with the book object
) ) 	
````

So to add this to the Edit component, you can replace the hard coded book markup with the `map` method, which loops through the books, and renders the markup for each book

```js
	return (
        <div {...useBlockProps()}>
            <p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
            { books.map( ( book ) => (
                <div>
                    <h2>{ book.title }</h2>
                    <img src={ book.featured_image }/>
					<p>{ book.content }</p>
                </div>
            ) ) }
        </div>
    );
```

The code for your Edit component should now look like this:

```js
export default function Edit() {

	const books = [
		{
			title: 'The Fellowship of the Ring',
			content: 'When Mr. Bilbo Baggins of Bag End announced that he would shortly be celebrating his eleventy-first birthday with a party of special magnificence, there was much talk and excitement in Hobbiton.',
			featured_image: 'https://picsum.photos/360/240',
		},
		{
			title: 'The Two Towers',
			content: 'Frodo and Sam lay side by side, peering through the ferns. The path was now almost due west, and it went gently downwards. The sun was shining brightly, and the grass under the fluttering wind-flags was sweet and soft, as if it had been mown.',
			featured_image: 'https://picsum.photos/360/240',
		},
		{
			title: 'The Return of the King',
			content: 'Pippin looked out from the shelter of Gandalf\'s cloak. He wondered if he was awake or still sleeping, still in the swift-moving dream in which he had been wrapped so long since the great ride began. The dark world was rushing by and the wind sang loudly in his ears.',
			featured_image: 'https://picsum.photos/360/240',
		}
	];

	return (
		<div {...useBlockProps()}>
			<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			{ books.map( ( book ) => (
				<div>
					<h2>{ book.title }</h2>
					<img src={ book.featured_image }/>
					<p>{ book.content }</p>
				</div>
			) ) }
		</div>
	);
}
```

And your block should start looking like this in the editor:

![A multiple books](/images/books-block-02.png)

## Replacing the placeholder data with real data using @wordpress/core-data

Now that you have some placeholder data, you can start thinking about how to replace that placeholder data with real data from the REST API using the @wordpress/core-data package.

The @wordpress/core-data package package is built on top of the [@wordpress/data](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-data/) package, which provides what as known as a data store, when stores the relevant data from the WordPress REST API.

The @wordpress/core-data package uses the store and special functions called selectors to fetch data from the store. The selector you'll be using from the core-data package is the `getEntityRecords` selector. 

Finally, you'll need to use the `useSelect` [hook](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-data/#useselect). `useSelect` will ensure that the `getEntityRecords` selector is re-run when the data is available in the store. This is because the data is fetched asynchronously, and you want to make sure the block content is rendered when the data is available.

The first step is to import the `useSelect` hook from the @wordpress/data package, and the `store` from the @wordpress/core-data package.

```js
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';
```

You can import these at the top of the file, just below the `import { useBlockProps } from '@wordpress/block-editor';` statement.

Next, you need to create a function that will return the data from the store. This function will be passed to the `useSelect` hook, which will then return the data from the store. Notice how you pass the post type and the post type slug to the `getEntityRecords` selector.

```js
const books = useSelect(
	select =>
		select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
	[]
);
```

You can replace the books placeholder with this new function, and the Edit component should now look like this:

```js
export default function Edit() {

	const books = useSelect(
		select =>
			select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
		[]
	);

	return (
		<div {...useBlockProps()}>
			<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			{ books.map( ( book ) => (
				<div>
					<h2>{ book.title }</h2>
					<img src={ book.featured_image }/>
					<p>{ book.content }</p>
				</div>
			) ) }
		</div>
	);
}
```

But now, when you refresh the page in the browser, you see an error!

![An error](/images/books-block-03.png)

Time to start debugging!

## Replacing the placeholder data with real data using @wordpress/core-data

Whenever you see an error like this, it means there's a problem with your code somewhere. Because JavaScript is run in the browser, it's possible to use the browser's built in developer tools to help you debug your code.

Most [modern browsers have developer tools built in](modern browsers have developer tools built in), and you can usually access it in multiple different ways. 

Once opened, if you click on the Console tab, you'll see the error message, which will tell you what the problem is.

![An error in the console](/images/books-block-04.png)

In this case, the error is telling us it can't read the properties of the book objects in the books.map. If you remember from earlier, you learned that the useSelect hook will retun the getEntityRecords selector, when the data has been fetched asynchronously from the REST API. So this tells us that the data hasn't been fetched yet, and that's why we're getting an error.

One way to work around this is to perform a check if the books variable is empty, and if it is, return some markup without the list of books

```js
	if ( ! books ) {
		return (
			<div {...useBlockProps()}>
				<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			</div>
		);
	}

```

You can add this above the return statement that contains the books.map method.

```js
export default function Edit() {

	const books = useSelect(
		select =>
			select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
		[]
	);

	if ( ! books ) {
		return (
			<div {...useBlockProps()}>
				<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			</div>
		);
	}

	return (
		<div {...useBlockProps()}>
			<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			{ books.map( ( book ) => (
				<div>
					<h2>{ book.title }</h2>
					<img src={ book.featured_image }/>
					<p>{ book.content }</p>
				</div>
			) ) }
		</div>
	);
}
```

Now, when you refresh the page in the browser, you should see the markup rendered from the first return, and then useSelect re-runs the selector, but an error still happens. And in your console, you're now seeing a bunch of React errors!

[image]

Now would be a good time to check what the books variable is returning. You can do this by adding a console.log statement below the if statement.

```js
console.log( books );
```

Now, when you refresh the page in the browser, you should see the books variable logged to the top of the console. Click on the little arrows to expand the books array, and then expand the first book object.

[image]

You'll notice that the book properties are different to the placeholder data you created earlier. This is because the REST API returns different fields to what you created in the placeholder data.

- The title is returned as `title.rendered`
- The content is returned as `content.rendered`
- The featured image url is not returned, but there is a featured_media id, which you can use to fetch the featured image url

For now, update the title and the content to use the correct fields, and remove the image tag. 

```js
	return (
	<div {...useBlockProps()}>
		<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
		{ books.map( ( book ) => (
			<div>
				<h2>{ book.title.rendered }</h2>
				<p>{ book.content.rendered }</p>
			</div>
		) ) }
	</div>
);
```

Now, when you refresh the page in the browser, you should see the book title and content being rendered. But wait, the content looks all weird?

[image]

This is because the content is returned as HTML, and you need to render it as HTML. You can fix this by using the `dangerouslySetInnerHTML` React property on a div element.

```js
	return (
	<div {...useBlockProps()}>
		<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
		{ books.map( ( book ) => (
			<div>
				<h2>{ book.title.rendered }</h2>
				<div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div>
			</div>
		) ) }
	</div>
);
```

> [!Note] The `dangerouslySetInnerHTML` property sounds scary, but it's safe to use here. You'll learn more about this property in a future lesson.

Refresh the page in the browser, and you should now see the book content rendered correctly.

[image]

The last piece of the puzzle is to fetch the featured image url. Because the image url is not included in the REST API endpoint for custom post types, you need to add it as a field to the REST API response using the `register_rest_field` function in PHP. Adding fields to a REST API response is outside of the scope of this course, so for now, add this code in your my-reading-list.php file, under where you have the `register_post_type` function.

```php
/**
 * Add featured image to the book post type
 */
add_action( 'rest_api_init', 'my_reading_list_register_book_featured_image' );
function my_reading_list_register_book_featured_image() {
	register_rest_field(
		'book',
		'featured_image_src',
		array(
			'get_callback' => 'my_reading_list_get_book_featured_image_src',
			'schema'       => null,
		)
	);
}
function my_reading_list_get_book_featured_image_src( $object ) {
	if ( $object['featured_media'] ) {
		$img = wp_get_attachment_image_src( $object['featured_media'], 'medium' );
		return $img[0];
	}
	return false;
}
```

All you need to know about this code is that it's adding a field on the REST API response called `featured_image_src`, which will return the featured image url for the book.

So now, you can add your image markup back to your list of books, and use the featured_image_src property

```js
<img src={ book.featured_image_src }/>
```

Your final Edit component should now look like this:

```js
export default function Edit() {

	const books = useSelect(
		select =>
			select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
		[]
	);

	if ( ! books ) {
		return (
			<div {...useBlockProps()}>
				<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			</div>
		);
	}

	console.log( books );

	return (
		<div {...useBlockProps()}>
			<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			{ books.map( ( book ) => (
				<div>
					<h2>{ book.title.rendered }</h2>
					<img src={ book.featured_image_src }/>
					<div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div>
				</div>
			) ) }
		</div>
	);
}
```

And if you refresh the page in the browser, you should now see the list of books rendered in the editor!

# JavaScript fundamentals: var, let, and const
# JavaScript fundamentals: arrays and objects
# JavaScript's fundamentals: the map function

The `map` method takes a function as an argument, and that function is called for each item in the array. The function takes a single argument, which is the current item in the array. In this case, the current item is a book object.

To explain this, you could also write the above code like this:

```js
const bookData = books.map(bookContent);
function bookContent( book ){
    let bookContent = '<h2>' + book.title + '</h2>';
    
    let bookImage = book.featured_image;
	let bookContent = book.content;
	return bookTitle + bookContent + bookImage;
}
```

# React fundamentals: JSX
# Block editor fundamentals: data, core data, and useSelect
# JavaScript fundamentals: debugging
# React fundamentals: dangerouslySetInnerHTML