# Introduction to Theme.json

## Outline

- Overview of theme.json, what it is, what it can do
    -  JSON object that uses the schema for auto-completion
    - developer can enable theme settings and create setting styles, and then apply styles globally, or to block-level elements
    - replaces a lot of add_theme_support from classic themes
    - can also register custom templates and template parts
- Enabling theme settings
    - setting appearanceTools to true
- Creating theme settings
    - creating a theme-specific text color
- Applying styles
    - globally
    - to elements (since 6.1)
    - to a block

### Recording Notes

- Requires a new theme with a single page template file created

## Introduction

Hey there, and welcome to Learn WordPress.

In this lesson, you're going learn about the theme.json file that allows block theme developers to define theme settings, and then apply those settings to the elements of the theme.

## What is theme.json?

Introduced in WordPress 5.8, the theme.json file allows block theme developers to control the settings and styles of the blocks in the Editor. 

With the introduction of blocks into the site editing experience, the number of settings theme developers may need control over has increased. 

By making these settings available in a specific standard, the theme.json file allows for a central point of configuration while also providing a more consistent experience when configuring theme settings and styles.

## The anatomy of theme.json

Let's take a look at the theme.json file in a code editor. 

The theme.json file resides in the root directory of a WordPress theme. It contains a JSON object, which is a collection of key-value pairs. JSON (an abbreviation of JavaScript Object Notation) is a standard text-based format for representing structured data based on the JavaScript object syntax. 

```json
{
  "version": 2,
  "settings": {
  },
  "styles": {
  }
}
```

Note that the main JSON object is always wrapped in curly braces, and that each key-value pair is separated by a comma. The comma is important, and must be included after each key-value pair, except the last one in any given object. 

If you're using a good code editor, you'll notice that leaving out a comma will result in the code editor highlighting the error.

In this example, "version" is the first key, with a value of "2". The values of next two keys, "settings" and "styles", are also objects (indicated by the curly braces). These are additional collections of key-value pairs, which also follow the JSON syntax.

The theme.json file is validated against a schema, which provides auto-completion in code editors. This means that, as you type, the editor will suggest the available options, and possible available values for each option. This is a great way to learn about the available options, and to ensure that you are using the correct syntax. To enable the schema validation, the "$schema" key and value needs to be added to the top of the file. The schema value can be found in the Block Editor Handbook, Theme.json Reference Guide under the Schema heading.
  
```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
  },
  "styles": {
  }
}
```

With this added, most modern IDE's will automatically suggest the available options as you type, and will also provide a description of each option. For example, watch what happens if you create an empty theme.json in Visual Code Studio, with just the `$schema` key.

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json"
}
```

As soon as you start a new key by opening the double quotes, a list of available top level keys is suggested. If you select "version", it automatically populates the value with "2", which is the latest version of theme.json

## Theme.json settings

By default, WordPress core ships with [a default theme.json](https://github.com/WordPress/wordpress-develop/blob/trunk/src/wp-includes/theme.json) which enables a specific set of settings, and creates a set of predefined CSS variables. 

The "settings" key is where the theme developer can extend the default theme.json to enable or disable specific theme settings and functionality, as well as configure new CSS variables. These settings can then be applied to the theme globally, or to specific block elements.

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
  }
}
```

## appearanceTools

Let's look at one of the first settings than a theme developer can enable, appearanceTools:

appearanceTools is disabled by default, and this one setting controls all the following features on blocks that support them:

- the ability to set border color, radius, style, and width
- the ability to set link color
- the ability to set blockGap, margin and padding 
- the ability to set text lineHeight

By enabling appearanceTools, the theme developer is enabling all of these features. 

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "appearanceTools": true
  }
}
```

If you're editing a theme in the Site Editor, you can now see these features in the sidebar.

 - On the header block you can now set the link color 
 - On the featured image you have additional border and radius settings
 - And on a paragraph block you can set the link color, and add padding and margin

## Disabling/enabling settings/functionality

The ability to enable or disable theme specific settings in a theme.json file replaces the requirement to use `add_theme_support` in a functions.php file. Enabling or disabling settings is merely a case of setting a switch from true to false.

For example, let's look at the ability to define custom colors for elements in the Site Editor. 

By default, if a user wanted to change the color of something (say the text), it's possible to choose a custom color by selecting it from the custom color picker

In a classic theme, if the theme developer wanted to disable this functionality, they would need to add the following code to their functions.php file.  

```php
add_action('init', 'setup_theme_functions');
function setup_theme_functions() {
    add_theme_support( 'disable-custom-colors' );
}
```

However, using theme.json, you could do so by setting the `settings.color.custom` key to a value of `false` in the theme.json. 

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "custom": false
    }
  }
}
```

Doing so would disable the custom color picker across all elements in the Site Editor.

However, you could specifically enable the color picker for a single block. For example, you could enable the custom color picker specifically on the paragraph block, by adding the following to the paragraph block in the theme.json settings.

```json
{
    "$schema": "https://schemas.wp.org/trunk/theme.json",
    "version": 2,
    "settings": {
        "color": {
            "custom": false
        },
        "blocks": {
            "core/paragraph": {
                "color": {
                    "custom": true
                }
            }
        }
    }
}
```

Now if you edit a paragraph block, you'll be able to choose a custom color.

### Configuring a new CSS preset

It's also possible to create new CSS preset variables for a theme. CSS variables are defined once, but can be used throughout the theme.

For example, let's say you wanted to add a new color to the color palette available to WordPress. You could do so by adding a new color object to the `settings.color.palette` key.

Notice how the color pallete key defaults to square braces. This indicates a JavaScript array, meaning you can add multiple objects to the colour pallet.

To add a color to the color pallete, give it a name, hex color value, and slug. 

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Alternative",
          "color": "#135e96",
          "slug": "alternative"
        }
      ]
    }
  }
}
```

This new Alternative color would then be available in the color palette in the Site Editor.

Additionally, users can now use this color for any element that supports color, for example if you wanted to apply this colour to a paragraph block's text.

## Applying settings

With this new color available to the color palette of the theme, let's look at some ways that it can be applied to a theme: globally, to specific block elements, or to a specific block. 

By creating this new color, you've also created a new CSS variable for the color, which can be applied to various elements in the theme. The format for the CSS variable is `--wp--preset--color--{slug}`. In this case, the slug is `alternative`, so the CSS variable is `--wp--preset--color--alternative`.

### Applying a setting globally

For example, let's say you wanted to style all text across the entire theme/site to the new Alternative color:

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Alternative",
          "color": "#135e96",
          "slug": "alternative"
        }
      ]
    }
  },
  "styles": {
    "color": {
      "text": "var(--wp--preset--color--alternative)"
    }
  }
}
```

If you load this in the Site Editor, you'll see that all the text across all blocks is now the Alternative color.

### Applying a setting to a block

Let's say you wanted to apply the new color to a specific block. For example, let's say you wanted to apply the new color to any instances of the Post Content block. You could do so by removing the global text color, and adding the color to the specific block in the `styles.blocks` key.

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Alternative",
          "color": "#135e96",
          "slug": "alternative"
        }
      ]
    }
  },
  "styles": {
    "blocks": {
      "core/post-content": {
        "color": {
          "text": "var(--wp--preset--color--alternative)"
        }
      }
    }
  }
}
``` 

Now if you refresh the Site Editor, you'll see that the text in the Post Content block is now Alt Green, but all other text is the default color.

### Applying a setting to a block element

Since WordPress 6.1, it is now also possible to apply predefined CSS variables to certain elements across a theme. For example let's say you wanted to apply this color to the background of all button elements. You could apply this color in the theme.json by targeting the `styles.elements.button` key.

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Alternative",
          "color": "#135e96",
          "slug": "alternative"
        }
      ]
    }
  },
  "styles": {
    "elements": {
      "button": {
        "color": {
          "background": "var(--wp--preset--color--alternative)"
        }
      }
    }
  }
}
```

This would apply the color to any blocks that use button elements, for example the Buttons block and the Search block.

## Summary

This is just a high level overview of what's possible with theme.json. For more information on how to use it, please see the [Global Settings and Styles guide](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/) and the [theme.json reference](https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/) in the Block Editor Handbook, as well as the [theme.json documentation](https://developer.wordpress.org/themes/advanced-topics/theme-json/) in the Theme Handbook.

Happy coding!