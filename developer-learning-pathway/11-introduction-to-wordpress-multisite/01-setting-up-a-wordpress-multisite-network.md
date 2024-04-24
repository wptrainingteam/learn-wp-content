# Setting up a WordPress multisite network

## Introduction

A WordPress multisite network is a way to host multiple WordPress sites on a single WordPress installation. 

In this lesson, you will learn more about WordPress multisite, why you would consider setting up a multisite network, and the steps to follow to create one.

## What is a WordPress multisite network?

A WordPress multisite network is a collection of WordPress sites that share a single WordPress installation. With a multisite network, it's possible to allow users to create their own sites, or configure the network so that only administrators can create sites. These sites are known as "subsites" or "individual sites" on the network.

All sites on a multisite network use the same WordPress installation files and share the same plugins and themes. Plugins and themes are installed on the network, and then activated on individual sites.

Each individual site has separate directories for media uploads within the shared installation and separate tables in the database for site content.

It is important to note that while they share the same core WordPress files the sites in a multisite network are separate from each other. They are not interconnected like other kinds of networks. If you plan on creating sites that are strongly interconnected, that share data, or share users, then a multisite network might not be the best solution.

## Why use a WordPress multisite network?

A multisite network is a good solution where you have a number of sites that are similar in nature, but that need to be kept separate from each other.

Examples of this include higher education websites, non-profit organizations, and open-source projects.

One of the biggest examples of an active multisite network is [Make WordPress](https://make.wordpress.org/).

Here, each contributor team that works on the WordPress open source project has its own subsite that is part of the Make WordPress network. This means that if you are a member of, for example, the [Core Team](https://make.wordpress.org/core/), you can only log into the core subsite, and create content for that team.

## Multisite network support

The web host or local development environment you use will determine how you create a multisite network either locally, or on a live server. The two common options are either to offer a setting that you enable during the new installation process, to set up the new installation as a multisite network, or to follow the manual steps to setting up a multisite network after WordPress is installed.

Web hosts and local development environments that use the Apache web server generally allow you to convert an existing WordPress install into a multisite network using the manual method. Those who use nginx generally require you to create a multisite network during the new site creation process. This is because by default nginx doesn't support the .htaccess file that is used to enable multisite manually.

Whether you use a web host or local development environment that enables multisite automatically or manually, it's a good idea to understand the additional steps needed to create a multisite network from a WordPress install, which is what this lesson will focus on.

## How to create a multisite network

Before you create your multisite network, make sure to read the [Before You Create A Network](https://wordpress.org/documentation/article/before-you-create-a-network/) page in the developer documentation, as it covers some important considerations you need to be aware of before you create a multisite network.

Next, you should make a backup of the current site files and database. This is not strictly necessary, especially if you've created a brand-new WordPress install, but it's a good idea if you've already created some content on the site you want to turn into a multisite network.

Additionally, if you have a backup of the site files, you can quickly revert any changes you make creating the multisite network, if anything goes wrong during the process.

At the same time, you should make sure that Permalinks work on the site, and that any plugins installed are deactivated.

Finally, if you want to have your WordPress install running in its own directory, make sure to do that before you create the multisite network.

## Enable Multisite

To enable multisite, you need to edit the `wp-config.php` file in the root directory of your WordPress install and define the PHP constant `WP_ALLOW_MULTISITE` as `true`.

```php
    define( 'WP_ALLOW_MULTISITE', true );
```

Then, refresh your WordPress dashboard. By setting this constant, you'll now see a new menu item in the dashboard under the Tools menu called "Network Setup".

## Installing the Network

The Network Setup page will walk you through the steps to create your multisite network.

First, you'll need to choose whether you will use subdomains of your top-level domain as addresses for the sites on the network, or subdirectories. Subdirectories are easier, as you don't require any additional DNS configuration, but subdomains give the sites a more professional-looking URL. To use subdomains, you will need a wildcard DNS record for your top-level domain.

For the purposes of this lesson, we'll use subdirectories.

You can leave the rest of the settings as is, or change them to your liking.

Once you're ready, click the "Install" button.

WordPress will now run the network installation, creating any database tables required, and adding any specific data needed to those tables.

## Enable the Network

Once the installation is complete, you'll need to enable the network, This is done by making two changes:

1.  Edit the `wp-config.php` file again, and add the constants described at step one of the Enabling the Network page in the dashboard
2.  Edit the `.htaccess` file in the root directory of your WordPress install, and add the rules described at step two of the Enabling the Network page

Once you've made these changes, refresh your WordPress dashboard, and you will be asked to log in again.

You will be redirected back to the "Network Setup" page, but you will notice you are now logged in as the network admin, and you have a different set of menu options for your newly created Multisite network.

## Further reading

For more details on how to create a multisite network, make sure to read the [Create A Network](https://wordpress.org/documentation/article/create-a-network/) page in the official WordPress documentation.