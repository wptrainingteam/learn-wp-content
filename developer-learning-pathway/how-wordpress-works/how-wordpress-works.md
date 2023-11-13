# Web Servers

## Overview

At the most basic level, WordPress is a web application that runs on a web server. But what makes up a web server, and what makes it possible for the web server to run WordPress?

A web server is computer that is connected to the internet and is configured to serve web pages. Web servers come in all shapes, sizes and configurations, but ultimately they are all just computers, just like the one you use every day to work on.

What makes a web server a little different is that it has software installed and configured to serve a web application like WordPress.

WordPress runs on a tech stack called LAMP. LAMP stands for Linux, Apache, MySQL, and PHP.

Linux is the operating system that manages the hardware and software resources of the server. Popular Linux distributions include Ubuntu, Debian, and CentOS. These operating systems made up of the Linux kernel and a collection of software packages that are installed on top of the kernel. 

Apache, MySQl, and PHP are all installed via the package manager of the specific Linux distribution on the server.

Apache is the web server software that is used to serve information on a web server. When you type a URL into your browser, the browser sends a request for some information to the web server. The web server then responds with the information that you requested. The web server software is what makes this possible. 

A popular alternative to Apache is called Nginx. Nginx is slightly newer web server software that is generally considered to be faster and more efficient than Apache at serving static content. When using Nginx, the tech stack is referred to as LEMP, which stands for Linux, Nginx, MySQL, and PHP.

By default, Apache and Nginx are configured to serve static files. Static files are files that don't change. Examples of static files include HTML files, image files or video files. HTML files can be styled using CSS, and can be made interactive using JavaScript. 

MySQL is a database software that is used to store information on the web server. For example, if you are running an online store, you will need to store information about the products that you are selling. This is where a MySQL database comes in. 

PHP is a programming language that is used to create dynamic web pages. PHP is a server side language, which means that it is executed on the web server, and the results are sent to the browser. In the online store example above, PHP is used to fetch the product information from the MySQL database and display it on the web page in the browser.

## Apache/Nginx configuration

When you install Apache or Nginx on a server, there are some files that you can configure to change the way that the web server works. Generally this configuration is done by a server system administrator. However, it's useful to understand one specific configuration set, and that's the configuration that allows a single instance of a web server to serve content for multiple websites. 

On Apache this is called a virtual host, and on Nginx this is called a server block, but they both do the same thing. They allow you to configure the web server to serve different content for different websites.

Here's an example of a virtual host configuration for Apache:

```
Listen 80
<VirtualHost *:80>
    DocumentRoot "/www/example1"
    ServerName www.example.com
</VirtualHost>
```

And here's the same example for Nginx:

```
server {
    listen 80;
    server_name www.example.com;
    root /www/example1;
}
```

In both examples, the web server is configured to listen for requests on port 80, which is the default port for HTTP requests. When the server receives a request for the domain www.example.com, it will serve the files that are located in the directory `/www/example1`.

## Directory Index

By default, the web server is configured to look for a Directory Index file. If it finds one, it will serve that file. If it doesn't find one, it will return a 404 error. The default Directory Index file is usually `index.html`. 

When PHP is installed and enabled, it's possible to configure the web server to look for and serve a PHP file as the Directory Index. This is usually a file named `index.php`.

In Apache, this is done using the `DirectoryIndex` directive and in Nginx this is done using the `index` directive, by placing index.php before the index.html file in the list.

``` 
DirectoryIndex index.php index.html
```

```
index index.php index.html;
```

Most LAMP or LEMP web servers will have this configuration set up by default. 

So in the above example, when you visit www.example.com in your browser, the web server will look for the `index.php` Directory Index file in the `/www/example1` directory and execute that file. If no `index.php` is found, it will look for a serve an `index.html` file. If no `index.html` file is found, it will return a 404 error.

## WordPress request flow

When a user visits a URL on a WordPress site, the following happens:

1. The browser sends a request to the web server for the data at the URL that the user entered
2. The web server receives the request and determines which `index.php` file should be executed to serve the requested data.
3. The PHP interpreter executes the PHP code
4. If required, the PHP code will interact with the MySQL database to retrieve any required data
5. The PHP code will then output HTML code, and include any relevant CSS or JavaScript
6. The web server will send the HTML, CSS and JavaScript code back to the browser
7. The browser will render this code and display the page to the user

This is the same whether you are visiting the home page of a WordPress site, a specific post or page, or the admin area of the site. The only difference is the PHP code that is executed.

# The WordPress file structure

## Introduction

Like most software applications, WordPress is made up of a collection of files organised into a specific structure. Because WordPress is open source, when you download the archive of the latest version of WordPress, you're able to see and inspect all the files.

## Root files

The root directory of a WordPress site contains a series of files, as well as three directories. 

### Non static files

To start, let's look at some static files:

The `.htaccess` file is a special file that is used to configure the Apache web server for a WordPress installation. It is essentially an extension of the Apache Virtual Host configuration that we looked at earlier. Any valid Apache directives can be added to this file, and will be applied for this WordPress installation. 

It's worth noting that Nginx does not support the use of an .htaccess file like configuration on a per WordPress level. Instead, the configuration is done in the main server block configuration file. This is one of the reasons that Nginx is considered to be faster than Apache, but also what makes it less configurable by the site owner. 

The `license.txt` file contains the license information for WordPress. WordPress is licensed under the open source GNU General Public License, version 2. This license allows anyone to use, modify, and redistribute WordPress.

The `readme.html` file contains information about the WordPress, including sections on installing and updating WordPress, system requirements, and links to various online resources. As an HTML file, it's best viewed in a web browser.

### PHP files that control a WordPress request

Let's start with the `index.php` file. As you learned in the Web Servers lesson, the `index.php` file is the directory index file, and it is executed when a user visits the root URL of this WordPress site. 

When the code in `index.php` is executed, it includes the code in the `wp-blog-header.php` file. As you can see `wp-blog-header.php` includes `wp-load.php`, which in turn includes the `wp-config.php` file.

The `wp-config.php` file is the main configuration file for a WordPress site. It contains all the configuration options that are required to run WordPress, like database connection information, security keys, and any custom configuration options that you may want to add.

`wp-config.php` then includes the `wp-settings.php` file, which sets up all the WordPress core functionality. 

### Additional PHP files in the root directory

There are some additional PHP files in the root directory that perform specific functions outside of regular WordPress requests. These files are usually accessed directly by either a user or some other function, and are not included in the normal WordPress request flow.

`wp-activate.php` is used to confirm that the activation key that is sent in an email after a user signs up for a new site. Typically, this would be used this if you were setting up a WordPress install yourself, or managing a WordPress multisite network. 

`wp-comments-post.php` is used to process any comments that are submitted on a WordPress site.

`wp-cron.php` is used to run any scheduled tasks that are set up on a WordPress site. This file is executed every time a WordPress page is requested, and it checks to see if there are any scheduled tasks that need to be run. If there are, it runs them.

`wp-links-opml.php` is used to generate an XML list of links. This was used by a Link Manager feature that was removed in WordPress 3.5. However, it is possible to enable this functionality using [a plugin](https://wordpress.org/plugins/link-manager/), and so this file is still included for backwards compatibility.

`wp-login.php` is used to display the login form for a WordPress site. It also processes any login requests that are submitted.

`wp-mail.php` is used by the Post via email feature of WordPress. This feature allows you to publish posts on your WordPress site by sending an email to a specific email address. If enabled, this file is executed every time an email is received to create a new post.

`wp-signup.php` is used to display the signup form for a new site on a WordPress multisite network. 

`wp-trackback.php` is used to process any trackback requests that are sent to a WordPress site. Trackbacks are a way for one website to notify another website that it has linked to it, usually in a post content or comment.  

`xmlrpc.php` is used to process any XML-RPC requests that are sent to a WordPress site. XML-RPC is a remote procedure call protocol that allows software to make requests to a WordPress site. This is used by the WordPress mobile apps, as well as some plugins and themes.

## Root directories

The root directory of a WordPress site contains three directories:

`wp-admin` contains all the files that power the WordPress dashboard area. Whenever you're interacting with the WordPress dashboard, you're using files from this directory.

`wp-content` contains any files that can be added to a default WordPress site. This includes any plugins, themes, and uploaded files. Any directories that plugins need to create to store additional files are also created in this directory.

`wp-includes` contains the bulk of the core WordPress files. This includes all the PHP files that make up the WordPress core, as well as any JavaScript and CSS files that are required to run WordPress. Common functionality like the database API, HTTP API, and plugin API are all included in this directory, and are used by both the WordPress dashboard and any front end requests.