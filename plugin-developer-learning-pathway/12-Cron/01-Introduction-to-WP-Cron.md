# Introduction to WP Cron

On some occasions, we need to have some actions that need to be executed periodically instead of executing when a user has an interaction.

A good example of this would be a subscription.

First, we would need some logic to proceed with the registration from the customer to the subscription with some usual logic.

Then we would have to bill the user monthly, and for this part of the logic we would need a Cron. This is due to the fact we don't want the customer to pay manually each month, but instead we want the payment to proceed automatically.

## Understanding how Cron works

A Cron is always based on two parts:
- A recurrence that will define the time when the Cron are executed.
- A task that will be executed when the Cron is fired.

## WP Cron 

Real crons needs to be settled up by the WordPress user to be able to run, which is not something straightforward nor an easy process.

That is why WordPress introduced WP Crons, they are emulated crons that run by default on a WordPress website when real crons are not configured.

That way, users are not left with a broken website if they forgot to set up the crons.

However, WP Cron also have their own drawbacks compared to crons.

They rely on a user to load the page to run, and so they can be unreliable if you have little traffic on your website.

## Switching from WP Cron to Cron

At the level of the plugin, it is not possible to know of we are using WP Crons or Crons as it will be handled inside WordPress configuration.

This, even if it can seem like a problem, is in fact good news for us plugin developers.
This due to the fact there will be one syntax to register Crons and WordPress will handle us which one from WP Crons or Crons will be used.