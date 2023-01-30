# WordPress REST API - Custom Fields, Authentication

## Objectives

Upon completion of this lesson the participant will be able to:

- Update Posts using the WP REST API
- Create or update Post custom fields
- Authenticate with the REST API using Application Passwords
- Locate additional information about the WP REST API

## Outline

- Updating Posts using the WP REST API
- Creating or updating Post custom fields
- Authenticating using Application Passwords
- Where to go for more information

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn more ways to interact with the WordPress REST API.

You will learn how to update posts using the REST API, how to create or update custom fields when creating or updating Posts, and how to authenticate with the REST API using Application Passwords. You'll also learn how to use a REST API testing tool called Postman.

If you've never interacted with the WP REST API before, I recommend watching the Interacting with the WP REST API tutorial first, and then coming back here.

## Example code

This tutorial builds on the code examples shared in the Interacting the WordPress REST API tutorial. You can download the plugin that was created in that tutorial from this URL, and use it as the basis for this tutorial.

To review, this plugin adds a new admin submenu page to the WordPress Tools menu, and uses the WP REST API to load the posts from the site, and display them in a textarea. It also has a form that allows you to create a new post, by entering the title and content fields. Finally, you can delete a post by entering the post id in the text field, and clicking the delete button.

The JavaScript code that powers this makes use of the Backbone.js client that ships with WordPress. Listing posts uses the Posts collection, and creating and deleting posts uses the Posts model. More specifically, the submitPost funciton uses the Posts model to create a new post. This function is then called when the Create Post button is clicked.



## Working with custom fields

Besides the default fields that exist on a Post, WordPress also allows you to add and manage custom fields, also known as metadata. These fields are often used on custom post types, to store additional pieces of data that are specific to that post type. Under the hood, these custom fields are stored in the postmeta table, as a set of key/value pairs attached to the post by the post id.

If you've ever worked with the add_post_meta, update_post_meta and get_post_meta functions, you were accessing the these custom fields. 

The WP REST API allows you to create or update custom fields when creating or updating Posts. 

This is possible by passing an object of key/value pairs to the meta property of the Post model.

However, in order to use a custom field, you have to register it first. This is done using the [register_meta function](https://developer.wordpress.org/reference/functions/register_meta/).

For example, if you wanted to add a custom field called "url" to the post type "post", you would use the following code:

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

This can be added to the root of the plugin PHP file, and does not need to be wrapped in an action hook callback function. However, you can also add it to something like the init action hook callback function if you prefer.

It's important to ensure that the show_in_rest property is set to true, otherwise the custom field will not be available in the REST API.

Once this is done, go ahead and add a field to the form that creates a post, to capture the url value:

```html
<div>
    <label for="wp-learn-post-url">Post Url</label>
    <input type="text" id="wp-learn-post-url" placeholder="Url">
</div>
```

Next, switch to the JavaScript file, and update the submitPost function to get the url value:

```js
const url = document.getElementById( 'wp-learn-post-url' ).value;
```

Then, pass the url value to the Post model, as part of the meta property:

```js
        meta: {
            url: url
        }
```

Note that the meta property is an object, so you can add as many key/value pairs as you need. The key value is the name of the custom field, as specified in the register_meta function.

Once you've done this, refresh the admin page, and test adding a post with the url.

To validate that the url has been saved, open a new browser window or tab, and navigate to the post in the WP dashboard. You should see the url field in the Custom Fields area, at the bottom of the editor screen. 

Depending on your set-up, you might need to enable the Custom Fields area. If you're using the block editor, you can do this by clicking on the Options button, selecting Preferences, clicking on Panels, and enabling the Custom Fields toggle. This will require the editor to be reloaded.

If you're using the classic editor, click on the Screen Options button, and enable the Custom Fields option.

## Authentication

Bhy default the WP REST API uses the same authentication cookie that is set in your browser as when you log into your WordPress dashboard. So if you're a logged-in user, you can access the API for create and update requests.

If you wanted to build a front-end application that allows users to create and update posts, you would need to provide a way for them to log in. One way would be to make use of the default WordPress user login form, and use the [wp-login.php](https://developer.wordpress.org/reference/files/wp-login-php/) file to handle the authentication. Another way that's now available to WordPress is [Application passwords](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/).

Application passwords can be set on a per-user basis, and are used to authenticate requests to the WP REST API. This allows you to let users access the API without having to share their actual password.

Go ahead and create an application password for your user, and save it to be used later.

Let's take a look at how this works, by introducing a tool called Postman.

### Postman

Postman is a tool that allows you to make HTTP requests to a server. It's a great way to test out the WP REST API, and can be used to test out any API that uses HTTP requests. 

You can download Postman from the [Postman website](https://www.postman.com/downloads/). The current version at the time of this recording is Version 10.8.6.

Once installed, open Postman, and click on the Create Collection button. This will create a new collection, where you can add multiple requests to test. 

You can give the collecting a name to differentiate it from other collections. Inside the collection click the Add a request button.

This will open a new request, where you can give the request a unique name.  Then, enter the url to your local posts endpoint, and click the Send button.

```php
https://workpress.test/wp-json/wp/v2/posts
```

The request will be made, and the JSON response will be parsed and displayed in the response area.

Now, create a new request, and enter the same url to the posts endpoint, but this time change the request method to POST, and hit send. 

This time you'll be presented with an error message, because you're not authenticated.

To authenticate the request, click on the Authorization tab, and select Basic Auth from the dropdown. Then, enter your username and password, and click the Save button.

This time you don't get the same error, because you're not authenticated. So you can create posts. 

Go ahead and create one, by clicking on the Body tab in the request, and selecting the raw radio button. Then, select JSON from the dropdown, and enter the following JSON:

```json
{
    "title": "My Postman Post",
    "content": "This is my Postman post",
    "status": "publish",
    "meta": {
        "url": "https://postman.com"
    }
}
``` 

Hit send again, and the post will be created, returning the JSON response of the new post.

To be sure, go ahead and check the post in the WP dashboard, and you should see the post, with the url custom field.

Using Postman to test REST API endpoints is a great way to save time, by ensuring that the data you intend to send is formatted correctly, and that the request is being made to the correct endpoint. It's also extremely useful when extending the WP REST API.

For more information in interacting with the WP REST API, as well as how to extend it, check out the [WP REST API Handbook](https://developer.wordpress.org/rest-api/) at developer.wordpress.org.

Happy coding