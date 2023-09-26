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

This week you'll be working on accessing the book post type data and displaying it in your block. 

At the same time, you'll be introduced to a bunch of JavaScript, React, and Block editor concepts. Don't worry if they don't make sense straight away, there are additional lessons and resources in this week's content to help you learn more about these concepts.

This week you'll also start learning how to use your browsers built in developer tools to help you debug your code. Getting comfortable with your browser's developer tools, especially the developer tools Console, is an important skill to learn when you're developing with JavaScript.

Good luck, and I look forward to seeing your progress.

# Accessing WordPress data

Now would be a good time to think about adding the book data to your reading list block. Generally, in any JavaScript application, you'll need to access that data from an external source. 

In this case, you'll be accessing the book data from the WordPress REST API.

How the REST API works is beyond the scope of this course, but you can think of it as a way to access data from WordPress. 

If you want to read more about it you can do so in the [official WordPress REST API handbook](https://developer.wordpress.org/rest-api/). There's also [this course on Learn WordPress](https://learn.wordpress.org/course/developing-with-the-wordpress-rest-api/) which explains how to work with and use the REST API.

For now, all you need to know is that the REST API is how you fetch data from the WordPress database, and the block editor has a built-in way to access that data called the [@wordpress/core-data package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-core-data/).

Before you get into all that though, let's create some placeholder data for our books

## Placeholder data

Placeholder data is a way to create some data that you can use to test your code, without having to worry about accessing the real data until you need to. If you've ever built websites, you've probably used placeholder data or text like Lorem Ipsum before, to create pages or posts in order to demo your layout or designs to a client, before getting the actual content.

In this case, you'll be creating some placeholder data for your books, so that you can first focus on how the list of books should look and function in the editor, before replacing that placeholder data with real data from the REST API.

[!Note] Developing with placeholder data is not a requirement, but it does make things easier if you're learning more than one new thing at a time. In this case, you're learning how to both render a list of books in your block, AND how to connect to the REST API to fetch those books. Using some placeholder data allows you to focus on one thing at a time.

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
 - it uses the `const` keyword to declare the local variable. 
 - it uses square brackets `[]` to declare the array
 - it uses curly braces `{}` to declare each object in the array

Each object in the array has three properties: `title`, `content`, and `featured_image`. These are the same properties that you might expect from a list of books fetched from a REST API.

Creating placeholder data as an array of objects will more closely match the type of data structure you would expect from a REST API, which would return an  array of objects, where each object represents a book.

## Rendering the placeholder data

### Preparing the Edit component to return the book markup

Before thinking about how to use this placeholder data in the Edit component, take a look at the `Edit` component's `return` statement.

```js
	return (
		<p {...useBlockProps()}>
			{__('My Reading List – hello from the editor!', 'my-reading-list')}
		</p>
	);
```

In the last module you learned that the code inside the `return` statement is JSX, which uses an HTML like syntax. So while the `<p>` tags might look like typical HTML, you can see that there is some code inside the tags, which is wrapped in curly braces `{}`.  

JSX is a special React syntax extension for JavaScript that lets you write HTML-like markup inside a JavaScript file. 

You will learn more about how JSX works, and what the curly braces mean, later in this module. For now, notice that the code returned by the `Edit` component is wrapped in a `<p>` tag. This is known as the parent container, and any React component can only return a single parent container.

This means that if you wanted to add additional markup to the `Edit` components render statement, it would be a good idea to change the `<p>` tag to a `<div>` tag, so that you can add additional markup inside the `<div>` tag.

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

Let the development build server finish building, and then refresh the page in the browser. You should see the new HTML markup rendered in the editor.

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

Notice that you add the placeholder data above the `return` statement. The `return` statement is generally only used to return the output of the component, any other variables or functionality are usually added above the `return` statement.

Now you need a way to loop through the books array, and render the book data for each book in the array, with the defined markup.

You can use the JavaScript `map` [method](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/map) to loop through the array, and render the fields for each book inside the relevant markup.

```js
books.map( ( book ) => (
	<div>
		<h2>{ book.title }</h2>
		<img src={ book.featured_image }/>
		<p>{ book.content }</p>
	</div>
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

The code for your `Edit` component should now look like this:

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

The @wordpress/core-data package is built on top of the [@wordpress/data](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-data/) package, which provides what as known as a data store, which is a way to fetch data from the WordPress REST API. 

The @wordpress/core-data package uses the store and special functions called selectors select the data you need. The selector you'll be using from the core-data package is the `getEntityRecords` selector. 

Finally, you'll need to use the `useSelect` [hook](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-data/#useselect). 

`useSelect` will ensure that the `getEntityRecords` selector is re-run when the data is available in the store. 

This is because the data is fetched asynchronously, and you want to make sure the block content is rendered when the data is available.

The first step is to import the `useSelect` hook from the @wordpress/data package, and the `store` from the @wordpress/core-data package.

```js
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';
```

You can import these at the top of the file, just below the `import { useBlockProps } from '@wordpress/block-editor';` statement.

Next, you need to fetch the books from the REST API, using the useSelect hook, the coreDataStore and the getEntityRecords selector.

```js
    const books = useSelect(
        select =>
            select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
        []
    );
```

Don't worry if you don't entirely understand what this code is doing, you'll learn more about it in a future lesson.

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

## Debugging your JavaScript

Whenever you see an error like this, it means there's a problem with your code somewhere. Because JavaScript is run in the browser, it's possible to use the browser's built in developer tools to help you debug your code.

Most modern browsers have developer tools built in, and you can usually access it in multiple different ways.

These articles covers how to open the developer tools in [Chrome](https://developers.google.com/web/tools/chrome-devtools/open), [Firefox](https://developer.mozilla.org/en-US/docs/Tools), [Safari](https://developer.apple.com/safari/tools/), and [Edge](https://docs.microsoft.com/en-us/microsoft-edge/devtools-guide-chromium/open).

> [!TIP] By default, the developer tools may open docked at the bottom of the browser, but you can change it so that it's docked to the left or the right, which is very useful when debugging

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

For now, use the `/*` and `*/` characters to comment out the `book.featured_image` proprety in the image tag. Then update the title and the content to use the correct fields, from the REST API. 

```js
	return (
	<div {...useBlockProps()}>
		<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
		{ books.map( ( book ) => (
			<div>
				<h2>{ book.title.rendered }</h2>
				<img src={ /* book.featured_image */ }/>
				<p>{ book.content.rendered }</p>
			</div>
		) ) }
	</div>
);
```

Now, when you refresh the page in the browser, you should see the book title and content being rendered. But wait, the content looks all weird?

[image]

This is because the content was created in the block editor, and includes HTML, but that HTML is being rendered as the text versions of the HTML characters, but you need to render it as HTML. You can fix this by using the `dangerouslySetInnerHTML` React property on a div element.

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

> [!Note] The `dangerouslySetInnerHTML` property's name sounds scary, but it's safe to use here. You'll learn more about this property in a future lesson.

Refresh the page in the browser, and you should now see the book content rendered correctly.

[image]

The last piece of the puzzle is to fetch the featured image url. Because the image url is not included in the REST API endpoint for custom post types, you need to add it as a field to the REST API response using the `register_rest_field` function in PHP. Adding fields to a REST API response is outside of the scope of this course, so for now, add this code in your my-reading-list.php file, under the `register_post_type` function.

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

So now, you can add your image markup back to your list of books, and use the `featured_image_src` property.

```js
<img src={ book.featured_image_src }/>
```

At the same time, you can remove that console.log() call.

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

Congratulations, you've successfully connected your block to the WordPress core data package to display the books in your block.

Unfortunately you can't preview these changes on the front end yet, because you need to make some additions to the save function first. 

However, those additions are going to add some complexity to your code, and so you'll be learning about how to reduce that complexity through custom components, and so we'll focus on learning that in the next module.

# Developer Tools and the Console

This week you've learned a lot about programming in JavaScript, from creating variables like arrays and objects, to using stores to fetch data from a REST API. So now would be a good to learn a bit more about some of these concepts.

Before you do that though, let's review the browser Developer Tools Console, and how useful it is.

A browser's Developer Tools provides so much useful information to the web developer, which could be the subject of an entire course of its own. However, two tabs are useful to highlight at the moment.

The first tab is usually the Elements. Here you can view the HTML source code of the page, see what CSS is applied to each element, and inspect and trigger any event listeners on the elements.

For example, wherever you have the Reading List block in the editor, right click on it and select Inspect from the context menu. 

You will see that it opens the Elements tab, and highlights the parent container of the block. Then below that in the Styles area, you can see the CSS applied to that element. If you scroll down a little, you can see the CSS being applied to the .wp-block-my-reading-list-reading-list-block blocks from your block's editor.scss file. 

You can also make changes to the HTML and CSS of the page in this tab. For example, click on the CSS property being applied to the border of the block, and change it from 5px to 10px. If you hit enter, the new CSS property takes effect, and the border is thicker. This is only applied in the browser, and if you refresh the page, it will read the CSS property value from the source file again.

The Console tab is usually the second tab, and this allows you to see any JavaScript errors being reported, as well as log anything to the Console using console.log(). console.log is very handy when you need to see what the value is of a variable. 

However, it's also possible to execute JavaScript code in the Console. 

Why not try it out?

Open the Console, paste the following code into the Console, and hit enter. 

```js
const names = ['John', 'George', 'Paul', Ringo];
```

Then, log the names array to the Console.

```js
console.log(names);
```

Using the Console like this is a great way to test out any JavaScript code you're working on.

For example, try this in the Console;

```js
const books = wp.data.select( 'core' ).getEntityRecords( 'postType', 'book' );
```

Now, log the books to the console

```js
console.log(books);
```

Recognize this? It's the same list of books you were working with in your Edit component.

```js
const books = useSelect(
		select =>
			select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
		[]
	);
```

There are some slight differences, which we'll cover later on, but using the getEntityRecords selector from the core data store is exactly the same.

Using the Console to test and inspect the results of your JavaScript code is a very useful skill to have.

# JavaScript concepts

Now that you have somewhere to test you JavaScript code, let's learn a bit more about the JavaScript programming concepts you learned this week. This lesson is only going to cover the basics of these concepts, and will link to the relevant MDN pages for further reading.

> Tip, why not try out all these JavaScript code snippets in the Console, and then log their results to the Console.

## Variables

If you have no idea what a variable is, think of them like containers for data. Whenever you need to use data in your code, you usually create a variable to contain that data. 

In JavaScript originally, the way to create a variable was to use the var statement:

```js
var name = 'John';
```
As JavaScript evolved, two new statements were added, let and const

let is used when a variable's value might change later on in the code execution. 

```js
let orderCount = 0;
if (newOrder){
	orderCount = orderCount + 1;
}
```

const is used when the variable's value may not be changed. So generally when variables are created to hold data like the list of books, it's expected that this data won't change, so const is used.

There are also differences between let and const when it comes to something called variable scope, but that's outside of the scope of this lesson.

Variables can also be different types, strings, numbers, booleans (true or false) etc. 

## Arrays and objects

Arrays and objects are special types of variables.

Arrays can contain multiple items, are defined by using either let or const, and use square brackets [] to indicate an array of items. The items are separated by a comma.

```js
const names = ['John', 'Paul', 'George', 'Ringo'];
const ages = [20, 36, 47, 58];
```

You can access array items by their numeric index, starting with 0;

```js
console.log(names[0]);
```

Objects are like more advanced arrays. Objects can contain multiple items, but each item can also have a property and a value. Objects use curly brackets to indicate an object.

```js
const person = {
	name: 'John',
    age: 20
}
```

As you have already learned, it's also possible to have an array of objects, for more complex sets of data

```js
const people = [
	{
		name: 'John',
        age: 20
    },
	{
		name: 'Paul',
        age: 36
    },
	{
		name: 'George',
        age: 47
    },
	{
		name: "Ringo",
        age: 58
    }
]
```

## JavaScript functions

Functions in JavaScript are a way to contain specific bits of code, to make that code reusable. Functions usually have a name, and some parameters that you pass to the function (think of parameters like special function variables). When you call a function, you usually pass some value, either the raw data, or a variable representing the data, to the function as the function's parameters, the function performs some action with the parameter, and returns the result of that action. Originally in JavaScript you used the function statement to declare a function;

```js
function calculateNameLength(name){
	return name.length;
}

const nameLength = calculateNameLength('John');
```

In modern JavaScript, there is now a new way to create a function, called an arrow function;

### Arrow functions

With arrow functions, you can create a variable which will contain the functions' code. then you can call the function in the same way as before. 

```js
const calculateNameLength = (name) => {
	return name.length;
}

const nameLength = calculateNameLength('John');

console.log(nameLength);
```

There are even more simplied versions of declaring arrow functions, but they are outside of the scope of this lesson. You can read more about this on the [MDN page for arrow functions](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Functions/Arrow_functions).

### Anonymous functions

Anonymous functions allow you to make use of a function, without needing to first define it. 

In the earlier lesson, you learned about the Array.map method, which allows you to loop over an array of items. If you read the documentation for Array.map, you will see that the Array.map method parameter is a function. This means you have to define a function to call in the Array.map, and set up that function accept a parameter which will accept the item from the array during the loop.

Here is an example of that using the names array


```js
const calculateNameLength = (name) => {
	return name.length;
}

const names = ['John', 'Jane', 'Peter', 'Penny'];

const nameLengths = names.map(calculateNameLength);
````

The Array.map method's return type is an array, so it loops through the names array, calls the calculateNameLength function for each name in the array, and returns an array of items with the result of all those function calls, in this case the lengths.

In this example though, you're only creating the calculateNameLength function to be able to use it in the Array.map method call. You could just as easily use an anonymous function.

```js
const names = ['John', 'Jane', 'Peter', 'Penny'];

const nameLengths = names.map( (name) => {
            return name.length;
        } );

console.log(nameLengths);
```

Notice how the code for the function has been replaced inside the Array.map instead of the function name. So it's still calling that function code, but without needing to declare the function first. 

Look familar? It should, because this combination of an anonymous function and arrow functions is the same type of code you used for the books loop in your Edit component.

```js
books.map( ( book ) => (
    <div>
        <h2>{ book.title }</h2>
        <img src={ book.featured_image }/>
        <p>{ book.content }</p>
    </div>
) )
```

The differences there were that you weren't storing the results in a variable, and that code was wrapped in curly braces {} (which you'll get to when you learn more about JSX later), but the Array.map loop, and calling the anonymous arrow function to do something with each book item is the same.

As you learned earlier, there are also ways to simplify the syntax of arrow functions, especially when using them as anonymous functions. Take a look at the code you used to fetch the list of books:

```js
const books = useSelect(
    select =>
        select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
    []
);
```

Like Array.map, the first parameter to the useSelect hook is function. So you can use an anonymous function in the same way.

```js
const books = useSelect( 
    ( select ) => {
        return select( coreDataStore ).getEntityRecords( 'postType', 'book' );
    }, 
    [] 
);
```

However, as there is only one parameter, you can remove the parentheses around the parameter:

```js
const books = useSelect(
    select => {
        return select( coreDataStore ).getEntityRecords( 'postType', 'book' );
    },
[]
);
```

Additionally, because the function is only returning the result of data store selector, you can remove the curly braces and the return statement, and just return the result of the function call:

```js
const books = useSelect(
    select => 
        select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
    []
);
```

Can you guess what the empty square brackets are for? It's the second parameter to the useSelect hook, which is an array of dependencies. As you learned earlier, JavaScript arrays are indicated by square brackets, and the empty array indicates that there are no dependencies for the useSelect hook here. 

# React concepts

React is a JavaScript framework. Think of a programming framework as a set of tools built on top of a programming language, that make certain tasks easier. One of the biggest things that React offers to make development easier is JSX.

## JSX

As you learned earlier in this week's content, JSX is a React syntax for writing HTML code to be rendered in the browser. The React developers made JSX look very similar to HTML, so when you render a `<p>` tag in a React component, it will render an HTML `<p>` tag. 

However, it's important to note that the `<p>` you code in JSX is not the same as a `<p>` tag you would code in HTML. 

The JSX `<p>` tag just represents a `<p>` tag in HTML, and the JSX code will eventually be transpiled during the build step, and only the final output will be an HTML `<p>` tag. 

The JSX syntax does make it easier to write semantic HTML markup, but it can be confusing the first time you use it, when you try to do things in HTML that don't work the way you expect.

One of the first things about JSX you've already encountered is that a JSX component can only render one parent container. 

So, if you need to render multiple html tags, you have to render them inside a parent container tag, usually a div:

```js
return (
	<div>
        <h1>Header</h1>
		<p>Content</p>
        <span>
            <a href="" />
        </span>    
    </div>    
)
```

In code for the block editor, you probably also noticed the use of the curly braces {}. 

```js
return (
	<div>
		<h2>{ book.title }</h2>
		<img src={ book.featured_image }/>
		<p>{ book.content }</p>
	</div>
)
```

In JSX the curly braces show something to be evaluated as JavaScript. So whenever you need to render any JavaScript code (variables, conditional statements, anonymous functions) you would wrap them in curly braces {}

Go back and take a look at the code in your Edit component's return statement, and notice all the places where curly braces are used to evaluate some JavaScript variable, function call, or anonymous function.

The other thing to note about JSX is that it doesn't always use the same HTML attributes as true HTML elements would. One example of this is `class`. Because `class` is a already used for something else in JavaScript, React uses the [DOM Element Properties](https://developer.mozilla.org/en-US/docs/Web/API/Element#instance_properties) [className](https://developer.mozilla.org/en-US/docs/Web/API/Element/className) instead.

## dangerouslySetInnerHTML

In this module you used the dangerouslySetInnerHTML property to render the book content as HTML instead of the text characters that represent the HTML in the book content. 

dangerouslySetInnerHTML is a React [property](https://react.dev/reference/react-dom/components/common#common-props) that allows you to render HTML markup as HTML, instead of as text. The name makes it sound like it's dangerous to use, but in this case it's safe to use, because the data is coming from the WordPress REST API, and therefore any HTML markup should be safe from malicious code.

If it helps, WordPress core uses this property in the block editor to render the content of the post in the editor, so it safe for you to use here as well.

# Block editor concepts

## useBlockProps

The useBlockProps hook is a React hook that is used to add the block's CSS class names and attributes to the block's container element. 

Want to see what happens when you don't use the useBlockProps hook? Try removing it from the parent div in the returns of Edit component, and then refresh the page in the browser.

> [!Tip] Remember there are two places you need to remove the useBlockProps hook.

```js
    if ( ! books ) {
		return (
			<div>
				<p>{__('My Reading List – hello from the editor!', 'my-reading-list')}</p>
			</div>
		);
	}

	return (
		<div>
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
```

Notice a few changes. The border has dissappeared, because the generated classname hasn't been added to the container div. Now, try removing the block from the editor. You can't, because the block toolbar doesn't appear when you click on the block.

These are just a few things that useBlockProps does for you, so you should always apply it to the block parent container.

Notice how you use it in the Edit component vs the save function.

In the Edit component, you use it like this:

```js
<div {...useBlockProps()}>
```

In the save function, you use it like this:

```js
<div {...useBlockProps.save()}>
```

This is because the useBlockProps needs to perform different actions depending on whether it's being used in the Edit component, or the save function.

Finally, notice the use of the `...`. This is known in JavaScript as the [spread syntax](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Spread_syntax), which takes the properties of an object and adds the objects key-value pairs to whatever it's being applied to. `useBlockProps` returns an object with properties and values, and the spread syntax applies the properties and their values as attributes to the parent container. 

A very simplified version of the useBlockProps hook would look like this:

```js
function useBlockProps(){
	return {
		className: 'wp-block-my-reading-list-reading-list-block'
    }
}
```

And when it's used on the Edit component with the spread syntax, it means the `div` would look like this

```html
<div className={"wp-block-my-reading-list-reading-list-block"}>
```

# Block editor concepts