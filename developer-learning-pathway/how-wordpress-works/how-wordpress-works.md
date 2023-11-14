# Web Servers

## Overview

At the most basic level, WordPress is a web application that runs on a web server. But what makes up a web server, and what makes it possible for the web server to run WordPress?

A web server is computer that is connected to the internet and is configured to serve web pages. Web servers come in all shapes, sizes and configurations, but ultimately they are all just computers, just like the one you use every day to work on.

What makes a web server a little different is that it has software installed and configured to serve a web application like WordPress.

WordPress runs on a tech stack called LAMP. LAMP stands for Linux, Apache, MySQL, and PHP.

Linux is the operating system that manages the hardware and software resources of the server. Popular Linux distributions include Ubuntu, Debian, and CentOS. These operating systems made up of the Linux kernel and a collection of software packages that are installed on top of the kernel. 

Apache, MySQL, and PHP are all installed via the package manager of the specific Linux distribution on the server.

Apache is the web server software that is used to serve information on a web server. When you type a URL into your browser, the browser sends a request for some information to the web server. The web server then responds with the information that you requested. The web server software is what makes this possible. 

A popular alternative to Apache is called Nginx. Nginx is slightly newer web server software that is generally considered to be faster and more efficient than Apache at serving static content. When using Nginx, the tech stack is referred to as LEMP, which stands for Linux, Nginx, MySQL, and PHP.

By default, Apache and Nginx are configured to serve static files. Static files are files that don't change. Examples of static files include HTML files, image files or video files. HTML files can be styled using CSS, and can be made interactive using JavaScript. 

MySQL is a database software that is used to store information on the web server. For example, if you are running an online store, you will need to store information about the products that you are selling. This is where a MySQL database comes in. 

PHP is a programming language that is used to create dynamic web pages. PHP is a server side language, which means that it is executed on the web server, and the results are sent to the browser. In the online store example above, PHP is used to fetch the product information from the MySQL database and display it on the web page in the browser.

## Apache/Nginx configuration

When you install Apache or Nginx on a server, there are some files that you can configure to change the way that the web server works. Generally this configuration is done by a server system administrator. However, it's useful to understand one specific configuration set, and that's the configuration that allows a single instance of a web server to serve content for multiple websites. 

On Apache this is called a virtual host, and on Nginx this is called a server block, but they both do the same thing. They allow you to configure the web server to serve different content for different websites.

Here's an example of a virtual host configuration for Apache:

```
Listen 80
<VirtualHost *:80>
    DocumentRoot "/www/example1"
    ServerName www.example.com
</VirtualHost>
```

And here's the same example for Nginx:

```
server {
    listen 80;
    server_name www.example.com;
    root /www/example1;
}
```

In both examples, the web server is configured to listen for requests on port 80, which is the default port for HTTP requests. When the server receives a request for the domain www.example.com, it will serve the files that are located in the directory `/www/example1`.

## Directory Index

By default, the web server is configured to look for a Directory Index file. If it finds one, it will serve that file. If it doesn't find one, it will return a 404 error. The default Directory Index file is usually `index.html`. 

When PHP is installed and enabled, it's possible to configure the web server to look for and serve a PHP file as the Directory Index. This is usually a file named `index.php`.

In Apache, this is done using the `DirectoryIndex` directive and in Nginx this is done using the `index` directive, by placing index.php before the index.html file in the list.

``` 
DirectoryIndex index.php index.html
```

```
index index.php index.html;
```

Most LAMP or LEMP web servers will have this configuration set up by default. 

So in the above example, when you visit www.example.com in your browser, the web server will look for the `index.php` Directory Index file in the `/www/example1` directory and execute that file. If no `index.php` is found, it will look for a serve an `index.html` file. If no `index.html` file is found, it will return a 404 error.

## WordPress request flow

When a user visits a URL on a WordPress site, the following happens:

1. The browser sends a request to the web server for the data at the URL that the user entered
2. The web server receives the request and determines which `index.php` file should be executed to serve the requested data.
3. The PHP interpreter executes the PHP code
4. If required, the PHP code will interact with the MySQL database to retrieve any required data
5. The PHP code will then output HTML code, and include any relevant CSS or JavaScript
6. The web server will send the HTML, CSS and JavaScript code back to the browser
7. The browser will render this code and display the page to the user

This is the same whether you are visiting the home page of a WordPress site, a specific post or page, or the admin area of the site. The only difference is the PHP code that is executed.

# The WordPress file structure

## Introduction

Like most software applications, WordPress is made up of a collection of files organised into a specific structure. Because WordPress is open source, when you download the archive of the latest version of WordPress, you're able to see and inspect all the files.

## Root files

The root directory of a WordPress site contains a series of files, as well as three directories. 

### Non static files

To start, let's look at some static files:

The `.htaccess` file is a special file that is used to configure the Apache web server for a WordPress installation. It is essentially an extension of the Apache Virtual Host configuration that we looked at earlier. Any valid Apache directives can be added to this file, and will be applied for this WordPress installation. 

It's worth noting that Nginx does not support the use of an .htaccess file like configuration on a per WordPress level. Instead, the configuration is done in the main server block configuration file. This is one of the reasons that Nginx is considered to be faster than Apache, but also what makes it less configurable by the site owner. 

The `license.txt` file contains the license information for WordPress. WordPress is licensed under the open source GNU General Public License, version 2. This license allows anyone to use, modify, and redistribute WordPress.

The `readme.html` file contains information about the WordPress, including sections on installing and updating WordPress, system requirements, and links to various online resources. As an HTML file, it's best viewed in a web browser.

### PHP files that control a WordPress request

Let's start with the `index.php` file. As you learned in the Web Servers lesson, the `index.php` file is the directory index file, and it is executed when a user visits the root URL of this WordPress site. 

When the code in `index.php` is executed, it includes the code in the `wp-blog-header.php` file. As you can see `wp-blog-header.php` includes `wp-load.php`, which in turn includes the `wp-config.php` file.

The `wp-config.php` file is the main configuration file for a WordPress site. It contains all the configuration options that are required to run WordPress, like database connection information, security keys, and any custom configuration options that you may want to add.

`wp-config.php` then includes the `wp-settings.php` file, which sets up all the WordPress core functionality. 

### Additional PHP files in the root directory

There are some additional PHP files in the root directory that perform specific functions outside of regular WordPress requests. These files are usually accessed directly by either a user or some other function, and are not included in the normal WordPress request flow.

`wp-activate.php` is used to confirm that the activation key that is sent in an email after a user signs up for a new site. Typically, this would be used this if you were setting up a WordPress install yourself, or managing a WordPress multisite network. 

`wp-comments-post.php` is used to process any comments that are submitted on a WordPress site.

`wp-cron.php` is used to run any scheduled tasks that are set up on a WordPress site. This file is executed every time a WordPress page is requested, and it checks to see if there are any scheduled tasks that need to be run. If there are, it runs them.

`wp-links-opml.php` is used to generate an XML list of links. This was used by a Link Manager feature that was removed in WordPress 3.5. However, it is possible to enable this functionality using [a plugin](https://wordpress.org/plugins/link-manager/), and so this file is still included for backwards compatibility.

`wp-login.php` is used to display the login form for a WordPress site. It also processes any login requests that are submitted.

`wp-mail.php` is used by the Post via email feature of WordPress. This feature allows you to publish posts on your WordPress site by sending an email to a specific email address. If enabled, this file is executed every time an email is received to create a new post.

`wp-signup.php` is used to display the signup form for a new site on a WordPress multisite network. 

`wp-trackback.php` is used to process any trackback requests that are sent to a WordPress site. Trackbacks are a way for one website to notify another website that it has linked to it, usually in a post content or comment.  

`xmlrpc.php` is used to process any XML-RPC requests that are sent to a WordPress site. XML-RPC is a remote procedure call protocol that allows software to make requests to a WordPress site. This is used by the WordPress mobile apps, but you can disable this functionality if you don't use those apps to manage your site.

## Root directories

The root directory of a WordPress site contains three directories:

`wp-admin` contains all the files that power the WordPress dashboard area. Whenever you're interacting with the WordPress dashboard, you're using files from this directory.

`wp-content` contains any files that can be added to a default WordPress site. This includes any plugins, themes, and uploaded files. Any directories that plugins need to create to store additional files are also created in this directory.

`wp-includes` contains the bulk of the core WordPress files. This includes all the PHP files that make up the WordPress core, as well as any JavaScript and CSS files that are required to run WordPress. Common functionality like the database API, HTTP API, and plugin API are all included in this directory, and are used by both the WordPress dashboard and any front end requests.

# The WordPress Database

WordPress uses a database to store, retrieve, and display all the content that you create on your website. This includes posts, pages, comments, and more.

The database is also used to store information about your website's users, and various site options that power your site.

As you learned in the Web Servers lesson, WordPress uses [MySQL](https://www.mysql.com/). MySQL is a free, open-source database management system that is used by many popular web applications.

## Interacting with your WordPress database

There are a few ways to interact directly with your WordPress database.

The majority of local development environments or hosting companies use a free tool called [phpMyAdmin](https://www.phpmyadmin.net/). phpMyAdmin is a web-based tool that allows you to interact with your WordPress database using a web browser.

An alternative to phpMyAdmin is a tool called [Adminer](https://www.adminer.org/). Adminer is a single PHP file that you can upload to your website, and it provides a similar interface to phpMyAdmin. Some hosting companies and local development environments prefer to use Adminer instead of phpMyAdmin.

Finally, if you don't have access to either, you can also install a plugin called [SQL Buddy](https://wordpress.org/plugins/sql-buddy/).

This is a free WordPress plugin that provides a similar interface to phpMyAdmin and Adminer, but it runs inside your WordPress dashboard.

If you do decide to use SQL Buddy, please remember to deactivate and delete the plugin when you are done using it. Leaving it installed on your website is a possible security risk.

## Database Tables

The WordPress database is made up of many tables. Each table stores a different type of data for your website.

Each table has the same prefix, which is defined in the `wp-config.php` file. By default, the prefix is `wp_`, but you can change this to anything you like during the WordPress installation process.

Let's start by looking at the most important tables for managing content.

### wp_posts and wp_postmeta

The `wp_posts` table is probably the most important table in a WordPress site, and stores information about your website's posts, pages, or any other custom post type. Each row in the `wp_posts` table represents a single post. The `wp_postmeta` table allows you to store additional information about each post. The post meta are also often referred to as custom fields.

### wp_comments and wp_commentmeta

The `wp_comments` table stores information about the comments on your posts and pages. Whenever someone comments on a post or page, this table is where that comment is stored. Each row in the `wp_comments` table represents a single comment. The `wp_commentmeta` table can store additional information about each comment.

### wp_user and wp_usermeta

The `wp_users` table stores all the information about your website's users. Each row in the `wp_users` table represents a single user. Like other meta tables, the `wp_usermeta` table can store additional information about each user.

## Functions to interact with posts, comments, and users

For all WordPress database tables, there are functions that you can use to interact with that table.

These functions form part of the WordPress Database API.

All of these functions can be found by using the search feature in the WordPress developer documentation, under Code Reference.

Generally, the functions that you can use to interact with the WordPress database all follow a similar pattern.

There is an insert function, an update function, and a delete function.

These usually have the same name, with the prefix `wp_` followed by the action, followed by the name of the table.

Let's look at these functions for posts for example:

`wp_insert_post` is the function to create a new post

`wp_update_post` is the function to update an existing post

`wp_delete_post` is the function to delete a post

Then there are usually a functions to fetch either all the records from a table, or a single record.

These usually have the same name, with the prefix `get_` followed by either the singular or plural name of the table.

So for example `get_posts` is the function to fetch a collection of posts.

And `get_post` is the function to fetch a singular post.

Each of these functions typically has a number of parameters that you can use to filter the results that are returned.

Then, there are also functions to interact with any meta tables, usually to insert, update, or delete meta fields.

These usually have the same name, with the action, followed by the singular name of the table, followed by `_meta`.

So for example for posts, `add_post_meta` is the function to insert a meta field.

Similarly `update_post_meta` the function to update a meta field and `delete_post_meta` the function to delete a meta field.

## wp_terms, wp_termmeta, wp_term_relationships, and wp_term_taxonomy

The `wp_terms`, `wp_termmeta`, `wp_term_relationships`, and `wp_term_taxonomy` tables are the tables that manage the categories and tags in your WordPress site.

The `wp_terms` table stores information about your website's terms. Each row in the `wp_terms` table represents a single term. Under the hood, categories and tags are both terms.

What determines whether they are a category or a tag is the taxonomy that they are associated with, which is stored in the `wp_term_taxonomy` table.

The `wp_term_relationships` table stores the relationships between terms and their parent objects, be that a post, page, or custom post type.

Finally, The `wp_termmeta` table can store additional information about each term.

### Functions to interact with terms and taxonomies

Similar to the functions to interact with posts, comments, and users, there are also functions to interact with terms and taxonomies, which can be found by searching the WordPress Code Reference for term or taxonomy.

### wp_options

The `wp_options` table stores information about your website's settings. Each row in the `wp_options` table represents a specific setting. For example, the `siteurl` option stores the URL of your website, and the `blogdescription` option stores the tagline of your website. The `wp_options` table also stores information about your website's active theme and active plugins.

Data is stored in the `wp_options` table using a key-value format. The key is the name of the option, and the value is the value of the option.

It is also possible to store serialized data in the `wp_options` table. Serialized data is a string that contains multiple values. Serialized data is often used to store arrays and objects of data. A good example of this is the list of active plugins, which is stored as a serialized array.

### Functions to interact with options

The [Options API](https://developer.wordpress.org/apis/options/) is typically used along with the [Settings API](https://developer.wordpress.org/apis/settings/) to create settings pages for the WordPress dashboard, either via core, plugins, and themes. The Options API provides functions to interact with the `wp_options` table, like `add_option`, `update_option`, and `delete_option`.

### wp_links

The `wp_links` table stores information about your website's links. Each row in the `wp_links` table represents a single link. Links was a feature that was [removed from WordPress in version 3.5](https://core.trac.wordpress.org/ticket/21307).

However, the `wp_links` table is still included in the WordPress database for backwards compatibility, and it is still possible to add links to your website using [the Links Manager plugin](https://wordpress.org/plugins/link-manager/).

# Permalinks - rewriting dynamic URLS

In the Web Servers lesson, you learned that most PHP based applications will have a Directory Index file. This is the file that will be executed when a user browses to the URL of the site in question.

With a site powered by WordPress however, it's possible to have multiple different types of content rendered, like posts, pages, or products, all via the same Directory Index file. The key behind how this works is a feature called Permalinks.

## Query String Variables

Before we dive into permalinks, it's useful to understand a concept known as a query string. Let's take a look at a simple example.

```
https://example.com/?p=1
```

In this example, the URL is `https://example.com/`, and the query string is `?p=1`. The query string is a way to pass data to the web server. Here, the query string is passing the value `1` to the variable `p`.

In PHP, it's possible to access the value of the variable `p` using the `$_GET` super global. 

```php
<?php
$p = $_GET['p'];
```

The PHP code can then use this to perform some sort of data look up, for example to retrieve a post from the database with the ID of 1.

Permalinks, also known as clean URLs, are a way to make URLs more human-readable. Instead of using a query string, permalinks use a URL structure that is based on the content that is being requested. Here is the same example as above, but using a permalink.

```
https://example.com/1/
```

In this example, the URL is `https://example.com/1/`. There is no query string, and the URL is much more human-readable. But how does the web server know what content to serve?

Based on the expected URL structure, the web server can be configured to perform URL mapping, which uses a web server feature called URL rewriting. The web server can be configured to expect a certain URL structure after the main URL, and pass that data to the web application, which handles fetching the relevant information based on the data it receives.

## WordPress and Permalinks

WordPress has a feature called Permalinks, which allows you to configure the URL structure of your WordPress site. You can find this feature in the WordPress dashboard under **Settings > Permalinks**.

The default permalink structure is Plain, meaning no Permalinks are in use, and plain query strings are used. 

The other options allow you to set your desired permalink structure from a list of common options, or define your own custom structure.

When you set one of any these options other than Plain, the server will be configured to expect a clean URL based on the structure that you have defined. At the same time, WordPress will store the selected structure in the database. When a request is made to the site using a matching structure, WordPress will use these two pieces of data to map the URL structure to information that needs to be displayed, fetch that information, and display it on the page.

On Apache web servers, this is typically handled in the .htaccess file. For example, if you set your permalink structure to any of the options other than Plain, the following code will be added to your .htaccess file:

```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
```

This checks if the Apache web server has the rewrite module enabled, and then sets up the rewrite rules to expect a clean URL structure. 

On Nginx web servers, this is typically handled in the server block configuration file. Because Nginx does not support the use of an .htaccess file like configuration on a per WordPress level, the configuration is typically added by default to the server block in a location directive.

```
location / {
            try_files $uri $uri/ /index.php?$args; 
}
```

Whenever a permalink structure is set, if you add links to internal content like posts or pages, WordPress will automatically generate the correct URL based on the permalink structure that you have set.

# WordPress Front End Request

Except for specific requests (like the ones we looked at in the Root Files lesson), any requests for content on a WordPress site (also known as the WordPres front end)  is handled by the `index.php` file in the root directory. 

Here, the `WP_USE_THEMES` constant is set up, and then the first additional file is required, `wp-blog-header.php`.

## A note on require, require_once, include, include_once

[require](https://www.php.net/manual/en/function.require.php) is a special php statement that will include the contents of the file being required. There's a similar statement in PHP called [include](https://www.php.net/manual/en/function.include.php), which does the same thing. The difference is that using require will throw an error and end execution if the file can't be required. There's also a supplementary statement called require_once (or include_once) that will only include the file if it's not been included already.

# wp-blog-header.php

The wp-blog-header file sets up the WordPress environment by requiring the wp-load.php file

It then calls the wp() function, which sets up the WordPress query, and then loads the theme template by requiring the template-loader.php file.

## wp-load.php

Here the ABSPATH constant is defined, which is used by most plugins as a check if the plugin is indeed being run in a WordPress environment.

This file then sets some error_reporting levels.

After that it finds and loads the wp-config.php file OR attempts to redirect to /wp-admin/setup-config.php, to inform the user to create the wp-config.php file

You'll also note that this code allows the wp-config.php file to be moved outside of the WordPress directory, which is a common security best practice. By moving the wp-config.php file outside of the WordPress directory, you can prevent the file from being accessed by a malicious user.

### wp-config.php

This file defines the DB constants, debugging constants, and other constants that your WordPress installation might need

It then requires the wp-settings.php file which sets up the WordPress environment

### wp-settings.php

wp-settings.php is the file that sets up the WordPress environment. It's does a lot of work, so this will be a high level summary of all the things it sets up.

1. Sets up version information
2. Requires files needed for initialisation
3. Sets up most default constants
4. Registers a fatal error handler if anything goes wrong
5. Sets up various server vars, checks for maintenance mode or and checks debug modes
6. Requires the core WordPress files needed for core WordPress functionality
7. Sets up the database layer and global database variables
8. Initializes multisite
9. defines the SHORTINIT constant, which can be used for custom requests
9. Loads the rest of the WordPress files
10. Loads must-use plugins
11. Loads network active plugins (if multisite)
12. Sets up any constants needed for cookies or SSL
13. Creates any common variables
14. Creates core taxonomies and post types
15. Registers the theme directory root
16. Loads active plugins
17. Loads pluggable functions (no longer in use)
18. Adds magic quotes to any request vars
12. Creates the global WP_Query object, WP_Rewrite object, WP object, WP_Widget_Factory object, WP_Roles object
13. Sets up locale functionality (multi-language support and localisation/translation)
14. Loads the active theme's functions.php file
15. Creates an instance of WP_Site_Health for cron events

## wp() function

Once the WordPress environment has been set up, the wp() function is called. This function determines what needs to be rendered, and fetches the relevant data from the database.

1. Runs $wp->main( $query_vars ); - this is the main method of the WP class which is found in the wp-includes/class-wp.php file.
    1. Runs the WP-> init() method, which calls the wp_get_current_user function, which sets up the current user object
    2. Runs the WP->parse_request() method, which parses the request and sets up the query variables, based on the request
        1. Matches the request to the rewrite rules, and creates the query_vars array based on the matched rules
        2. If no rewrite rules match, the query_vars array is populated based on the request (query string or whatever)
        3. if WP->parse_request() returns true:
            1. Runs the WP->query_posts() method, which sets up the global $wp_query object
                1. Runs the WP->build_query_string() method, which builds the query string from the query variables
                2. Runs the WP_Query->query() method, which runs the query and populates the WP_Query object with the results
                    1. init
                    2. wp_parse_args
                    3. get_posts - Retrieves an array of posts based on query variables.
                        1. pre_get_posts
                        2. creates the SQL query based on the passed query parameters/permalink
                        3. Builds and runs the query and returns the posts
            2. Runs the WP->handle_404() method, which sets the Headers for 404, if nothing is found for requested URL.
            3. Runs the WP->register_globals() method, which registers the query variables as global variables
    3. Runs the WP->send_headers() method, which sends the headers to the browser
    4. do_action_ref_array wp - Calls the callback functions that have been added to an action hook, specifying arguments in an array. https://developer.wordpress.org/reference/functions/do_action_ref_array/

## template-loader.php

After all the query data is setup the template loader is required. This finds and loads the correct template based on the visitor's url

1. template_redirect action - Fires before the template is loaded.
2. is_robots() - Checks if the request is for the robots.txt file.
3. is_favicon() - Checks if the request is for the favicon.ico file.
4. is_feed() - Checks if the request is for an RSS feed.
5. is_trackback() - Checks if the request is for a trackback.
6. if wp_using_themes
    1. Loop through each of the template conditionals, and find the appropriate template file.
7. template_include filter - Filters the path of the current template before including it.
8. include the template file - note the use of include not require, so that the rest of the page can still be rendered if the template file is missing.

# WordPress Admin page request

The functionality of the WordPress administration interface is handled by the files in the `wp-admin` directory. Unlike the typical front end request, different PHP files are executed depending on the functionality being used. Additionally, permalinks are not used in the dashboard, and instead query strings are used to pass data to these locations.

For example, the default URL of the admin dashboard is `https://example.com/wp-admin/` and this will load the `index.php` file in the `wp-admin` directory. 

However, if you want to view the posts in your site, the URL is `https://example.com/wp-admin/edit.php` and this will load the `edit.php` file in the `wp-admin` directory.

If you click in the Edit post button, the requested URL is `https://example.com/wp-admin/post.php?post=1&action=edit` and this will load the `post.php` file in the `wp-admin` directory, passing it the `edit` action and the `post` ID of `1`. These query string variables are then used to determine what content to display.

There are however a lot of commonalities in how each of these different admin files work.

1. The `wp-admin/admin.php` file is included, which sets up the WordPress environment
   1. This file sets up any admin specific constants, and then includes the same `wp-load.php` file that is used on the front end, which in turn includes `wp-config.php` to include all the configuration settings for the WordPress install, and `wp-settings.php` which sets up the WordPress environment.
2. The file will then load any specific internal functionality, but only for the purposes of this specific section of the admin interface. 
   1. In the case of the dashboard, it wil include the WordPress Dashboard API which is located at `wp-admin/includes/dashboard.php`
   2. It will then set up any specific content and variables required for the dashboard functionality
3. Next it will include the `wp-admin/admin-header.php` file, which performs things like setting up and rendering the header area of the admin interface as well as rendering the admin menu.
4. After that it will generate and render the content for the specific admin page
5. Finally, it will include the `wp-admin/admin-footer.php` file, which sets up and renders the footer of the admin interface