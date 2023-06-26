So here's a little tip for registering callback functions for actions and filter hooks

Often in the WordPress documentation you'll see examples of assigning callback functions to hooks like this:

```php
function wporg_callback() {
    // do something
}
add_action( 'init', 'wporg_callback' );
```

But a better way to write that is to move the hook callback assignment above the callback function.

```php
add_action( 'init', 'wporg_callback' );
function wporg_callback() {
    // do something
}
```

This makes your code more readable, and therefore  easier to understand what's going on. 
