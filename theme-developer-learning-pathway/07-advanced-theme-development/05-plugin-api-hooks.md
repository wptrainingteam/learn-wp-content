# Plugin API Hooks

## Introduction

https://youtu.be/QNQrsOGeHRY

WordPress provides a number of hooks that allow plugins to "hook into" the functionality of WordPress. 

Your theme should support these hooks, to allow plugins developers to extend your theme.

In this lesson, you will learn how to allow plugins to hook into your theme, by implementing the specific template tags.

## A note on block themes.

If you are developing a block theme, you should not have to worry about implementing these template tags. 

The blocks that implement the functionality described in this lesson already support the relevant hooks. 

Using these template tags is only necessary if you are developing a classic theme, or custom functionality outside of the core blocks.

## Template tags

Most hooks are executed internally by WordPress, so your theme does not need special tags for them to work. 

However, a few hooks need to be supported in specific theme templates. 

These hooks are fired by specific template tags:

`wp_head()` fires the `wp_head` action, which is used by plugins to add code to the `<head>` section of your theme. 

This tag should always at the end of the `<head>` element of a theme’s `header.php` template file.

`wp_body_open()` fires the `wp_body_open` action, which is used by plugins to add code to the `<body>` element of your theme.

This tag goes at the beginning of the `<body>` element of a theme’s `header.php` template file.

`wp_footer()` fires the `wp_footer` action, which is used by plugins to add code to the footer of your theme.

This tag should be in the theme's `footer.php` file, just before the closing `</body>` tag.

`wp_meta()` fires the `wp_meta` action. This action can have several purposes, depending on how you use it, but one purpose might have been to allow for theme switching.

This tag typically goes in the `<li>Meta</li>` section of a Theme’s menu or sidebar.

`comment_form()` is used to display the comment form at the end of posts

This tag goes in the `comments.php` template file, directly before the file’s closing `</div>` tag.