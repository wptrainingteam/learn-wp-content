# Implementing the WP REST API

# Objectives

After completing this lesson, participants will be able to:
- Create a new post using the WP REST API
- Delete a post using the WP REST API

# Prerequisite Skills

Participants will get the most from this lesson if they have familiarity with:
- PHP
- JavaScript
- The WP REST API
- The Backbone.js client to interact with the WP REST API

# Readiness Questions

A list of questions for participants to see if they have the background and skills necessary to learn and understand the lesson.

- Do you have a basic understanding of the WP REST API?
- Do you have a basic understanding of Backbone.js?

# Materials Needed

A list of files, resources, equipment, or other materials the presenter will need for the lesson.

- A WordPress site running the latest version of WordPress
- A WordPress plugin that fetches posts from the WP REST API (download here)
- A text editor

# Notes for the presenter

A list of any handy tips or other information for the presenter.

- This lesson is intended to be a follow up to the Using the WordPress REST API tutorial. It is recommended that learners watch/take part in a lesson for that tutorial first, and then come back here.

# Lesson Outline

The plan for the lesson. Outline form works well.

- Introduction
- WP REST API Schema
- Creating a Post
- Deleting a Post
- Summary

# Exercises

These are short or specific activities that help participants practice certain components of the lesson. They should not be fully scripted exercises, but rather something that participants could do on their own. For example, you can create an exercise based on one step of the Example Lesson.

# Assessment

A few questions to ask participants to evaluate their retention of the material presented. They should be a measure of whether the objectives were reached. Consider having a question for each objective. There should be one assement item (or more) for each objective listed above. Each assessment item should support an objective; there should be none that don't.

# Additional Resources

An optional section that can contain a list of resources that the presenter can use to get more information on the topic.

# Example

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn all about interacting with the WordPress REST API to create and delete WordPress data.

If this is your first time working with the WP REST API, I recommend watching the Using the WordPress REST API tutorial first, and then come back here.

## WP REST API Schema

The WordPress Developer Documentation on the WP REST API has an entire section dedicated to the [endpoints](https://developer.wordpress.org/rest-api/reference/) available to the REST API. These are the endpoints that ship with core WordPress. 

Clicking on an individual endpoint, say [Posts](https://developer.wordpress.org/rest-api/reference/posts/), will show you the schema for that endpoint. The schema defines all the fields that exist for when fetching or creating data of that specific type.

You will notice that many of the endpoint fields match up with the fields that are available in the WordPress database table related to that data type. Some however, are slightly different. For example, the `title` field for the Post endpoint will match up to the post_title field in the posts table. It is important to remember that these differences exist, and to use the correct field name when interacting with the API.

For example, in the Using the WordPress REST API tutorial, when fetching the posts via the API, you would filter by the title field, not post_title:

```js
allPosts.fetch(
    { data: { "_fields": "title" } }
)
```

Similarly, the original admin-ajax response contained an array of posts, and when iterating through the posts to get the title, you would have used post.post_title, to build the list of posts: 

```js
function ( posts ) {
    const textarea = $( '#wp-learn-posts' );
    posts.forEach( function ( post ) {
        textarea.append( post.post_title + '\n' )
    } );
},
```

However, when using the WP REST API, you would use post.title.rendered, as the title field on the post is an object, and the rendered property of that object is the actual content of the posts title.

```js
done( function ( posts ) {
    const textarea = document.getElementById( 'wp-learn-posts' );
    posts.forEach( function ( post ) {
        textarea.value += post.title.rendered + '\n'
    } );
} );
```

When working with the WP REST API, it's always a good idea to keep the schema documentation for the specific endpoint you are working with at hand.

## Creating a Post

Let's take what you've learned about the WP REST API, and use it to create a new post.

You can either create a new plugin to test this out, or download the plugin that was created in the Using the WordPress REST API tutorial.

First, you'll need to create a form that will allow you to enter the title and content of the post you want to create. You can use the following HTML to create the form, and add it to an admin page callback:

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

With the form added, the next step would be to add the JavaScript that will handle things when the button is clicked:

```js
const submitPostButton = document.getElementById( 'wp-learn-submit-post' );
if ( typeof ( submitPostButton ) != 'undefined' && submitPostButton != null ) {
    submitPostButton.addEventListener( 'click', function () {
        // create post code
    } );
}
```

Now that you have the button click event listener added, you can add the code that will handle the creation of the post. The first thing you'll need to do is get the values from the form fields:

```js
    const title = document.getElementById( 'wp-learn-post-title' ).value;
    const content = document.getElementById( 'wp-learn-post-content' ).value;
```

Next, you'll need to create a new post model object, using [the Backbone.js Post model](https://developer.wordpress.org/rest-api/using-the-rest-api/backbone-javascript-client/#model-examples):

```js
    const post = new wp.api.models.Post( {
        title: title,
        content: content,
    } );
```

If you log the model to the console, you'll notice that the new Post modal has been created using the default values from the WP REST API schema, but updated with the field values you've entered in the form:

```js
    console.log( post );
```

This doesn't mean the post is created, but it does show what data will be sent to the API when you call the save method on the model. This is one of the benefits of using the Backbone.js client, as it can, for example, already predict what the next id for the new post will be.

Finally, you'll need to save the post to the database, using the Posts model's save method. You can also add a `done` callback to handle the response once the post is saved:

```js
post.save().done( function ( post ) {
    console.log( post );
    alert( 'Post saved!' );
} );
```

If you used the plugin example from the previous tutorial, you might now want to click the `loadPosts` button to load the posts. Notice however that the post doesn't appear. This is because the default value for the status field on the Post model is `draft`. You can change this by adding the status field to the post model object:

```js
    const post = new wp.api.models.Post( {
        title: title,
        content: content,
        status: 'publish',
    } );
```

Create the new post, hit the `loadPosts` button, and you should see the new post in the list.

## Deleting a Post

Now that you know how to create a post, let's take a look at how to delete a post.

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

Then, as before, set up the click event listener for the button:

```js
const deletePostButton = document.getElementById( 'wp-learn-delete-post' );
if ( typeof ( deletePostButton ) != 'undefined' && deletePostButton != null ) {
    deletePostButton.addEventListener( 'click', function () {
        
    } );
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

Finally, you'll need to delete the post from the database, using the Posts model's `destroy` method. You can also add a `done` callback to handle the response once the post is deleted:

```js
post.destroy().done( function ( post ) {
    alert( 'Post deleted!' );
} );
```

To get a list of the ids, you can update the `loadPosts` functionality to include the id in the list:

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

Now, if you enter an id in the delete field, and hit the delete button, then the `loadPosts` button, you should see that the post has been deleted.