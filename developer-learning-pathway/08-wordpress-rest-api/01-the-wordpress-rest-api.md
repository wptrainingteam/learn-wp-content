# The WordPress REST API

## Introduction

When you're developing for WordPress, there are a number of APIs that you can use to interact with your site data. One of the most important of these is the REST API.

This lesson will be an introduction to the WordPress REST API. 

You will learn what the REST API is, as well as some key REST API concepts like routes, endpoints and global parameters, through a series of example requests you can perform in a browser. 

You will also learn about something called application passwords which can be used to authenticate REST API requests, and where to go to find out more information about the REST API.

## What is the WordPress REST API?

The WordPress REST API provides an interface for applications to interact with a WordPress site. These applications could be WordPress plugins, themes, or custom applications that need to access WordPress site data.

One of the most well known implementations of the WordPress REST API is the Block Editor, which is a JavaScript application that interacts with WordPress data through the REST API.

If you open your browser's developer tools, and look at the Network tab, you can see the requests that are made to the WordPress REST API when you interact with the Block Editor.

## What does REST API mean?

API stands for Application Programming Interface. It's a set of functionality that allow applications to interact with each other. WordPress has many APIs, the REST API is just one of them.

REST stands for [REpresentational State Transfer](https://en.wikipedia.org/wiki/Representational_state_transfer), which is a software architectural style that describes a uniform interface between physically separate components.

At it's core, the WordPress REST API provides REST endpoints (URIs) which represent the posts, pages, taxonomies, and any other custom data types. Your code can send and receive data as JavaScript Object Notation (aka JSON) to these endpoints to fetch, modify, and create content on your site.

Let's dive into some concepts of REST to understand them better.

## Routes & Endpoints

In the context of the WordPress REST API, a route is a URI which can be mapped to different HTTP methods.

An HTTP method is the type of request that's made whenever you interact with anything on the web. For example, when you browse to a URL on the web, a GET request is made to the server to request the data.

When you submit a form, a POST request is made, which passes the submitted form data to the web server.

The mapping of an individual HTTP method to a route is known as an endpoint. 

## Local Development Testing

One thing to note about testing REST API routes on a local WordPress installation is that you may need to enable a Permalink setting other than "Plain".

This is because the REST API uses the same URL rewriting functionality as Permalinks to map the human-readable routes to the relevant internal request.

So if your local WordPress installation is using the default Plain permalink setting, change it to something like Post name.

## Example Routes & Endpoints

Let's look at some examples of routes and endpoints.

If you open a browser, and go to the `/wp-json/` URI of a WordPress site, you will be making a GET request to that URI. 

```
https://local.test/wp-json/
```

The data returned is a JSON response showing what routes are available, and what endpoints are available within each route.

By default, your browser will display the JSON data in its raw data format. To convert it to a more readable format, you can use a browser extension like [JSON Formatter](https://chrome.google.com/webstore/detail/json-formatter/bcjindcccaagfpapjjmafapmmgkkhgoa) for Chrome, [Basic JSON Formatter](https://addons.mozilla.org/en-US/firefox/addon/basic-json-formatter/) for Firefox, or [JSON Peep](https://apps.apple.com/us/app/json-peep-for-safari/id1458969831?mt=12) for Safari.

In this example `/wp-json/` is a route, and when that route receives a GET request it's handled by the endpoint which displays the data. This data is what is known as the index for the WordPress REST API.

By contrast, the `/wp-json/wp/v2/posts` route offers a GET endpoint which returns a list of posts, but also a POST endpoint. If you are an authenticated user, and you submit the right data via a POST request to the `/wp-json/wp/v2/posts` route, that request is handled by the endpoint which creates new posts.

Typically, the same route (in this case `/wp-json/wp/v2/posts`) will have different endpoints for different HTTP methods, including GET for fetching data, POST for creating data and DELETE for deleting data.

## Global Parameters

The WP REST API includes a number of global parameters which control how the API handles the request/response handling. These operate at a layer above the actual resources themselves, and are available on all resources.

Global parameters are implemented on REST API routes as query string parameters. Query strings start with a `?` and are followed by a series of `key=value` pairs, separated by `&`.

Take a look at the `/wp-json/wp/v2/posts` route you looked at earlier, by requesting the route in a browser, thereby activating the GET endpoint. As you can see, the default is to return all available fields for a post.

However, if you update the route by adding the `_fields` global parameter, and then specify the fields you want to return in the response as a comma delimited list.

```
wp-json/wp/v2/posts?_fields=author,id,excerpt,title,link
```

If you make a second GET request, by refreshing the browser, only the fields you have requested to be returned in the response are available.

## Pagination and Ordering

The WP REST API also supports pagination and ordering of results.

Pagination is handled by the `per_page`, `page` and `offset` parameters.

For example, you can update the `wp-json/wp/v2/posts` route to return only 5 posts per page, by adding the `per_page` parameter to the route.

```
wp-json/wp/v2/posts?_fields=author,id,excerpt,title,link&per_page=5
```

If you make a new GET request be refreshing the page, only the first 5 posts are returned.

It's also possible to order the results, using the `order` and `order_by` parameters.

For example, you can update the `wp-json/wp/v2/posts` route to order by post title, in descending order.

``` 
wp-json/wp/v2/posts?_fields=author,id,excerpt,title,link&per_page=5&orderby=title&order=asc
```

## Authentication

By default, the WordPress REST API uses the same cookie based Authentication method that is used when logging into the WordPress dashboard. 

As you can see from the earlier examples, the GET requests you made to the REST API were not authenticated, because that data is public.

So for any REST API endpoints that are not public, or require an authentication user to view or modify, the authentication cookie needs to be present. This is how the block editor works, for example.

There are a number of ways to authenticate requests, including [JSON Web Tokens](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/) and [OAuth](https://wordpress.org/plugins/rest-api-oauth1/).

Another way that's built into WordPress is [Application passwords](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/).

Application passwords can be set on a per-user basis, and are used to authenticate requests to the WP REST API. This allows you to let users access the API without having to share the password they use to log in to the WordPress dashboard.

To create an application password for your user, navigate to your user in the Users list, and click on the User to edit it. Scroll down to the bottom of the screen, under the Application Passwords section. 

Give the new application password a name and click Add New Application Password.

The password will be generated for you. Make sure to copy it and store it somewhere securely, as you won't be able to see it again. 

In this screen you are also able to revoke the password, should it ever be leaked.

Using an application password for your user is a great way to test out REST API requests. In a future lesson, you'll learn to use an external tool to test REST API routes and endpoints. 

If you intend building something more complex, like a mobile app that connects to a WordPress REST API, you should rather consider using JSON Web Tokens or OAuth 1.0a.

## Further Reading

The WordPress Developer Resources site has an entire section handbook to the [REST API](https://developer.wordpress.org/rest-api/) which includes sections on the key REST API concepts, frequently asked questions, using and extending the REST API, and more.

