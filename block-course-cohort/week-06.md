## Implementing the save function

## The save function

Because the save function is merely saving the output, you don't need to re-run the `getEntityRecords` selector using the `useSelect` hook. Instead, you can just use the `select` function on the `core` store, and fetch the books that are already in the store.

At the top of your save.js file, import the `select` function from the `@wordpress/data` package.

```js
import { select } from '@wordpress/data';
```

Then, inside the save function create the `books` variable and use the `select` function to fetch the books from the `core` store.

```js
const books = select( 'core' ).getEntityRecords( 'postType', 'book' );
```

Then, you can use the same books.map method to loop through the books and return the markup for each book.

Remember to first update the existing markup to change the parent <p> to a <div>, and wrap the text in a <p>.

```js
return (
	<div {...useBlockProps.save()}>
		<p>{'My Reading List – hello from the saved content!'}</p>
	</div>
);
```

Then, add the books.map method to loop through the books and return the markup for each book.

```js
return (
	<div {...useBlockProps.save()}>
		<p>{'My Reading List – hello from the saved content!'}</p>
		{ books.map( ( book ) => (
			<div>
				<h2>{ book.title.rendered }</h2>
				<img src={ book.featured_image_src } />
				<div dangerouslySetInnerHTML={ { __html: book.content.rendered } }></div>
			</div>
		) ) }
	</div>
);
```

Now, before you refresh your browser, remove the block from the post or page you added it to, and either save the draft, or update the page.

This is because when ever you hit refresh, the block already in the editor perform a check to compare the output being rendered by the save method, with the output that was saved in the database. If the output is different, the block will be marked as invalid, and you'll see an error in the editor.

So whenever you make changes to your block's save function, it's a good idea to first remove the block from wherever you are testing it on, save content, hit refresh, and then add the block again.

[!INFO] For the rest of this lesson, when you're testing your block's functionality, make sure to remove the block from the post or page you're testing it on, save the content, then refresh the browser before adding the block again.

Once you've done this, you should see the same output in the editor that you saw earlier. However, if you preview the post or page, you'll see that the books are now being displayed.