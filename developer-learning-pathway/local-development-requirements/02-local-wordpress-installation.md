# Local WordPress installation

## Introduction

Once you have decided on a local development environment, you will need to install the WordPress core files, to set up a working WordPress site.

Tools like LocalWP, DevKinsta, and VVV all include a way to automatically create a new local site, and install WordPress for you. To do this, you will need to check the documentation for your chosen tool, and you can skip this lesson.

However, if you decide to use something like MAMP or XAMPP, you will need to install WordPress yourself, which you will learn in this lesson.

While knowing how to install WordPress manually is not an essential skill, it is a good idea to understand the process.

## Requirements

Before you can install WordPress, you will need to know the following details about your local development environment.

- The location of the document root or web root folder. This is the folder that your local development environment uses to serve files to the web browser. 
- The database details. This includes the database server name database name, the database username, and the database password. On local development environments the database server name is usually `localhost`, and the database username is usually `root`. The database password is either blank or `password`. You may need to create a new database for your WordPress site.
- The URL of your local site. This is the URL that you will use to access your local site in a web browser. 

It's a good idea to refer to the documentation for your local development environment to find out this information.

## Downloading WordPress

The first step is to download the WordPress core files. You can do this from the [Get WordPress](https://wordpress.org/download/) page on the WordPress.org website. 

Click on the "Download WordPress" button to download the latest version of WordPress, which is available as a zip file.

Depending on your browser, you may be prompted to save the file, or it may be downloaded automatically. It may also default to being saved in your Downloads folder, or a custom folder if you have set one up.

If you're on a Mac using Safari, the zip file may be automatically extracted for you. If not, you will need to extract the files from the zip manually. Either way, you should now have a folder called `wordpress` on your computer.

If you open that folder, you'll see the core WordPress files. 

## Copy the WordPress files to your local development environment

The next step is to copy the WordPress files to the document root of your local development environment.

## The famous five minute install

Once you have copied the WordPress files to your local development environment, you can now install WordPress. To do this, browse to the URL of your local site in a web browser. This will start the WordPress installation process.

The first step is to choose a language. Once you have chosen a language, click on the blue "Continue" button.

You will be presented with a screen that explains what information you will need to complete the installation. Click on the blue "Let's go!" button to continue.

You will now be asked to enter the database details. This includes the database server name, the database name, the database username, and the database password. You will also need to choose a table prefix, or accept the default value. 

Once you have entered all the details, click on the "Submit" button.

If the database details are incorrect, you will be presented with an error message. You will need to check the database details, and try again.

If the database details are correct, you will be presented with a screen that confirms the installation is ready.

During this process, a `wp-config.php` file will be created in the root of your WordPress installation. This file contains all the database details you entered, and is used by WordPress to connect to the database.

Click on the blue "Run the installation" button to continue. WordPress will use the database details to connect to the database, and create the database tables.

You will now be asked to enter some information about your site. This includes the site title, the admin user username and password, and the admin user email address. 

A secure password will be generated for the admin user, but you can change it if you want to. 

You can also choose whether to discourage search engines from indexing your site.

Once you have entered all the details, click on the "Install WordPress" button to continue. 

This process will create the admin user in the user table (user ID 1). It will also set up the default site options. 

You will now be presented with a screen that confirms the installation is complete. You can now log in to your local site using the admin user username and password you entered previously.

## Conclusion

Congratulations, you've successfully installed WordPress on your local development environment.

