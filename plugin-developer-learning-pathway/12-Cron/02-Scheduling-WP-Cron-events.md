# Scheduling WP Cron events

In order to make use of the built in WP-Cron functionality, you need to know how to schedule WP-Cron events.

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

- You need to create an action hooked into a callback function.
- Inside the callback function you define the logic to be executed for the event
- Finally, you schedule the task by passing your action and the interval at which the event should run.

## Creating the action to execute

In WordPress, any action can be scheduled as a WP-Cron event. To set up an action to be schduled you use the same `add_action` function you would use to hook into any existing action.

```php
add_action('wp_learn_trigger_event', 'wp_learn_trigger_event_callback');

function wp_learn_trigger_event_callback() {
    // event logic here
}
```

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

However, there is a big thing to not about actions being executed within a Cron job, they run as a front-end request and not an administrator one.

## Scheduling an event

Once we get your action with the logic the next step is to schedule it.

For that, we got a couple of intervals already implemented into WordPress we can use:
- `hourly`: To run the event each hour.
- `twicedaily`: To run the event each 12 hours.
- `daily`:  To run the event each 24 hours.
- `weekly`: To run the event each 7 days.

Then, to actually schedule the event, we need to use the function [`wp_schedule_event`](https://developer.wordpress.org/reference/functions/wp_schedule_event/) within at the WordPress initialization:

```php
function my_plugin_schedule_my_event() {
    wp_schedule_event(time(), 'my_plugin_my_event', 'hourly');
}

add_action('init', 'my_plugin_schedule_my_event');
```

That method takes three parameters:
- The timestamp from the next run from that event, we often set it up to the current date.
- The hook that should be executed.
- The recurrence from that event.

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
