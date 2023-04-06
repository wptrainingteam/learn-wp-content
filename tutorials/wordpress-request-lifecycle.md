# The WordPress request lifecycle

## Objectives

Upon completion of this lesson the participant will be able to:

## Outline

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you'll learn about the WordPress request lifecycle. 

You'll learn about the different files that are involved in a typical WordPress request, how they interact with each other, and how the relevant data is queried and finally rendered to the browser.

## A Note on query strings and query variables

Before you dive into the WordPress request lifecycle, it's important to understand how query strings and permalinks. 

A query string is a part of a URL which assigns values to specified parameters. The query string appears at the end of the URL and is indicated by a question mark. For example, the following URL contains a query string:

`http://example.com/?page_id=2`

The query string in this example is `?page_id=2`. The `page_id` parameter is assigned the value `2`. The query string can contain multiple parameters, each separated by an ampersand. For example:

`http://example.com/?page_id=2&s=hello`

Typically, a WordPress install has permalinks enabled, which means that the query string is not part of the URL. Instead, the URL is rewritten to look like this:

`http://example.com/sample-page/`

Either way, the permalink or the query string contains information about the data that needs to be queried. During a typical WordPress request, the query string or permalink will be converted into query variables, that WordPress will use to fetch the relevant data.

## The WordPress request lifecycle

## index.php

The entry point of any WordPress front end request is the index.php file. This is the file that will run whenever a request is made to anything not under the wp-admin directory (ie the dashboard). If permalinks are enabled, the code in the .htaccess file will rewrite the request to the index.php file. If permalinks are disabled, the query string will be passed to the index.php file.

Here, the WP_USE_THEMES constant is set up, and then the first additional file is required, wp-blog-header.php. 

### A note on require, require_once, include, include_once

[require](https://www.php.net/manual/en/function.require.php) is a special php statement that will include the contents of the file being required. There's a similar statement in PHP called [include](https://www.php.net/manual/en/function.include.php), which does the same thing. The difference is that using require will throw an error and end execution if the file can't be required. There's also a supplementary statement called require_once (or include_once) that will only include the file if it's not been included already. 

## wp-blog-header.php

The wp-blog-header file sets up the WordPress environment by requiring the wp-load.php file

It then calls the wp() function, which sets up the WordPress query, and then loads the theme template by requiring the template-loader.php file.

### wp-load.php

Here the ABSPATH constant is defined, which is used by most plugins as a check if the plugin is indeed being run in a WordPress environment.

This file then sets some error_reporting levels.

After that it finds and loads the wp-config.php file OR attempts to redirect to /wp-admin/setup-config.php, to inform the user to create the wp-config.php file

You'll also note that this code allows the wp-config.php file to be moved outside of the WordPress directory, which is a common security best practice. By moving the wp-config.php file outside of the WordPress directory, you can prevent the file from being accessed by a malicious user.

#### wp-config.php

This file defines the DB constants, debugging constants, and other constants that your WordPress installation might need 

It then requires the wp-settings.php file which sets up the WordPress environment

#### wp-settings.php

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

### wp() function

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

### template-loader.php

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

## Summary

And that's it, the typical WordPress request lifecycle for rendering a post or page. 

Happy coding!

