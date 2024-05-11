# Building plugins and themes that support multisite

## Introduction (0:00)

When developing themes or plugins for a WordPress multisite network, there are a few things to consider that are slightly different from developing for a single site WordPress install.

In this lesson, you'll discover some differences to consider, and how to ensure your plugins and themes are supported for multisite.

## Developing themes and child themes (0:18)

Generally themes and child themes work exactly the same on a multisite network as they do on a single site. Once a theme or child theme is network activated, it can be activated on any single site on the network.

All specific functionality that you may want to code into a `functions.php` file will work in the scope of the current theme. For example, if you wanted to display the sitename in the footer of the theme, you could use the standard `get_bloginfo` function to retrieve the site name.

```php
if ( ! function_exists( 'tt3c_get_site_name' ) ) {
	function tt3c_get_site_name() {
        return get_bloginfo( 'name' );
    }
}
```

However, let's say you wanted to include the main site name in the footer of the theme, regardless of which site was currently being viewed. You could use the `switch_to_blog` function to switch to the main site, retrieve the site name, and then restore the current site.

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

Taking this one step further, perhaps you want to exclude the main site only from this custom functionality. You could use the `is_main_site` function to check whether the current site is the main site, and if so, just return the site name.

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

All of this is possible from just one `functions.php` file inside of a single child theme.

## Developing plugins (5:04)

As discussed, most plugin functionality will work the same in a single site as well as a multisite. Functions like `register_post_type` or `get_posts` will function in the same way, just in the scope of the specific site in question.

However, there are two things to consider when developing plugins for multisite.

Plugins often have a settings page, which is usually accessible from the admin dashboard. This is fine for single site plugins, but on a multisite network, you need to consider where the settings page should be located. Should it be on the network admin dashboard, or on the individual site dashboard? 

If you need to have a settings page on the network admin dashboard, you can use the `network_admin_menu` hook to add a menu item to the network admin dashboard. If you need to have a settings page on the individual site dashboard, you can use the `admin_menu` hook to add a menu item to the individual site dashboard.

Plugins might have to add custom tables to store custom data. If you use something like the `$wpdb->prefix` variable to prefix your table names, you'll end up with a table name that is prefixed with the site ID. So if you need to have a custom table for this functionality on a per-site basis, you need to plan for it.

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

### Where to find more information (13:49)

Besides the documentation on [Creating a network ](https://wordpress.org/documentation/article/create-a-network/) and things to [consider before creating a network](https://wordpress.org/documentation/article/before-you-create-a-network/), there's not a lot of developer focused documentation specific to developing for multisite.

However, it is possible to view a list of all multisite related functionality by browsing to the [multisite package](https://developer.wordpress.org/reference/package/multisite/) section of the WordPress code reference.

## YouTube chapters

0:00 Introduction
0:18 Developing themes and child themes
5:04 Developing plugins