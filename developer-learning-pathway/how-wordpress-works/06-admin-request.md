# WordPress Admin page request

## Introduction

The functionality of the WordPress administration interface is handled by all the files in the `wp-admin` directory.

Let's dive a bit deeper into the code that runs on a typical WordPress admin request, and understand how it differs from a front end request.

## What is an admin request?

Unlike the typical front end request, different PHP files are executed depending on the functionality being used. Additionally, permalinks are not used in the dashboard, and instead query strings are used to pass data to these locations.

For example, the default URL of the admin dashboard is `https://example.com/wp-admin/`

This will load the `index.php` file in the `wp-admin` directory.

However, if you want to view the posts in your site, the URL is `https://example.com/wp-admin/edit.php`.

This will load the `edit.php` file in the `wp-admin` directory.

If you click in the Edit post button, the requested URL is `https://example.com/wp-admin/post.php?post=1&action=edit`.

This will load the `post.php` file in the `wp-admin` directory, passing it the `edit` action and the `post` ID of `1`. These query string variables are then used to determine what content to display.

There are however a lot of commonalities in how each of these different admin files work.

1. The `wp-admin/admin.php` file is included, which sets up the WordPress environment
    1. This file sets up any admin specific constants, and then includes the same `wp-load.php` file that is used on the front end, which in turn includes `wp-config.php` to include all the configuration settings for the WordPress install, and `wp-settings.php` which sets up the WordPress environment.
2. The file will then load any specific internal functionality, but only for the purposes of this specific section of the admin interface.
    1. In the case of the dashboard, it wil include the WordPress Dashboard API which is located at `wp-admin/includes/dashboard.php`
    2. It will then set up any specific content and variables required for the dashboard functionality
3. Next it will include the `wp-admin/admin-header.php` file, which performs things like setting up and rendering the header area of the admin interface as well as rendering the admin menu.
4. After that it will generate and render the content for the specific admin page
5. Finally, it will include the `wp-admin/admin-footer.php` file, which sets up and renders the footer of the admin interface