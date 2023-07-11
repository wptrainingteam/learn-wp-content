# Developing for multisite

## Learning Objectives

Upon completion of this lesson the participant will be able to:

1. 

## Outline

1. Introduction
2. A note on naming conventions
3. Useful functions
4. Useful hooks
5. 
5. Developing for sites in a network
6. Developing themes and child themes
7. Developing plugins
8. Where to find multisite information

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you'll learn about things to consider when developing themes or plugins for a WordPress multisite network.

You'll learn about useful multisite specific functions and hooks, and what to consider when developing themes and plugins to work on a multisite network.

### A note on naming conventions

Before we get started, it's worth noting that there are a couple of different naming conventions used in the WordPress codebase when it comes to multisite. 

WordPress multisite was originally known as [WordPress MU](https://codex.wordpress.org/WordPress_MU) (or WordPress multi-user), and many multisite related functions and hooks use the `wpmu_` prefix. 

Additionally, some functions are named based on the old terminology which described multiple “blogs” on a “site”. This has since been updated to describe multiple “sites” on a “network” instead, but some old terminology still lives on in some function names.

## Useful functions

When you're developing a product to support a multisite network, there are a some of useful internal functions and hooks worth knowing.

The first is the [is_multsite](https://developer.wordpress.org/reference/functions/is_multisite/) function. This function will return true if multisite is enabled, and is probably the most widely used function related to multisite. If you do a search through the WordPress codebase for uses of the is_multsite function, you'll see that it's used in a number of places, to either perform specific tasks in the context of a multisite network, or to restrict functionality only to multisite networks.

There are also some common functions that are useful when developing administration interfaces for a multisite network:

- [is_super_admin](https://developer.wordpress.org/reference/functions/is_super_admin/) can be used to check if the currently logged-in user is a Network Administrator on the network.
- [is_network_admin](https://developer.wordpress.org/reference/functions/is_network_admin/) is the multisite equivalent of the is_admin function, and determines whether the current request is for the network administrative interface.
- [network_admin_url](https://developer.wordpress.org/reference/functions/network_admin_url/) is the multisite equivalent of the admin_url function, and allows you to create URLs relative to the admin area of the network. This is useful for redirecting to different areas of the network admin dashboard.

When working with site content, there are some functions that are widely used. 

[is_main_site](https://developer.wordpress.org/reference/functions/is_main_site/) determines whether the current site is the main site of the current network or not.

Next there is the [get_sites](https://developer.wordpress.org/reference/functions/get_sites/) function, which will return a list of sites matching requested arguments. 

Then there is [switch_to_blog](https://developer.wordpress.org/reference/functions/switch_to_blog/), which allows you to switch to a different site in the network, [restore_current_blog](https://developer.wordpress.org/reference/functions/restore_current_blog/) which restores the current site after you've switched to a different sitem and [get_current_blog_id](https://developer.wordpress.org/reference/functions/get_current_blog_id/), which returns the ID of the current site.

Using these functions, you can perform actions across the network.

For example, let's say you wanted to create a function that updated an option on a specific site on the network.

```php
function update_site_option( $site_id,  $option_name, $option_value ) {
    switch_to_blog( $site_id );
    update_option(  $option_name, $option_value );
    restore_current_blog();
}
```

However, if you wanted to extend that to update the same option across all sites, you could use the get_sites function, and loop through all sites on the network.

```php
function update_option_on_all_sites( $option_name, $option_value ) {
    $sites = get_sites();
    foreach ( $sites as $site ) {
        switch_to_blog( $site->blog_id );
        update_option( 'my_option', 'my_value' );
        restore_current_blog();
    }
}
```

You could also use the [update_blog_option](https://developer.wordpress.org/reference/functions/update_blog_option/) function to update an option on a specific site, without having to switch to that site.
        
```php  
function update_option_on_all_sites( $option_name, $option_value ) {
    $sites = get_sites();
    foreach ( $sites as $site ) {
        update_blog_option( $site->blog_id, $option_name, $option_value );
    }
}
```

When developing multsite plugins, there is the [is_network_only_plugin](https://developer.wordpress.org/reference/functions/is_network_only_plugin/) function. This is a plugin specific function that checks for the “Network: true” in the plugin header to see if this should be activated only as a network wide plugin. This is useful if you want to restrict a plugin to only be activated on a network, and not on individual sites.

## Useful hooks

There are also a couple of useful hooks that you can use when developing for multisite.

The first is the [network_admin_menu](https://developer.wordpress.org/reference/hooks/network_admin_menu/) hook, which allows you to add menu items to the network admin dashboard. This is useful if you want to add a custom menu item to the network admin dashboard.

The second is the [network_admin_notices](https://developer.wordpress.org/reference/hooks/network_admin_notices/) hook, which allows you to add notices to the network admin dashboard. This can be used to display a notice to network admins, in the same way that admin_notices is used for single site notices.

[signup_blogform](https://developer.wordpress.org/reference/hooks/signup_blogform/) is a filter that allows you to modify the signup form for new sites. You can use this to add additional fields to the signup form.

[wp_initialize_site](https://developer.wordpress.org/reference/hooks/wp_initialize_site/) is an action that is fired when a new site is created. This is useful if you want to perform actions when a new site is created, for example if you wanted to assign a custom top level domain to a sub site.

## Developing for sites in a network

When you are rendering any content in the scope of a site on the network, WordPress core is clever enough to know that you are working inside the scope of that site. This means that any functions that you use to retrieve information, such as get_bloginfo, get_option, get_posts, or get_post_meta, and any functions you might use to add or update information, like update_option, wp_insert_post or update_post_meta, will get, add or update the correct tables for the site that you are currently working with. Additionally, if you use functions like register_post_type or register_taxonomy, these will be registered for the current site only.

## Developing themes and child themes

Generally themes and child themes work exactly the same on a multisite network as they do on a single site. Once a theme or child theme is network activated, it can be activated on any single site on the network. 

All specific functionality that you may want to code into a functions.php file will work in the scope of the current theme. For example, if you wanted to display the sitename in the footer of the theme, you could use the standard get_bloginfo function to retrieve the site name.

```php
if ( ! function_exists( 'tt3c_get_site_name' ) ) {
	function tt3c_get_site_name() {
        return get_bloginfo( 'name' );
    }
}
```

However, let's say you wanted to include the main site name in the footer of the theme, regardless of which site was currently being viewed. You could use the switch_to_blog function to switch to the main site, retrieve the site name, and then restore the current site.

```php
if ( ! function_exists( 'tt3c_get_site_name' ) ) {
	function tt3c_get_site_name() {
		$site_name = get_bloginfo( 'name' );
		switch_to_blog( 1 );
		$main_site_name = get_bloginfo( 'name' );
		restore_current_blog();

		return $site_name . ' (part of the ' . $main_site_name . ' network)';
	}
}
```

Taking this one step further, perhaps you want to exlude the main site only from this custom functionality. You could use the is_main_site function to check whether the current site is the main site, and if so, just return the site name.

```php
if ( ! function_exists( 'tt3c_get_site_name' ) ) {
	function tt3c_get_site_name() {
		if ( is_main_site() ) {
			return get_bloginfo( 'name' );
		}

		$current_site = get_bloginfo( 'name' );
		switch_to_blog( 1 );
		$main_site_name = get_bloginfo( 'name' );
		restore_current_blog();
		return $current_site . ' (part of the ' . $main_site_name . ' network)';
	}
}
```

All of this is possible from just one functions.php file inside of a single child theme.

## Developing plugins

As discussed, most plugin functionality will work the same in a single site as well as a multisite. Functions like register_post_type or get_posts will function in the same way, just in the scope of the specific site in question.

However, there are two things to consider when developing plugins for multisite.

Plugins often have a settings page, which is usually accessible from the admin dashboard. This is fine for single site plugins, but on a multisite network, you need to consider where the settings page should be located. Should it be on the network admin dashboard, or on the individual site dashboard? If you need to have a settings page on the network admin dashboard, you can use the network_admin_menu hook to add a menu item to the network admin dashboard. If you need to have a settings page on the individual site dashboard, you can use the admin_menu hook to add a menu item to the individual site dashboard.

Plugins might have to add custom tables to store custom data. If you use something like the $wpdb->prefix variable to prefix your table names, you'll end up with a table name that is prefixed with the site ID. So if you need to have a custom table for this functionality on a per-site basis, you need to plan for it.

Let's look at an example.

Here we have the example plugin from the Introduction to securely developing plugins tutorial. It has a form_submissions table being created when the plugin is activated, which is used to store form submissions. If you look at the code, you'll see that the table name is prefixed with the prefix property from the global $wpdb object. 

In a single site install, this means that it will create one table using the prefix that is defined in the wp-config.php file, in this example `wp_form_submissions`

However, on a multisite network, depending on how the plugin is installed, it will create different tables.

If it's activated on a single site on the network, the table prefix will include the site ID. 

So for site 2 the table name will be `wp_2_form_submissions`.

However, if the plugin is network activated, the activation process is running in the scope of the main site, and it creates the same table as if it's activated on a single site install.

So for network activation the table name will be `wp_form_submissions`.

The problem comes in when you look at the code that stores the form submissions. 

Because this uses the same prefix property from the global $wpdb object, when this code is run in the scope of the main site, it will look for the `wp_form_submissions` table to store the data, but, for exmaple, when it's run in the scope of site 2 on the network, it will look for `wp_2_form_submissions` to store the data, which does not exist.

To fix this we need to update the plugin activation routine, to take this into account:

To start, move the table creation code into a separate function, and then call this function from the activation hook.

```php
function wp_learn_create_table(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  name varchar (100) NOT NULL,
	  email varchar (100) NOT NULL,
	  PRIMARY KEY  (id)
	)";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
```

In the docs for the [register_activation_hook](https://developer.wordpress.org/reference/functions/register_activation_hook/) hook, you'll notice the callback function accepts a $network_wide parameter. This is a boolean value that is passed to the activation hook callback, and indicates whether the plugin is being activated network wide or not.

Then you can update the callback to first check if the site is a multisite network, and if the plugin is being activated network wide. 

If it is, fetch and loop through all sites on the network, switch to each site in turn, and create the table for each site.

Alternatively if the plugin is not being activated network wide, you can just create the table for the current site.

```php
register_activation_hook( __FILE__, 'wp_learn_setup_table' );
function wp_learn_setup_table( $network_wide ) {
	if ( is_multisite() && $network_wide ) {
		$sites = get_sites();
		foreach ( $sites as $site ) {
			switch_to_blog( $site->blog_id );
			wp_learn_create_table();
			restore_current_blog();
		}
	} else {
		wp_learn_create_table();
	}
}
```

Test this out by activating the plugin on the network, you should see all the right tables have been created.

But what happens when a new site is created? In that case, you'd need to use a hook like `wp_initialize_site`, to create the table for any new sites.

```php
add_action( 'wp_initialize_site', 'wp_learn_setup_newsite_table' );
function wp_learn_setup_newsite_table( $site ) {
    switch_to_blog( $site->id );
    wp_learn_create_table();
    restore_current_blog();
}
```

Test that out by creating a new site. It should create the new table for that site's form submissions in the database.

In this way you allow your plugin to work on a multisite network, both when it's activated for the first time on the network, taking into account any existing sites, but also future proofing for any new sites.

### Where to find more information

Besides the documentation on [Creating a network ](https://wordpress.org/documentation/article/create-a-network/) and things to [consider before creating a network](https://wordpress.org/documentation/article/before-you-create-a-network/), there's not a lot of developer focused documentation specific to developing for multisite. 

However, it is possible to view a list of all multisite related functionality by browsing to the [multisite package](https://developer.wordpress.org/reference/package/multisite/) section of the WordPress code reference. 

From there you can filter by classes, functions, hooks, and class methods.

Happy coding!