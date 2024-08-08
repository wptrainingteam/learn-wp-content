# Required theme files

https://youtu.be/vhWis5HiUdY

## Introduction (0:00)

When you submit your theme to the WordPress.org theme directory, there are a set of required theme files that you need to include in your theme.

Let's look at what these files are, and why they are important.

## Block themes (0:13)

In the lesson on theme structure, you learned that the only two files required for a valid block theme are the `style.css` and `index.html` files.

However, when you submit your theme to the WordPress.org theme directory, there are additional files that are required.

You will also need to include a `theme.json file`, and a `readme.txt` file.

Additionally, you will also need a screenshot file.

Let's look at each of these files in more detail.

## `theme.json` (0:54)

The `theme.json` file is used to define the global styles and settings for your theme.

You will generally have created this file during theme development, unless you don't have any specific global styles or settings.

Even Create Block theme creates a `theme.json` file for you, which includes some default global styles and settings.

If you don't have a `theme.json` file, you can use this template to create one in the root of your theme.

```json
{
  "$schema": "https://schemas.wp.org/wp/6.5/theme.json",
  "version": 2,
  "settings": {
  },
  "styles": {
  }
}
```

## `readme.txt` (1:22)

Originally required for plugins, the `readme.txt` file is used by both plugins and themes to provide more information about them. 

For themes, the information from the `readme.txt` is displayed on the theme's page in the WordPress.org theme directory.

This file should include information about the theme, such as the theme name, description, version number, author, and other details.

The [WordPress readme file standard](https://wordpress.org/plugins/readme.txt) contains the details of the type of information that you can use in your `readme.txt` file.

There is also a [readme validator](https://wordpress.org/plugins/developers/readme-validator/) that you can use to check if your `readme.txt` file is formatted correctly.

## Screenshot (2:03)

The screenshot file is used to display a preview of your theme in the WordPress.org theme directory, as well as in the theme directory page in the WordPress admin area.

This file should be a PNG or JPG image, and should be no bigger than 1200 x 900 pixels in size.

If you use Create Block Theme to create your theme, a default screenshot file is automatically generated for you, but it's best to replace this with a custom screenshot that showcases your theme.

## Classic themes  (2:33)

If you are submitting a classic theme, the required files are slightly different.

As covered in the "Introduction to Classic themes" lesson, the only required files for a classic theme to work inside a WordPress site, are the `style.css` and `index.php` files.

However, when submitting a classic theme to the WordPress.org theme directory, you will also need to include a `comments.php` file.

In a classic theme, this is the file which contains the comment template included wherever comments are allowed. 

As with block themes, you will also need to include the `readme.txt` file, and the screenshot file.

## Further reading (3:15)

For more information on the required theme files, you can refer to the Required files and Optional files sections of the [Theme structure](https://developer.wordpress.org/themes/core-concepts/theme-structure/#required-files) chapter in the WordPress theme developer handbook.


## YouTube chapters

0:00 Introduction
0:13 Block themes
0:54 theme.json
1:22 readme.txt
2:03 Screenshot
2:33 Classic themes
3:15 Further reading