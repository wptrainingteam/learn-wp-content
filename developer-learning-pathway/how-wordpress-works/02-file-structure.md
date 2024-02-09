# The WordPress file structure

## Introduction

Welcome to this lesson on the WordPress file structure.

Like most software applications, WordPress is made up of a collection of files organised into a specific structure. 

Because WordPress is open source, when you download the archive of the latest version of WordPress, you're able to see and inspect all the files.

Let's explore the root directory of a WordPress installation in Visual Studio Code, a free and open source code editor.

## The root directory

The root directory of a WordPress site contains a series of files, as well as three directories.

## Static files

First, let's look at some of the static files:

The `.htaccess` file is a special file that is used to configure the Apache web server for a WordPress installation. It is essentially an extension of the Apache Virtual Host configuration that we looked at earlier. Any valid Apache directives can be added to this file, and will be applied for this WordPress installation.

It's worth noting that Nginx does not support the use of an .htaccess file like configuration on a per WordPress level. Instead, the configuration is done in the main server block configuration file. This is one of the reasons that Nginx is considered to be faster than Apache, but also what makes it less configurable by the site owner.

The `license.txt` file contains the license information for WordPress. WordPress is licensed under the open source GNU General Public License, version 2. This license allows anyone to use, modify, and redistribute WordPress.

The `readme.html` file contains information about the WordPress, including sections on installing and updating WordPress, system requirements, and links to various online resources. As an HTML file, it's best viewed in a web browser.

## PHP files that control a WordPress request

Now let's move onto the PHP files that control a typical WordPress request

As you learned in the Web Servers lesson, the `index.php` file is the directory index file, and it is executed when a user visits the root URL of this WordPress site.

When the code in `index.php` is executed, it includes the code in the `wp-blog-header.php` file. As you can see `wp-blog-header.php` includes `wp-load.php`, which in turn includes the `wp-config.php` file.

The `wp-config.php` file is the main configuration file for a WordPress site. It contains all the configuration options that are required to run WordPress, like database connection information, security keys, and any custom configuration options that you may want to add.

`wp-config.php` then includes the `wp-settings.php` file, which sets up all the WordPress core functionality.

## Additional PHP files in the root directory

There are some additional PHP files in the root directory that perform specific functions outside of regular WordPress requests. These files are usually accessed directly by either a user or some other function, and are not included in the normal WordPress request flow.

`wp-activate.php` is used to confirm the activation key that is sent in an email after a user signs up for a new site. Typically, this would be used this if you were setting up a WordPress install yourself, or managing a WordPress multisite network.

`wp-comments-post.php` is used to process any comments that are submitted on a WordPress site.

`wp-cron.php` is used to run any scheduled tasks that are set up on a WordPress site. This file is executed every time a WordPress page is requested, and it checks to see if there are any scheduled tasks that need to be run. If there are, it runs them.

`wp-links-opml.php` is used to generate an XML list of links. This was used by a Link Manager feature that was removed in WordPress 3.5. However, it is possible to enable this functionality using [the Link Manager plugin](https://wordpress.org/plugins/link-manager/), and so this file is still included for backwards compatibility.

`wp-login.php` is used to display the login form for a WordPress site. It also processes any login requests that are submitted.

`wp-mail.php` is used by the Post via email feature of WordPress. This feature allows you to publish posts on your WordPress site by sending an email to a specific email address. If enabled, this file is executed every time an email is received to create a new post.

`wp-signup.php` is used to display the signup form for a new site on a WordPress multisite network.

`wp-trackback.php` is used to process any trackback requests that are sent to a WordPress site. Trackbacks are a way for one website to notify another website that it has linked to it, usually in a post content or comment.

`xmlrpc.php` is used to process any XML-RPC requests that are sent to a WordPress site. XML-RPC is a remote procedure call protocol that allows software to make requests to a WordPress site. This is used by the WordPress mobile apps, but you can disable this functionality if you don't use those apps to manage your site.

## Root directories

Along side these files, the root directory of a WordPress installation also contains three directories:

`wp-admin` contains all the files that power the WordPress admin interface. Whenever you're interacting with the WordPress admin, you're using files from this directory.

`wp-content` contains any files that can be added to a default WordPress site. This includes any plugins, themes, and uploaded files. Any directories that plugins need to create to store additional files are also created in this directory.

`wp-includes` contains the bulk of the core WordPress files. This includes all the PHP files that make up the WordPress core, as well as any JavaScript and CSS files that are required to run WordPress. Common functionality like the database API, HTTP API, and plugin API are all included in this directory, and are used by both the WordPress admin and any front end requests.


## YouTube chapters

(0:00) Introduction
(0:29) The root directory
(0:35) Static files
(1:53) PHP files that control a WordPress request
(3:00) Additional PHP files in the root directory
(5:32) Root directories