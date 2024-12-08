# Introduction to WP Cron

In some instances, you might need a way to trigger an action that needs to be executed at a specific time interval instead of being based on a user interaction.

More commonly known as scheduled tasks, these actions are often used to automate repetitive tasks that need to be executed.

Let's look at some examples of scheduled tasks that you might need to automate, and what this looks like in the context of a WordPress site.

## Common examples of scheduled tasks

Some common examples of scheduled tasks your plugin might need to offer include sending subscription reminder emails to users, automatically publishing scheduled content, or regularly checking an external data API for updates.

The subscription reminder email might need to be sent one week before the end of the month, the scheduled content might need to be published at 8:00 am every day, and the data API updates might need to be checked and imported every 12 hours.

A web server also has its own scheduled tasks, such as checking for updates, backing up the database, and clearing the cache.

These tasks are automated using a command line utility known as cron, which gets its name from the word Chronos, the Greek word for time.

## Understanding how Cron works

On a web server, a scheduled task is known as a cron job, and it's always made up of two parts:

- A recurring time when the task will be executed.
- A task that will be executed when the cron job is run.

Cron jobs are defined in a file called a crontab, which is a configuration file that contains a list of cron jobs.

But being able to create a cron job requires access to the server's crontab, as well as understanding the crontab syntax.

This is not something that all WordPress site owners have, and even if they did, it's not a straightforward or easy process.

## Introducing WP-Cron

That is why WordPress introduced WP-Cron. 

WP-Cron emulates server crons and allows WordPress developers to create scheduled tasks without needing to access the server's crontab.

WP-Cron is implemented by making an internal HTTP request to the wp-cron.php file in the root of the WordPress install whenever the site receives a visit.

This will then will trigger any events that have been scheduled and stored in the WordPress database, by using the relevant WordPress scheduling functions.

While this makes it easier for plugin developers to implement scheduled tasks, it does have some limitations.

The primary limitation is that it relies on a user to load the page to run. This means it can be unreliable if the site has a drop in traffic, as the scheduled tasks may not run when expected.

Fortunately, WordPress does provide a mechanism to overcome this limitation, which you can learn about in lesson on Hooking WP-Cron Into the System Task Scheduler 

## Switching from WP Cron to Cron

As the developer of a plugin, it is not possible to know of a site that has your plugin installed is using the default WP-Cron implementation or server crons, as this will be different on every WordPress configuration.

While this might seem like a problem, is in fact good news.

This is because the mechanism for hooking WP-Cron into the system cron makes use of the same core functionality. 

This means as long as your plugin uses the WordPress scheduling functions, it will work regardless of whether the site is using WP-Cron or server crons.