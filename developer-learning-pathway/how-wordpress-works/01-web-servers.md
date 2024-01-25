# WordPress and Web servers

## Introduction

Welcome to the introduction to WordPress and Web Servers. 

If you want to develop custom WordPress sites, plugins or themes, it's important to understand how WordPress works under the hood. 

One of the first things you should learn about is how WordPress and web servers work together.

In this lesson, you'll learn the basics of web servers, and how they power your WordPress sites.


## What is a web server

At the most basic level, WordPress is a web application that runs on a web server. But what makes up a web server, and what makes it possible for the web server to run WordPress?

A web server is computer that is connected to the internet and is configured to serve web pages. Web servers come in all shapes, sizes and configurations, but ultimately they are all just computers, just like the one you use every day to work on.

What makes a web server a little different is that it has software installed and configured to serve a web application like WordPress.

WordPress runs on a tech stack called LAMP. LAMP stands for Linux, Apache, MySQL, and PHP.

Linux is the operating system that manages the hardware and software resources of the server. Popular Linux distributions include Debian, Ubuntu, RedHat, and CentOS. These operating systems made up of the Linux kernel and a collection of software packages that are installed on top of the kernel.

Apache, MySQL, and PHP are all installed via the package manager of the specific Linux distribution on the server.

Apache is the web server software that is used to serve information on a web server. When you type a URL into your browser, the browser sends a request for some information to the web server. The web server then responds with the information that you requested. The web server software is what makes this possible.

A popular alternative to Apache is called Nginx. Nginx is slightly newer web server software that is generally considered to be faster and more efficient than Apache at serving static content. When using Nginx, the tech stack is referred to as LEMP, which stands for Linux, Nginx, MySQL, and PHP.

By default, Apache and Nginx are configured to serve static files. Static files are files that don't change. Examples of static files include HTML files, image files or video files. HTML files can be styled using CSS, and can be made interactive using JavaScript.

MySQL is a database software that is used to store information about the site on the web server. For example, if you are running an online store, you will need to store information about the products that you are selling. This is where a MySQL database comes in.

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

In an Apache server configuration, this is done using the `DirectoryIndex` directive and in Nginx this is done using the `index` directive.

``` 
DirectoryIndex index.php index.html
```

```
index index.php index.html;
```

In both cases, index.php is placed before index.html in the list of files to look for.

Most LAMP or LEMP web servers will have this configuration set up by default.

So in the above examples, with the Directory Index set, when you visit www.example.com in your browser, the web server will look for the `index.php` Directory Index file in the `/www/example1` directory and execute that file. If no `index.php` is found, it will look for a serve an `index.html` file. If no `index.html` file is found, it will return a 404 "Not found" error.

## WordPress request flow

When a user visits a URL on a WordPress site, the following happens:

1. The browser sends a request to the web server for the data at the URL that the user entered
2. The web server receives the request and determines which file should be executed to serve the requested data.
3. In a WordPress site, this will be the `index.php` file in the root directory for a front end request, or the specific file in the `wp-admin` directory for an admin request
4. The PHP interpreter executes the PHP code
5. If required, the PHP code will interact with the MySQL database to retrieve any required data
6. The PHP code will then output HTML code, and include any relevant CSS or JavaScript
7. The web server will send the HTML, CSS and JavaScript code back to the browser
8. The browser will render this code and display the page to the user