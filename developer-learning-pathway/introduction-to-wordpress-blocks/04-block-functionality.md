# Block Functionality

Now that you have a basic block set up, you can start adding the block's functionality.

This is usually a case of adding the block's functionality in the editor, via the Edit component, and how the block stores it's output, via the save function. 

Let's look at what it takes to add the block's functionality.

## Adding the block's Edit functionality

It's often a good idea to start by building out the block's `Edit` component, so it functions correctly in the editor.

At the moment, the block displays in the editor with the scaffolded text: "Copyright Date Block – hello from the editor!"

If you open the edit.js file in the `src` directory, and scroll to the bottom you'll see the following code:

```jsx
export default function Edit() {
	return (
		<p { ...useBlockProps() }>
			{ __(
				'Copyright Date Block – hello from the editor!',
				'copyright-date-block'
			) }
		</p>
	);
}
```

This is the code that displays the block in the editor, also known as the `Edit` component. There are a couple of things to note here.

The first is that the component returns what looks like a paragraph block with some text inside it.:

```jsx
return (
		<p { ...useBlockProps() }>
			{ __(
				'Copyright Date Block – hello from the editor!',
				'copyright-date-block'
			) }
		</p>
	);
```

This code is known as JSX, and it's a special syntax that looks like HTML, but it's actually JavaScript. So while the `<p>` tags might look like typical HTML, you can see that there is some code inside the tags, which is wrapped in curly braces `{}`.

The curly braces are used to indicate that the code inside them should be evaluated as JavaScript, and the result should be inserted into the JSX.

Learning about how JSX works is outside the scope of this lesson, but you can learn more about JSX on the [React](https://react.dev/learn/writing-markup-with-jsx) website.

At this stage, what's important is to note that the code returned by the `Edit` component is wrapped in a `<p>` tag. This is the parent container of this component, and any React component can only return a single parent container.

The second thing to note is the use of the `useBlockProps` function. This is a special function known as a React hook that is used to fetch the block's attributes.

You'll see `useBlockProps` it has the three dots `...` before it, which is known in JavaScript as the [spread syntax](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Spread_syntax). This takes the properties of an object and adds the objects key-value pairs to whatever it's being applied to.

Because `useBlockProps` returns an object with properties and values, the use of the spread syntax applies the properties and their values as attributes to the parent container.

Lastly, you will note that this scaffolded code is using the WordPress `__()` function. This is a special function that allows text to be translated for different languages, also known as Internationalization.

You can read more about [Internationalization](https://developer.wordpress.org/apis/internationalization/) in the WordPress Developer documentation.

For now, update the component to return something more appropriate to the requirements of your block. For example:

```jsx
return (
    <p { ...useBlockProps() }>
        { __(
            'Copyright',
            'copyright-date-block'
        ) }
		&copy; 2019 - 2024
    </p>
);
```

For the copyright symbol, you can use the HTML entity `&copy;`, which will be converted to the right symbol when it's rendered.

If you're wondering, the reason the symbol and year is rendered outside the `__()` function is because you only need to make the word "Copyright" translatable.

You probably don't want to hard code that date though, so you can use some JavaScript to get the current year as a variable, and replace the year with the variable.

```jsx
export default function Edit() {
	const currentYear = new Date().getFullYear().toString();
	return (
		<p { ...useBlockProps() }>
			{ __(
				'Copyright',
				'copyright-date-block'
			) }
			&copy; 2019 - { currentYear }
		</p>
	);
}
```

Once you've made the changes, save the file, and let the build run, or run the build command manually.

When you refresh the post editor, you should see the block now displays the word "Copyright" followed by the copyright symbol and the current year.

## Adding the block's save functionality

The next step is to update the block's save functionality, so it renders correctly on the front end.

The save function in the `save.js` file is what is run every time the block is saved in the Editor. This is the content that is stored in the `post_content` field in the database, and rendered on the front end.

Open the `save.js` file in the `src` directory, and you'll see the following code:

```jsx
export default function save() {
	return (
		<p { ...useBlockProps.save() }>
			{ 'Copyright Date Block – hello from the saved content!' }
		</p>
	);
}
```

This is very similar to what was scaffolded in the `Edit` component, with a couple of differences.

The biggest difference being that only a specific subset of the block's properties are applied to the parent tag, via `useBlockProps.save()`.

This is because the `save` function is only concerned with the properties that are relevant to the front end, and not the editor.

The other difference is that the code inside the parent tag is all on one line.

This is mostly irrelevant to the block's actually functionality, and it just shows a different way to write the same code. Some developers prefer to split out the code onto multiple lines, as it makes it more readable in some circumstances.

So you can update the `save` function to return the same content as the `Edit` component.

```jsx
export default function save() {
    const currentYear = new Date().getFullYear().toString();
	return (
		<p { ...useBlockProps.save() }>
			{ 'Copyright' } &copy; 2019 - { currentYear }
		</p>
	);
}
```

Once the updated block has been built, add the block to a post, and preview it. You should see the block now displays the saved markup.

## Additional resources

To read more about the fundamentals of developing blocks, you can read the [Fundamentals of Block Development](https://developer.wordpress.org/block-editor/getting-started/fundamentals/) section of the Block Editor handbook as well as the [Edit and Save guide](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/) 