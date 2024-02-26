# Static vs dynamic blocks

When developing WordPress blocks, you'll need to consider the functionality of your block, and whether it needs to change based on external factors.

Fortunately, it is possible to create blocks that are either static or dynamic, depending on your requirements.

Let's look at what the difference is between static and dynamic blocks, and how to determine which is right for your needs.

## Static blocks

If you've been following the lessons in this module to build the Copyright Date Block, you have been building a static block. 

As static block's content is fixed, once it's added to the editor, the `save` function is triggered, and the post or page is saved, the block's content will not change.

Static blocks are useful for content that doesn't change, like a quote, or a testimonial.

## Dynamic blocks

However, if you consider the real world requirements of a Copyright Date Block, it would actually be ideal that if the year changes, the rendered content of the block should also update. 

Otherwise, you'll need to edit anywhere that you've added the block, to trigger the save function and update the year.

This is where dynamic blocks come in. Dynamic blocks do not generally render their content on the save function, and instead use PHP to render their content on the front end, wherever the block is in use.

Let's look at what it would take to turn the Copyright Date Block into a dynamic block.

## Making the Copyright Date Block dynamic

To make the Copyright Date Block dynamic, you need to specify a PHP file or function that contains the block's rendering logic.

This can be done in a few ways, but the easiest is to use the `render` property in the block's metadata in the block.json file.

Open the `block.json` file in the `src` directory, and add the following code to the bottom of that file:

```json
"render": "file:./render.php"
```

This tells WordPress to use the `render.php` file to render the block's content on the front end.

You can then create a `render.php` file in the `src` directory, and add your rendering logic to that file.:

```php
<?php
    $block_props = get_block_wrapper_attributes();
    $starting_year = $attributes['startingYear'];
    $current_year = date( 'Y' );
    ?>
    <p <?php echo $block_props?>>
        Copyright &copy; <?php echo $starting_year?> - <?php echo $current_year; ?>
    </p>
    <?php
}
```

In this example, you're using the `get_block_wrapper_attributes` function to get the block's wrapper attributes. This is similar to calling `useBlockProps` in JavaScript. 

You are also getting the `startingYear` value from the PHP `$attributes` array. This variable is one of three that are exposed to the file you set for your block's `render` metadata property, and contains any attributes that have been set up for your block.

Then you can create the $current_year variable, which uses the PHP date() function to always get the current year. This way, when the block is rendered, it always get's the current year.

Last but not least, render the paragraph tag, with all the relevant content.

Once you've set up your `render.php` file, you can remove any code related to the block's save process in the editor, as you don't need to save any content to the post. 

To do that, you can delete the `save.js` file.

You can also delete the `save` property in the `block.json` file.

At the same time you can also delete the `view.js` and the viewScript property, as you don't need any additional JavaScript to be run on the front end.

Once you've made these changes, you can run the build process, and then create a post, and add the block to the post.

You'll see that the block still renders as expected in the editor, as well as when you view it on the front end.

However, if you simulate the year change, say by changing the date on your computer, the front end rendering of the block will update accordingly. 

## Additional resources

For more information on the difference between developing static vs dynamic blocks, you can read the [Static or Dynamic rendering of a block](https://developer.wordpress.org/block-editor/getting-started/fundamentals/static-dynamic-rendering/) section of the Fundamentals of Block Development.