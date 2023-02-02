# Interacting with the WordPress REST API

## Objectives

Upon completion of this lesson the participant will be able to:

- Create Posts using the WP REST API
- Update Posts using the WP REST API
- Delete Posts using the WP REST API
- Locate additional information about the using WP REST API

## Outline

- Review WP REST API Schema
- Creating a Post via the WP REST API
- Updating a Post via the WP REST API
- Deleting a POst via the WP REST API
- Where to go for more information

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn about interacting with the WordPress REST API, using the Backbone.js client that ships with WordPress.

This video will cover the WP REST API Schema, and then you'll learn how to create, update, and delete WordPress data.

If this is your first time working with the WP REST API, I recommend watching the Using the WordPress REST API tutorial. This will introduce you to using the WP REST API, with code examples.

Alternatively, download the example code from that tutorial from this URL, and use it to follow this tutorial.

## WP REST API Schema

When working with the REST API, it's useful to keep the [Endpoint Reference](https://developer.wordpress.org/rest-api/reference/) section of the WP REST API documentation handy. The Endpoint Reference lists all endpoints that ship with WordPress core. 

Clicking on an individual endpoint, say [Posts](https://developer.wordpress.org/rest-api/reference/posts/), will show you the schema for that endpoint. The schema defines all the fields that exist for a resource when fetching or creating data of that specific type.

You will notice that many of the endpoint fields match up with the fields that are available in the WordPress database table related to that data type. Some however, are slightly different. For example, the `title` field for the Post endpoint will match up to the post_title field in the posts table. It is important to remember that these differences exist, and to use the correct field name when interacting with the API.

For example, when fetching the posts via the API using the built-in Backbone.js client, you would filter by the `title` field, and not `post_title`:

```js
allPosts.fetch(
    { data: { "_fields": "title" } }
)
```

Similarly, when looping through a list of posts returned from the WP REST PAI, you would use post.title.rendered, as the title field on the post is an object, and the rendered property of that object is the actual content of the post's title.

```js
done( function ( posts ) {
    const textarea = document.getElementById( 'wp-learn-posts' );
    posts.forEach( function ( post ) {
        textarea.value += post.title.rendered + '\n'
    } );
} );
```

## Creating a Post

Let's use the WP REST API it to create a new post. To do so, we'll need to pass the title and content fields to a new post model.   

You already have a plugin that allows you to list posts, so you can use that as a starting point.

First, you'll need to update the page with a form that will allow you to enter the title and content of the post you want to create. You can use the following HTML to create the form, and add it to the admin page callback:

```html
<div style="width:50%;">
    <h2>Add Post</h2>
    <form>
        <div>
            <label for="wp-learn-post-title">Post Title</label>
            <input type="text" id="wp-learn-post-title" placeholder="Title">
        </div>
        <div>
            <label for="wp-learn-post-content">Post Content</label>
            <textarea id="wp-learn-post-content" cols="100" rows="10"></textarea>
        </div>
        <div>
            <input type="button" id="wp-learn-submit-post" value="Add">
        </div>
    </form>
</div>
```

This html code adds a new form to the admin page, that:
    - has a title of "Add Post"
    - opens a new form element
    - includes a text input for the title with the id attribute of wp-learn-post-title
    - includes a textarea for the content with the id attribute of wp-learn-post-content
    - has a button to submit the form with the id attribute of wp-learn-submit-post

Here is what the form looks like when it's rendered in the admin page:

[screen of form]

With the form added, the next step would be to add the JavaScript that will handle things when the button is clicked:

```js
const submitPostButton = document.getElementById( 'wp-learn-submit-post' );
if ( submitPostButton ) {
    submitPostButton.addEventListener( 'click', function () {
        // create post code
    } );
}
```

Now that you have the button click event listener added, you can add the code that will handle the creation of the post. To do this, it's a good idea to create a separate function to create the post, and call that function on the click event. 

The first thing you'll need to do is create the submitPost function:

```php
function submitPost() {
    // create post code
}
```

Then update the click event listener to call that function:

```js
submitPostButton.addEventListener( 'click', submitPost );
```

Inside the `submitPost` function, you'll need to get the title and content values from the form fields:

```js
    const title = document.getElementById( 'wp-learn-post-title' ).value;
    const content = document.getElementById( 'wp-learn-post-content' ).value;
```

Next, you'll need to create a new post model object, using [the Backbone.js Post model](https://developer.wordpress.org/rest-api/using-the-rest-api/backbone-javascript-client/#model-examples). You will then need to pass the values for the title and content to the model object:

```js
    const post = new wp.api.models.Post( {
        title: title,
        content: content,
    } );
```

Finally, you'll need to send the request to the Posts route to save the data, using the Posts model's `save` method. You can also add a `done` callback to handle the response once the post is saved:

```js
post.save().done( function ( post ) {
    alert( 'Post saved!' );
} );
```

If you refresh the admin page, and enter a title and content, and click the Add button, you should see an alert that says "Post saved!".

If you used the plugin example from the previous tutorial, you might now want to click the `loadPosts` button to load the posts. Notice however that the post doesn't appear. This is because the default value for the status field on the Post model is `draft`. You can change this by adding the status field to the post model object:

```js
    const post = new wp.api.models.Post( {
        title: title,
        content: content,
        status: 'publish',
    } );
```

Create the new post, hit the `loadPosts` button, and you should see the new post in the list.

## Updating Posts

You can also update Posts in the same way as creating posts. The main difference is that you also need to pass the post id to the Post model, so that it knows which post to update.

First, in the PHP file for the plugin, you'll need to add a form to manage handling updates. For this, you can simply copy the code that's used to create posts, but update the form field ids, add a field for the Post's id, and change the button text to Update:

```html
<div style="width:50%;">
    <h2>Update Post</h2>
    <form>
        <div>
            <label for="wp-learn-update-post-id">Post ID</label>
            <input type="text" id="wp-learn-update-post-id" placeholder="ID">
        </div>
        <div>
            <div>
                <label for="wp-learn-update-post-title">Post Title</label>
                <input type="text" id="wp-learn-update-post-title" placeholder="Title">
            </div>
            <div>
                <label for="wp-learn-update-post-content">Post Content</label>
                <textarea id="wp-learn-update-post-content" cols="100" rows="10"></textarea>
            </div>
            <div>
                <input type="button" id="wp-learn-update-post" value="Update">
            </div>
    </form>
</div>
```

That's all that's needed in the PHP side, so you can switch over to the JavaScript file. The next step is to add a function to handle the Post update. This will be very similar to the current `submitPost` function, but will need to update the various element ids, and pass the post id to the Post model. You can also leave out the status for this example. 

```js
function updatePost() {
    const id = document.getElementById( 'wp-learn-update-post-id' ).value;
    const title = document.getElementById( 'wp-learn-update-post-title' ).value;
    const content = document.getElementById( 'wp-learn-update-post-content' ).value;
    const post = new wp.api.models.Post( {
        id: id,
        title: title,
        content: content,
    } );
    post.save().done( function ( post ) {
        alert( 'Post Updated!' );
    } );
}
```

Finally, you need to add a click handler to the Update button, and call the updatePost function when it's clicked:

```js
const updatePostButton = document.getElementById( 'wp-learn-update-post' );
if ( updatePostButton ) {
	updatePostButton.addEventListener( 'click', updatePost );
}
```

The last thing you'll need to do is update `loadPosts` functionality to include the id in the list:

```js                                                                                                 
allPosts.fetch(                                                                                       
    { data: { "_fields": "id, title" } }                                                              
).done( function ( posts ) {                                                                          
    const textarea = document.getElementById( 'wp-learn-posts' );                                     
    posts.forEach( function ( post ) {                                                                
        textarea.value += post.id  + ', ' +  post.title.rendered + '\n'                               
    } );                                                                                              
} );                                                                                                  
```                                                                                                   

Go ahead and test this out in your browser, refresh the admin page and load the posts. 

Then enter an id, updated post title and content, and click Update.

Once the post has been updated, reload the list of posts, to confirm the content has been updated. You can also check the Post in the WordPress admin to confirm the changes.

Notice how the post content is displayed as a Classic Block. This is because in this simple example we're not passing block markup to the Post model. You can pass block markup to the Post model, for example by wrapping the content in a `wp:paragraph` block tags, but this is beyond the scope of this tutorial.

```html
<!-- wp:paragraph -->
<p>Updated Post Content</p>
<!-- /wp:paragraph -->
```

## Deleting a Post

Now that you know how to create and update a post, let's take a look at how to delete a post.

First, you'll need to add a form to the admin page callback that will allow you to enter the ID of the post you want to delete, as well as a button to trigger the delete:

```html
<div style="width:50%;">
    <h2>Delete Post</h2>
    <form>
        <div>
            <label for="wp-learn-post-id">Post ID</label>
            <input type="text" id="wp-learn-post-id" placeholder="ID">
        </div>
        <div>
            <input type="button" id="wp-learn-delete-post" value="Delete">
        </div>
    </form>
</div>
```

Then, as before, set up the click event listener for the button, as well as the function to handle the deletion:

```js
const deletePostButton = document.getElementById( 'wp-learn-delete-post' );
if ( typeof ( deletePostButton ) != 'undefined' && deletePostButton != null ) {
    deletePostButton.addEventListener( 'click', deletePost );
}
```

```php
function deletePost() {
    // code to delete posts 
}
```

Now that you have the button click event listener added, you can add the code that will handle the deletion of the post. The first thing you'll need to do is get the value from the form field:

```js
const id = document.getElementById( 'wp-learn-post-id' ).value;
```

Next, you'll need to create a new post model object, using [the Backbone.js Post model](https://developer.wordpress.org/rest-api/using-the-rest-api/backbone-javascript-client/#model-examples):

```js
const post = new wp.api.models.Post( { id: id } );
```

Finally, you'll need to delete the post from the database, using the Posts model's `destroy` method. You can also add the `done` callback to handle the response once the post is deleted:

```js
post.destroy().done( function ( post ) {
    alert( 'Post deleted!' );
} );
```

Now, refresh the page, and load the posts

Then, grab one of the post ids, paste it in the ID field for the delete post form, and hit the delete button. 

The post should be deleted, and you can use the load posts button to confirm the post has been deleted, or check the list of posts in the WordPress admin.

For more information in using and interacting with the WP REST API, as well as how to extend it, check out the [WP REST API Handbook](https://developer.wordpress.org/rest-api/) at developer.wordpress.org.

Happy coding