# Advanced Multsite Management

## Learning Objectives

Upon completion of this lesson the participant will be able to:

1. Export a subsite into a single site
2. Convert a multisite network back to a single site install

## Outline

1. Introduction
2. Exporting a subsite into a single site
3. Converting a multisite network back to a single site install

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn how to perform some more advanced site management on a multisite network.

You will learn how to export a single site from a multisite network to it's own install, as well as how to convert a multisite network install back to a single site install..

## Export a site to a single site install

Under certain circumstances, you might want to extract one of the sub sites to its own single site WordPress install. This is possible, but requires some manual steps. There are a few ways to do this, this is just one possibility.

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