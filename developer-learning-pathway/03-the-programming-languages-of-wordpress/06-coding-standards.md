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

While there are many coding standards available for writing code in PHP, JavaScript, CSS or HTML, WordPress has its own set of standards that it uses for its core code, and that it recommends for plugins and themes. 

If you are planning to write WordPress code, it would be a good idea familiarize yourself with these standards.

While it is not a requirement that your code adheres to these standards by adopting these standards for your WordPress code, it will make your code easier to read and understand for other WordPress developers you might collaborate with.

The WordPress project maintains a [handbook](https://developer.wordpress.org/coding-standards/) that contains all the information you need to know about these standards.

This handbook contains sections for the four different languages that WordPress uses, as well as a section on Accessibility standards. 

For the purposes of this lesson we're going to focus on the language specific standards for HTML, CSS, JavaScript and PHP.

## HTML Coding Standards

The [HTML Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/html/) follow the World Wide Web Consortium (or W3C) [standards for HTML and CSS](https://www.w3.org/standards/webdesign/htmlcss).

Therefore, the page on HTML standards in the WordPress handbook is not that lengthy. It mostly defers to the W3C standards, with some specific notes on self-closing elements, attributes and tags, quotes, and indentation.

## CSS Coding Standards

Like the HTML standards, the [CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/) also follow the World Wide Web Consortium (or W3C) [standards for HTML and CSS](https://www.w3.org/standards/webdesign/htmlcss).

The WordPress CSS Standards page does however contain a bit more information around things like CSS structure, selectors, properties, and more.

## JavaScript Coding Standards

Unlike the HTML and CSS standards, the page on [JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/) is a more detailed document.

The JavaScript coding standards where originally adapted from the [jQuery JavaScript Style Guide](https://contribute.jquery.org/style-guide/js/), and have evolved over time as WordPress has expanded its use of JavaScript.

## PHP Coding Standards

Of all the coding standards, the [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) are the most detailed of the four language specific standards.

This is because roughly 80% of WordPress core is written in PHP. (source language breakdown on https://github.com/WordPress/wordpress-develop)

The WordPress PHP coding standards were originally adapted from the [PEAR coding standards](https://pear.php.net/manual/en/standards.php), but have evolved into a very specific set of standards for writing PHP code in a WordPress context.

### Tools for checking coding standards

For HTML and CSS, you can use the [W3C HTML Validator](https://validator.w3.org/) and the [W3C CSS Validator](https://jigsaw.w3.org/css-validator/) to check your code for errors.

Additionally for CSS, JavaScript, and PHP, there are a number of linters available. A linter is a tool that automatically checks your code against a specific coding standard.

For JavaScript and CSS you can install and configure the [@wordpress/scripts package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/), which includes a linter for [CSS](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/#lint-style) and [JavaScript](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/#lint-js).

Finally for PHP, you can use the [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) tool with the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) sniffs.

Each of these linting tools requires some work to set up, but once you have them installed, you can run them on your codebase to check for errors and style issues.

You'll learn how to install and use these tools in a future lesson.
