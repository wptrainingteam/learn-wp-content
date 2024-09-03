# Removing Hooks

## Introduction

While the WordPress hooks system makes WordPress very extendable, it can sometimes create incompatibilities with your plugin’s execution.

In this lesson you will learn about removing hook callback functions.

Example

Let’s say you are developing a plugin that will add some a copyright text string to the end of every post on a WordPress site. Your plugin code might look something like this:

```php
<?php
/**
 * Plugin Name: Add Copyright
 * Description: Add Copyright with current year to all Post content
 * Version: 1.0
 * Author: Jon Doe
 */

namespace JonDoe\AddCopyright;

add_filter( 'the_content', __NAMESPACE__ . '\add_copyright' );

function add_copyright( $content ) {
    $year = date( 'Y' );
    return $content . "<p>&copy; $year</p>";
}
```

In that situation there is little we can do except to remove the incompatible callback and add a new one with some compatible logic.

Removing a callback from a hook

To remove a callback, we have two functions based on the hook type.

Filter

In the case of a filter, we can use the function remove_filter.

To understand further how this works, let's take an example.

Imagine I got the function my_callback as a callback from the filter my_filter with priority 12.

function my_callback() {

}

add_filter('my_filter', 'my_callback', 12);
To remove that callback, I will have to call the function remove_filter with the following arguments:
remove_filter('my_filter', 'my_callback', 12);
Action
In the case of an action, we can use the function remove_action.
To understand further how this works, let's take an example.
Imagine I got the function my_callback as a callback from the action my_action with priority 12.
function my_callback() {

}

add_filter('my_action', 'my_callback', 12);
To remove that callback, I will have to call the function remove_action with the following arguments:
remove_action('my_action', 'my_callback', 12);
Removing all callbacks for a hook
First, I need to warn you doing this is not recommended because it can remove important hooks for certain plugins or themes, and it is more recommendable to remove only the callback which creates an incompatibility.
If you want to remove all callbacks from an action, you can use the method remove_all_actions:
remove_all_actions('my_action');
For a filter you can use remove_all_filters:
remove_all_filters('my_filter');
It is also important to note that these methods should not be called from inside the hook you want to remove otherwise this would result in an infinite loop.

