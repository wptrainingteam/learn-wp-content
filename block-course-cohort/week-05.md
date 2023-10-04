# Welcome

Welcome to week 5 of the Learn WordPress Developing your first block course cohort.

We're starting to get close to the end of this course, and you're making great progress. So far you've learned a bunch of new concepts, spanning PHP, JavaScript, React, and the WordPress Block Editor.

Last week, you connected your block to the book data using the WordPress REST API and the core-data package. You also learned about new concepts like anonymous functions, arrow functions, as well as how to debug issues in the Dev Tools console.

This week you will learn about how to create a custom component, which will help you to avoid repeating code.

After that you'll learn about how you can use attributes to enhance your block functionality, which will also teach you how to add controls to your block.

Good luck, and I look forward to seeing your progress.

# Week 5 Title to be determined

## Do not repeat yourself

Developers generally like to avoid repeating code. Having the same thing repeat twice or more is frowned upon, as it means future changes require more work, and makes the code difficult to maintain. 

If you look at the current code in your Edit component, you'll see some repeating code.

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

There are two return statements, and the parent container `div`, with the paragraph with the text "My Reading List – hello from the editor!", are being repeated in two places. 

If you decided to change that code you'd have to make the same change twice. 

To avoid this, you can create a custom React [component](https://react.dev/learn/your-first-component), which can handle the rendering of the list of books. The component can handle the check if there are books fetched from the REST API. 

Then all you need is one `return` statement in your `Edit` component, which renders the parent container, paragraph, and the custom component.

Think of components as special functions that return some JSX markup. You can pass data to them, and they can return markup based on that data.

### Creating a custom component

To start, create a new directory inside of your `src` directory called `components`.

Then create a new file in that directory called `BookList.js`.

Start by using the `export default` syntax to export a function called `BookList`.

```js
export default function BookList() {
	
}
```

This is the same syntax used to export the `Edit` component from the `Edit.js` file.

Inside this function, you're going to return the markup for the list of books. You can copy the code from the `Edit` component, and paste it inside the `BookList` function.

```js
export default function BookList() {
	return books.map( ( book ) => (
		<div>
			<h2>{ book.title.rendered }</h2>
			<img src={ book.featured_image_src } />
			<div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div>
		</div>
	) );
}
```

The main difference is that you're returning the output from the `books.maps` loop here, which returns the markup back to the `Edit` component. 

To make this work, you'll need to pass the `books` from the `Edit` component to the `BookList` component. You will do this by passing the books to the `BookList` component as a property. This property will be added to the `props` object of the `BookList` component. So you need to access the `props` object in the `BookList` component, which you do by adding a `props` parameter to the `BookList` function.

```js
export default function BookList( props ) {
```

[!Note] Components use `props` to communicate with each other. Every parent component can pass some information to its child components by giving them `props`. 

To access the books, you can use the dot notation to access the `books` property of the `props` object.

```js
books = props.books;
```

However, another way to do this is to use something called [destructuring assignment](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment), which means it will look for a property called `books` on the `this.props` object, and assign it to a variable called `books`. You can do this directly in the function parameter.

```js
export default function BookList( { books } ) {
```

You can also add the `if` statement that checks if there are any books to display. You can copy this from the `Edit` component. However, instead of returning any markup, you can return `null`, which means nothing will be rendered.

Add this above the original `return` statement

```js
	if ( ! books ) {
    	return null;
    }
```

Your final `BookList` component should look like this.

```js
export default function BookList( { books } ) {

	if ( ! books ) {
		return null;
	}
	
	return books.map( ( book ) => (
		<div>
			<h2>{ book.title.rendered }</h2>
			<img src={ book.featured_image_src } />
			<div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div>
		</div>
	) );
	
}
```

[REDACTED]

At the top of this file, import the `Component` [class](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-element/#component) from the `@wordpress/element` [package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-element/).

```js
import { Component } from '@wordpress/element';
```

Then, create a new class called `BookList`, which extends the `Component` base class.

```js
class BookList extends Component {
    
}
```

[!Note] Remember last week when you learned that `class` is a reserved keyword in JavaScript, and you can't use it as a JSX attribute? This is what `class` does, it creates a new JavaScript [Class](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Classes).

All components must have a `render` method, which returns the markup for the component.

```js
class BookList extends BookList {
   render() {
       
   }
}
```

Inside the render, you can return the same `books.map` loop that you did inside the `Edit` component. Because the component needs to return this markup back to where it's used, you use the `return` statement.

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

You're going to need to pass the books from the `Edit` component to the `BookList` component, so that it can render the books. To do this, you can use the `props` property of the component. Add this above the `return` statement.

```js
const { books } = this.props;
```

This code will do something called [destructuring assignment](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment), which means it will look for a property called `books` on the `this.props` object, and assign it to a variable called `books`.

Components will always have a `this.props` object, which is an object that contains all the properties that are passed to the component. In this case, you're going to pass the `books` to the `BookList` component, so that it can render them. You'll handle this part when you use the component in the `Edit` component. 

You can also add the `if` statement that checks if there are any books to display. You can copy this from the `Edit` component. However, instead of returning any markup, you can return `null`, which means nothing will be rendered.
Add this below the code where you extract the `books` from the `this.props` object.

```js
	if ( ! books ) {
    	return null;
    }
```

The final line of code you need for your BookList component is to export the component, so that you can import it elsewhere. You add this code right at the bottom of your BookList.js file.

```js
export default BookList;
```

The final component file should look like this:

```js
import { Component } from '@wordpress/element';

class BookList extends Component {

	render() {
		const { books } = this.props;

		if ( ! books ) {
			return null;
		}

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

[REDACTED]

Now, you can import the `BookList` component into your `Edit` component, and use it to render the books.

At the top of your `Edit` component, import the `BookList` component.

```js
import BookList from './components/BookList';
```

It doesn't matter too much where you import the BookList component, but it's a good idea to keep all your imports together at the top of the file. One way to manage it is to import your internal components after any external ones.

```js
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';

import BookList from './components/BookList';
```

Then, inside the `Edit` component, after you've fetched the array of books from the WP REST API, update the rest of the code to return the parent container, and use the `BookList` component. 

```js
return (
    <div { ...useBlockProps() }>
        <p>{ 'My Reading List – hello from the edit component!' }</p>
        <BookList />
    </div>
);
```

Notice how you use the JSX syntax to render the `BookList` component. 

Your last step is to pass the array of `books` to the `BookList` component. You do this by adding a property to the `BookList` component, called `books`, and passing it the `books` array.

```js
return (
	<div { ...useBlockProps() }>
		<p>{ 'My Reading List – hello from the edit component!' }</p>
        <BookList books={ books } />
    </div>
);
```

Because you have defined the `books` property when you use the `BookList` component, it will be passed to the `props` parameter object, and you can extract it.

Your updated Edit component should look like this.

```js
export default function Edit() {

	const books = useSelect(
		select =>
			select( coreDataStore ).getEntityRecords( 'postType', 'book' ),
		[]
	);

	return (
		<div { ...useBlockProps() }>
			<p>{ 'My Reading List – hello from the edit component!' }</p>
			<BookList books={ books } />
		</div>
	);
}
```

If everything is working correctly, you should see the same output in the editor as you did before. The main difference is that you've moved the code that renders the books to a separate component.

This makes your code cleaner, easier to maintain, and easier to reuse elsewhere. 

## Attributes

If you go back to the start of this project, you may remember that there is one final requirement for the reading list block

- It will also be possible to set whether to display the book content, and whether to display the featured image.

You've already added the content and featured image to the block, but you haven't added the ability to enable or disable these fields.

It would be a good idea to implement this first in your `Edit` component. Then, once you have all the required functionality you need in the `Edit` component, you can focus on the `save` function.

This is because in the `save` function, you only need to save the final output of whatever the user changes in the editor. So if the user enables or disables the content or featured image, you only need to save the final output, you don't need to save the data for the content and featured image separately.

To do this, you're going to need to add some attributes to your block.

### What are attributes?

[Block attributes](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/) are the way that a block stores data. You can use attributes to store data about how the block functions, or data that makes up the content of the block.

Attributes are defined in the `block.json` file, and are then made available to the block in the `Edit` component and `save` function through an object argument passed to both `Edit` and `save`.

For the My Reading List block, you need to store some data about how the block functions by storing whether the user has enabled or disabled the content and featured image.

## Adding attributes

To achieve this, you're going to add two attributes to your block, one for the content, and one for the featured image.

Open your `block.json` file, and add a new property called `attributes`, which is an object. It doesn't really matter where you add this property, but above `supports` is a logical place.

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

Now, you can use these attributes in your `Edit` component.

In your `Edit` component, you need to set up the parameter to accept the object of properties passed to the component. This is the first parameter of the `Edit` component.

```js
export default function Edit( props ) {
```

[!TIP] `props` is a common name for this parameter, but you can call it whatever you want. It's a shorthand for `properties`.

Why not log the `props` object to the console, so you can see what it contains.

```js
export default function Edit( props ) {
    console.log( props );
```

Notice that the first property of the props object is called `attributes`, and it contains an object with the same properties as the attributes you defined in the `block.json` file.

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

This function is used to update the attributes of the block. Remember this, because you will use it later.

Now, one way you could access the attributes in the Edit component is to use the dot notation to access them from the `props` object, and assign them to a variable.

```js
export default function Edit( props ) {
    const attributes = props.attributes;
```

A better way is to use the [destructuring assignment](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment) you learned earlier, to "extract" the `attributes` from the `props` object, and make them available to the `Edit` component as a local variable with the same name. 

You can do this by replacing the `props` parameter in the Edit component with `{ attributes }`.

```js
export default function Edit( { attributes } ) {
```

Then, log the attributes variable to the console.

```js
export default function Edit( { attributes } ) {
    console.log( attributes );
````

> [!Note] The destructuring assignment is a very useful feature of JavaScript, and you'll see it used a lot in React. It's worth taking some time to read the [documentation](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment) to understand how it works.

This time you'll just see the `attributes` object logged to the console.

## Using attributes

Now that you have the `attributes` available to you in the `Edit` component, you can use them to control whether the content and featured image are displayed.

However, that functionality has been moved to the BookList component, so you need to pass the attributes to the BookList component.

To do this, you can add a new property to the `BookList` component, called `attributes`, and pass it the `attributes` object.

```js
<BookList books={ books } attributes={ attributes } />
```

Then, in your `BookList` component, you can extract the `attributes` from the `props` object, the same way you extracted the `books`

```js
const { books } = this.props;
const { attributes } = this.props;
```

You can simplify this code even more, by using the destructuring assignment to extract both the books and attributes from the props object.

```js
const { books, attributes } = this.props;
```

> [!Note] Notice the difference between how you extracted the books and attributes from the props object in this custom component, vs in the Edit component. In the Edit component, you used the destructuring assignment in the function parameter, whereas in the BookList component, you used it in the function body. This is because the Edit component is set as the edit property of the block when the block is registered using registerBlockType. We'll cover this later on in this module. 

As you know, the `attributes` variable currently contains two properties, `showContent` and `showImage`, which are both set to true.

So why not use the same destructuring assignment again to extract them from the `attributes` object.

```js
const { showImage, showContent } = attributes;
```

Now, starting with the `showImage` variable you're going to use this attribute to control whether the image is displayed. In the `books.map` loop, replace the `img` tag with the following code.

```js
{ showImage && <img src={ book.featured_image_src } /> }
```

What this line of code is doing is using something called conditional rendering with the JavaScript [logical AND](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Logical_AND) operator to render the image tag if the `showImage` attribute is `true`. If it's `false`, then the image tag won't be rendered.

Now, do the same for the content, and replace the `div` that uses `dangerouslySetInnerHTML` to render the content with following code.

```js
{ showContent && <div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div> }
```

Your updated `BookList` component should look like this.

```js
import { Component } from '@wordpress/element';

class BookList extends Component {
	render() {

		const { books, attributes } = this.props;
		const { showImage, showContent } = attributes;

		if ( ! books ) {
			return null;
		}

		return books.map( ( book ) => (
			<div>
				<h2>{ book.title.rendered }</h2>
				{ showImage && <img src={ book.featured_image_src } /> }
				{ showContent && <div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div> }
			</div>
		) );
	}
}

export default BookList;
```

If you refresh your browser, you won't see any difference, because both attributes are still set to true. But change the default of one of them to false in your `block.json` file, then refresh the page, and see what happens.

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

[!Missing image](/images/attributes-01.png);

Notice how the image is no longer displayed, because the `showImage` attribute is set to false.

Change the `showImage` attribute back to true in the `block.json` file.

So now we have attributes that control whether the content and featured image are displayed. But how do we change them?

## Adding controls to manage attributes

To change the attributes, you need to make use of [Block Controls](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/block-controls-toolbar-and-sidebar/). There are two ways to add controls, either in the block toolbar, that appears above the block when it's selected, or in the settings sidebar (also knowing as the inspector), which appears in the sidebar when the block is selected.

Your block currently has the alignment control in the block toolbar, and the text and background color settings in the sidebar.

The block toolbar is generally used for controls that are more often used, or have a simpler UI. Things like the alignment control. For the reading list block, you're going to add the controls to the block sidebar, because a boolean attribute is usually best represented with an on/off control, like a toggle.

To add controls to the block sidebar, for your block, you're first going to need to import a few things.

- You'll need the `InspectorControls` component from the `@wordpress/block-editor` package.
- You'll need the `Panel`, `PanelBody`, and `ToggleControl` components from the `@wordpress/components` package.

Start by adding these imports to the top of your `Edit` component.

```js
import { InspectorControls } from '@wordpress/block-editor';
import { Panel, PanelBody, ToggleControl } from '@wordpress/components';
```

You're also going to need the extract the `showImage` and `showContent` attributes from the `attributes` object, so add the following code to the top of your Edit component, before you fetch the books from the REST API.

```js
const { showContent, showImage } = attributes;
```

Your block controls, whether you're adding toolbar or sidebar controls, are added just inside the parent container of your block, in this case the parent `div`. 

```js
return (
    <div {...useBlockProps()}>
        // Block controls go here
        <p>{'My Reading List – hello from the edit component!'}</p>
        <BookList books={books} attributes={attributes} />
    </div>
);
```

To start add the `InspectorControls` component inside the parent container `div`, and inside it, add the `Panel` and `PanelBody` components.

```js
<InspectorControls key="setting">
    <Panel>
        <PanelBody title="My Reading List Settings">
        </PanelBody>
    </Panel>
</InspectorControls>
```

Inside the `PanelBody` component, you can add the `ToggleControl` components. You need one for each attribute, so add two.

```js
<InspectorControls key="setting">
    <Panel>
        <PanelBody title="My Reading List Settings">
            <ToggleControl
                label="Toggle Image"
                checked={ showImage }
            />
            <ToggleControl
                label="Toggle Content"
                checked={ showContent }
            />
        </PanelBody>
    </Panel>
</InspectorControls>
```

Notice how the `ToggleControl` component has a `label` property, which is the text that will be displayed next to the toggle. It also has a `checked` property, which is the value of the attribute.

If you refresh your browser, you should see the two toggle controls in the block inspector.

[!Toggle controls](/images/attributes-02.png);

However, if you try and toggle either of them, nothing seems to happen. That's because you haven't added any functionality to them yet. 

Open your Dev Tools console, and you'll see an error logged every time you click on one of the toggles.

This is telling you that the `ToggleComponent` is expecting a property called `onChange`, which is a function that will be called when the toggle is clicked. So go ahead and add that for each of the toggles.

```js
<InspectorControls key="setting">
    <Panel>
        <PanelBody title="My Reading List Settings">
            <ToggleControl
                label="Toggle Image"
                checked={ showImage }
                onChange={ () => {} }
            />
            <ToggleControl
                label="Toggle Content"
                checked={ showContent }
                onChange={ () => {} }
            />
        </PanelBody>
    </Panel>
</InspectorControls>
```

As mentioned earlier, you need to set a function that will be called when the toggle is clicked in the `onChange` property. This function will be used to update the attributes of the block and will receive as a parameter the new value of the toggle.

```js
onChange={ (newValue) => { 
	/* do something with newValue */ 
} }
```

Remember the `setAttributes` function that was in the `props` object? This is the function you need to use to update the attributes of the block. You can use that to update the attributes value.

`setAttributes` is a function that takes an object as a parameter, and the object contains the attributes you want to update, and their new values. In this case you can use the `newValue` parameter as the value for the attribute.

First, you need to extract the `setAttributes` function from the `props` object, so it's available in the `Edit` component by adding it to the destructuring assignment in the `Edit` component.

```js
export default function Edit( { attributes, setAttributes } ) {
```

Then, in the `onChange` function for the image toggle, you can call the `setAttributes` function, and pass it an object with the attribute you want to update, and the new value.

```js
onChange={ (newValue) => { 
	setAttributes( { showImage: newValue } ); 
} }
```

Then do the same for the content toggle.

```js
onChange={ ( newValue ) => { 
    setAttributes( { showContent: newValue } ); 
} }
```

Now, when you toggle the controls, you should see the image and content disappear and reappear.

[!Toggle controls](/images/attributes-03.png);

You're probably wondering where the updated attribute value is stored, and how it's used to control the display of the image and content.

To see this, click on the Block Editor Options (the three dots on the top right of the editor), and then enable the `Code Editor` view.

[!Code editor](/images/attributes-04.png);

Notice how the block markup contains the following, right at the top of the block

```html
<!-- wp:my-reading-list/reading-list-block {"showContent":false,"showImage":false} -->
```

This is an HTML comment, and it's how block markup is saved. Here you can see the block name, and next to it an object containing the attributes and their values.

Switch back to the Visual Editor, and make some changes to the block.

- Enable the image but disable the content
- Change the alignment to wide alignment
- Change the background color

Then switch back to the Code Editor, and see how that changes the block markup.

```html
<!-- wp:my-reading-list/reading-list-block {"showContent":false,"align":"wide","backgroundColor":"cyan-bluish-gray"} -->
```

Notice how the attributes have been updated to reflect the changes you made in the editor.

At this stage, you have completed the requirements for the reading list block. You can now add books to the block, and control whether the content and featured image are displayed.

Your last step will be to update the save function, so that the block markup is updated to reflect the changes made in the editor, which we will tackle in next week's content.

# Components

Components are a handy tool when developing your blocks. Being able to take code that's either repeating, or that you want to be able to use elsewhere, and move it to a separate component, makes your code cleaner, easier to maintain, and easier to reuse.

Using components is also one of the best things about using a modern JavaScript framework like React. Consider the code for your block's sidebar settings:

```js
<InspectorControls key="setting">
	<Panel>
		<PanelBody title="My Reading List Settings">
			<ToggleControl
				label="Toggle Image"
				checked={ showImage }
				onChange={ (newValue) => {
					setAttributes( { showImage: newValue } );
				} }
			/>
			<ToggleControl
				label="Toggle Content"
				checked={ showContent }
				onChange={ ( newValue ) => {
					setAttributes( { showContent: newValue } );
				} }
			/>
		</PanelBody>
	</Panel>
</InspectorControls>
```

That's a lot of code in your Edit component that's not really going to change much over time. Wouldn't it be great if you could move it elsewhere? There's absolutely no reason you couldn't create a new component called `BlockControls`, move the controls to the component, and make your `Edit` component even cleaner.

Try it out yourself. Here's a hint, think about what it would take to make it possible to replace all the InspectorControls code in the Edit component, with this?

```js
<BlockControls attributes={ attributes } setAttributes={ setAttributes } />
```

### Importing components

You'll notice that when you imported the BookList component into the Edit component, you used a slightly different syntax as when you import components from Block Editor packages.

BookList

```js
import BookList from './components/BookList';
```

Block Editor components

```
import { Panel, PanelBody, ToggleControl } from '@wordpress/components';
```

You've probably already guessed that this is because your BookList component is a custom or built in component, whereas the Block Editor components are external components. 

Additionally, there are multiple components available in the Block Editor packages, so you need to use the curly braces to import them, using the destructuring assignment.


# Attributes
# Controls
# Passing props
# Destructing assignment
