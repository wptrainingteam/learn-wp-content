# Scheduling WP Cron events

In order to make use of the built-in WP-Cron functionality, you need to know how to schedule and unschedule WP-Cron events.

Let's take a look how this is done.

## Example plugin

To start, create a new plugin in your local WordPress install, with the following plugin header:

```php
<?php
/**
 * Plugin Name: WP Learn Scheduled Event
 * Description: A plugin to schedule a WP Cron event.
 * Version: 1.0
 * 
 */
```

## Scheduling a WP Cron event

To schedule a WP Cron event you will need a few things:

- You need to create a function that defines the logic for your event to be executed
- The function should be hooked into an action hook that will be triggered by the WP-Cron event
- Finally, you schedule the task by passing your action and the interval at which the event should run.

## Creating the action and callback to execute

In order to trigger a scheduled event, WP-Cron performs a similar function to the WordPress `do_action` function, but it's specifically for WP-Cron events. 

So to start, you set up an action hook callback to be scheduled by using the same `add_action` function you would use to hook into any existing action.

```php
add_action('wp_learn_trigger_event', 'wp_learn_trigger_event_callback');

function wp_learn_trigger_event_callback() {
    // event logic here
}
```

This action is not an existing WordPress action, but one that you will create in the next step for the purposes of scheduling a WP-Cron event.

Inside the callback function hooked into this action, you add your event logic. For now, just add a simple log message to the callback function:

```php
function wp_learn_trigger_event_callback() {
    error_log('WP Learn Scheduled Event Triggered');
}
```

Remember to make sure that you've enabled your error logs in your `wp-config.php` file, so that the error log is written to the WordPress `debug.log`.

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );
```

## Scheduling an event

Once you have hooked the callback function with the logic to be executed for the event into the action hook, the next step is to schedule it at a specific interval.

WordPress ships with a few predefined intervals you can use:
- `hourly`: runs the event each hour.
- `twicedaily`: runs the event every 12 hours.
- `daily`:  runs the event every 24 hours.
- `weekly`: runs the event every 7 days.

To schedule the event, you use the [`wp_schedule_event`](https://developer.wordpress.org/reference/functions/wp_schedule_event/) function. 

It's recommended to use this function during plugin activation, by using the register_activation_hook() function.

```php
register_activation_hook( __FILE__, 'wp_learn_schedule_event' );

function wp_learn_schedule_event() {
    wp_schedule_event( time(), 'hourly', 'wp_learn_trigger_event' );
}
```

That function takes three parameters:
- The Unix timestamp from when next to run the event, ie the start time. You can use the PHP `time()` [function](https://www.php.net/manual/en/function.time.php) to get the current time.
- The internal at which the event should run after the initial start time, as set by the first parameter.
- The action hook you created that should be executed.

## Testing the event

There are a number of ways to check and test your WP Cron events, which you will learn about in a different lesson. 

However, because you've created this event to run hourly, you can check that it's working by activating the plugin, and then loading the front end of your site after about an hour.

This is an important thing to note about WP-Cron events, they are only triggered by requests to the front-end of your site, and not the administration dashboard.

## Preventing an event from scheduling twice

Even if the action we scheduled previously is executed, there will be an issue with it, WordPress doesn't check if an event is already scheduled before scheduling it.

Due to that, the event will be scheduled again, and again each time WordPress is loading and that is why we need to use the function [`wp_next_scheduled`](https://developer.wordpress.org/reference/functions/wp_next_scheduled/) to prevent this from happening.

The [`wp_next_scheduled`](https://developer.wordpress.org/reference/functions/wp_next_scheduled/) provides the timestamp from when is the next occurrence from an event or `false` if it does not have.

We will use that function to bail out when we already have an event scheduled the following way:

```php
function my_plugin_schedule_my_event() {

    if( wp_next_scheduled('my_plugin_my_event') ) {
        return;
    }

    wp_schedule_event(time(), 'my_plugin_my_event', 'hourly');
}

add_action('init', 'my_plugin_schedule_my_event');
```

Now we are sure that the event will be scheduled only once.
