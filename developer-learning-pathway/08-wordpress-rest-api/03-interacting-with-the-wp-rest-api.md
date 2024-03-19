# Interacting with the WP REST API

## Introduction

While the WP REST API is commonly used to fetch data from WordPress, it can also be used to perform other actions.

The REST API also allows you to create, update, and delete various WordPress data types.

In this lesson, you'll learn about the WP REST API schema, methods to authenticate a WP REST API request, tools to test WP REST API requests, as well as a couple of ways to add, edit or delete data via the WP REST API.

If you skipped the previous lessons in this module, download the [Bookstore plugin](https://github.com/wptrainingteam/beginner-developer/raw/main/bookstore.1.0.zip) from the link in the repository readme, and install and activate the plugin on your local WordPress install.

## WP REST API Schema

When working with the REST API, it's useful to keep the [Endpoint Reference](https://developer.wordpress.org/rest-api/reference/) section of the WP REST API documentation handy. The Endpoint Reference lists all endpoints that ship with WordPress core.

Clicking on an individual endpoint, say [Posts](https://developer.wordpress.org/rest-api/reference/posts/), will show you the schema for that endpoint. The schema defines all the fields that exist for a resource when fetching or creating data of that specific type.

If you've created a custom post type, like the books custom post type from the bookstore plugin, the schema for the custom post type endpoint will be similar to the posts endpoint.

You will notice that many of the endpoint fields match up with the fields that are available in the WordPress database table related to that data type. Some however, are slightly different. For example, the `title` field for the Post endpoint will match up to the `post_title` field in the posts table. It is important to remember that these differences exist, and to use the correct field name when interacting with the API.

## Authentication

By default, the WordPress REST API uses the same cookie based Authentication method that is used when logging into the WordPress dashboard.

For any REST API endpoints that are not public, or require an authentication user to view or modify, the authentication cookie needs to be present. 

This is how the block editor works, for example.

There are a number of ways to authenticate requests, including [JSON Web Tokens](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/) and [OAuth](https://wordpress.org/plugins/rest-api-oauth1/).

Another way that's built into WordPress is [Application passwords](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/).

Application passwords can be set on a per-user basis, and are used to authenticate requests to the WP REST API. This allows you to let users access the API without having to share the password they use to log in to the WordPress dashboard.

To create an application password for your user, navigate to your user in the Users list, and click on the User to edit it. Scroll down to the bottom of the screen, under the Application Passwords section.

Give the new application password a name and click Add New Application Password.

The password will be generated for you. Make sure to copy it and store it somewhere securely, as you won't be able to see it again.

In this screen you are also able to revoke the password, should it ever be leaked.

Using an application password for your user is a great way to test out REST API requests, using a REST API testing tool. 

If you intend building something more complex, like a mobile app that connects to a WordPress REST API, you should rather consider using JSON Web Tokens or OAuth 1.0a.

### Postman

There are a number of tools available to test REST API requests. For example, if you use PhpStorm, it has a built-in [HTTP client](https://www.jetbrains.com/help/phpstorm/http-client-in-product-code-editor.html), and if you use VS Code, there are extensions like https://marketplace.visualstudio.com/items?itemName=rohinivsenthil.postcode. There are also standalone tools like [Hoppscotch](https://hoppscotch.io/), and [Postman](https://www.postman.com/). You can even test your REST API endpoints using the [curl command](https://curl.se/) in your terminal.

For the purposes of this lesson, you'll learn how to use Postman to test some REST API requests.

You can download Postman from the [Postman website](https://www.postman.com/downloads/). By default, Postman will create an initial workspace, to store your request collections.

Once installed, open Postman, and click on the Create Collection button. This will create a new collection, where you can add multiple requests to test.

You can give the collecting a name to differentiate it from other collections. Inside the collection click the Add a request button.

This will open a new request, where you can give the request a unique name.  Then, enter the url to your local `books` endpoint, and click the Send button.

```php
https://learn.test/wp-json/wp/v2/books
```

The request will be made, and the JSON response will be parsed and displayed in the response area.

Now, create a new request, and enter the same url to the `books` endpoint, but this time change the request method to POST, and hit send. By changing the request method to POST, you're telling the server that you want to either create or possibly update a book.

This time you'll be presented with an error message, because you're not authenticated.

To authenticate the request, click on the Authorization tab, and select Basic Auth from the dropdown. 

Then, enter your username and the Application Password you created earlier, then click the Save button.

This time you don't get the same error, because you're not authenticated. So you can create books.

Go ahead and create one, by clicking on the Body tab in the request, and selecting the raw radio button. Then, select JSON from the dropdown, and enter the following JSON:

```json
{
    "title": "My Postman Book",
    "content": "This is my Postman book",
    "status": "publish"
}
``` 

Hit send again, and the book will be created, returning the JSON response of the new post.

To be sure, go ahead and check the post in the WP dashboard, and you should see the book.

To update a post, you use the same request configuration to add a post, but you change the endpoint URL to include the post id. 

To delete a post, you use the same endpoint URL as updating a post, but you change the request method to DELETE, and don't send any data in the request body.

You'll also notice that deleting a post actually moves it to the trash, and doesn't permanently delete it. This matches the behavior of the WordPress dashboard.

Using a tool like Postman to test REST API endpoints is a great way to learn how to use the WP REST API. It's also extremely useful for testing WP REST API requests, by ensuring that the data you intend to send is formatted correctly, and that the request is being made to the correct endpoint.

## Creating a Book

Let's use the WP REST API and api-fetch to create a new post. 

To do so, we'll need to pass the title and content fields to a new post model.

You already have a plugin that allows you to list books, so you can use that as a starting point.

First, you'll need to update the page with a form that will allow you to enter the title and content of the book you want to create. You can use the following HTML to create the form, and add it to the admin page callback:

```html
<div style="width:50%;">
    <h2>Add Book</h2>
    <form>
        <div>
            <label for="bookstore-book-title">Book Title</label>
            <input type="text" id="bookstore-book-title" placeholder="Title">
        </div>
        <div>
            <label for="bookstore-book-content">Book Content</label>
            <textarea id="bookstore-book-content" cols="100" rows="10"></textarea>
        </div>
        <div>
            <input type="button" id="bookstore-submit-book" value="Add">
        </div>
    </form>
</div>
```

This HTML code adds a new form to the custom admin page, that allows you to enter a title and content for the new book. The form also includes a button to submit the form.

With the form added, the next step would be to add the JavaScript that will handle things when the button is clicked:

```js
const submitBookButton = document.getElementById( 'bookstore-submit-book' );
if ( submitPostButton ) {
    submitBookButton.addEventListener( 'click', function () {
        // create post code
    } );
}
```

Now that you have the button click event listener added, you can add the code that will handle the creation of the book. To do this, it's a good idea to create a separate function to create the book, and call that function on the click event.

The first thing you'll need to do is create a submitBook function:

```php
function submitBook() {
    // create book code
}
```

Then update the click event listener to call that function:

```js
submitBookButton.addEventListener( 'click', submitBook );
```

Inside the `submitBook` function, you'll need to get the title and content values from the form fields:

```js
    const title = document.getElementById( 'bookstore-book-title' ).value;
    const content = document.getElementById( 'bookstore-book-content' ).value;
```

Now you can create the request to the books endpoint using api-fetch, by setting the path to the `books` endpoint, setting the request method to `POST` and passing the `title` and `content` as a data object:

```js
    wp.apiFetch( {
        path: '/wp/v2/books/',
        method: 'POST',
        data: {
            title: title,
            content: content
        },
    } ).then( ( result ) => {
        alert( 'Book saved!' );
    } );
```

Open the custom admin page, enter a title and content, and click the Add button. You should see an alert that says "Book saved!".

Then, if you browse to the list of books, you'll see your new book listed.

## Updating and Deleting Posts

You can also use the WP REST API to update and delete posts.

You can use the same api-fetch implementation for updating items as you did for adding items. You need to update the path to include the ID of the data entity being updated (in this case books), so that it updates that item, as well as the updated data object, with the new values for the fields you want to update. 

```js
    wp.apiFetch( {
        path: '/wp/v2/books/' + id,
        method: 'POST',
        data: {
            title: newTitle,
            content: newContent
        },
    } ).then( ( result ) => {
        alert( 'Book Updated!' );
    } );
```

Deleting a post only requires the path to be set to the URL of the item, and setting the method to `DELETE`.

```js
    wp.apiFetch( {
        path: '/wp/v2/books/' + id,
        method: 'DELETE',
    } ).then( ( result ) => {
        alert( 'Book deleted!' );
    } );
```

## POSTing Block Markup

During these examples, you may have noticed how the book content is displayed as a Classic Block. 

This is because you're not passing block markup to the Books model. You can pass block markup to the Books model, for example by wrapping the content in a `wp:paragraph` block tags, but this is beyond the scope of this tutorial.

```html
<!-- wp:paragraph -->
<p>Updated Post Content</p>
<!-- /wp:paragraph -->
```