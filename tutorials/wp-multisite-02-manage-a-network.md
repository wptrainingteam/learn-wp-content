# Managing a WordPress Multisite Network

## Learning Objectives

Upon completion of this lesson the participant will be able to:

Manage a WordPress multisite network
Create and manage sub sites
Allow users to register their own sub sites

## Outline

1. Introduction
2. The Network Admin dashboard
3. The Network Settings page
4. Creating and Managing Sub-sites

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn how to manage your WordPress multisite network. 

You will learn about the Network Admin dashboard, and the options available in the Network Settings page, as well as different ways to create and manage sub-sites on the network. 

## The Network Admin dashboard

Once you have enabled your multisite network, your Admin user will change to a Super Admin, and will have access to the Network Admin dashboard. Here, you can manage sites, users, themes, plugins and settings for the network. 

## The Network Settings page

The Network Settings page is where you manage your multisite network. 

The Operational Settings allow you to set the Network Title and Admin Email

The Registration Settings allow you to set the registration options for the network.

For example, if you want to allow users to register new sites on the network, you can enable the "Both sites and user accounts can be registered" option.

You can also set banned names for new sites, limit registrations by certain email domains, and ban registrations by certain email domains

New Site Settings controls what emails are sent and what content is created when creating a new site on the network. 

Upload Settings controls file uploads, including file size limits, and allowed file types.

Language Settings allows you to control the default language for new sites on the network.

Finally, the Menu Settings allows you to control which menus are available to site admins.

## Creating and Managing Sub-sites

### Create a site

One of the first things that you might want to do is create a sub-site. To do this, go to the Sites page, and click the "Add New" button. You'll be asked to enter the site address (either a subdomain or a subdirectory), the site title, site language, and the site admin email address. If you use an email address of an already existing user, that user will be added as the site admin. Otherwise a new user will be created, and made admin of the new site

Once the site is created, you can edit the site by clicking on the Edit button in the Sites list.

The Info tab manages the site address, shows when the site was registered and last updated, and manages the site attributes

The Users tab allows you to add existing users to the site, or add new users to the site.

The Themes tab allows you to activate a theme for the site. The themes available here are the themes that are not activated for the entire network. This means you can have specific themes only available for specific sites on the network.

The Settings tab contains all the rest of the site settings that you can manage.

### Assign a TLD to a site

It is also possible to set a top-level or apex domain for a site on the network. This is useful if you or the site admin wants to use a different domain for a site on the network. 

In order for this to be possible, you need to have registered the domain with your domain registar, and have it pointed to the same location as the multsite install.  

In this case you can see the new top level domain is pointing to the same location as the multisite install, as it redirects to the multisite url. 

Then, in the Network Admin, edit to the site in question, and change the Site Address field to the new top level domain.  

Now, when you browse to that domain, you will be redirected to the associated site on the network.

### Allow users to register their own sites

Depending on how you plan to use your multisite network, you might want users to be able to register their own subsites. To do this, go to the Network Settings page, and enable the "Both sites and user accounts can be registered" option.

Now, if a user browses to the default WordPress registration url, they will see an option to also register a site one the network.

https://multipress.test/wp-login.php?action=register

After entering their username and email address, they will be asked if they want to create a site, or just register a user account on the network. 

If they choose to create a site, they will need to set the site name and title.

Once they click signup, the site will be created and the user will be sent an email to activate the new site, after which they will be able to log in to the subsite. 

## Further reading.

For more details on managing your Multisite network, check out the [Network Admin](https://wordpress.org/documentation/article/network-admin/) and [Network Admin Settings](https://wordpress.org/documentation/article/network-admin-settings-screen/) pages in the WordPress developer documenation.

Happy coding!
