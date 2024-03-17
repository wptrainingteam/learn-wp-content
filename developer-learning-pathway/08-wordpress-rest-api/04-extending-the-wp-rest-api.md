# Extending the WP REST API

## Introduction

The WordPress REST API provides an interface for fetching, adding, updating and deleting data from a WordPress site in a uniform way.

While the schema for the data types that are available in the REST API is quite extensive, there may be times when you need to store additional data that is not part of the core schema. 

In this lesson you will learn about two methods of adding fields to your REST API requests, either by enabling custom fields in the REST API route, or by making custom fields available as top level fields.

You'll also learn about the pros and cons to both approaches.

If you skipped the previous lessons in this module, download the [Bookstore plugin](https://github.com/wptrainingteam/beginner-developer/raw/main/bookstore.1.0.zip) from the link in the repository readme, and install and activate the plugin on your local WordPress install. Additionally, if you haven't done it already, download and install the Postman app for your operating system.

## Important note about modifying responses

Before we get started, it's important to note that modifying WP REST API responses can have unexpected consequences. Changing or removing data from core REST API endpoint responses can break plugins or WordPress core behavior, and should be avoided wherever possible.

If you need to retrieve a subset of data from a REST API request, the recommended method is to rather use the [_fields](https://developer.wordpress.org/rest-api/using-the-rest-api/global-parameters/#_fields) global parameter, to limit the fields returned in the response.

For example, you can use the fields parameter to limit the fields returned to just id, title, and expert, if those are the only fields you need for your application.

[Show limiting fields in a GET request via Postman]

Adding fields to a REST API response is less risky, and so this tutorial only covers adding fields.

## Working with custom fields

If you watched the lessons in the Introduction to WordPress plugins module, you would have learned about custom fields, also known as metadata.

These fields are often used on custom post types, to store additional pieces of data that are specific to that post type. Under the hood, these custom fields are stored in the postmeta table, as a set of key/value pairs attached to the post by the post id.

Beyond Posts, WordPress also supports metadata on other data types, such as comments and users. You can read more about this in the [Metadata API documentation](https://developer.wordpress.org/apis/metadata/).

The WP REST API allows you to create or update custom fields when creating or updating data.

This is possible by passing an object of key/value pairs to the meta property of the model you're working with.

However, in order to use a custom field, you have to register it first. This is done using the [register_meta function](https://developer.wordpress.org/reference/functions/register_meta/).

## Registering a custom field

If you wanted to register a custom field called `location` on the post type, you would use the register_meta function like this:

```php
    register_meta(
        'post',
        'url',
        array(
            'single'       => true,
            'type'         => 'string',
            'default'      => '',
            'show_in_rest' => true,
        )
    );

```

While it's possible to add this anywhere to a plugin, it's recommended to add it to something like the `init` action hook.

```php
add_action( 'init', 'wp_learn_register_meta' );
function wp_learn_register_meta(){
    register_meta(
        'post',
        'url',
        array(
            'single'       => true,
            'type'         => 'string',
            'default'      => '',
            'show_in_rest' => true,
        )
    );
}
```

It's important to ensure that the `show_in_rest` property is set to true, otherwise the custom field will not be available in the REST API.

This will enable the custom field to be added to the REST API schema for the post, and will also allow you to post data to the custom field using the REST API. This is handled by passing the custom field as a key value pair in the meta object of the request body.

```json
{
	"title": "New Post Title",
    "content": "New Post Content",
    "status": "publish",
    "meta": {
		"location": "London"
	}
}
```

## Enabling Custom Fields for the specific WP REST API routes

Prior to WordPress 4.9.8, custom fields set to `show_in_rest` using `register_meta` were registered for all objects of a given type. For example, if you added a custom field to the posts type, and then created a custom post type, the custom field would automatically be available in the custom post type. 

As of WordPress 4.9.8 it’s possible to use `register_meta` with the `object_subtype` argument that allows one to reduce the usage of the meta key to a particular post type.

For example, let's say you wanted to register an isbn custom field only on the book custom post type:

```php
    register_meta(
        'post',
        'url',
        array(
            'single'         => true,
            'type'           => 'string',
            'default'        => '',
            'show_in_rest'   => true,
            'object_subtype' => 'book',
        )
    );
```

You would now have `isbn` custom field available only the `book` custom post type.

You can test this by adding a book via the REST API.

In Postman, create or update the POST request to the book route, and include the meta object with the isbn field:

```
https://example.com/wp-json/wp/v2/book
```

Then post the following in the request body:

```json
{
	"title": "New Book",
    "content": "New Book Content",
    "status": "publish",
    "meta": {
        "isbn": "978-1-4302-6418-2"
	}
}
```

If you then edit the book in the WordPress admin, you'll see the isbn field in the Custom Fields panel, if you have it enabled. 

## Adding Custom Fields as top level fields to API Responses

The other way to add custom fields to the WP REST API is to add them as top level fields on the API responses.

In the earlier example, the isbn was registered as a meta field, and is therefore available in the meta object of the REST API response. But what if you'd prefer to have it as a top level field, alongside the title, content, and excerpt?

This can be achieved using the [register_rest_field](https://developer.wordpress.org/reference/functions/register_rest_field/) function. Let's look at how this would be implemented.

First, you need to register your rest fields in the `rest_api_init` action hook. This is to ensure that the field is only registered on the REST API.

```php
add_action( 'rest_api_init', 'bookstore_add_rest_fields' );
function bookstore_add_rest_fields() { 
    // register some REST API functionality
}
```

You then use the `register_rest_field` function to register the field. The first parameter is the object type the field should be registered on. This can be a string, for a single object, or an array, for more than one object. In this case, just register the field on the `book` custom post type. The second argument is the name of the field. In this case, just make it the same as the custom field, `isbn`.

```php
    register_rest_field(
        'book',
        'isbn',
    );
```

The third parameter is an array of arguments that determines how the field functions. You need pass at least the following three arguments to the array.
1. get_callback - a function that returns the value of the field
2. update_callback - a function that updates the value of the field
3. schema - an array containing the schema for the field

```php
    register_rest_field(
        'book',
        'isbn',
        array(
            'get_callback'    => null,
            'update_callback' => null,
            'schema'          => null,
        )
    );
```

For now, you can leave the schema argument of the rest field as null, but you will need to specify the `get_callback` and `update_callback` functions. These are the functions that will be triggered when the API request is made, either to fetch the data, or to create or update the data.

```php
    register_rest_field(
        'book',
        'isbn',
        array(
            'get_callback'    => 'bookstore_rest_get_isbn',
            'update_callback' => 'bookstore_rest_update_isbn',
            'schema'          => null,
        )
    );
```

By default, an array of the post type's prepared data is passed to the get_callback function as the first argument. An implementation of this function could be as straightforward as returning the value of the custom field.

```php
function bookstore_rest_get_isbn( $book ){
	return  get_post_meta( $book['id'], 'isbn', true );
}
```

The value sent for the field from a REST API create or update request is passed to the update_callback function as the first argument, and the model object (ie the post) as the second. An implementation of this function could be as simple as updating the value of the custom field.

```php
function bookstore_rest_update_isbn( $value, $book ){
    return update_post_meta( $book->ID, 'isbn', $value );
}
```

If you test this out by creating a new book, and passing a value for the `isbn` field, you'll see the data being saved to the database in the `post_meta` table, but is also displayed as a top level field in the REST API response.

## Including Schema

The schema argument is an array that describes the schema for the field. While it's not a requirement, including a schema is encouraged. If nothing else, it helps future developers understand what the field is for. It can also be used to validate the data being sent when creating automated API tests

```php
    register_rest_field(
        'book',
        'isbn',
        array(
            'get_callback'    => 'wp_learn_rest_get_isbn',
            'update_callback' => 'wp_learn_rest_update_isbn',
            'schema'          => array(
                'description' => __( 'The ISBN of the book' ),
                'type'        => 'string',
            ),
        )
    );
```

You can read more about how to define the schema for REST API resources and fields in the [WP REST API Handbook](https://developer.wordpress.org/rest-api/extending-the-rest-api/schema/).

## Deciding between register_rest_field vs register_meta

The main advantage of using the register_meta route is that you do not need to add any further code to enable storing or retrieving data from the custom fields, as long as you remember to fetch and save the data using the meta object in your application code. You enable the field to show in the REST API, and you can use it straight away. This is also the more performant option, as it does not add any additional code that needs to be executed.

On the other hand, the main advantage of using the register_rest_field route is that the fields appear as top level fields in your REST API routes. The other advantage is that you can perform additional processing on the data before it is returned, or before it is saved. For example, you could perform some validation on the data before it is saved to the database. You could also add hooks to the get_callback and update_callback functions to either perform additional processing on the data, or to allow other developers to extend your custom fields. The downside is that you're adding a slight overhead to the API requests, as it adds more code that needs to be executed.

Ultimately, the route you choose should be decided on a case by case basis.

## Further reading

For more information on modifying REST API responses, check out the [Modifying Responses](https://developer.wordpress.org/rest-api/extending-the-rest-api/modifying-responses) section of the WP REST API Handbook.