# Managing a WordPress Multisite Network

## Learning Objectives

Upon completion of this lesson the participant will be able to:

1. Manage a WordPress multisite network
2. Create and manage sub sites
3. Allow users to register their own sub sites
4. Export a subsite into a single site
5. Convert a multisite network back to a single site install

## Outline

1. Introduction
2. The Network Admin dashboard
3. The Network Settings page
4. Creating and Managing Sub-sites
5. Exporting a subsite into a single site
6. Converting a multisite network back to a single site install

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn what tools are available to manage your WordPress multisite network.

You will learn about the options in the Network Settings page, how to create and manage sub-sites, how you might export a subsite into a single site, and what do to if you want to convert your multisite back to a single site install. 

## The Network Admin dashboard

Once you have enabled your multisite network, your Admin user will change to a Super Admin, and will have access to the Network Admin dashboard. Here, you can manage sites, users, themes, plugins and settings for the network. 

## The Network Settings page

The Network Settings page is where you manage your multisite network. Here you can set things like the Network Title, Registration Settings, and Email Settings. For example, if you want to allow users to register new sites on the network, you can enable the "Both sites and user accounts can be registered" option. 

## Creating and Managing Sub-sites

### Create a site

One of the first things that you might want to do is create a sub-site. To do this, go to the Sites page, and click the "Add New" button. You'll be asked to enter the site address (either a subdomain or a subdirectory), the site title, site language, and the site admin email address. If you use an email address of an already existing user, that user will be added as the site admin. Otherwise a new user will be created, and made admin of the new site

### Assign a TLD to a site

It is also possible to set a top-level or apex domain for a site on the network. This is useful if you or the site admin wants to use a different domain for a site on the network. 

In order for this to be possible, you need to have registered the domain with your domain registar, and have it pointed to the same location as the multsite install.  

In this case you can see the new top level domain is pointing to the same location as the multisite install, as it redirects to the multisite url. 

Then, in the Network Admin, edit to the site in question, and change the Site Address field to the new top level domain.  

Now, when you browse to that domain, you will be redirected to the associated site on the network.

### Site Users

The Users page allows you to manage users on the network. You can add exiting users on the network to the site, or add new users.

### Allow users to register their own sites

Depending on how you plan to use your multisite network, you might want users to be able to register their own subsites. To do this, go to the Network Settings page, and enable the "Both sites and user accounts can be registered" option.

Now, if a user browses to the default WordPress registration url, they will see an option to also register a site one the network.

https://multipress.test/wp-login.php?action=register

If they choose to create a site, they will need to set the site name and title. 

### Site Themes

The Themes page allows you to activate specific themes on site. This page lists any themes that are not activated for the entire network, and allows you to activate the theme for a specific site. This is useful if you want to have a parent theme for the network, and then allow individual sites to use a child theme.

## Export a site to a single site install

Under some circumstances, you might want to extract one of the sub sites to its own single site WordPress install. This is possible, but requires some manual steps. There are a few ways to do this, but this is one possiblity.

Use the WordPress export tool to export your posts, pages, comments to the WXR format. 

Create the new single site, and associated user. Make sure to install any plugins and the theme used on the sub site.

Use the WordPress importer to import the data into the new site, and assign the data to the relevant users on the new site.

Copy the uploads directory for the sub site over to the new single site. 

Run a search and replace tool like [Better Search Replace](https://wordpress.org/plugins/better-search-replace/) to update any urls in the database.

Test, test, test

An alternative to the WordPress data export option is to manually copy the database tables for the sub site to the new site. However, this might lead to further issues if the content isn't associated with the correct user.

### Caveats

If you have any plugins that create custom database tables, you might need to manually copy these tables over to the new installation. 

In that case, it might be easier to rely on paid third-party backup solutions that have multisite extensions and will handle this for you

https://servmask.com/products/multisite-extension
https://deliciousbrains.com/wp-migrate-db-pro/doc/multisite-tools-addon/

## Convert a Multisite back to a single site install

It's also possible to convert a multisite back to a single site install. This is useful if you have a multisite network, but only have one site on it.

If you have other sub sites, it might be a good idea to export them to single site installs first, as this will delete the sub site content tables. Additionally, delete any users that were created for the sub sites.

To revert back to a single site, you first need to remove all multisite-related constants in wp-config

Then, if you previously updated the .htaccess file, you will need to revert that back to the original .htaccess file. 

You can do this by resetting permalinks.

Then, delete the tables specifically created during the multisite installation

```sql
wp_blogmeta, wp_blogs, wp_registration_log, wp_signups, wp_site, wp_site_meta
```

If everything went well, you should now have a working single site installation of your main site.

## Further reading.

For more details on managing your Multisite network, check out the [Network Admin](https://wordpress.org/documentation/article/network-admin/) and [Network Admin Settings](https://wordpress.org/documentation/article/network-admin-settings-screen/) pages in the WordPress developer documenation.

Happy coding!
