# Scheduling WP-Cron events

In order to make use of the built-in WP-Cron functionality, you need to know how to schedule and unschedule WP-Cron events.

Let's take a look how this is done, by learning about the functions used to schedule and unschedule events, and how to hook events into WP-Cron.

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
add_action( 'wp_learn_trigger_event', 'wp_learn_trigger_event' );

function wp_learn_trigger_event() {
    // event logic here
}
```

This action is not an existing WordPress action, but one that you will create in the next step for the purposes of scheduling a WP-Cron event.

Inside the callback function hooked into this action, you add your event logic. For now, just add a simple log message with a date/time to the callback function:

```php
function wp_learn_trigger_event() {
    error_log( 'WP Learn Scheduled Event Triggered at ' . gmdate( 'Y-m-d H:i:s' ) );
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

To schedule the event, you use the [`wp_schedule_event`](https://developer.wordpress.org/reference/functions/wp_schedule_event/) function.

The `wp_schedule_event()` function takes three parameters:

- The Unix timestamp from when next to run the event, ie the start time. You can use the PHP `time()` [function](https://www.php.net/manual/en/function.time.php) to get the current time.
- The interval at which the event should run after the initial start time, as set by the first parameter.
- The action hook to run at the start time and subsequent intervals.

WordPress ships with a few predefined intervals you can use for the reccurance of the event:

- `hourly`: runs the event each hour.
- `twicedaily`: runs the event every 12 hours.
- `daily`:  runs the event every 24 hours.
- `weekly`: runs the event every 7 days.

It is also possible to add your own intervals, for more fine grained con

 For the purposes of this example, trigger this function during plugin activation, by using the `register_activation_hook()` function with a callback function.

```php
register_activation_hook( __FILE__, 'wp_learn_schedule_event' );

function wp_learn_schedule_event() {
	wp_schedule_event( time(), 'hourly', 'wp_learn_trigger_event' );
}
```

## Preventing an event from scheduling twice

Using the `register_activation_hook` to schedule the event is fine if you are adding a scheduled event from the first version of the plugin. 

However, if you need to add a new scheduled event to an existing plugin, you can't hook into the activation hook, because that only runs code on plugin activation, and not if the plugin is updated. 

It's therefore generally better to hook into something like the `init` action hook to add new scheduled events.

```php
add_action( 'init', 'wp_learn_schedule_event' );

function wp_learn_schedule_event() {
    wp_schedule_event( time(), 'hourly', 'wp_learn_trigger_event' );
}
```

When scheduling a new event using `wp_schedule_event()`, WordPress doesn't perform any checks to see if the event is already scheduled.

This means that every time the `init` hook is triggered, `wp_learn_trigger_event` will be scheduled as a new event.

To prevent this, you can use the [`wp_next_scheduled`](https://developer.wordpress.org/reference/functions/wp_next_scheduled/) function to check if an already existing event with the same action as been scheduled.

The [`wp_next_scheduled`](https://developer.wordpress.org/reference/functions/wp_next_scheduled/) returns the timestamp for the next occurrence of the given event or `false` if it's not scheduled.

```php
add_action( 'init', 'wp_learn_schedule_event' );

function wp_learn_schedule_event() {
	if ( wp_next_scheduled( 'wp_learn_trigger_event' ) ) {
		return;
	}
	wp_schedule_event( time(), 'hourly', 'wp_learn_trigger_event' );
}
```

By checking and returning early if the event is already scheduled, you can be sure the event will only be scheduled once.

## Testing the event

There are a number of ways to check and test your WP Cron events, which you will learn about in lesson on Testing WP-Cron events.

For now, the easiest way to test this on your local development environment is to activate the plugin

Then check the options table in the database for the 'cron' option. 

If you unserialize option_value stored in the 'cron' option, you can see the scheduled events, and your event should appear somewhere in that list. 

## Unscheduling an event

To unschedule an event, you use the [`wp_unschedule_event`](https://developer.wordpress.org/reference/functions/wp_unschedule_event/) function.

This function has two parameters:

- The Unix timestamp from when the event was last scheduled to run.
- The action hook to unschedule.

To unschedule the event, you can use the `register_deactivation_hook` function to trigger the unscheduling when the plugin is deactivated.

```php
register_deactivation_hook( __FILE__, 'wp_learn_unschedule_event' ); 

function wp_learn_unschedule_event() {
    $timestamp = wp_next_scheduled( 'wp_learn_trigger_event' );
    wp_unschedule_event( $timestamp, 'wp_learn_trigger_event' );
}
```

## Further reading

To read more about scheduling and unscheduling WP-Cron events, check out the [Scheduling WP Cron Events](https://developer.wordpress.org/plugins/cron/scheduling-wp-cron-events/) page in the Cron section of the WordPress Plugin Developer handbook.