# Building your first block

Once you've scaffolded your block, you can start building out the functionality. Let's dive into what this might look like for our Copyright Date Block.

## The main plugin file

Navigate to the `copyright-date-block` directory in your code editor and then open the copyright-date-block.php file. 

There's not a lot to be changed in this file, except maybe the @package annotation in the plugin header. This defaults to create-block and you might want to change it to something more specific to your plugin. 

For the purposes of this lesson, let's change it to `copyright-date`.

As you can see the code uses the `register_block_type` function to register the block using the metadata from the block.json file.

Therefore, now would be a good time to review the block.json file.

## Block Metadata

Navigate to the `src` directory and open the block.json file. It contains the block metadata, in a JSON format.

JSON stands for JavaScript Object Notation, and it's a lightweight data format that is easy for humans to read and write and easy for machines to parse and generate. JSON is made up of key-value pairs, and each value can also be a nested JSON object.

To modify the scaffolded block metadata for your block, you should change at leas the following values:
 - update the name. In this case you can replace `create-block` with the same value you used for the package value in the plugin header, `copyright-date`.
 - update the icon. For now, change the `icon` value to `calendar`. This icon comes from the [Gutenberg Icon Library](https://wordpress.github.io/gutenberg/?path=/story/icons-icon--library).
 - update the description, to make it more specific to your block.

Your block.json file should look something like this:

```json
{
	"$schema": "https://schemas.wp.org/trunk/block.json",
	"apiVersion": 3,
	"name": "copyright-date/copyright-date-block",
	"version": "0.1.0",
	"title": "Copyright Date Block",
	"category": "widgets",
	"description": "A Copyright Date block.",
	"example": {},
	"supports": {
		"html": false
	},
	"textdomain": "copyright-date-block",
	"editorScript": "file:./index.js",
	"editorStyle": "file:./index.css",
	"style": "file:./style-index.css",
	"viewScript": "file:./view.js"
}
```

## Your first build

Now that you've updated some block code, you can build your block for the first time.

The process of building your block code, also known as bundling your code, is the process of converting your block code into a format that is compatible with all browsers.

The `@wordpress/scripts` package you learned out in the Scaffolding your block lesson uses a tool called Webpack to bundle your block code. The details of how this works is outside the scope of this lesson, but you can learn more about it in the [Webpack documentation](https://webpack.js.org/concepts/).

To build your block, open a terminal and navigate to the root of your block plugin directory. Then run the following command:

```bash
npm run build
```

This will scan through the contents of your `src` directory, and compile the files from that directory into the `build` directory. If the `build` directory doesn't exist, it will be created.

Whenever you make changes to your block code, you'll need to run this command again to update the build directory.

Optionally, there is a `npm run start` command that will start a development server that watches for changes to the files in the `src` directory and automatically builds them into the `build` directory. 

```bash
npm run start
```

This is useful for when you're actively developing your block.

Whichever option you use, if you open your WordPress dashboard, create a new Post, and add your block, you should see the block in the block inserter.

You'll notice that the icon has changed, and the description is more specific to your block.

## Adding the block's functionality

Now that your block works, you can add the initial functionality to it.

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

This is the code that displays the block in the editor, also known as the Edit component. Notice how the component returns what looks like a paragraph block with some text inside it.:

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

For now, notice that the code returned by the `Edit` component is wrapped in a `<p>` tag. This is known as the parent container, and any React component can only return a single parent container.

You will also see this scaffolded code is using the WordPress `__()` function. This is a special function that allows text to be translated for different languages, also known as Internationalization.

For now, update the component to return something more appropriate for your block. For example:

```jsx
return (
    <p { ...useBlockProps() }>
        { __(
            'Copyright',
            'copyright-date-block'
        ) }
		&copy; 2024
    </p>
);
```

To get the copyright symbol, you can use the HTML entity `&copy;`, which will be converted to the right symbol when it's rendered.

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
			&copy; { currentYear }
		</p>
	);
}
```

Once you've made the changes, save the file, and let the build run, or run the build command manually.

When you refresh the post editor, you should see the block now displays the word "Copyright" followed by the copyright symbol and the current year.