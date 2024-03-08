# Permalinks - rewriting dynamic URLS

In this lesson, you'll learn about a concept called Permalinks, and how they are used to rewrite dynamic URLs in a WordPress site.

## Introduction

In the web servers lesson, you learned that most PHP based applications, including WordPress, will have a Directory Index file. 

This is the file that will be executed when a user browses to the URL of the site in question.

With a site powered by WordPress however, it's possible to have multiple different types of content rendered, like posts, pages, or products, all via the same Directory Index file. 

The key behind how this works is a feature called Permalinks.

## Query String Variables

Before we dive into permalinks, it's useful to understand a concept known as a query string.

Let's take a look at an example on a test WordPress installation.

```
https://wordpress.test/?p=1
```

In this example, the URL is `https://wordpress.test/`, and the query string is `?p=1`. The query string is a way to pass data to the web server. Here, the query string is passing the value `1` to the variable `p`.

In PHP, it's possible to access the value of the variable `p` using the `$_GET` super global.

```php
<?php
$p = $_GET['p'];
```

The PHP code can then use this to perform some sort of data look up, for example to retrieve a post from the database with the ID of 1.

Permalinks, also known as clean URLs, are a way to make URLs more human-readable.

Instead of using a query string, permalinks use a URL structure that is based on the content that is being requested.

Here is the same example as above, but using a permalink.

```
https://wordpress.test/1/
```

In this example, the URL is `https://wordpress.test/1/`. There is no query string, and the URL is much more human-readable. But how does the web server know what content to serve?

Based on the expected URL structure, the web server can be configured to perform URL mapping, which uses a web server feature called URL rewriting. The web server can be configured to expect a certain URL structure and pass that data to the web application, which handles fetching the relevant information based on the data it receives.

## WordPress and Permalinks

WordPress has a feature called Permalinks, which allows you to configure the URL structure of your WordPress site. You can find this feature in the WordPress dashboard under **Settings > Permalinks**.

The default permalink structure is Plain, meaning no Permalinks are in use, and plain query strings are used.

The other options allow you to set your desired permalink structure from a list of common options, or define your own custom structure.

When you set one of any these options other than Plain, the server will be configured to expect a clean URL based on the structure that you have defined. At the same time, WordPress will store the selected structure in the database. When a request is made to the site using a matching structure, WordPress will use these two pieces of data to map the URL structure to information that needs to be displayed, fetch that information, and display it on the page.

On Apache web servers, this is typically handled in the .htaccess file. For example, if you set your permalink structure to any of the options other than Plain, the following code will be added to your .htaccess file:

```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
```

This checks if the Apache web server has the rewrite module enabled, and then sets up the rewrite rules to expect a clean URL structure.

On Nginx web servers, this is typically handled in the server block configuration file. Because Nginx does not support the use of an .htaccess file like configuration on a per WordPress level, the configuration is typically added by default to the server block in a location directive.

```
location / {
            try_files $uri $uri/ /index.php?$args; 
}
```

Whenever a permalink structure is set, if you add links to internal content like posts or pages, WordPress will automatically generate the correct URL based on the permalink structure that you have set.


## YouTube chapters

0:00 Introduction
0:39 Query String Variables
2:18 WordPress and Permalinks