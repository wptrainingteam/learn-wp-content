
## Do not repeat yourself

Developers generally like to avoid repeating code. If you look at the current code in your Edit component, you'll see some repeating code.

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
                    <h2>{ book.title.rendered }</h2>
                    <img src={ book.featured_image_src }/>
                    <div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div>
                </div>
            ) ) }
		</div>
	);
}
```

The parent container div, and the paragraph with the text "My Reading List – hello from the editor!" are being repeated in two places. 

If you decided to change that code you'd have to make the same change twice. 

To avoid this, you can create a custom component, which can handle the rendering of the books data, depending if there are books in the array or not. Then all you need is one return statement in your Edit component.

### Creating a custom component

To start, create a directory in your src directory called components.

Then create a new file in that directory called BookList.js.

At the top of this file, import the `Component` function from the `@wordpress/element` package.

```js
import { Component } from '@wordpress/element';
```

Then, create a new class called BookList, which extends the Component function.

```js
class BookList extends BookList {
    
}
```

[!Note] Remember last week when you learned that `class` is a reserved keyword in JavaScript, and you can't use it as a JSX attribute? This is what `class` does, it creates a new JavaScript [Class](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Classes), are a templates for creating objects.

All components must have a render method, which returns the markup for the component.

```js
class BookList extends BookList {
   render() {
       
   }  
}
```

Inside the render, you can return the same books.map loop that you did inside the Edit component and save function.

```js
class BookList extends BookList {
	render() {
		return books.map( ( book ) => (
			<div>
				<h2>{ book.title.rendered }</h2>
				<img src={ book.featured_image_src }/>
				<div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div>
			</div>
		) );
	}
}
```

You're going to need to pass the books from the Edit component and save function to the BookList component, so that it can render the books. To do this, you can use the `props` object of the component.

```js
const { books } = this.props;
```

This code will do something called [destructuring](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment), which means it will look for a property called `books` on the `props` object, and assign it to a variable called `books`.

Components will always have a `props` object, which is an object that contains all the properties that are passed to the component. In this case, you're going to pass the books to the BookList component, so that it can render them.

The final line of code you need for your BookList component is to export the component, so that you can import it into your Edit component and save function. You add this code right at the bottom of your BookList.js file.

```js
export default BookList;
```

The final component file should look like this:

```js
import { Component } from '@wordpress/element';

class BookList extends BookList {
	render() {
		return books.map( ( book ) => (
			<div>
				<h2>{ book.title.rendered }</h2>
				<img src={ book.featured_image_src }/>
				<div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div>
			</div>
		) );
	}
}

export default BookList;
```

Now, you can import the BookList component into your Edit component and save function, and use it to render the books.

At the top of your Edit component, import the BookList component.

```js
import BookList from './components/BookList';
```

It doesn't matter too much where you import the BookList component, but it's a good idea to keep all your imports together at the top of the file. One way to manage it is to import your internal components after any external ones.

Then, inside the Edit component, replace the books.map method with the BookList component, passing it the books array from the REST API fetch as a property.

```js
return (
    <div {...useBlockProps()}>
        <p>{'My Reading List – hello from the edit component!'}</p>
        <BookList books={books} />
    </div>
);
```

Now, you can do the same thing in the save function.

At the top of your save.js file, import the BookList component.

```js
import BookList from './components/BookList';
```

Then, inside the save function, replace the books.map method with the BookList component, passing it the books array from the REST API fetch as a property.

```js
return (
    <div {...useBlockProps.save()}>
        <p>{'My Reading List – hello from the saved content!'}</p>
        <BookList books={books} />
    </div>
);
```

You could save and test this out now, but do you see any other code that is being repeated anywhere?

The answer is the paragraph with the test being rendered when there are no books to display. You're repeating the same markup in both the Edit component and the save function, the only difference is the text. You're also repeating it two in both places. So let's move all of that to the BookList component.

In the BookList component, add an if statement to check if there are any books to display. You do this after you've destructured the books from the props object.

```js
const { books } = this.props;
if ( ! books ) {
    return null;
}
```

This will return null if there are no books to display, which means nothing will be rendered.

Now, because you're performing that check inside your BookList component, you can remove the check from the Edit component and save function.

Your Edit component should now look like this:

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
			<BookList books={books} />
		</div>
	);
}
```

And your save function should look like this:

```js
export default function save() {
	const books = select( 'core' ).getEntityRecords( 'postType', 'book' );
	
	return (
		<div {...useBlockProps.save()}>
			<p>{'My Reading List – hello from the saved content!'}</p>
			<BookList books={books} />
		</div>
	);
	
}
```

Now, test this out. Because you've made changes to your save function, first remove the block from the editor, and either save the draft, or hit Update.

Then refresh your browser, and add the block again.

If everything is working correctly, you should see the same output in the editor as you did before, and the books should be displayed in the front end of your site.

The only difference now is that your code is cleaner, as you've moved all the functionality related to rendering the list of books to a separate Component.

## Attributes

Last week you used the @wordpress/core-data package and it's selectors and hooks to fetch data from the REST API and display it in the Edit component of your block.

You might think the next step would be to update your save function to display the same data in the front end of your site.

However, if you go back to the start of this project, you may remember that there is one final requirement for the reading list block

- It will also be possible to set whether to display the book content, and whether to display the featured image.

You've already added the content and featured image to the block, but you haven't added the ability to enable or disable these fields.

It would be a good idea to implement this first in your Edit component. Then, once you have all the functionality you need in the Edit component, you can focus on the save function.

This is because in the save function, you only need to save the final output of whatever the user changes in the editor. So if the user enables or disables the content or featured image, you only need to save the final output, you don't need to save the data for the content and featured image separately.

To do this, you're going to need to add some attributes to your block.

### What are attributes?

Block attributes are the way that a block stores data. You can use attributes to store data about how the block functions, or data that makes up the content of the block.

Attributes are defined in the block.json file, and are then made available to the block in the Edit component and save function in the block's properties parameter.

Let's start with the first one, data about how the block functions. In your block, you're going to need to store whether the user has enabled or disabled the content and featured image.

### Adding attributes

To do this, you're going to add two attributes to your block, one for the content, and one for the featured image.

Open your block.json file, and add a new property called attributes, which is an object. It doesn't really matter where you add this property, but above "supports" is a logical place.

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "my-reading-list/reading-list-block",
  "version": "0.0.1",
  "title": "My Reading List Block",
  "category": "widgets",
  "icon": "smiley",
  "description": "Displays a list of books for a reading list.",
  "example": {},
  "attributes": {},
  "supports": {
    "html": false,
    "align": true,
    "color": {
      "background": true,
      "text": true
    }
  },
  "textdomain": "my-reading-list",
  "editorScript": "file:./index.js",
  "editorStyle": "file:./index.css",
  "style": "file:./style-index.css",
  "viewScript": "file:./view.js"
}
```

Now add two new properties to the `attributes` property, `showContent` and `showImage`. Each have a `boolean` type, and default to `true`.

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

Now, you can use these attributes in your Edit component and save function.

In your Edit component, you need to set up the parameter to accept the attributes. This is the first parameter of the Edit component, which is an object.

```js
export default function Edit( props ) {
```

[!TIP] `props` is a common name for this parameter, but you can call it whatever you want. It's a shorthand for properties.

Why not log the props object to the console, so you can see what it contains.

```js
export default function Edit( props ) {
    console.log( props );
```

Notice that the first property of the props object is called `attributes`, and it contains an object with the same properties as the attributes you defined in the block.json file.

```js
{
    attributes: {
        showContent: true,
        showImage: true
    }
}
```

At the same time, take a little look further down the list of properties, and you'll see that there is also a property called `setAttributes`, which is a function.

```js
{
	setAttributes : (...t)=>n.current(r.dispatch,r)[e](...t)
}
```

This function is used to update the attributes of the block. You can use it to update the attributes in the Edit component, and the save function.

You can use something called [destructuring assignment](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment), to "extract" the attributes and setAttributes from the props object, and make them available to the Edit component as local variables with the same name. Try it out by replacing the props parameter in the Edit component with `{ attributes, setAttributes }`.

```js
export default function Edit( { attributes, setAttributes } ) {
```

Then, log the attributes and setAttributes variables to the console.

```js
    console.log( attributes );
    console.log( setAttributes );
````

This time you'll just see the attributes object and the setAttributes function logged to the console.

### Using attributes

Now that you have the attributes available to you in the Edit component, you can use them to control whether the content and featured image are displayed.

As you know, the attributes variable currently contains two properties, `showContent` and `showImage`, which are both set to true.

So why not use the same destructuring assignment to extract them from the attributes object.

```js
const { showImage, showContent } = attributes;
```

Now, starting with the showImage you're going to use this attribute to control whether the image is displayed. Replace the img tag with the following code.

```js
{ showImage && <img src={ book.featured_image_src } /> }
```

What this line of code is doing is checking if the `showImage` attribute is true, and if it is, it will render the image. If it's false, it won't render the image.

Now, do the same for the content, and replace the div that uses dangerouslySetInnerHTML to render the content with following code.

```js
{ showContent && <div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div> }
```

If you refresh your browser, you won't see any difference, because both attributes are still set to true. But change the default of one of them to false in your block.json file, then refresh the page, and see what happens.

```json
  "attributes": {
    "showContent": {
      "type": "boolean",
      "default": true
    },
    "showImage": {
      "type": "boolean",
      "default": false
    }
  },
```

[!IMAGE OF MISSING IMAGE]

### Adding controls to manage attributes





