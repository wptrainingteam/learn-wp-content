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

## Introduction

Hey there, and welcome to Learn WordPress.

In this lesson, you're going learn about the theme.json file that allows block theme developers to define theme settings, and then apply those settings to the elements of the theme.

## What is theme.json?

Introduced in WordPress 5.8, the theme.json file contains a JSON object that allows block theme developers to control the settings and styles of blocks in the Site Editor. With the introduction of blocks into the site editing experience, the number of settings developers may need control over has increased. By making these settings available in a specific standard, the theme.json file allows for a central point of configuration while also providing a more consistent experience when configuring theme settings and styles.

By default, WordPress core ships with [a default theme.json](https://github.com/WordPress/wordpress-develop/blob/trunk/src/wp-includes/theme.json) which loads with a specific set of settings enabled and predefined CSS variables presets. 

However, by creating a theme specific theme.json file in a theme’s top-level directory, theme developers can control these settings, as well as configure and apply their own CSS variable presets which can be applied to the theme globally, or to specific block elements.

The ability to enable or disable theme specific settings replaces many of the `add_theme_support` functions that were used in classic themes. By allowing CSS variables to be defined and applied to the theme globally, or to specific block elements, theme developers can create a more consistent experience for their users.

## The anatomy of theme.json

The theme.json file resides in the root directory of a WordPress theme. It contains a JSON object, which is a collection of key-value pairs. JSON (an acronym for JavaScript Object Notation) is a standard text-based format for representing structured data based on JavaScript object syntax. 

Let's look at the most basic example of a theme.json file:

```json
{
  "version": 2,
  "settings": {
  },
  "styles": {
  }
}
```

Note that the JSON object is always wrapped in curly braces, and that each key-value pair is separated by a comma. In this example, "version" is the first key, with a value of "2". The next two keys, "settings" and "styles", are also objects (indicated by the curly braces), which are additional collections of key-value pairs.

The theme.json file is validated against a schema, which provides auto-completion in code editors. This means that, as you type, the editor will suggest the available options, and the available values for each option. This is a great way to learn about the available options, and to ensure that you are using the correct syntax. To enable the schema validation, the "$schema" key needs to be added to the top of the file, and the value should be set to "https://schemas.wp.org/trunk/theme.json".
  
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

With this enabled, most modern IDE's will automatically suggest the available options as you type, and will also provide a description of each option. For example, watch what happens if we create an empty theme.json in VS Code, with just the `$schema` key.

[theme.json schema example]

As soon as I hit "Enter", and start a new key by hitting the double quote key, a list of available top level keys is suggested to me. If I select "version", it automatically populates the value with "2", which is the latest version of theme.json

## Theme.json settings

The "settings" key is where the theme developer can enable or disable specific theme settings and functionality, as well as configure new CSS variable presets.

## Disabling/enabling settings/functionality

For example, let's look at the ability to define custom colors for elements in the Site Editor. By default, if a user wanted to change the color of something (say the text), it's possible to choose a custom color by selecting it from the custom color picker 

[]

However, as a theme developer, if you wanted to disable this functionality, you could do so by setting the settings.color.custom key to a value of false in the theme.json. 

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

However, you could specifically enable the color picker for a single block. For example, you could enable the custom color picker on the paragraph block, by adding the following to the settings.

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

For example, let's say you wanted to add a new color to the color palette that ships with WordPress. You could do so by adding a new color to the settings.color.palette key. 

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Alt Green",
          "color": "#b8e6bf",
          "slug": "alt-green"
        }
      ]
    }
  }
}
```

This color would then be available in the color palette in the Site Editor.

Additionally, users can now use this color for any element that supports color.

## Applying settings

With this new color available to the color palette of the theme, let's look at some ways that it can be applied to a theme, globally, to specific block elements, or to a specific block. 

By creating this new color, you've also created a new CSS variable for the color, which can be applied to various elements in the theme. The format for the CSS variable is `--wp--preset--color--{slug}`. In this case, the slug is `alt-green`, so the CSS variable is `--wp--preset--color--alt-green`.

### Applying a setting globally

For example, let's say you wanted to style all text across the entire theme/site to the new Alt Green color:

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Alt Green",
          "color": "#b8e6bf",
          "slug": "alt-green"
        }
      ]
    }
  },
  "styles": {
    "color": {
      "text": "var(--wp--preset--color--alt-green)"
    }
  }
}
```

If you load this in the Site Editor, you'll see that all the text across all blocks is now Alt Green.

### Applying a setting to a block

Let's say you wanted to apply the new color to a specific block. For example, let's say you wanted to apply the new color to any instances of the Post Content block. You could do so by removing the global text color, and adding the color to the specific block in the styles.blocks key.

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Alt Green",
          "color": "#b8e6bf",
          "slug": "alt-green"
        }
      ]
    }
  },
  "styles": {
    "blocks": {
      "core/post-content": {
        "color": {
          "text": "var(--wp--preset--color--alt-green)"
        }
      }
    }
  }
}
``` 

Now if you refresh the Site Editor, you'll see that the text in the Post Content block is now Alt Green, but all other text is the default color.

### Applying a setting to a block element

Since WordPress 6.1, it is not also possible to apply predefined CSS variables to certain elements across a theme. For example let's say you wanted to apply this color to the background of all button elements. You could apply this color in the theme.json by targeting the styles.elements.button key

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Alt Green",
          "color": "#b8e6bf",
          "slug": "alt-green"
        }
      ]
    }
  },
  "styles": {
    "elements": {
      "button": {
        "color": {
          "background": "var(--wp--preset--color--alt-green)"
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