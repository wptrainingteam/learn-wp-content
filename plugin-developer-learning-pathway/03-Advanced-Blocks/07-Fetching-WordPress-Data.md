# Fetching WordPress Data

Depending on the requirements of the block you are building, you may need to fetch data from the WordPress database. 

This could be data from the posts, comments, users, or terms tables, or custom data from any of the meta tables.

To make this possible, WordPress provides two JavaScript packages which allow you to fetch data from the WordPress REST API.

Let's learn what these packages are, how they work, and how to use them to perform data fetching in your block.

## A quick recap of the WordPress REST API

Before we begin fetching data, let's quickly recap the WordPress REST API.

The WordPress REST API provides an interface for applications to interact with a WordPress site.

It provides a set of REST endpoints (URIs) which represent the data types in a WordPress site. 

Your code can send and receive data as JavaScript Object Notation (JSON) to these endpoints to fetch, modify, and create content on your site.

For a more detailed introduction to the WordPress REST API, take a look at the introduction to the WordPress REST API module of the Beginner Developer Learning Pathway, starting with the lesson on [The WordPress REST API](https://learn.wordpress.org/lesson/the-wordpress-rest-api/). 

You can also read up about it in the [WordPress REST API Handbook](https://developer.wordpress.org/rest-api/).

## Scaffold a new block

To start, let's scaffold a new block using the `@wordpress/create-block` tool. 

If you haven't already used this tool, please follow the lesson on [Setting up your block development environment](https://learn.wordpress.org/lesson/setting-up-your-block-development-environment/) in the Beginner Developer Learning Pathway.

```bash
cd wp-content/plugins/
npx @wordpress/create-block@latest wp-learn-fetching-data
```

Once the block code has been scaffolded start the development server to watch the files for any changes and trigger the build step.

```bash
cd wp-learn-fetching-data
npm start
```

Last but not least, activate the plugin in the WordPress admin area.

The two packages we will be using to fetch data are `@wordpress/api-fetch` and `@wordpress/data`.

## Fetching data with `@wordpress/api-fetch`

Let's first look at using the `@wordpress/api-fetch` package. Open the `src/edit.js` file in your code editor.

Because you're in an ESNext environment, you can import the `apiFetch` function from the `@wordpress/api-fetch` package.

```js
import apiFetch from '@wordpress/api-fetch';
```

If you were writing this block code in a non ESNext environment, you could use the `wp.apiFetch` global variable.

```js
const apiFetch = wp.apiFetch;
```

Either way, to fetch a list of posts, you would use the `apiFetch` function and pass it an object of request options. 

Let's fetch a list of posts when you add the block to the editor.

At minimum, you need to provide the `path` option, which is the REST API endpoint you want to fetch data from.

```js
apiFetch( { path: '/wp/v2/posts' } );
```

This will return a JavaScript Promise that resolves with the response from the REST API.

Because a Promise is the eventual result of an asynchronous operation, you can use the `.then()` method to handle the response.

```js
apiFetch( { path: '/wp/v2/posts' } )
    .then( ( posts ) => {
        console.log( posts );
    } );
```

With the plugin activated this will log the list of posts to the browser console when the block is added to the editor.

Because the code needs to wait for the Promise to be resolved, in order to add the list of users to the JSX returned by your block, you would need to implement some React hooks.

Learning about React hooks is outside of the scope of this lesson, but you can read more about them in the [React documentation on hooks](https://react.dev/reference/react/hooks).

React hooks are very much like WordPress hooks, in that they allow you to use other React features in your function components.

Here's an example of how you could use two React hooks with `apiFetch` to fetch a list of post and display their post titles in an unordered list in your block.

```js
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect } from '@wordpress/element';
```

```js
export default function Edit() {

	const [posts, setPosts] = useState([]);

	useEffect(() => {
		apiFetch( { path: '/wp/v2/posts' } ).then( ( posts ) => {
			setPosts(posts);
		} );
	}, []);

	if ( ! posts ) {
		return (
			<p { ...useBlockProps() }>
				{ __(
					'Loading...',
					'wp-learn-fetching-data'
				) }
			</p>
		);
	}
		
	let postsList = posts.map( ( post ) => {
		return <li key={ post.id }>{ post.title.rendered }</li>;
	});

	return (
		<p { ...useBlockProps() }>
			{ __(
				'Wp Learn Fetching Data – hello from the editor!',
				'wp-learn-fetching-data'
			) }
			<ul>
				{ postsList }
			</ul>
		</p>
	);
}
```

Here, the `useState` [hook](https://react.dev/reference/react/useState) hook is used to create a state variable called `posts` and a function called `setPosts` to update the `posts` state variable.

Then, the `useEffect` [hook](https://react.dev/reference/react/useEffect) hook is used to fetch a list of posts from the REST API when the block is added to the editor. 

Once the posts are fetched, the `setPosts` function is called to update the `posts` state variable.

If the `posts` state variable is empty, a loading message is displayed in the block. 

The `posts` state variable is mapped to an unordered list of posts, which is returned by the block.    

By using these two hooks, when the apiFetch Promise is resolved, the `users` state variable inside the unordered list is updated with the list of users and displayed in the block.

## Fetching data with `@wordpress/data`

Dealing with these React hooks can be a bit complex, especially if you're new to React development.

To simplify this process, WordPress provides the `@wordpress/data` package, which allows you to fatch data and manage the state of your block in a more predictable way. 

To use the `@wordpress/data` package to fetch core WordPress data, you can import the `useSelect` hook from the package, as well as the `store` from the `@wordpress/core-data` package.

```js
import { useSelect } from '@wordpress/data';
import { store as coreDataStore } from '@wordpress/core-data';
```

Then you can use the `useSelect` hook, the `coreDataStore`, and the `getEntityRecords` selector to fetch the data you need, and return it to your block.

```js
export default function Edit() {

	const posts = useSelect(
		select =>
			select( coreDataStore ).getEntityRecords( 'postType', 'post' ),
		[]
	);

	if ( ! posts ) {
		return (
			<p { ...useBlockProps() }>
				{ __(
					'Loading...',
					'wp-learn-fetching-data'
				) }
			</p>
		);
	}

	let postsList = posts.map( ( post ) => {
		return <li key={ post.id }>{ post.title.rendered }</li>;
	});

	return (
		<p { ...useBlockProps() }>
			{ __(
				'Wp Learn Fetching Data – hello from the editor!',
				'wp-learn-fetching-data'
			) }
			<ul>
				{ postsList }
			</ul>
		</p>
	);
}
```

One of the benefits of using this method, is that if you wanted to get a different set of entity records, for example if you had a custom post type called book, you could just update the `getEntityRecords` selector to `'postType', 'book'`.

```js
select( coreDataStore ).getEntityRecords( 'postType', 'post' ),
```

The other advantage is that you don't need know which REST API endpoint to call, or how to handle the response. The `@wordpress/data` package takes care of all of that for you.

## Further reading

You can read more about the `@wordpress/api-fetch` package in the [WordPress API Fetch package reference](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-api-fetch/) and the `@wordpress/data` package in the [WordPress core data package reference](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-core-data/).