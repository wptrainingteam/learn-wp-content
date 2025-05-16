# Front End Request

## Introduction

There are two types of requests that can be made to a WordPress site, a front end request, and an admin request. Let's dive a bit deeper into the code that runs on a typical WordPress front end request.

## What is a front end request?

Except for specific requests (like the ones we looked at in the File structure lesson), any requests for content on a WordPress site (also known as the WordPres front end)  is handled by the `index.php` file in the root directory.

Here, the `WP_USE_THEMES` constant is set up, and then the first additional file is required, `wp-blog-header.php`.

## A note on require, require_once, include, include_once

[require](https://www.php.net/manual/en/function.require.php) is a special php statement that will include the contents of the file being required. There's a similar statement in PHP called [include](https://www.php.net/manual/en/function.include.php), which does the same thing. The difference is that using require will throw an error and end execution if the file can't be required.

There are also a supplementary statements namely require_once (or include_once) that will only include the file if it's not been included already.

## wp-blog-header.php

The wp-blog-header file sets up the WordPress environment by requiring the wp-load.php file

## wp-load.php

Here the ABSPATH constant is defined, which is used by most plugins as a check if the plugin is indeed being run in a WordPress environment.

This file then sets some error_reporting levels.

After that it finds and loads the wp-config.php file OR attempts to redirect to /wp-admin/setup-config.php, to inform the user to create the wp-config.php file

You'll also note that this code allows the wp-config.php file to be moved outside of the WordPress directory, which is a common security best practice. By moving the wp-config.php file outside of the WordPress directory, you can prevent the file from being accessed by a malicious user.

## wp-config.php

This file defines the database constants, debugging constants, and other constants that your WordPress installation might need

It then requires the wp-settings.php file which sets up the WordPress environment

## wp-settings.php

wp-settings.php is the file that sets up the WordPress environment. It's does a lot of work, so this will be a high level summary of all the things it sets up.

1. Sets up version information
2. Requires any files needed for initialisation
3. Sets up most default constants
4. Registers a fatal error handler if anything goes wrong
5. Sets up various server vars, checks for maintenance mode or and checks debug modes
6. Requires the core WordPress files needed for core WordPress functionality
7. Sets up the database layer and global database variables
8. Initializes multisite
9. defines the SHORTINIT constant, which can be used for custom requests
10. Loads the rest of the WordPress files
11. Loads must-use plugins
12. Loads network active plugins (if multisite)
13. Sets up any constants needed for cookies or SSL
14. Creates any common variables
15. Creates core taxonomies and post types
16. Registers the theme directory root
17. Loads active plugins
18. Loads pluggable functions (no longer in use)
19. Adds magic quotes to any request vars
20. Creates the global WP_Query object, WP_Rewrite object, WP object, WP_Widget_Factory object, WP_Roles object
21. Sets up locale functionality (multi-language support and localisation/translation)
22. Loads the active theme's functions.php file
23. Creates an instance of WP_Site_Health for cron events

## wp() function

Back to the wp-blog-header.php file, Once the WordPress environment has been set up, the wp() function is called. This function determines what needs to be rendered, and fetches the relevant data from the database.

The wp function calls the main method of the $wp object which is found in the wp-includes/class-wp.php file.

This method calls the init() method.

This method calls the wp_get_current_user function, which sets up the current user object

In then calls the parse_request() method

This method which parses the request and sets up the query variables, based on the request

This method does a lot, but the short version is that it matches the request to the rewrite rules, and creates the query_vars array based on the matched rules. If no rewrite rules match, it will attempt to populate the query_vars array based on a the query string.

Back to the main method, if parse_request() returns true it will call the query_posts, handle_404, and register_globals methods.

query_posts() calls build_query_string() method, which builds the query string from the query variables

It then calls the query() method of the wp_the_query object. This code is found in the WP_Query class file at wp-includes/class-wp-query.php. This will run the query and populates the WP_Query object with the results

Once it initialises the query and parse the arguments, it will run the get_posts method, which creates the SQL query based on the passed query parameters/permalink, and then runs the query against the database to returns the relevant data

handle_404() which sets the Headers for 404, if nothing is found for requested URL.

Finally register_globals() registers the query variables as global variables

After that's done, the send_headers() method, which sends any relevant headers to the browser

Last but not least it runs any callback functions that have been added to the wp action hook. You will learn about hooks in a latter lesson.

## template-loader.php

Back to wp-blog-header.php, after all the query data is set up the template loader is required. This finds and loads the correct template based on the visitor's url

1. template_redirect action - Fires before the template is loaded.
2. is_robots() - Checks if the request is for the robots.txt file.
3. is_favicon() - Checks if the request is for the favicon.ico file.
4. is_feed() - Checks if the request is for an RSS feed.
5. is_trackback() - Checks if the request is for a trackback.
6. if wp_using_themes
    1. Loop through each of the template conditionals, and find the appropriate template file.
7. template_include filter - Filters the path of the current template before including it.
8. include the template file - note the use of include not require, so that the rest of the page can still be rendered if the template file is missing.


## YouTube chapters

0:00 Introduction
0:12 What is a front-end request?
0:33 A note on require, require_once, include, include_once
0:57 wp-blog-header.php
1:03 wp-load.php
1:43 wp-config.php
1:57 wp-settings.php
4:45 wp() function
6:53 template-loader.php
