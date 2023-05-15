# Testing your plugins for PHP version compatiblitiy

# Learning Objectives

Upon completion of this lesson the participant will be able to:

## Outline

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn about testing your WordPress plugins for PHP version compatibility.

You will learn why it's important to test for PHP version compatibility, where to find information about PHP version changes, as well as two methods to test your plugins.

## Why test for PHP version compatibility?

WordPress is written in PHP, and as such, it needs to be able to run on at least the minimum supported version of PHP that is available to web hosts. While WordPress has a minimum requirement of PHP 7.4, PHP 7.4 is officially considered end of life by the PHP developers, and will not receive any security updates in the near future. 

WordPress core itself is considered compatible with PHP 8.0, and the WordPress core team is working on making WordPress compatible with PHP 8.1 and PHP 8.2. However, they cannot guarantee that all plugins will be compatible with current or future versions of PHP.

As a plugin developer, it's therefore important to have a process in place to test your plugins for PHP version compatibility.

## Where to find information on PHP version changes

In order to know when and how PHP versions are going to change, it's a good idea to refer to the official PHP website at https://www.php.net/. 

On the [Supported Versions](https://www.php.net/supported-versions.php) page, you can find information about which versions are currently supported, and which versions are end of life.

At the time of this recording, all PHP 7.x versions or end of life, PHP 8.0 only has support for security fixes, and PHP 8.1 and PHP 8.2 have active support, with PHP 8.3 in development.

In the Appendices section of the PHP documentation you can find the guides on migrating from older PHP versions, which list all the changes between the old version and the new one. For example, the [Migrating from PHP 7.4.x to PHP 8.0.x](https://www.php.net/manual/en/migration80.php) guide lists all the changes between PHP 7.4 and PHP 8.0.

## How to test for PHP version compatibility

There are a few ways to test for PHP version compatibility:

1. You can test your plugin manually by setting up a site on a staging or local environment that's running PHP 8, enabling WP debugging, testing the functionality, and inspecting the logs for errors.
2. You can use an automated tool like PHPCompatibility to scan your codebase for PHP version compatibility issues.

### Manual method

### Automated method(s)

4. Manual method
   1. Set up a site on a staging or local environment that's running PHP 8
   2. Enable WP debugging
   3. Test the functionality and inspect the logs for errors
   4. Fix
   5. Rinse, repeat
2. Automated method: [PHPCompatibility](https://github.com/PHPCompatibility/PHPCompatibility)
   1. Requires [Composer](https://getcomposer.org/)
   2. Requires [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
   3. Requires [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) rules for PHPCS
      1. composer init
      2. composer require --dev dealerdirect/phpcodesniffer-composer-installer
      3. composer require --dev phpcompatibility/php-compatibility
      4. composer require -- dev wp-coding-standards/wpcs
      5. ./vendor/bin/phpcs -p . --standard=PHPCompatibility --ignore=*/vendor/* --runtime-set testVersion 8.0 --report-full=report.txt
   4. Pros
      1. Does not require a different PHP version
      2. Can scan your entire codebase
      3. Can be automated (git hooks, CI/CD, etc.)
   5. Cons
      1. Does not catch all issues (https://github.com/PHPCompatibility/PHPCompatibility/issues/808)
      2. Does require CLI familiarly