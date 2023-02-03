# WordPress REST API - Custom Fields, Authentication

https://workpress.test/wp-json/wp/v2/

## Objectives

Upon completion of this lesson the participant will be able to:

1. Learn the different ways to add custom fields to the WP REST API
2. Add custom top level fields to the Posts route using register_rest_field
3. Post custom data to the Posts route
4. Enable custom meta fields for the WP REST API using register_meta
5. Post meta data to the Posts route

## Outline

- Introduction
- Important Note about Changing Responses
- Enabling Custom Fields for the WP REST API using register_meta
- Enabling Custom Fields for the specific WP REST API routes
- Adding Custom Fields as top level fields to API Responses using register_rest_field
- Including Schema
- Deciding between register_rest_field vs register_meta

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn how to modify WP REST API responses.

You will learn about two methods of adding fields to your REST API requests, either by enabling custom fields in the REST API route, or by making custom fields available as top level fields.

You'll also learn about the pros and cons to both approaches.

For the purposes of this tutorial, you'll be using the Postman API testing tool to test your modified API requests. If you haven't done it already, download and install the Postman app for your operating system.

## Important note about modifying responses

Before we get started, it's important to note that modifying WP REST API responses can have unexpected consequences. Changing or removing data from core REST API endpoint responses can break plugins or WordPress core behavior, and should be avoided wherever possible.

If you need to retrieve a subset of data from a REST API request, the recommended method is to rather use the [_fields](https://developer.wordpress.org/rest-api/using-the-rest-api/global-parameters/#_fields) global parameter, to limit the fields returned in the response.

[Example of using fields to limit fields returned by Posts GET route]

Adding fields is less risky, and so this tutorial only covers adding fields to REST API responses.

## Enabling Custom Fields for the WP REST API

In the [Custom Fields and Authentication tutorial], you learned how take custom fields, also known as post meta, added using the `register_meta` function and enable them for the WP REST API. 

To do this, you used the show_in_rest argument in the arguments array passed to the `register_meta` function, and set it to true.

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

This will enable the custom field to be added to the REST API response, and will also allow you to post data to the custom field using the REST API. This is handled by passing the custom field as a key value pair in the meta object of the request body.

```js
const post = new wp.api.models.Post( {
  title: title,
  content: content,
  status: 'publish',
  meta: {
    'url': url_value
  }
} );
```

[Show posting a meta object to the Posts POST endpoint via Postman]

This is the most common way to enable custom fields on your WP REST API routes, as it allows you to make use of custom fields you may have already registered using the `register_meta` function.

## Enabling Custom Fields for the specific WP REST API routes

Prior to WordPress 4.9.8, custom fields set to show_in_rest using register_meta were registered for all objects of a given type. For example, if you added a custom field to the posts type, and then created a custom post type, the custom field would automatically be available in the custom post type. As of WordPress 4.9.8 it’s possible to use `register_meta` with the `object_subtype` argument that allows one to reduce the usage of the meta key to a particular post type.

For example, let's say in your plugin you have a url custom field registered on the default post type:

```
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

You then decide to register a new custom post type book:

```php
    /**
     * Register a book custom post type
     */
	register_post_type(
		'book',
		array(
			'labels'       => array(
				'name'          => __( 'Books' ),
				'singular_name' => __( 'Book' )
			),
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'supports'     => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'custom-fields',
				'revisions',
			),
			'taxonomies'   => array(
				'category',
				'post_tag',
			),
		)
    );
```

By default, the url custom field will be available on both Posts and Books. 

You then decide you want the url custom field to only be available on the book custom post type. You can do this by passing the `object_subtype` argument to the existing `register_meta` function.

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

If you queried Posts, the url meta field would no longer be visible. It would now only appear if you queried the Books.

You can of course also register custom fields on a per custom post type basis. For example, if you wanted to register a custom field `isbn` on the book custom post type, you could do this:

```php
	register_meta(
		'book',
		'isbn',
		array(
			'single'       => true,
			'type'         => 'string',
			'default'      => '',
			'show_in_rest'   => true,
		)
	);
```

You would now have both `url` and `isbn` custom fields available on the `book` custom post type.

## Adding Custom Fields as top level fields to API Responses

The other way to add custom fields to the WP REST API is to add them as top level fields to the API responses. 

In the earlier example, the isbn was registered as a meta field, and is therefore available in the meta object of the REST API response. But what if you'd prefer to have it as a top level field, alongside the title, content, and excerpt?

This can be acheived using the register_rest_field function. 

```php
    register_rest_field(
        'book',
        'isbn',
    );
```

You then need to pass at least the following three arguments to the $args array for this to work
1. get_callback - a function that returns the value of the field
2. update_callback - a function that updates the value of the field
3. schema - an array containing the schema for the field

```php
    register_rest_field(
        'book',
        'isbn',
        array(
            'schema'          => null,
            'get_callback'    => null,
            'update_callback' => null,
        )
    );
```

Also, new rest fields should only be registered on the REST API. To do this, hook your function to the rest_api_init action.

```php
    add_action( 'rest_api_init', 'wp_learn_rest_add_fields' );
    function wp_learn_rest_add_fields() {
        register_rest_field(
            'book',
            'isbn',
            array(
                'schema'          => null,
                'get_callback'    => null,
                'update_callback' => null,
            )
        );
    }
```

For now, you can leave the schema argument of the rest field as null, but you will need to specify the get_callback and update_callback functions. These are the functions that will be triggered when the API request is made, either to fetch the data, or to create or update the data.

```php
    register_rest_field(
        'book',
        'isbn',
        array(
            'get_callback'    => 'wp_learn_rest_get_isbn',
            'update_callback' => 'wp_learn_rest_update_isbn',
            'schema'          => null,
        )
    );
```

By default, an array of the post type's prepared data is passed to the get_callback function as the first argument. An implementation of the this function could be as straightforward as returning the value of the custom field. 

```php
function wp_learn_rest_get_isbn( $book ){
	return  get_post_meta( $book['id'], 'isbn', true );
}
```

The value sent for the field from a REST API create or update request is passed to the update_callback function as the first argument, and the model object (ie the post). An implementation of this function could be as simple as straightforward the value of the custom field.

```php
function wp_learn_rest_update_isbn( $value, $book ){
    return update_post_meta( $book->ID, 'isbn', $value );
}
```

If you test this out by creating a new book, and passing a value for the isbn field, you'll see the data being saved to the database in the post_meta table, but being displayed as a top level field in the REST API response.

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

The main advantage of using the register_meta route is that you do not need to add any further code to enable storing or retrieving data from the custom fields, as long as you remember to fetch and save the data using the meta object in your JavaScript code. You enable the field to show in the REST API, and you can use it straight away. This is also the more performant option, as it does not add any additional code that needs to be executed.

On the other hand, the main advantage of using the register_rest_field route is that the fields appear as top level fields in your REST API routes. The other advantage is that you can perform additional processing on the data before it is returned, or before it is saved. For example, you could perform some validation on the data before it is saved to the database. You could also add hooks to the get_callback and update_callback functions to either perform additional processing on the data, or to allow other developers to extend your custom fields. The downside is that you're adding a slight overhead to the API requests, as it adds more code that needs to be executed.

Ultimately, the route you choose should be decided on a case by case basis. 

For more information on this, check out the [Modifying Responses](https://developer.wordpress.org/rest-api/extending-the-rest-api/modifying-responses) section of the WP REST API Handbook.

Happy coding!