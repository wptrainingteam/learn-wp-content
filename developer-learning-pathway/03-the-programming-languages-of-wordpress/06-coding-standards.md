# Coding Standards

## Introduction

As you learn to write WordPress code, you might come across the concept of coding standards. 

In this lesson, you'll learn what coding standards are and why they are important, where to find information on each coding standard, and learn about some useful coding standard tools.

## What are Coding Standards?

Coding standards, also known as [coding conventions](https://en.wikipedia.org/wiki/Coding_conventions), are a set of guidelines for a programming language.

These standards recommend the programming style, practices, and methods for each aspect of the code.

Having coding standards creates a baseline for collaboration and review within a project or a community.

Coding standards help avoid common coding errors, improve the readability of code, and simplify modification.

Following the standards means anyone will be able to understand the code and modify it, if needed, without regard to when it was written or by whom.

## Coding Standards for WordPress

While there are many coding standards available for writing code in PHP, JavaScript, CSS, or HTML, WordPress has its own set of standards that it uses for its core code and recommends for plugins and themes. 

If you plan to write WordPress code, it's a good idea to familiarize yourself with these standards.

Although it's not required that your code adheres to these standards, adopting them will make your WordPress code easier for other developers to read and understand, enhancing collaboration.

The WordPress project maintains a [handbook](https://developer.wordpress.org/coding-standards/) with all the information you need to understand these standards.

This handbook includes sections for the four different languages that WordPress uses, as well as a section on accessibility standards. 

For this lesson, we’ll focus on the language-specific standards for HTML, CSS, JavaScript, and PHP.

## HTML Coding Standards

The [HTML Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/html/) follow the World Wide Web Consortium [standards for HTML and CSS](https://www.w3.org/standards/webdesign/htmlcss), also known as the W3C standards for HTML and CSS.

The HTML standards page in the WordPress handbook is concise, mostly deferring to the W3C standards, with specific notes on self-closing elements, attributes and tags, quotes, and indentation.

## CSS Coding Standards

Like the HTML standards, the [CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/) also follow the World Wide Web Consortium (or W3C) [standards for HTML and CSS](https://www.w3.org/standards/webdesign/htmlcss).

The WordPress CSS Standards page, however, provides more detailed information on topics like CSS structure, selectors, properties, and more.

## JavaScript Coding Standards

Unlike the HTML and CSS standards, the page on WordPress [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/) is more detailed.

The JavaScript coding standards where originally adapted from the [jQuery JavaScript Style Guide](https://contribute.jquery.org/style-guide/js/), and have evolved over time as WordPress has expanded its use of JavaScript.

## PHP Coding Standards

Of all the coding standards, the [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) are the most detailed among the WordPress language-specific standards.

This is because approximately 80% of WordPress core is written in PHP. (source language breakdown on https://github.com/WordPress/wordpress-develop)

The WordPress PHP coding standards were originally adapted from the [PEAR coding standards](https://pear.php.net/manual/en/standards.php), but have since evolved into a specific set of standards tailored for writing PHP code within the WordPress context..

### Tools for checking coding standards

For HTML and CSS, you can use the [W3C HTML Validator](https://validator.w3.org/) and the [W3C CSS Validator](https://jigsaw.w3.org/css-validator/) to check your code for errors.

Additionally, for CSS, JavaScript, and PHP, there are several linters available. A linter is a tool that automatically checks your code against a specific coding standard.

For JavaScript and CSS, you can install and configure the [@wordpress/scripts package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/), which includes linters for both [CSS](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/#lint-style) and [JavaScript](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/#lint-js).

Finally for PHP, you can use the [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) tool with the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) sniffs.

Each of these linting tools requires some setup, but once installed, you can run them on your codebase to check for errors and style issues.

You'll learn how to install and use these tools in a future lesson.
