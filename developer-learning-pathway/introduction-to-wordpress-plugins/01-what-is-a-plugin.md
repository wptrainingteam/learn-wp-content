# Developing WordPress plugins

## What is a plugin

A WordPress plugin is a package of code that can be installed on a WordPress website to add new features or functionality.

Whereas themes are used to control the look and feel of a WordPress site, plugins are used to extend its functionality.

## Why use a plugin?

As a WordPress developer, you will often need to create custom functionality for a WordPress website. 

While it is possible to add this functionality to a theme or child theme's `functions.php` file, it often makes more sense to add this code to a WordPress plugin.

This is because a plugin can be activated or deactivated without affecting the theme, and the same plugin can be used across multiple sites.

## The WordPress plugin directory

The WordPress plugin directory contains over fifty-thousand plugins that can be installed on a WordPress site. 

These plugins can turn a WordPress site into an online store, a social network, a learning management system, and much more.

## The structure of a WordPress plugin

Most WordPress plugins in the plugin directory are composed of many files, but to create a valid plugin you only really only need one main PHP file with a specifically formatted comment block, also known as a DocBlock, at the top of that file.

When installed, plugins exist inside the `wp-content/plugins` directory of a WordPress site.

Hello Dolly, one of the first plugins for WordPress, is an example of a single file plugin. All the functionality of the plugin is contained in a single PHP file called `hello.php`.

Akismet, an anti-spam plugin for WordPress, is an example of a multi file plugin. Here the `akismet` directory contains all the files for the plugin. The main file for the plugin is `akismet.php` and it handles the loading of all the other required files.

## The plugin developer handbook

The WordPress plugin developer handbook is a great resource for learning how to create a WordPress plugin. It contains information on how to create a plugin, how to use the various WordPress APIs, and how to submit a plugin to the WordPress plugin directory.

You can find the plugin developer handbook by visiting developer.wordpress.org and clicking on the "Plugins" link at the top of the page.

## YouTube chapters

(0:00) What is a plugin
(0:00) Why use a plugin
(0:00) The WordPress plugin directory
(0:00) The structure of a WordPress plugin
(0:00) The plugin developer handbook