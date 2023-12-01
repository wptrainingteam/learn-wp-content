# Introducing the HTML API

## A simple image tag

```html
<img src="https://picsum.photos/id/21/640/480" class="default" alt="placeholder" data-image-class="black-and-white" />
```

Problem: All images on a page need to have a class attribute of "new-class" added to them.

Empty plugin:

```php
<?php
/**
 * Plugin Name:       WP Learn HTML API
 * Description:       Dabbling with the new HTML API.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
```

Option 1: JavaScript

```php
/**
 * Enqueue the block's JavaScript file for the front end
 */
add_action('wp_enqueue_scripts', 'wp_learn_html_api_enqueue_scripts');
function wp_learn_html_api_enqueue_scripts() {
	wp_enqueue_script(
		'wp-learn-html-api',
		plugins_url( 'wp-learn-html-api.js', __FILE__ ),
		array(),
		time(),
	);
}
```

Inside the wp-learn-html-api.js file:

```js
document.addEventListener('DOMContentLoaded', function () {
	const images = document.querySelectorAll('img');
	images.forEach(image => image.classList.add('new-class'));
});
```

Problem 1: only triggers once the DOM is loaded, so if any images are added dynamically, they won't be affected.
Problem 2: front end flicker, as the images are loaded, then the JS runs, then the images are updated. This could cause weird visual effects.


Option 2: PHP

Possibility 1: Regex
    
```php
add_filter( 'the_content', 'wp_learn_html_api_the_content' );
function wp_learn_html_api_the_content( $content ) {
	/**
	 * Using Regex
	 */
	$content = preg_replace(
		'~<img(.*?)class="([^"]*)"([^>]*)>~',
		'<img$1 class="$2 new-class"$3>',
		$content
	);
	return $content;
}
```

Pros: fast
Cons: What about images that have a custom attribute that includes the word class. What about images that don't have a class attribute? What about images that use single quotes for attributes. What does this code even do?

Possibility 2: [DOMDocument](https://www.php.net/manual/en/class.domdocument.php)

```php
add_filter( 'the_content', 'wp_learn_html_api_the_content' );
function wp_learn_html_api_the_content( $content ) {
	/**
	 * Using DOMDocument
	 */
	$doc = new DOMDocument();
	$doc->loadHTML($content);
	$images = $doc->getElementsByTagName('img');
	foreach ($images as $image) {
		$image->setAttribute('class', 'new-class');
	}
	$content = $doc->saveHTML();


	return $content;
}
```

Pros: More reliable, more readable
Cons: Slower, DomDocument completely rewrites the entire HTML, instead of just making the simple change needed. You can't just get the first image, you have to loop through all of them. 

[PARTICIPANT CHECK]

Possibility 3: HTML API

```php
add_filter( 'the_content', 'wp_learn_html_api_the_content' );
function wp_learn_html_api_the_content( $content ) {
	$processor = new WP_HTML_Tag_Processor( $content );
	while ( $processor->next_tag( 'img' ) ) {
		$processor->add_class( 'new-class' );
	}
	$content = $processor->get_updated_html();

	return $content;
}
```

Pros: More reliable, more readable, can target by using has_class, faster and less memory intensive than DomDocument, ability to add bookmarks to go back to specific tags 
Cons: Slower than Regex, currently limited set of functionality

Further readingI just
https://developer.wordpress.org/news/2023/09/the-html-api-process-your-tags-not-your-pain/
https://developer.wordpress.org/reference/classes/wp_html_tag_processor/