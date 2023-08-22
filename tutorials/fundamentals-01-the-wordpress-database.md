# WordPress Developer Fundamentals - The WordPress Database

<!-- 
This area is for general notes about the tutorial script, and does not to be recorded in the final audio/video.
PHP script to be used in the tutorial https://gist.github.com/jonathanbossenger/d96520acd6225ea969f091752a3bca8b
Any linked URLS do not need to be read out, as they will be displayed on screen.
Headings do not need to be read out, as they will be displayed on screen.
When reading things like function names or table names, it's not required to read out the _'s. So wp_posts can be read as "wp posts".
-->

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you'll be learning about the WordPress database. 

You will learn about the different tables in the WordPress database, what they're used for, and learn about core WordPress functions to use when you want to interact with these database tables.

## The WordPress Database

WordPress uses a database to store, retrieve, and display all the content that you create on your website. This includes posts, pages, comments, and more. 

The database is also used to store information about your website's users, and various site options that power your site.

WordPress uses a database management system called [MySQL](https://www.mysql.com/). MySQL is a free, open-source database management system that is used by many popular web applications.

## Interacting with your WordPress database

There are a few ways to interact directly with your WordPress database. 
 
The majority of local development environments or hosting companies use a free tool called [phpMyAdmin](https://www.phpmyadmin.net/). phpMyAdmin is a web-based tool that allows you to interact with your WordPress database using a web browser.

An alternative to phpMyAdmin is a tool called [Adminer](https://www.adminer.org/). Adminer is a single PHP file that you can upload to your website, and it provides a similar interface to phpMyAdmin. Some hosting companies and local development environments prefer to use Adminer instead of phpMyAdmin.

Finally, if you don't have access to either, you can also install a plugin called [SQL Buddy](https://wordpress.org/plugins/sql-buddy/). 

This is a free WordPress plugin that provides a similar interface to phpMyAdmin and Adminer, but it runs inside your WordPress dashboard.

If you do decide to use SQL Buddy, please remember to deactivate and delete the plugin when you are done using it. Leaving it installed on your website is a possible security risk.

For the purposes of this tutorial, we will be using phpMyAdmin to interact with the WordPress database.

## Database Tables

The WordPress database is made up of many tables. Each table stores a different type of data for your website. 

Each table has the same prefix, which is defined in the wp-config file. By default, the prefix is `wp_`, but you can change this to anything you like during the WordPress installation process.

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

## Conclusion

And that wraps up this overview of the WordPress database. 

Happy coding.