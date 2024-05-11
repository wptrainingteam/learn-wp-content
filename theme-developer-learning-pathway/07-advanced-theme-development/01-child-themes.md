# Child themes

## Introduction

Sometimes, you may need a way to modify or extend an existing theme, without making changes to the theme's code. 

This is where child themes come in. 

In this lesson, you'll learn what child themes are, why you should use them, and how to create and use a child theme.

## What is a child theme?

A child theme is an extension of its parent theme. 

Using a child theme allows you to extend or modify any part of the parent theme, without making any chances to the parent theme code.

To understand how this works, let's look at a simplified example. 

## Why use a child theme?

Install and activate any theme from the WordPress theme repository in your local WordPress environment. 

In this example, the Twenty Twenty-Four default theme is installed and active. 

Let's say you want to customise some part of the theme, for example the footer layout and structure.

You'd prefer it if the site menus were on the left, and the Site Logo, Title, and Tagline where on the right. 

You also want to change the footer credit to reference your company name, and update the URL

One way you could do this is inside the Site Editor. 

You could find and edit the footer template part, and make the changes you need.

Moving the menu to the left, and the site logo and title to the right, and updating the footer credits.

However, what if you wanted to reuse those same changes on a different site? You would have to manually make these changes in the Site Editor every time.

You could of course make the changes manually, by navigating to the Twenty Twenty-Four footer pattern, which is used in the footer template and changing the content of the pattern. 

But what happens if there's ever a theme update. During a theme update, the entire theme is replaced with the updated version, so any changes you make to any specific files will be lost!

This is where child themes come in.

By creating a child theme, which includes only the specific changes you need, you can activate the child theme, and the change will be applied.

If there are any future updates to the parent theme, in this case, Twenty Twenty-Four, the modifications in the child theme will still be applied, and the changes will remain. 

## Creating a child theme

To understand how child themes work, it would be a good idea to create your first child theme. 

Let's use the example above to create your first child theme.

In the `wp-content/themes` directory, create a new empty theme directory called `twentytwentyfourchild`.

Inside of that directory, create a style.css file. Then, add the following Theme header to that file

```php
/**
 * Theme Name: Child theme of Twenty Twenty-Four
 * Template:   twentytwentyfour
 */
```

Now browse to the WordPress admin area, and navigate to the Themes page. You should see the new child theme listed there.

Now activate the child theme. Then browse to the front end of the site.

You''l notice that the site looks exactly the same as it did before. This is because the all the theme elements are inherited from the parent theme.

## How child themes work

All WordPress themes — unless they are specifically a child theme — are technically parent themes.

Child themes are slightly different to parent themes in that they have a special theme header field in the `style.css` file that defines which parent them they are a child of.

This header field is the `Template` field. The value of this field must match the folder name (or slug) of the parent theme, relative to the `wp-content/themes` directory.

Therefore, in this example, this theme is a child theme of Twenty Twenty-Four, because it has the Template field defined as `twentytwentyfour`.

What you can do now is select which specific parts of the parent theme you want to modify. 

For example, if you just want to modify the footer, you can create a new `footer.html` file in the child theme directory, in the same relative location as the footer.html in the parent theme, and make the changes you need to the child theme footer.html.

One way to generate the child theme footer, is to make the changes you need to the footer template in the Site Editor. 

Then, switch to the Code Editor view, copy the code, and paste it into the child theme `footer.html` file.

With the child theme active, the changes you made to the `footer.html` file in the child theme will be applied, and the footer will be displayed as you want it to be.

## Using Create Block Theme

While it's possible to create a child theme manually, you can also use the Create Block Theme plugin to create a child theme, based on changes you've made in the Site Editor.

To do this, activate Twenty Twenty-Four and delete the child theme you created earlier in this lesson.

Then, search for and install and activate the Create Block Theme plugin. 

Next, navigate to the Site Editor, and make the same changes to the footer template part.

Once you're happy with the changes, open the Create Block Theme options by clicking on the Create Block Theme icon in the top right corner of the Site Editor.

Select Create Theme, and then Create Child Theme.

Give the child theme and name, and optionally add any relelvant theme meta data.

Then click Create Child Theme.

The Child Theme will be created, and the Editor will be reloaded.

If you navigate to the Themes page in the WordPress admin area, you'll see the new child theme listed there, installed and activated.

If you browse to the front end, you'll see the changes you made to the footer template part are applied.

And finally, if you look in your code editor, you'll see the child theme files have been created.

## Further reading

For more information on child themes, see the [Child themes page](https://developer.wordpress.org/themes/advanced-topics/child-themes/) under Advanced Topics in the Theme Developer Handbook.