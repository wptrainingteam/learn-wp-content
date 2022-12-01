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

Introduced in WordPress 5.8, the theme.json file contains a JSON object that allows block theme developers to control the settings and styles of blocks in the Site Editor. WIth the introduction of blocks into the site editing experience, the number of settings developers may need control over has increased. Acting as a central point of configuration, using theme.json aims to provide a more consistent experience.

By default, WordPress core ships with a series of predefined CSS variables, that control things like the colors, font sizes, and spacing of blocks and block elements. However, these settings only cover the very basics of what is possible with blocks. By creating a theme.json file in the theme’s top-level directory, theme developers can configure the existing CSS variable presets, as well as any new ones as they are introduced. The advantage of this is that, unlike traditional CSS, only the presets configured by theme.json are loaded onto the front end. 

Not only that, but theme.json can also configure brand new custom presets. These are specific to the theme, which can be applied to the theme globally, or to specific block elements.

Finally, theme.json enables the theme developer to enable theme specific settings, replacing many of the `add_theme_support` functions that were used in classic themes. 

## The anatomy of theme.json

## Creating settings

### Configuring a WordPress preset

### Configuring a custom theme preset

### Enabling theme settings

## Applying settings

### Applying a setting globally

### Applying a setting to a block element

### Applying a setting to a block



