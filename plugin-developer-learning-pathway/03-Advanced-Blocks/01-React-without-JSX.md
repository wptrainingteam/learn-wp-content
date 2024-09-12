# Developing WordPress Blocks without React or a Build Step

One of the benefits of working with WordPress, is that there are often many ways to do the same thing. 

While the block development examples you've seen so far make use of JSX for rendering, and `@wordpress/scripts` for building, you can also develop blocks without using these features.

However, there are some things to be aware of when developing blocks this way.

In this lesson, you will learn what block without JSX or a build step could look like, and what the pros and cons are of both approaches.

## The Copyright Date Block

If you followed the [Introduction to developing WordPress blocks](https://learn.wordpress.org/lesson/scaffolding-a-new-block/) module in the Beginner Developer Learning Pathway, you build a Copyright Date block. This block is based on the [Build your first block tutorial](https://developer.wordpress.org/block-editor/getting-started/tutorial/) from the WordPress Block Editor Handbook.

If you didn't follow those lessons, you can download the block from the [WordPress Training Team GitHub repository](https://github.com/wptrainingteam/plugin-developer/blob/trunk/copyright-date-block.0.1.0.zip).

This block was built using the `create-block` tool, uses JSX for the block content in the editor and when the content is saved, as well as `@wordpress/scripts` for building the block.

In the same WordPress Training Team GitHub repository, you can also find a [version of the Copyright Date block](https://github.com/wptrainingteam/plugin-developer/blob/trunk/rps-copyright-date-block.1.0.0.zip) that doesn't use JSX or a build step. 

Download that file now, install it in your local WordPress site, and activate it.

Then add the block to a post or page, and you'll see it has all the same functionality as the JSX version.

## Comparing the JSX and non-JSX versions

Let's open the non JSX version of the block in your code editor, and compare it to the JSX version.

The first thing you'll notice is that the non JSX version has a different file structure. This is because you don't need both a `src` and `build` folder, all the block code can be located in a single location.

For the purposes of this plugin, the block code is located in the `block` directory.

Before you open that directory, take a look at the main plugin file, `rps-copyright-date-block.php`. 

```php
add_action( 'init', 'rps_copyright_date_block_block_init' );
function rps_copyright_date_block_block_init() {
	register_block_type( __DIR__ . '/block' );
}
```

You will notice this is very similar to the JSX version, with the main difference being that the path passed to the `register_block_type()` function is the path to the `block` directory, rather than the `build` directory.

Now take a look at the `block` directory. It has a block.json file, an index.asset.php file, an index.js file, and a style-index.css file.

The `block.json` file is exactly the same as the JSX version. 

The `index.asset.php` file is also very similar, the main difference being that the `dependencies` array is includes the `wp-polyfill` dependency, which is required developing blocks without support for ES2015+ language features and APIs.

The `block.json` and `index.asset.php` files must be named this way to work when calling `register_block_type` with a path to a block directory.

The `style-index.css` file contains the CSS for the block. You'll notice this file name is the value for the style property in the block settings in the `block.json` file. You could rename this to `style.css` if you prefer, as long as you update the `block.json` file to reflect this change.

Finally, the `index.js` file contains all the block code. 

The first thing you'll notice is that the block code is wrapped in an [Immediately Invoked Function Expression or IIFE](https://developer.mozilla.org/en-US/docs/Glossary/IIFE). 

```js
(function() {
    // Block code here
})();
```

This is to prevent any variables from this code leaking into the global scope.

Next you will see all the of the dependencies that are required for the block to work.

```js
	const __ = wp.i18n.__;

	const createElement = window.wp.element.createElement;

	const { useBlockProps, BlockControls, AlignmentControl, InspectorControls } = window.wp.blockEditor

	const { PanelBody, TextControl } = window.wp.components

	const registerBlockType = window.wp.blocks.registerBlockType;
```

This is equivalent to importing the dependencies in the JSX version. All the WordPress Javascript components are available on the `wp` object.

The one new thing you'll see is `createElement`. `createElement` is a function exported from the [@wordpress/element package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-element) that is used to create React elements. When you use JSX, and the code is transpiled, `createElement` is called under the hood to create the React elements to render your block content.

The parameters of `createElement` are the tag or element to create, the properties of the element, and the children of the element. It's possible to pass more than one child to an element, separating them with commas.

After all the block dependencies are defined, you'll see the block registration. The big difference here is that the code for both the Edit component and save function all exist inside the single index.js file. This is because block development does not currently support JavaScript modules, so all the code must be in a single file.

The other major difference is that the block content is created using `createElement`. Instead of being to use the HTML like syntax of JSX to nest elements inside each other, you have to use `createElement` to create the elements.

For this lesson, we'll just focus on the Edit component.

In this example, each element is created using `createElement`, and then where needed the element is passed as a child of another element.

For example, creating the textControl for the starting year setting, and then adding it to the panelBody, and then adding the panelBody to the inspectorControls.

```js
/**
 * Create the text control for the starting year
 * Will be added to the PanelBody
 */
const textControl = createElement(
    TextControl,
    {
        label: __( 'Starting Year', 'copyright-date-block' ),
        value: startingYear,
        onChange: ( newStartingYear ) => {
            setAttributes( { startingYear: newStartingYear } );
        }
    }
);

/**
 * Create the panel body for the settings
 * Includes the text control
 * Will be added to the InspectorControls
 */
const panelBody = createElement(
    PanelBody,
    {
        title: __( 'Settings', 'copyright-date-block' ),
    },
    textControl
);

/**
 * Create the inspector controls for the block
 * Includes the panel body
 * Will be added to the final block output
 */
const inspectorControls = createElement(
    InspectorControls,
    {},
    panelBody
);
```

This could also be written as a single block of code, but it's easier to read when broken down into separate variables.

```js
const inspectorControls = createElement(
    InspectorControls,
    {},
    createElement(
        PanelBody,
        {
            title: __( 'Settings', 'copyright-date-block' ),
        },
        createElement(
            TextControl,
            {
                label: __( 'Starting Year', 'copyright-date-block' ),
                value: startingYear,
                onChange: ( newStartingYear ) => {
                    setAttributes( { startingYear: newStartingYear } );
                }
            }
        )
    )
);
```

Last but not least, the final block output is created and returned using `createElement`, passing in the wrapper element, the `useBlockProps()` hook, which will return the properties as an object, and all the other elements that make up the structure and functionality of the block.

```js
return createElement(
    'div',
    useBlockProps(),
    blockControls,
    inspectorControls,
    copyrightParagraph,
);
```

## Pros and Cons of JSX vs non-JSX

The main benefit of following the non JSX approach is that you don't need to set up a build configuration. This means not needing to install `node.js` and `npm`, nor worrying about `@wordpress/scripts` and running development servers and build scripts. You can write your code, bundle it in a plugin or upload it to your site, and it just works.

One of the first benefits of using JSX and the build step is that JSX is easier to write. For example, consider creating the `inspectorControls` element above, compared to the JSX version:

```jsx
<InspectorControls>
    <PanelBody title={ __( 'Settings', 'copyright-date-block' ) }>
        <TextControl
            label={ __( 'Starting Year', 'copyright-date-block' ) }
            value={ startingYear }
            onChange={ ( newStartingYear ) => {
                setAttributes( { startingYear: newStartingYear } );
            } }
        />
    </PanelBody>
</InspectorControls>
```

Another benefit is not having to worry about manging dependencies. When using JSX, you can import the WordPress dependencies you need, and the build step will take care of updating your index.asset.php file. When not using JSX, you need to manually update the index.asset.php file with the specific dependencies you need.

## Conclusion

Developing blocks without JSX or a build step is a valid way to create blocks for WordPress. It's a great way to get started with block development without needing any other software, and can be a good way to learn how blocks work. However, if you're planning on developing more complex blocks, you may find it easier to get to grips with JSX and the build step.