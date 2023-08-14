# Custom Post Types and Capabilities

## Objectives

Upon completion of this lesson the participant will be able to:
Identify how capabilities are assigned to a custom post type 
Create custom post type capabilities using the capability_type argument 
Map specific primitive capabilities to a custom post type using the capabilities argument 
Map all the primitive capabilities to a custom post type using the map_meta_cap argument
Add custom post type capabilities to an existing role
Create a new role with specific custom post type capabilities

## Outline

- Introduction
- Example project
- Understanding the default capabilities for a custom post type 
- Setting the capability_type argument
- Meta capabilities vs primitive capabilities and the map_meta_cap function
- Understanding map_meta_cap
- Mapping the capabilities on the custom post type

## Introduction

Hey there, and welcome to Learn WordPress! 

In this tutorial, you'll be learning how to restrict access to custom post types via the built-in capabilities' system. You will learn how the different capabilities are assigned to a custom post type and now to manage them, as well as how to add custom post type capabilities to new or existing roles.

## Example project

Let's say you need to create a plugin that allows a site owner to add the following functionality to a WordPress site:

1. The ability to define a custom post type called story.
2. The ability to create users with a writer role, who can only create, edit, and delete unpublished stories.
3. These writers should also be able to publish their stories, but not edit or delete stories once published. 
4. Any site administrator should be able to create, edit, and delete any stories, regardless of whether they are published or not, or who they were created by.

To create the story custom post type, you might have some code that looks like this. 

```php
add_action( 'init', 'wp_learn_init' );
function wp_learn_init() {
	/**
	 * Register a story custom post type
	 */
	register_post_type(
		'story',
		array(
			'labels'          => array(
				'name'          => __( 'Stories' ),
				'singular_name' => __( 'Story' )
			),
			'public'          => true,
			'show_ui'         => true,
			'show_in_rest'    => true,
			'supports'        => array(
				'title',
				'editor',
				'custom-fields',
			),
		)
	);
}
```

When a custom post type is registered in this way, it inherits the capabilities of the post custom post type. 

To see these capabilities in your dashboard during development, you might register a submenu page that displays the capabilities of the story post type, which is only available to admins. 

```php
add_action( 'admin_menu', 'wp_learn_submenu', 11 );
function wp_learn_submenu() {
    add_submenu_page(
        'tools.php',
        esc_html__( 'WP Learn Story CPT', 'wp_learn' ),
        esc_html__( 'WP Learn Story CPT', 'wp_learn' ),
        'manage_options',
        'wp_learn_story_cpt',
        'wp_learn_render_story_cpt'
    );
}
```

Inside the callback function for this page, you could access the custom post type object from the `wp_post_types` global array variable. You could then print out the `capability_type`, `map_meta_cap` and `cap` properties of the `story` post type.

```php
function wp_learn_render_story_cpt() {
	$story = $GLOBALS['wp_post_types']['story']
	?>
    <div class="wrap" id="wp_learn_admin">
        <h1>Book</h1>
        <pre style="font-size: 22px; line-height: 1.2;"><?php print_r( array( 'capability_type' => $story->capability_type ) ) ?></pre>
        <pre style="font-size: 22px; line-height: 1.2;"><?php print_r( array( 'map_meta_cap' => $story->map_meta_cap ) ) ?></pre>
        <pre style="font-size: 22px; line-height: 1.2;"><?php print_r( array( 'cap' => $story->cap ) ) ?></pre>
        <pre style="font-size: 22px; line-height: 1.2;"><?php print_r( $story ) ?></pre>
    </div>
	<?php
}
```

With the plugin active, when logged in as an admin user, this is what you might see when you visit the page in your dashboard.

```php
array(
    'capability_type' => 'post',
    'map_meta_cap' => true,
    'cap' => array(
        'edit_post' => 'edit_post',
        'read_post' => 'read_post',
        'delete_post' => 'delete_post',
        'edit_posts' => 'edit_posts',
        'edit_others_posts' => 'edit_others_posts',
        'delete_posts' => 'delete_posts',
        'publish_posts' => 'publish_posts',
        'read_private_posts' => 'read_private_posts',
        'read' => 'read',
        'delete_private_posts' => 'delete_private_posts',
        'delete_published_posts' => 'delete_published_posts',
        'delete_others_posts' => 'delete_others_posts',
        'edit_private_posts' => 'edit_private_posts',
        'edit_published_posts' => 'edit_published_posts',
        'create_posts' => 'edit_posts',
    ),
)
```

As you can see, the `capability_type` inherits from post, `map_meta_cap` is set to true, and the `cap` property is an array of capabilities that are inherited from the default WordPress capabilities for a post. 

To understand how these capabilities are mapped to the custom post type, you need to understand how these three properties are set up.

## Understanding the default capabilities for a custom post type

Take a look at the `register_post_type` function, in line 1679 of the wp-includes/post.php file.

```php
$post_type_object = new WP_Post_Type( $post_type, $args );
```

Here a new `WP_Post_Type` object is created, and the `$args` array is passed to the constructor. 

If you then open the file for the `WP_Post_Type` class, at wp-includes/class-wp-post-type.php, you can see the following code at around line 541:

```php
    if ( empty( $args['capabilities'] )
        && null === $args['map_meta_cap'] && in_array( $args['capability_type'], array( 'post', 'page' ), true )
    ) {
        $args['map_meta_cap'] = true;
    }

    // If not set, default to false.
    if ( null === $args['map_meta_cap'] ) {
        $args['map_meta_cap'] = false;
    }

    $this->cap = get_post_type_capabilities( (object) $args );
    unset( $args['capabilities'] );

    if ( is_array( $args['capability_type'] ) ) {
        $args['capability_type'] = $args['capability_type'][0];
    }
```

1. If the `capabilities` argument is empty, and the `map_meta_cap` argument is `null`, and the `capability_type` argument is set to either `post` or `page`, then set the `map_meta_cap` argument to `true`. If you scroll back up to line 480, you can see that the default value for the `capability_type` argument is `post`. So because your custom post type doesn't pass any of these arguments, then the `map_meta_cap` argument will be set to `true`.
2. Later on, the `cap` property is set to the result of the `get_post_type_capabilities` function. This function accepts the `$args` array as an argument, and returns an array of capabilities. So here, because the `capability_type` argument is set to `post`, and the `map_meta_cap` argument is set to true, then the `cap` property on the `story` custom post type will be set to an array of capabilities that are mapped to the default WordPress capabilities for a post.

You can control these capabilities by passing relevant values to the three arguments that are used above, and described in the [developer handbook entry](https://developer.wordpress.org/reference/functions/register_post_type/) for `register_post_type`. The possible arguments are:
1. `capability_type`
2. `capabilities`
3. `map_meta_cap`

## Setting the capability_type argument

The first step in setting custom capabilities for your custom post type is to set the `capability_type` argument to something other than `post`. In this case, you can set it to `story`.

```php
'capability_type' => 'story',
```

If you take a look at the `cap` property on your custom post type, you will see that the capabilities have changed. 

However, it's not using the correct pluralisation of story, so you need to explicitly set the plural version of the `capability_type` argument, by passing in an array of values, the first being the singular name, the second being the pluralisation.

```php
'capability_type' => array( 'story', 'stories' ),
```

Now if you take a look at the cap property on your custom post type, you will see that the capabilities have changed again.

```php
array(
    'capability_type' => 'story',
    'map_meta_cap' => false,
    'cap' => array(
        'edit_post' => 'edit_story',
        'read_post' => 'read_story',
        'delete_post' => 'delete_story',
        'edit_posts' => 'edit_stories',
        'edit_others_posts' => 'edit_others_stories',
        'delete_posts' => 'delete_stories',
        'publish_posts' => 'publish_stories',
        'read_private_posts' => 'read_private_stories',
        'create_posts' => 'edit_stories',
    ),
)
```

Notice that this list does not include all the same capabilities when they were inherited from post.

```php
    'read' => 'read',
    'delete_private_posts' => 'delete_private_stories',
    'delete_published_posts' => 'delete_published_stories',
    'delete_others_posts' => 'delete_others_stories',
    'edit_private_posts' => 'edit_private_stories',
    'edit_published_posts' => 'edit_published_stories',
```

This is because by setting a `capability_type`, the first conditional in the WP_Post_Type class you looked at earlier returns `false`, and so the `map_meta_cap` argument is now set to `false`, which means the additional capabilities are not mapped for the custom post type.

## Meta capabilities vs primitive capabilities and the map_meta_cap function

Now would be a good time to talk about the different types of capabilities. There are three types of capabilities available to custom post types.

Meta capabilities are capabilities that are mapped to primitive capabilities. The 3 meta capabilities are `edit_post`, `read_post`, and `delete_post`.

As an example, the `edit_post` meta capability is mapped to primitive capabilities like `edit_posts` and `edit_others_posts`. 

Because the meta capabilities are automatically mapped to certain primitive capabilities, it's generally recommended not to grant the meta capabilities directly to users or roles, and rather to add any of the primitive capabilities.

There are two different types of primitive capabilities, those that are automatically mapped to a meta capability when you register a custom post type with a specific capability type, and those that are not. 

To understand the differences, take a look at the `get_post_type_capabilities` function, in the wp-includes/post.php file. 

This is the same function you saw earlier in the `WP_Post_Type` class that builds the capabilities object for the `cap` property on the custom post type object.

Here you can see that the default capabilities are always set, which include the 3 meta capabilities, and 5 primitive capabilities. Then, an additional 6 primitive capabilities are added. These are known as the **Primitive capabilities used within the map_meta_cap function**. Below that, the `create_posts` capability is automatically mapped to `edit_posts`, for a total of 15 possible capabilities.

You might be wondering what the `map_meta_cap` function is, and what it does.

## Understanding map_meta_cap

The `map_meta_cap` function is a function that is used when the `current_user_can` function is called to check if a user role has a specific capability. 

If you dive into the code underneath the `current_user_can` function, you'll see it eventually calls the `has_cap` function of the WP_User class, which in turn calls the `map_meta_cap` function.

`map_meta_cap` maps a capability to the primitive capabilities required of the given user to satisfy the capability being checked, based on the context of the check. The map_meta_cap function does not check whether the user has the required capabilities, it just returns what the required capabilities are.

To help explain this, let's look at an example. Let's say you want to use `current_user_can` to check if the current user can edit a specific post, by checking against the `edit_post` meta capability. 

```php
function check_permissions_callback( $post ) {
	current_user_can( 'edit_post', $post->ID );
}
```

Based on the context of the post, the result for this check can depend on a few factors: 
1. Is the user the author of the post? 
2. Is the post already published?

When checking against the `edit_post` capability, the `map_meta_cap` function will check all these factors and return the correct set of primitive capabilities that the user must have to allow editing of the post. 

So, if the post is written by someone else and published, it would return an array containing the 'edit_others_posts' and 'edit_published_posts' capabilities:

```php
array('edit_others_posts', 'edit_published_posts')
```

In this case, the user would not only need the `edit_post` capability, but also the `edit_others_posts` and `edit_published_posts` capabilities in order to edit this post.

## Mapping the capabilities on the custom post type

If you look at the different lists of capabilities that you might need for your writer, you'll notice some exist as the meta capabilities, some as the automatically mapped primitive capabilities, and some exist as the primitive capabilities mapped inside `map_meta_cap`.

As discussed earlier, you should not grant the meta capabilities directly to users or roles, and rather to add any of the primitive capabilities.

Therefore, you are going to want to add the following automatically mapped primitive capabilities:

```php
    'edit_stories',
    'delete_stories',
    'publish_stories',
    'read_private_stories',
```

But you're also going to want to add the following primitive capabilities mapped inside map_meta_cap:

```php
    'read'
    'delete_private_stories'
    'edit_private_stories'
```

There are two ways you can do this.

## Using the capabilities argument

You can add the additional primitive capabilities you need by using the capabilities argument of the register_post_type function

```php
    'capabilities'    => array(
        'read' => 'read',
        'delete_private_posts' => 'delete_private_stories',
        'edit_private_posts' => 'edit_private_stories',
    ),
```

If you take a look at the cap property of the custom post type object, you'll see that the capabilities object now includes all the capabilities you need. You can now add these capabilities to your `writer` role

## Using the map_meta_cap argument

The other option is to set the map_meta_cap argument to true. This will automatically also map the meta capabilities to the primitive capabilities used inside map_meta_cap.

```php
    'map_meta_cap'    => true,
```

With this option, all the possible capabilities will be mapped, and you can then add the specific ones you need to your user roles.

Ultimately, it's up to you which option you choose. The `capabilities` argument is a bit more flexible and provides more fine-grained control, but using the `map_meta_cap` argument requires less work on your part. It's also worth noting that either option will not automatically add the capabilities to any role, you still need to define that yourself.

## Adding and updating roles

One other thing changed when you set the custom `capability_type` argument. Suddenly your admin user can no longer access stories! This is because the administrator user role has not been given the capabilities to access stories.

Because your admin user roles should be able to access everything, now would be a good time to add all the capabilities to the admin role. This is another reason to use the map_meta_cap argument, as it will automatically map all the capabilities, which you can assign to the administrator role. As you learned in the Developing WordPress User Roles and Capabilities tutorial, the right place to do this is inside a plugin activation hook callback. 

First, deactivate the plugin, so that you can activate it to trigger the activation hook. 

Then, create the register the activation hook in your plugin, and use it to assign the required capabilities to the administrator user:

```php
register_activation_hook( __FILE__, 'wp_learn_activate' );
function wp_learn_activate() {
    $role = get_role( 'administrator' );
    $capabilities = array(
        'edit_stories',
        'edit_others_stories',
        'delete_stories',
        'publish_stories',
        'read_private_stories',
        'delete_private_stories',
        'delete_published_stories',
        'delete_others_stories',
        'edit_private_stories',
        'edit_published_stories',
    );
    foreach ( $capabilities as $cap ) {
        $role->add_cap( $cap );
    }
}
```

Because there are so many capabilities, it's a good idea to store them in an array, and then loop through them and add them to the role. If you ever need to add or remove capabilities, you can just update the array.

You do not need to add the `read` capability, as this is already added to the administrator role.

At the same time, you can add the deactivation hook to remove the capabilities from the administrator role.

```php
register_deactivation_hook( __FILE__, 'wp_learn_deactivate' );
function wp_learn_deactivation() {
    $role = get_role( 'administrator' );
    $capabilities = array(
        'edit_stories',
        'edit_others_stories',
        'delete_stories',
        'publish_stories',
        'read_private_stories',
        'delete_private_stories',
        'delete_published_stories',
        'delete_others_stories',
        'edit_private_stories',
        'edit_published_stories',
    );
    foreach ( $capabilities as $cap ) {
        $role->remove_cap( $cap );
    }
}
```

Now, you can create the writer role, and only apply the capabilities that the writer needs.

Inside the activation hook callback function you can create the writer role using the `add_role` function, and add the relevant capabilities. As a reminder:

1. The writer role can only create, edit, and delete unpublished stories.
2. These writers should also be able to publish their stories, but not edit or delete stories once published.

```php
	add_role(
		'writer',
		'Writer',
		array(
			'edit_stories' => true,
			'delete_stories' => true,
			'publish_stories' => true,
			'read_private_stories' => true,
			'read' => true,
            'delete_private_stories' => true,
			'edit_private_stories' => true,
		)
	);
}
```

Note that you can do this inside an existing activation hook callback function, or you can create a new one.

At the same time, update the deactivation hook callback function (or create a new one) to remove the role when the plugin is deactivated.

```php
    remove_role('writer' );
```

Now activate the plugin, and check that the admin can create, edit, publish and edit a published story. 

Then, create a new user, and assign the writer role to the user. Log in as the writer, and check that the writer can create, edit and publish a story, but cannot delete a published story. They can also not edit or delete anyone else's stories.

## Summary

Your specific requirements will determine how you set your capabilities for your custom post types. Perhaps you don't need the administrator role to access stories? Then you could have just manually set the additional capabilities you need, and not used the `map_meta_cap` argument. Either way, whatever capabilities you configure will only be applied once you add them to a role. 

Generally, your process for setting up custom post type capabilities will be:

1. Set the correct capability_type(s)
2. Use either the capabilities argument or the map_meta_cap argument to set up any additional capabilities you require
3. Either update an existing role, or create a new custom role, and assign the relevant capabilities needed
4. Always remember to set up roles and or capabilities inside an activation hook callback function, and remove them inside a deactivation hook callback function

## Where to learn more

You can read up on how capabilities can be mapped on custom post types in the [Parameter Detail Information section](https://developer.wordpress.org/reference/functions/register_post_type/#parameter-detail-information) of the register_post_type documentation in the WordPress developer handbook, as well as the function reference entry on the get_post_type_capabilities [function](https://developer.wordpress.org/reference/functions/get_post_type_capabilities/).

Happy coding

