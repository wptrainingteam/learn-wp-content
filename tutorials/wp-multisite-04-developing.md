# Developing for multisite

## Learning Objectives

Upon completion of this lesson the participant will be able to:

1. 

## Outline

1. Introduction
2. 

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you'll learn about things to consider when developing themes or plugins for a WordPress multisite network.

You'll learn about useful multisite specific functions and hooks, and what to consider when developing themes and plugins to work on a multisite network.

## Useful functions

When you're developing a product to support a multisite network, there are a some of useful internal functions and hooks worth knowing.

### A note on naming conventions

Before we get started, it's worth noting that there are a couple of different naming conventions used in the WordPress codebase when it comes to multisite. Initially WordPress Multisite was 

The first is the is_multsite function. This function will return true if multisite is enabled, and is probably the most widely used function related to multisite. If you do a search through the WordPress codebase for uses of the is_multsite function, you'll see that it's used in a number of places, to either perform specific tasks in the context of a multisite network, or to restrict functionality only to multisite networks.

There are also some common functions that are useful when developing administration interfaces for a multisite network:

- is_super_admin can be used to check if the currently logged-in user is a Network Administrator on the network.
- is_network_admin is the multisite equivalent of the is_admin function, and determines whether the current request is for the network administrative interface.
- network_admin_url is the multisite equivalent of the admin_url function, and allows you to create URLs relative to the admin area of the network. This is useful for redirecting to different areas of the network admin dashboard.





is_main_site determines whether the current site is the main site of the current network or not.

Next there is the get_sites() function, which will return a list of sites matching requested arguments. Using this function, as well as the switch_to_blog, restore_current_blog and get_current_blog_id functions, you can perform actions across the network. 

switch_to_blog allows you to switch to a different site in the network, restore_current_blog restores the current site after you've switched to a different sitem and get_current_blog_id function, which returns the ID of the current site.

For example, let's say you wanted to create a function that updated an option on a specific site on the network.

```php
function update_site_option( $site_id ){
    switch_to_blog( $site_id );
    update_option( 'my_option', 'my_value' );
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

You could also use the update_blog_option function to update an option on a specific site, without having to switch to that site.
        
```php  
function update_option_on_all_sites( $option_name, $option_value ) {
    $sites = get_sites();
    foreach ( $sites as $site ) {
        update_blog_option( $site->blog_id, $option_name, $option_value );
    }
}
``` 

is_super_admin() checks whether the current user is a network admin of the network. This is useful if you want to allow or restrict access to certain functionality to network admins only.

Next, there are some commonly used single site functions that have multisite equivalents. is_network_admin is the multisite equivalent of is_admin, which determines whether the current request is for the network administrative interface, and network_admin_url allows you to create URLS relative to the admin area of the network. This is useful for redirecting to different areas of the network admin dashboard.

Finally, is_network_only_plugin is a plugin specific function that checks for the “Network: true” in the plugin header to see if this should be activated only as a network wide plugin. This is useful if you want to restrict a plugin to only be activated on a network, and not on individual sites.

## Useful hooks

There are also a couple of useful hooks that you can use when developing for multisite.

The first is the network_admin_menu hook, which allows you to add menu items to the network admin dashboard. This is useful if you want to add a custom menu item to the network admin dashboard.

The second is the network_admin_notices hook, which allows you to add notices to the network admin dashboard. This can be used to display a notice to network admins, in the same way that admin_notices is used for single site notices.

signup_blogform is a filter that allows you to modify the signup form for new sites. You can use this to add additional fields to the signup form.

wp_initialize_site is an action that is fired when a new site is created. This is useful if you want to perform actions when a new site is created, for example if you wanted to assign a custom top level domain to a sub site.

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

## Developing plugins

Like themes, most plugin functionality will work the same in a single site as well as a multisite. Functions like register_post_type or get_posts will function in the same way, just in the sceop of the specific site in question.

However, there are two things to consider when developing plugins for multisite.

Plugins often have a settings page, which is usually accessible from the admin dashboard. This is fine for single site plugins, but on a multisite network, you need to consider where the settings page should be located. Should it be on the network admin dashboard, or on the individual site dashboard? If you need to have a settings page on the network admin dashboard, you can use the network_admin_menu hook to add a menu item to the network admin dashboard. If you need to have a settings page on the individual site dashboard, you can use the admin_menu hook to add a menu item to the individual site dashboard.

Plugins might have to add custom tables to store custom data. If you use something like the $wpdb->prefix variable to prefix your table names, you'll end up with a table name that is prefixed with the site ID. So if you need to have a custom table for this functionality on a per-site basis, you need to plan for it.

Let's look at an example.

In the learn-form-security plugin, we have a form_submissions table being created on plugin activation, and used to store form submissions. If you look at the code, you'll see that the table name is prefixed with the $wpdb->prefix variable, which means that the table name will be prefixed with the site ID. so when form submissions are created on a per site basis, they need to be stored in the relevant form submissions table.

Let's look at what happens when we activate this plugin on our existing multisite.

As you can see, it only creates the one form submissions table, but not one for our additional site. So if we use the shortcode on the subsite, the functionality will break.

To fix this we need to update the plugin activation routine, to take this into account

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

1. Notice the $network_wide parameter. This is a boolean value that is passed to the activation hook, and indicates whether the plugin is being activated network wide or not. 
2. Then we check if the site is a multisite, and if the plugin is being activated network wide. If it is, we loop through all sites on the network, and switch to each site in turn, and create the table for each site.
3. If the plugin is not being activated network wide, we just create the table for the current site.

Let's test this out on our multisite.

And we can see all the right tables have been created.

But what happens when a new site is created?

Happy coding!