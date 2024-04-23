# Advanced Multisite Management

## Introduction

With the basics of managing a multisite network under your belt, it's time to look at some more advanced site management tasks.

Let's dive into the different possible site statuses, what happens when you delete a site, how to export a single site from a multisite network to its own install, and how to convert a multisite network install back to a single site install.

## Site Status

There are some site status options that are available to a Network Admin, that are useful to know about.

Deactivating a site will update the site status to deleted, and shows a message to anyone visiting the site. Additionally, there is a deactivate_blog action hook when a site is deactivated, and a activate_blog action hook when a site is activated, that can be used to run additional functionality when a site is activated or deactivated.

Archiving a site will update the site status to archived and show an archived message to anyone visiting the site URL.

Marking a site as Spam will update the site status to spam and show the same message as archiving, but no additional hooks are fired.

## Deleting a site from the network

Deleting a site from the network will remove all content associated with the site, including posts, pages, comments, and any other custom content types. It also removes any tables in the database that were used to house the site's content. Unlike when you trash a post or page, once you delete a site, you cannot undo this action.

## Export a site to a single site install

Under certain circumstances, you might want to extract one of the sub sites to its own single site WordPress install. This is possible, but requires some manual steps. There are a few ways to do this, this is just one possibility.

First, log into the subsite, and browse to the Tools > Export page.

You can use the WordPress export tool to export the content to the WordPress eXtended RSS or WXR format.

Then create the new single site, and the associated user.

Make sure to install any plugins and the themes used on the sub site.

Then browse to Tools -> Import to use the WordPress importer to import the data into the new site. You may need to install the importer by clicking Install Now under the WordPress option, and then clicking Run Importer once it's installed.

Don't forget to assign the data to the relevant user on the new site.

Once the data is imported, manually copy the uploads directory for the sub site over to the new single site install.

You will find the subsite uploads directory in wp-content/uploads/sites/ directory of the multisite install, in a folder with the same name as the sub site id.

Last but not the least run a search and replace tool like [Better Search Replace](https://wordpress.org/plugins/better-search-replace/) to update any urls in the database.

You will need to replace the sub site url (either the subdomain or sub directory version) with the new single site url. If you were pointing a top level domain to the subsite, this step might not be nessecary.

Lastly, test everything out to make sure it's working as expected.

An alternative to the WordPress data export option is to manually copy the database tables for the sub site to the new site. However, this might lead to further issues if the content isn't associated with the correct user.

### Considerations

If you have any plugins that create custom database tables, you might need to manually copy these tables over to the new installation.

In that case, it might be easier to rely on paid third-party backup solutions that have multisite extensions and will handle this for you.

## Convert a Multisite back to a single site install

It's also possible to convert a multisite back to a single site install. This is useful if you no longer need the multisite functionality, but want to retain the original site.

If you have other sub sites, it might be a good idea to export them to single site installs first, as this will delete the sub site content tables. Additionally, delete any users that were created for the sub sites.

To revert back to a single site, you first need to remove all multisite-related constants in wp-config.php.

Once you do this, your dashboard will revert back to a single site dashboard.

Then, if you previously updated the .htaccess file, you will need to revert that back to the original .htaccess file.

You can do this by resetting permalinks to whatever you want them to be.

Lastly, delete any tables specifically created during the multisite installation, namely:

```sql
wp_blogmeta, wp_blogs, wp_registration_log, wp_signups, wp_site, wp_site_meta
```

If everything went well, you should now have a working single site installation of your main site.

## Further reading.

For a full list of all the Multisite management functionality, check out the [Multisite](https://developer.wordpress.org/advanced-administration/multisite/) section in the WordPress developer handbook under Advanced Administration.