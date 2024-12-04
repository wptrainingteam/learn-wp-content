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

In WordPress, any action hook can be scheduled as a WP-Cron event. 

To set up an action hook to be scheduled you use the same `add_action` function you would use to hook into any existing action.

```php
add_action('wp_learn_trigger_event', 'wp_learn_trigger_event_callback');

function wp_learn_trigger_event_callback() {
    // event logic here
}
```

This action is not an existing WordPress action, but one that you will create for the purposes of scheduling a WP-Cron event.

For the purposes of this event, just add a simple log message to the callback function:

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

Once you have your action and callback function with the logic to be executed for the event, the next step is to schedule it at a specific interval.

WordPress ships with a few predefined intervals you can use:
- `hourly`: runs the event each hour.
- `twicedaily`: runs the event every 12 hours.
- `daily`:  runs the event every 24 hours.
- `weekly`: runs the event every 7 days.

To schedule the event, you use the [`wp_schedule_event`](https://developer.wordpress.org/reference/functions/wp_schedule_event/) function. It's recommended to use this function during WordPress initialization, by hooking into the `init` action.

```php
add_action('init', 'my_plugin_schedule_my_event');

function my_plugin_schedule_my_event() {
    wp_schedule_event( time(), 'wp_learn_trigger_event', 'hourly' );
}
```

That function takes three parameters:
- The Unix timestamp from when next to run the event, ie the start time. You can use the PHP `time()` [function](https://www.php.net/manual/en/function.time.php) to get the current time.
- The action hook you created that should be executed.
- The internal at which the event should run after the initial start time, as set by the first parameter.

## Testing the event

There are a number of ways to check and test your WP Cron events, which you will learn about in a different lesson. However, because you've created this event to run hourly, you can check that it's working by simply loading the front end of your site.

However, there is a big thing to not about actions being executed within a Cron job, they run as a front-end request and not an administrator one.

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
