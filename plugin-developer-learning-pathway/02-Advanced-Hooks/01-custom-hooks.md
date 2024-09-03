# Introduction

As a plugin developer, you’ll probably try to cover all possible use cases when building your plugin’s functionality.

However, all websites have unique requirements, and it’s impossible to satisfy all of them.

This sometimes means your plugin will end up being really close to what a potential user needs but might need one or two minor additions that would make it a perfect solution.

Fortunately, there was a way to allow your plugin’s users to extend its functionality without editing the plugin code.

In that lesson, you will learn about custom hooks, the perfect way to enable your users to customize your plugin to their needs.

## Custom hooks

Custom hooks are just like regular hooks you’ve already learned about, which are defined by WordPress Core.

However, this time, you are behind the wheel. You can create either custom action hooks or custom filter hooks through your plugin code, to enable other users or developers the ability to extend the functionality of your plugin.

## Why use custom hooks?

Custom hooks are a great way to keep focus on your plugin’s main functionality.

They make it possible for your plugin user to customize your plugin behavior to meet their needs and implement their own features.

They also allow you to avoid having to worry about making your plugin compatible every single possible external integration.

As custom hooks give users a way to implement their own custom scenarios themselves they will often report their solution back to you when they find one, which you can share with the rest of your users.

## Creating a custom action

You would often create custom actions to trigger before something specific is going to happen or after something specific has happened.

To do this, you call the `do_action` [function](https://developer.wordpress.org/reference/functions/do_action/), passing it the name of the action as a parameter.

```php
do_action('my_action');
```

It is also possible to pass a context to the action.

```php
do_action('my_action', ‘context’);
```

The context parameters will then be available to be used by any callback functions hooked on the action:

```php
$count = 10;

$is_admin = true;

do_action( 'my_action', $count, $is_admin );
```

```php
add_action( 'my_action', 'my_callback', 10 , 2 );
function my_callback( $count, $is_admin ) {
    // custom logic
}
```

## Creating a custom filter

Custom filters are a way to allow someone to change the value of something you define in your code.

It allows you to make a specific decision for how your code functions for plugin users, but also allow more experienced users the ability to extend that decision to suit their requirements.

To create a custom filter, you call the `apply_filters` [function](https://developer.wordpress.org/reference/functions/apply_filters/) with the name of the filter as the first parameter and the default value the filter is applied to as the second parameter:

```php
$enabled = false
apply_filters('my_filter', $enabled);
```

With this in place, someone could hook into the filter, and set the value to true, if this was their requirement.

Like custom actions, it is also possible to pass a context parameter:

```php
$count = 10;

$is_admin = true;

apply_filters('my_filter', false, $count, $is_admin);
```

Once this is done, then context parameters are available on callback functions hooked into the filter:

```php
add_filter( 'my_filter', 'my_callback', 10 , 3 );
function my_callback( $value, $count, $is_admin ) {
    // custom logic
}
```

## Naming conflicts

In the lesson on Naming Collisions, you learned how to avoid naming conflicts in the global namespace. This is also true when creating custom hooks.

## Conflict between plugins

When creating custom hooks, you should always prefix your hook names with a unique identifier, ideally the same one used elsewhere in your plugin:

```php
do_action('my_plugin_my_action');
```

That way, if someone else creates another hook with a similar name, it won't conflict with yours, as each one will be prefixed with its own unique identifier.

### Conflicts between filters and actions

Under the hood, actions and filters are functionally the same, the main difference being that actions don’t return a value and filters do.

For that reason, you should always use unique names for each hook. Don’t create an action and a filter with the same name.

```php
// This is wrong
do_action('my_hook');
apply_filters('my_hook', $some_variable)
```

Doing this will result in conflicts with callback functions registered to the action or filter, as it means the callback will run both when the action and filter are triggered. 

Depending on whether you hook a callback into the action or filter, it may also cause errors in the code execution.