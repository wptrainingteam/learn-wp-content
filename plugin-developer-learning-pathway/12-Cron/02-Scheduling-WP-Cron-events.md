# Scheduling WP Cron events

To schedule a Cron event we will need two things:

- An job to execute.
- An periodicity for our job.

## Creating a job to execute

In WordPress, any action can be a Cron event to schedule that makes it quite easy to write the logic to be run into a WP Cron as it will be a familiar syntax:

```php
function my_plugin_my_action() {
    // my logic
}

add_action('my_plugin_my_event', 'my_plugin_my_action');
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
