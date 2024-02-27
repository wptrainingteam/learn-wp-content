# Block Attributes

One of the benefits of building blocks is the ability to allow users to control the block's appearance and behavior via block attributes.

Let's learn how to add attributes to a block, and now to add controls to your block to allow users to change those attributes.

## Adding attributes to a block

Attributes are the properties of a block that can be controlled by the user. For example, for the Copyright Date Block, the starting year should be an attribute that the user can change.

To add attributes to a block, you define them in the block's metadata in the `block.json` file.

Open the `block.json` file in the `src` directory, and add the following code:

```json
"attributes": {
    "startingYear": {
        "type": "string",
        "default": "2000"
    }
},
```

One of the benefits of the JSON format, is that you can add a new property anywhere in the existing JSON object, as long as you use a property name that's expected. In this case the `attributes` property is expected by the block registration process, so you can add it anywhere in the JSON object.

In this example, we've added an attribute called `startingYear` to the block. The `type` property defines the data type of the attribute, and the `default` property sets the initial value of the attribute.

If you were running the development server, you might notice that it crashes when you edit the block metadata. If this happens, just restart the development server.

## Access the block's attributes

To access the block's attributes in the block's Edit component, you can specify a `props` argument in the Edit component function. 

Both the Edit component and the save function are set up to always accept this props object containing all the properties of the block.

```jsx
export default function Edit( props ) {
```

You can then access the block's attributes using the `props` object. For example, to access the `startingYear` attribute, you would use `props.attributes.startingYear`.

```js
export default function Edit( props ) {
	const startingYear = props.attributes.startingYear;
	const currentYear = new Date().getFullYear().toString();
	return (
		<p { ...useBlockProps() }>
			{ __(
				'Copyright',
				'copyright-date-block'
			) }
			&copy; { startingYear } - { currentYear }
		</p>
	);
}
```

You can also update the save function to include the block's attributes. 

```js
export default function save( props ) {
	const startingYear = props.attributes.startingYear;
	const currentYear = new Date().getFullYear().toString();
	return (
		<p { ...useBlockProps.save() }>
			{ 'Copyright' } &copy; { startingYear } - { currentYear }
		</p>
	);
}
```

Instead of writing out `props.attributes.startingYear` every time you want to access the starting year attribute, you can use something call the [destructuring assignment](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment) syntax to extract the `attributes` from the `props` object, and then again to extract the `startingYear` from the `attributes`.

```js
export default function Edit( { attributes } ) {
    const { startingYear } = attributes;
```

```js
export default function save( { attributes } ) {
    const { startingYear } = attributes;
```

If you're new to object destructuring, it's a way to extract properties from an object and assign them to variables. It seems a little strange at first, but once you get used to it, it saves a lot of time, and code. 

Let the build process complete, then add the block to a post or page, and you'll see that the block now displays the starting year attribute, with the default value you defined.

## Block recovery

If you happen to be testing your block in a post or page and refreshing your browser each time you make changes to the block attributes default value or the `save` function, you might sometimes run into this error:

```php
This block contains unexpected or invalid content.
```

This is because when a block's `save` function is run, it compares the output from the `save` function with the output already saved in the database. If they are different, it shows this error.

If you open the Console tab of your browser's developer tools, you'll see this reported as a Block validation error.

You can fix this by using the "Attempt Block Recovery" button, which re-renders the block, and re-saves it's output.

## Adding a Settings panel to the block

To allow users to change the block's attributes, you need to make use of [Block Controls](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/block-controls-toolbar-and-sidebar/). 

There are two ways to add controls, either in the block toolbar, that appears above the block when it's selected, or in the settings sidebar (also knowing as the inspector), which appears in the sidebar when the block is selected.

Because the startingYear attribute is a text string, you can use a `TextControl` to the block sidebar to allow users to change the starting year.

To add controls to the block sidebar for your block, you're first going to need to import a few things.

- You'll need the `InspectorControls` component from the `@wordpress/block-editor` package.
- You'll need the `PanelBody` and `TextControl` components from the `@wordpress/components` package.

Start by adding these imports to the top of your `Edit` component.

Open the `edit.js` file in the `src` directory, and look for the line that imports `useBlockProps`. 

```jsx
import { useBlockProps } from '@wordpress/block-editor';
```

The `InspectorControls` component can also be imported from the `@wordpress/block-editor` package in the same way.

```jsx
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
```

The `PanelBody`, and `TextControl` components can be imported in the same way from the `@wordpress/components` package.

```jsx
import { PanelBody, TextControl } from '@wordpress/components';
```

You can now use these components to add controls to the block sidebar.

Start by adding the `InspectorControls` component to the output of the `Edit` component. This component is a wrapper for the controls that appear in the block sidebar. 

Then add a `PanelBody` component, and give it a title attribute. 

```jsx
<InspectorControls>
    <PanelBody title={ __( 'Settings', 'copyright-date-block' ) }>
		Settings
    </PanelBody>
</InspectorControls>
```

In the Building your first block lesson, you will remember that a React component can only return a single parent container. 

Right now you don't have a single parent container, because you've added the `InspectorControls` component next to the paragraph component. 

This is also the reason your IDE might start showing you that the code has a problem

At this stage you have two options. 

You could update the Edit component to render a parent `div` tag, moving the block props to the parent `div`.

```jsx
<div { ...useBlockProps() }>
    <InspectorControls>
        <PanelBody title={ __( 'Settings', 'copyright-date-block' ) }>
            Testing
        </PanelBody>
    </InspectorControls>
    <p>
        { __(
            'Copyright',
            'copyright-date-block'
        ) }
        &copy; { startingYear } - { currentYear }
    </p>
</div>
```

Alternatively you can use a [React Fragment](https://react.dev/reference/react/Fragment) to wrap everything in a single parent container.

```jsx
<>
    <InspectorControls>
        <PanelBody title={ __( 'Settings', 'copyright-date-block' ) }>
            Testing
        </PanelBody>
    </InspectorControls>
    <p { ...useBlockProps() }>
        { __(
            'Copyright',
            'copyright-date-block'
        ) }
        &copy; { startingYear } - { currentYear }
    </p>
</>
```

Because the functionality of the block only really requires the paragraph tag, using the Fragment is the best option in this case. If your block required more markup, like a header tag above the paragraph, then using the `div` option might make more sense.

Once the build process has finished, if you add the block to a post or page, and enable the Editor's Settings sidebar, you'll see the Settings panel you added to the block sidebar.

## Adding a TextControl to the block sidebar

With the `PanelBody` component in place, you can now add a `TextControl` component to allow the user to edit your attribute.

The `TextControl` component is a text input field that allows the user to enter a string. There are three properties you need to set on the `TextControl` component. The first two are the label and value:

- `label`: the label that appears above the input field
- `value`: the value of the input field

Let's look at what this would look like

```jsx
    <PanelBody title={ __( 'Settings', 'copyright-date-block' ) }>
        <TextControl
            label={ __( 'Starting Year', 'copyright-date-block' ) }
            value={ startingYear }
        />
    </PanelBody>
```

The other property you need to set is the `onChange` property. This property is a function that is called when the value of the input field changes, which receives the new value entered by the user. 

```jsx
    <PanelBody title={ __( 'Settings', 'copyright-date-block' ) }>
        <TextControl
            label={ __( 'Starting Year', 'copyright-date-block' ) }
            value={ startingYear }
			onChange={ ( newStartingYear ) => {
				// update startingYear with newValue
			} }
        />
    </PanelBody>
```

This function is then used to update the block's `startingYear` attribute.

The syntax of this function is a little different to what you've seen before. This is an example of the [arrow function syntax](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Functions/Arrow_functions), which is a more compact way of writing functions in JavaScript.

In order to update the attribute, you can use one of the other properties on the `props` object that is passed to the Edit component, the `setAttributes` function. This function is used to update any block's attributes with a new value.

You can add `setAttributes` to the list of properties being destructed from the `props` object, and then use it to update the `startingYear` attribute.

```jsx
export default function Edit( { attributes, setAttributes } ) {
```

Then to update the `startingYear` attribute, you would use the `setAttributes` function, and pass it the new value of the `startingYear` attribute.

```jsx
    <PanelBody title={ __( 'Settings', 'copyright-date-block' ) }>
        <TextControl
            label={ __( 'Starting Year', 'copyright-date-block' ) }
            value={ startingYear }
            onChange={ ( newStartingYear ) => {
                setAttributes( { startingYear: newStartingYear } );
            } }
        />
    </PanelBody>
```

Let the build process complete, and then add the block to a post or page. You'll see that you can now change the starting year value in the block's sidebar, and the block will update in real time.

You can also switch the block editor to the Code Editor view, and you'll see the starting Year value stored as a JSON object on the block wrapper.

Finally, if you preview the block, it uses the new value of the `startingYear` attribute.

## Additional resources

For further reading on these topics, make sure to check out the [Attributes](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/) guide in the BLock Editor Handbook.