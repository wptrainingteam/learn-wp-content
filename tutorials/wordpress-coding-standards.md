# WordPress Coding Standards

# Learning Objectives

Upon completion of this lesson the participant will be able to:

## Outline

1. Learn where to find information about WordPress Coding Standards
2. Install the PHP Coding Standards tool
3. Install the @wordpress/scripts package and use the JS and CSS linter
4. Use the wordpress-project-template to automate this process

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn about the WordPress Coding Standards.

## What are Coding Standards?

Coding standards, also known as [coding conventions](https://en.wikipedia.org/wiki/Coding_conventions), are a set of guidelines for a specific programming language that recommend programming style, practices, and methods for each aspect of a program written in that language.

Having Coding Standards creates a baseline for collaboration and review within various aspects of an open source project and community.

Coding standards help avoid common coding errors, improve the readability of code, and simplify modification. 

Following the standards means anyone will be able to understand a section of code and modify it, if needed, without regard to when it was written or by whom.

If you are planning to contribute to WordPress core, you need to familiarize yourself with these standards, as any code you submit will need to comply with them.

While it is not strictly a requirement, it's also a good idea to follow these standards when developing plugins and themes, as it will make your code easier to read and understand, and will make it easier for other developers to contribute to your code.

## Coding Standards for WordPress

While there are many coding standards available for writing code in PHP, JavaScript, CSS or HTML, WordPress has its own set of standards that it uses for its core code, and that it recommends for plugins and themes. The WordPress project maintains a [handbook](https://developer.wordpress.org/coding-standards/) that contains all the information you need to know about these standards.

This handbook contains sections for the four different languages that WordPress uses, as well as a section on Accessibility standards. For the purposes of this tutorial we're going to focus on the language specific standards.

## HTML Coding Standards

The [HTML Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/html/) follow the World Wide Web Consortium (or W3C) [standards for HTML and CSS](https://www.w3.org/standards/webdesign/htmlcss).

Therefore, the page on HTML standards in the WordPress handbook is not that lengthy. It defers to the W3C standards, with some specific notes on self-closing elements, attributes and tags, quotes, and indentation.

These page also recommends using the [W3C HTML Validator](https://validator.w3.org/) to check your HTML code for errors.

## CSS Coding Standards

Like the HTML standards, the [CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/) follow the World Wide Web Consortium (or W3C) [standards for HTML and CSS](https://www.w3.org/standards/webdesign/htmlcss).

The WordPress CSS Standards page does however contain a bit more information around things like CSS structure, selectors, properties, and more.

## JavaScript Coding Standards

Unlike the HTML and CSS standards, the page on [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/) is a more detailed document, so it's hio. 

The JavaScript coding standards where originally adapted from the [jQuery JavaScript Style Guide](https://contribute.jquery.org/style-guide/js/), and have evolved over time, and as WordPress has expanded it's use of JavaScript.

## PHP Coding Standards

Of all the coding standards, the [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) are the most detailed of the four language specific standards. 

This is because roughly 80% of WordPress core is written in PHP. (source language breakdown on https://github.com/WordPress/wordpress-develop)



### PHP CodeSniffer and the WordPress Coding Standards sniffs

To lint your PHP code, you can use the [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) tool with the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) sniffs. There are a couple of ways to set this up, but the most straightforward way is to use the [Composer](https://getcomposer.org/) package manager, install the required packages with Composer, create a PHPCS configuration file, and then run the PHPCS tool.

Install Composer for your operating system by following the instructions on the [Composer website](https://getcomposer.org/doc/00-intro.md).

Once installed, open your terminal, browse to the root directory of your plugin or theme, and run the following command:

```bash
composer require --dev dealerdirect/phpcodesniffer-composer-installer
composer require --dev squizlabs/php_codesniffer
composer require --dev wp-coding-standards/wpcs
```

Next, create a phpcs.xml file in your root directory, and add the following code to it:

```xml
<?xml version="1.0"?>
<ruleset name="WordPress Coding Standards">
    <rule ref="WordPress"/>
	<arg name="extensions" value="php"/>
	<file>.</file>
	<!-- Exclude Vendor directory -->
	<exclude-pattern>*/vendor/*</exclude-pattern>
	<exclude-pattern>*/node_modules/*</exclude-pattern>
</ruleset>
```

Finally, run the following command in your terminal:

```bash
./vendor/bin/phpcs --standard=phpcs.xml
````
This will run the PHP Code Sniffer tool against your code, and report any errors or warnings.

Example code:

```php
<?php
/**
 * Plugin Name: WP Learn Coding Standards
 * Description: A plugin to demonstrate WordPress coding standards
 * Author: Jonathan Bossenger
 */

function register_custom_post_type()
{
    /**
     * Register a custom post type book
     */
    register_post_type('book', [
        'labels'=>[
            'name'=>__('Books'),
            'singular_name'=>__('Book'),
        ],
        'public'=>true,
        'has_archive'=>true,
        'rewrite'=>['slug'=>'books'],
    ]);
} 
```

Fixing tabs in VS Code: https://stackoverflow.com/questions/36814642/visual-studio-code-convert-spaces-to-tabs

Fixed code:

```php
<?php
/**
 * Plugin Name: WP Learn Coding Standards
 * Description: A plugin to demonstrate WordPress coding standards
 * Author: Jonathan Bossenger
 *
 * @package WP_Learn_Coding_Standards
 */

/**
 * Register a custom post type book
 */
function register_custom_post_type() {
	register_post_type(
		'book',
		array(
			'labels'      => array(
				'name'          => __( 'Books' ),
				'singular_name' => __( 'Book' ),
			),
			'public'      => true,
			'has_archive' => true,
			'rewrite'     => array( 'slug' => 'books' ),
		)
	);
}

```

### The @wordpress/scripts package for linting JavaScript (and CSS)

To link your JavaScript code, you can use the prebuilt linters in the [@wordpress/scripts package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/). This package is used by the WordPress Gutenberg project, and is a wrapper around the ESLint and Stylelint packages. To install and use this package, you need to install Node.js and the npm package manager on your computer.

You can find instructions on how to install Node.js and npm on the [Node.js website](https://nodejs.org/en/download). Installing Node.js will also install the npm package manager.

To install @wordpress/scripts for your project, open your terminal, browse to the root directory of your plugin or theme, and run the following command:

```bash
npm init
npm install @wordpress/scripts --save-dev
```

Then, once this is done, copy over the scripts from the [@wordpress/scripts package documentation](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) into your own package.json file.

```json
    "scripts": {
        "build": "wp-scripts build",
        "check-engines": "wp-scripts check-engines",
        "check-licenses": "wp-scripts check-licenses",
        "format": "wp-scripts format",
        "lint:css": "wp-scripts lint-style",
        "lint:js": "wp-scripts lint-js",
        "lint:md:docs": "wp-scripts lint-md-docs",
        "lint:pkg-json": "wp-scripts lint-pkg-json",
        "packages-update": "wp-scripts packages-update",
        "plugin-zip": "wp-scripts plugin-zip",
        "start": "wp-scripts start",
        "test:e2e": "wp-scripts test-e2e",
        "test:unit": "wp-scripts test-unit-js"
    }
```

Finally, run the following command in your terminal:

```bash
npm run lint:js
```

This will run the @wordpress/scripts JavaScript linter against your code, and report any errors or warnings.

What's great about @wordpress/scripts is that it also has a css linter, which you can run with the following command:

```bash
npm run lint:css
```

This will report any errors or warnings on your CSS code, which you can then fix.