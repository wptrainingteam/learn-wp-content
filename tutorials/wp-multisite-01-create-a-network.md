# Introduction to WordPress Multisite Networks

# Learning Objectives

Upon completion of this lesson the participant will be able to:
Describe what a WordPress multisite network is
Describe why you might use a WordPress multisite network
Create a WordPress multisite network

## Outline

1. Introduction
2. What is a WordPress multisite network?
3. Why use a WordPress multisite network?
4. Multisite network support
5. How to create a multisite network

## Introduction

In this lesson we will learn about WordPress multisite networks. We will learn what a multisite network is, why you might use a multisite network, and how to create a multisite network.

## What is a WordPress multisite network?

A WordPress multisite network is a collection of sites that all share the same WordPress installation. Each site in a multisite network is a separate site, with its own content, users, and settings. However, they all share the same WordPress installation, which means that they also share the same core WordPress files, plugins, and themes.

## How to create a multisite network

To create a multisite network, you need to add a few lines of code to your `wp-config.php` file. You also need to update your `.htaccess` file.

### wp-config.php

To enable multisite, open your `wp-config.php` file and add the following code above the line that says `/* That's all, stop editing! Happy publishing. */`:

```php
/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );
```





