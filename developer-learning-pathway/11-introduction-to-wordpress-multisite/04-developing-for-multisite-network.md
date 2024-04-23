# Developing for a Multisite Network

## Introduction

WordPress core contains some multisite specific functionality that controls certain aspects of the network, or subsites on the network.

In this lesson, you'll learn about some multisite specific naming conventions to be aware of, and then dive into some useful multisite specific functions and hooks to be aware of.

### Note on naming conventions

There are a couple of different naming conventions used in the WordPress codebase when it comes to multisite.

WordPress multisite was originally known as [WordPress MU](https://codex.wordpress.org/WordPress_MU) (or WordPress multi-user), and many multisite related functions and hooks use the `wpmu_` prefix.

Additionally, some functions are named based on the old terminology which described multiple “blogs” on a “site”. This has since been updated to describe multiple “sites” on a “network” instead, but some old terminology still lives on in some function names.

## Useful functions

When you're developing a product to support a multisite network, there are a some of useful internal functions worth knowing.

The first is the [is_multsite](https://developer.wordpress.org/reference/functions/is_multisite/) function. This function will return true if multisite is enabled, and is probably the most widely used function related to multisite. If you do a search through the WordPress codebase for uses of the `is_multsite` function, you'll see that it's used in a number of places, to either perform specific tasks in the context of a multisite network, or to restrict functionality only to multisite networks.

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

When you are rendering any content in the scope of a single site on the network, WordPress core is clever enough to know that you are working inside the scope of that site.

This means that any functions that you use to retrieve information, such as get_bloginfo, get_option, get_posts, or get_post_meta, and any functions you might use to add or update information, like update_option, wp_insert_post or update_post_meta, will get, add or update the correct tables for the site that you are currently working with.

Additionally, if you use functions like register_post_type or register_taxonomy, these will be registered for the current site only.

## Further reading

For a full list of all WordPress multisite related functions and hooks, check out the [Multisite Package Category](https://developer.wordpress.org/reference/package/multisite/) in the WordPress developer reference