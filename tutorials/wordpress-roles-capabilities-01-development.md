# WordPress User Roles and Capabilities

## Objectives

Upon completion of this lesson the participant will be able to:

Explain the WordPress user roles and capabilities system 
Assign capabilities to an existing role 
Create a new User Role and assign capabilities to it

## Outline

- Introduction
- WordPress User Roles and Capabilities
- Roles and Capabilities under the hood
- Assign capabilities to an existing role
- Create a new User Role and assign capabilities to it

## Introduction

Hey there, and welcome to Learn WordPress! 

In this tutorial, you'll be learning how to leverage the WordPress user roles and capabilities system. 

After a brief introduction to the default WordPress roles and capabilities, how they are created and managed, and how to assign them to users, you will learn how to add capabilities to an existing role, and how to create a new user role and add capabilities to it.

## WordPress User Roles and Capabilities

The WordPress user roles and capabilities system is a powerful tool for managing access to your site. It allows for user roles with specific capabilities, and you can then assign those roles to users. This allows you to create a hierarchy of users, with some users having more access than others.  

WordPress comes with several default user roles, namely Administrator, Editor, Author, Contributor, and Subscriber. 

There is also one additional role, but this is only available in multisite installations, and that's the Super Admin role.

Each of these roles has a set of capabilities, which are the things that the user can do in a WordPress site. For example, the Administrator role has the capability to manage users, while the Subscriber role does not.

When a new user is created in the WordPress dashboard, they are assigned the Subscriber role by default. You can change this by assigning a different role. Once assigned to a role, the user will have the capabilities that are associated with that role.

You can read up on the full list of default user roles and capabilities in the official WordPress Documentation on [Roles and Capabilities](https://wordpress.org/documentation/article/roles-and-capabilities/).

## Roles and Capabilities under the hood

The WordPress user roles and capabilities system is stored in the database during the WordPress installation process. The roles and capabilities are stored as a serialized array as site options the `options` table, in the `user_roles` option. The prefix for this option will depend on the table prefix configured in wp-config.php, or if this is a multisite installation. By default, on a single site installation, the prefix will be `wp_`, so the option name will be `wp_user_roles`.

If you extract the value of the `user_roles` option, and unserialize the array, you will see that the array keys are the role names, and the values are arrays of capabilities.

Whenever an authenticated user is attempting to do something, this action is checked against the user's role capabilities, using the [current_user_can](https://developer.wordpress.org/reference/functions/current_user_can/) function. If the user has the capability to perform the action, they will be allowed to do so. If they do not have the capability, they will be denied access.

Take a look at line 270 of the wp-admin/includes/post.php file. Here you will see that the `edit_post` capability is checked before a post is edited/update. If the user does not have the `edit_post` capability for this post, they will be informed that they do not have the required access to edit it.

## Assign capabilities to an existing role

Let's say that you want to allow your editors to be able to do something that only admins can do, maybe install and update plugins. By default, the Editor role does not have the `activate_plugins` or `update_plugins` capability. 

[Create editor user]

You can add this capability to the Editor role by using the [add_cap](https://developer.wordpress.org/reference/classes/wp_role/add_cap/) method of the WP_Role class.

However, doing so will add this capability to the user_roles stored in the database, so it's recommend to run this on something like plugin activation using the [register_activation_hook](https://developer.wordpress.org/reference/functions/register_activation_hook/) function.

```php
register_activation_hook( __FILE__, 'wp_learn_add_custom_caps' );
function wp_learn_add_custom_caps() {
    // do something
}
```

The register_activation_hook requires the path to the file that contains the hook callback, and the hook callback function name.

Then you can add the capabilities to the Editor role by using the [get_role](https://developer.wordpress.org/reference/functions/get_role/) function to get the role object, and then using the [add_cap](https://developer.wordpress.org/reference/classes/wp_role/add_cap/) method to add the capabilities.

```php
register_activation_hook( __FILE__, 'wp_learn_add_custom_caps' );
function wp_learn_add_custom_caps() {
    // gets the author role
    $role = get_role( 'editor' );
    $role->add_cap( 'activate_plugins' );
    $role->add_cap( 'update_plugins' );
}
```

1. First, you should hook into the `register_activation_hook` action, so that the code is only executed when the WP dashboard is loaded. 
2. Then, you get the role object by using the [get_role](https://developer.wordpress.org/reference/functions/get_role/) function. The `get_role` function takes the role name as a parameter, and returns the role object.
3. Finally, you use the role's [add_cap](https://developer.wordpress.org/reference/classes/wp_role/add_cap/) method to add the `activate_plugins` and `update_plugins` capabilities to the role object.

Adding this code to a plugin, and activating the plugin, triggering the activation hook, will update the Editor role with the new plugin capabilities. 

If you switch to an editor user, you will see that the editor user can now update plugins.

[Show how editor can active plugins]

Note that because adding capabilities to a role is a permanent change, you should only do this when the plugin is activated, and not on every page load. Also, if you want to remove a custom role, it's a good idea to do so on plugin deactivation, using the [register_deactivation_hook](https://developer.wordpress.org/reference/functions/register_deactivation_hook/) function.

```php
register_deactivation_hook( __FILE__, 'wp_learn_remove_custom_caps' );
function wp_learn_remove_custom_caps() {
	$role = get_role( 'editor' );
	$role->remove_cap( 'activate_plugins' );
	$role->remove_cap( 'update_plugins' );
}
```

This is useful for two reasons. Firstly, you can add and remove the capabilities when the plugin is activated and deactivated, which is useful when testing whether the capabilities you've set are what you need. Secondly, if the user deactivates your plugin, the capabilities will be removed, cleaning up the changes your plugin has made.

## Create a new User Role and assign capabilities to it

Just as it is possible to assign existing capabilities to roles, you can also create your own custom roles, and assign capabilities to them. This is useful if you want to create a role that has a specific set of capabilities, and you don't want to use an existing role.

For example, lets say you want a user role who can only activate and update plugins, say an assistant to the administrator. You can create a new role, using the [add_role](https://developer.wordpress.org/reference/functions/add_role/) function, and assign the `activate_plugins` and `update_plugins` capabilities to it.

```php
register_activation_hook( __FILE__, 'wp_learn_add_custom_role' );
function wp_learn_add_custom_role() {
	add_role(
		'assistant',
		'Assistant',
		array(
			'read'         => true,
			'activate_plugins'   => true,
			'update_plugins' => true,
		),
	);
}
```

Again, because the new role will be stored in the database, you should only run this code when the plugin is activated, and not on every page load. 

Notice how the array of capabilities includes the `read` capability. This is because the `read` capability is required for a user to be able to access the dashboard. Regardless of any other capabilities a user has, if they do not have the `read` capability, they will not be able to access the dashboard menu, in order to perform a specific task they should be able to.

You can also include a deactivation hook to remove the role when the plugin is deactivated, using the remove_role function.

```php
register_deactivation_hook( __FILE__, 'wp_learn_remove_custom_role' );
function wp_learn_remove_custom_role() {
	remove_role( 'assistant' );
}
```

Create a new user, with the Assistant capability. Then switch users, and notice how all that user can do is manage plugins

[Show how assistant can active plugins]

## Summary

For more information on developing user roles and capabilities, see the [Roles and Capabilities](https://developer.wordpress.org/plugins/users/roles-and-capabilities/) section of the Plugin developer handbook on developer.wordpress.org. 

Happy coding.
