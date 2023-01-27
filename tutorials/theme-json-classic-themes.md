# Theme.json in classic themes

## Learning Outcomes

1. Learn how adding a theme.json affects a classic theme
2. Use theme.json to enable Block Editor supports

## Comprehension Questions

1. What is the major benefit of adding a theme.json to a classic theme? 
2. What is the name of the PHP functionality that can be replaced by using theme.json?
3. What should you be aware of when adding a theme.json to a classic theme?

## Outline

- Quick Overview of theme.json, what it is, what it can do
    - highlights, and link back to theme.json tutorial
- What happens when you add a theme.json to a classic theme
    - How does theme.json affect an existing classic theme
    - The WordPress theme.json presets are enabled when adding a theme.json to a classic theme
- Where to go for more information

## Introduction

Hey there, and welcome to Learn WordPress.

In this lesson, you're going learn what happens when you add a theme.json file to a classic theme.

## What is theme.json?

The theme.json is a file that allows block theme developers to control the settings and styles of the blocks in the Editor.

This allows for a central point of configuration while also providing a more consistent experience when configuring theme settings and styles.

To learn more about theme.json in block themes and how to use it, check out the [Introduction to theme.json tutorial](https://learn.wordpress.org/tutorial/introduction-to-theme-json/) on Learn WordPress.

## What happens when you add theme.json in classic themes

Let's take a look at what happens when you add a theme.json file to a classic theme, by adding one to the Twenty Twenty-One theme. To do this, lets first familiarize ourselves with the layout of a page, as well as some block editor settings, when using Twenty Twenty-One.

As you can see, in the block editor the page header and content is a fixed width, and is centre aligned. 

[edit page in the WP dashboard]

When the page is rendered the page header and content structure is determined by the styling applied in the theme's style.css file.

[view page on the front end]

If we want to change the color of something in the editor, say the text color, the only colors listed are those set up by the theme. In this case this is achieved by adding theme support for the `editor-color-palette` feature, and passing the theme colors as an array.

[change text color in the editor]

Let's add a theme.json file to the Twenty Twenty-One theme. 

To do this, switch to a code editor like Visual Code Studio and create a new file in the root of the theme folder called theme.json.

Next use the curly braces to start a new JSON object, and add the `$schema` and `version` keys, and their respective values.

```php
{
	"$schema": "https://schemas.wp.org/trunk/theme.json",
	"version": 2the
}
```

If you refresh the page in the block editor, the first thing you'll notice is that the editor content is slightly out of alignment. 

[edit page in the WP dashboard]

This is because the by adding a theme.json to the theme, the default theme.json that ships with WordPress is now activated. That theme.json does not configure the `settings.layout.contentSize` setting, so you need to create it.

```php
{
	"$schema": "https://schemas.wp.org/trunk/theme.json",
	"version": 2, 	
	"settings": {
		"layout": {
			"contentSize": "650"
		}
	}
}
```

Fortunately this does not affect the front end rendering of the page, as this still uses the styling from the theme's style.css file. However, you might find that some default WordPress theme.json settings and styles conflict with or duplicate your existing CSS rules, so it's a good idea to check this, and fix anything that is causing issues.

If you want to inspect the default WordPress theme.json file, you can find it in your WordPress install, in the /wp-includes/ folder.

[navigate to default theme.json]

For example, if you go to change the color of the paragraph text, you'll notice that the available colors include the colors specified by the default WordPress theme.json. 

## Using theme.json to replace add_theme_support.

One of the major benefits of using a theme.json file in your classic themes, is the ability to add support for block editor functionality, without having to use the `add_theme_support` function in your themes functions.php file.

For example, let's take a look at the editor-color-palette feature. In the Twenty Twenty-One theme, the theme colors are set up in the functions.php file, using the `add_theme_support` function.

```php
// Editor color palette.
$black     = '#000000';
$dark_gray = '#28303D';
$gray      = '#39414D';
$green     = '#D1E4DD';
$blue      = '#D1DFE4';
$purple    = '#D1D1E4';
$red       = '#E4D1D1';
$orange    = '#E4DAD1';
$yellow    = '#EEEADD';
$white     = '#FFFFFF';

add_theme_support(
    'editor-color-palette',
    array(
        array(
            'name'  => esc_html__( 'Black', 'twentytwentyone' ),
            'slug'  => 'black',
            'color' => $black,
        ),
        array(
            'name'  => esc_html__( 'Dark gray', 'twentytwentyone' ),
            'slug'  => 'dark-gray',
            'color' => $dark_gray,
        ),
        array(
            'name'  => esc_html__( 'Gray', 'twentytwentyone' ),
            'slug'  => 'gray',
            'color' => $gray,
        ),
        array(
            'name'  => esc_html__( 'Green', 'twentytwentyone' ),
            'slug'  => 'green',
            'color' => $green,
        ),
        array(
            'name'  => esc_html__( 'Blue', 'twentytwentyone' ),
            'slug'  => 'blue',
            'color' => $blue,
        ),
        array(
            'name'  => esc_html__( 'Purple', 'twentytwentyone' ),
            'slug'  => 'purple',
            'color' => $purple,
        ),
        array(
            'name'  => esc_html__( 'Red', 'twentytwentyone' ),
            'slug'  => 'red',
            'color' => $red,
        ),
        array(
            'name'  => esc_html__( 'Orange', 'twentytwentyone' ),
            'slug'  => 'orange',
            'color' => $orange,
        ),
        array(
            'name'  => esc_html__( 'Yellow', 'twentytwentyone' ),
            'slug'  => 'yellow',
            'color' => $yellow,
        ),
        array(
            'name'  => esc_html__( 'White', 'twentytwentyone' ),
            'slug'  => 'white',
            'color' => $white,
        ),
    )
);
```

However, with a theme.json file, all of this code can be replicated under the `settings.color.pallete` key.

```json
{
	"$schema": "https://schemas.wp.org/trunk/theme.json",
	"version": 2, 
	"settings": {
		"layout": {
			"contentSize": "650"
		}, 
		"color": {
			"palette": [
				{
					"name": "Black",
					"color": "#000000",
					"slug": "black"
				}, 
				{
					"name": "Dark Grey",
					"color": "#28303D",
					"slug": "dark-grey"
				},
				{
					"name": "Gray",
					"color": "#39414D",
					"slug": "grey"
				},
				{
					"name": "Green",
					"color": "#D1E4DD",
					"slug": "green"
				},
				{
					"name": "Blue",
					"color": "#D1DFE4",
					"slug": "blue"
				},
				{
					"name": "Purple",
					"color": "#D1D1E4",
					"slug": "purple"
				},
				{
					"name": "Red",
					"color": "#E4D1D1",
					"slug": "red"
				},
				{
					"name": "Orange",
					"color": "#E4DAD1",
					"slug": "orange"
				},
				{
					"name": "Yellow",
					"color": "#EEEADD",
					"slug": "yellow"
				},
				{
					"name": "White",
					"color": "#FFFFFF",
					"slug": "white"
				}
			]
		}
	}
}
```

If you remove the colour pallete from the functions.php file, but specify them in the theme.json file, you can see that the colours are still available in the editor.

[remove add_theme_support, refresh block editor, select color]

This, and all the rest of the block editor settings and styles as detailed in the [theme.json tutorial](https://learn.wordpress.org/tutorial/introduction-to-theme-json/) can also be added and applied to your theme.json file.

For a full list of the theme features that can be enabled via theme.json, and therefore removed from functions.php, check out the [Backward compatibility with add_theme_support](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/#backward-compatibility-with-add_theme_support) section of the Global Settings & Styles how to guide, in the block editor handbook.

Happy coding.