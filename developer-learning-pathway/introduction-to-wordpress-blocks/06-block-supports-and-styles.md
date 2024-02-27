# Block supports and Block styles

One of the benefits of building blocks is the ability to control the block's appearance on a per block level. 

To do this, you can use something called block supports, as well as define the block's styles. 

Let's take a look at how this works.

## Block supports

Block Supports is the API that allows a block to declare support for certain common features. 

For example, most blocks support the ability to set their alignment, background and text color, fonts and font size, and more.

You can define support for these common features in the block metadata, and once enabled, the block will have those abilities enabled in the Editor.

## Adding Block Supports

To add support for a feature, you define it in the `supports` property of the block's metadata in the `block.json` file.

Open the `block.json` file in the `src` directory, and update the supports property to include the `align` support.

```json
	"supports": {
		"html": false,
		"align": true
	},
```

Once the build process is complete, create a post, and add the block to the post. You'll see that the block now has the ability to set its alignment.

As you can see, simply by enabling the alignment support, the block now has the ability to set its alignment, without any additional code.

Enabling any of the available block supports is as simple as adding them to the `supports` property in the `block.json` file, and it gives your user's a wealth of options to customize the block's appearance.

## Block styles

There are some cases however where you would prefer to define the block's styles yourself. 

In the Copyright Date block requirements defined in the Scaffolding a new block lesson, we required that the block to always have a specific border and border color.

This is where the `style.scss` and `editor.scss` files come in. These files allow you to set up specific blocks styles, and then those styles are applied in the editor and on the front end.

These two files are [Syntactically Awesome Stylesheets](https://sass-lang.com/), also known as Sass files. Just like the JSX format used to create blocks converts into regular JavaScript during the build step, Sass files convert into regular CSS. These files follow the new SCSS syntax, which you can learn more about on the Sass website. Fortunately, you can also just write plain CSS in Sass files, and that will work as well.

In the Scaffolding a new block lesson, you learned what those two files are used for:

 - `style.scss`: the styles in this file are applied to the block in the front end and in the editor
 - `editor.scss`: the styles in this file are applied to the block in the editor only

If you need a specific style to be applied to the block in the front end and in the editor, you would add the styles to the `style.scss` file. 

Open the `style.scss` file in the `src` directory, and you'll see the following scaffolded code:

```scss
.wp-block-create-block-copyright-date-block {
	background-color: #21759b;
	color: #fff;
	padding: 2px;
}
```

You may have noticed that this style is not being applied to the current block. This is because the class name applied to the parent container of the block via `useBlockProps` is automatically generated based on the block's name.

In the `block.json` file the name of the block is `copyright-date/copyright-date-block`, so the class name is generated as `wp-block-copyright-date-copyright-date-block`.

The class name you see being targeted in the `style.scss` file is based on the original name of the block, so you need to change it to match the class name that is being generated.

At the same time, you can also add the border and border color to the block. 

```scss
.wp-block-copyright-date-copyright-date-block {
	border: 1px solid #111111;
    padding: 5px;
}
```

Because you want the border to appear all the time, you don't need to define any specific editor styles. This means you can delete the `editor.scss` file. 

You can also delete the `editorStyle` property in the `block.json` file. 

And the importing of the `editor.scss` file in the `edit.js` file.

Doing this might break your development server, so you may need to restart it. 

Once the build process has been run, create a post, and add the block to the post. 

You'll see that the block now has the border and border color that you defined in the `style.scss` file. When you preview the block, the style is also applied on the front end.

## Conclusion

When you're developing your blocks, it's useful to think about what appearance elements you want users to be able to edit, vs what should always apply to the block. Then you can either add the relevant support or hard code any specific styles into the relevant style file. 