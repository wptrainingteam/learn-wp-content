# Testing your plugins for PHP version compatibility

# Learning Objectives

Upon completion of this lesson the participant will be able to:

## Outline

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn about testing your WordPress plugins for PHP version compatibility.

You will learn why it's important to test for PHP version compatibility, where to find information about PHP version changes, as well as two methods to test your plugins.

## Why test for PHP version compatibility?

WordPress is written in PHP, and as such, it needs to be able to run on at least the minimum supported version of PHP that is available to web hosts. While WordPress has a minimum requirement of PHP 7.4, PHP 7.4 is officially considered end of life by the PHP developers, and will not receive any security updates in the near future. 

WordPress core itself is considered compatible with PHP 8.0, and the WordPress core team is working on making WordPress compatible with PHP 8.1 and PHP 8.2. However, they cannot guarantee that all plugins will be compatible with current or future versions of PHP.

As a plugin developer, it's therefore important to have a process in place to test your plugins for PHP version compatibility.

## Where to find information on PHP version changes

In order to know when and how PHP versions are going to change, it's a good idea to refer to the official PHP website at https://www.php.net/. 

On the [Supported Versions](https://www.php.net/supported-versions.php) page, you can find information about which versions are currently supported, at what level of support, and which versions are end of life.

At the time of this recording, all PHP 7.x versions or end of life, PHP 8.0 is supported for security fixes only, and PHP 8.1 and PHP 8.2 are actively supported, meaning bug and security flaws will be fixed. Note that PHP 8.0 will only be supported for security fixes till November 2023, which is around the time PHP 8.4 will be released, and then PHP 8.0 will be considered end of life. 

In the Appendices section of the PHP documentation you can find the guides on migrating from older PHP versions, which list all the changes between the old version and the new one. For example, the [Migrating from PHP 7.4.x to PHP 8.0.x](https://www.php.net/manual/en/migration80.php) guide lists all the changes between PHP 7.4 and PHP 8.0.

## Example plugin

For the purposes of this tutorial, let's imagine you've developed a simple plugin. 

```php
/**
 * Plugin Name: WP Learn PHP8
 * Description: Learn to get a plugin ready for PHP 8
 * Version: 1.0.0
 */

/**
 * Posts fetcher class
 */
class post_fetcher {

	protected $posts;

	public function post_fetcher() {
		$this->posts     = get_posts();
	}

	public function fetch_posts() {
		$post_html = '<div class="post">';
		foreach ( $this->posts as $post ) {
			if ( array_key_exists( 'post_title', $post ) ) {
				$post_html .= sprintf(
					'<h4><a href="%s">%s</a></h4>',
					get_permalink( $post->ID ),
					$post->post_title
				);
			}
		}
		$post_html .= '</div>';
		return $post_html;
	}
}

/**
 * Shortcode to render posts
 * Uses the post_fetcher class
 */
add_shortcode( 'wp_learn_php8', 'wp_learn_php8_shortcode_render' );
function wp_learn_php8_shortcode_render() {
	$post_fetcher = new post_fetcher();
	$post_html = $post_fetcher->fetch_posts();
	return $post_html;
}
```

The plugin registers a shortcode, which fetches a list of posts and displays the post title of each post whenever the shortcode is used. The post_fetcher class handles the fetching of the posts.

Testing the shortcode on a page, you can see that it works as expected when running PHP 7.4.

## How to test for PHP version compatibility

There are a few ways to test for PHP version compatibility, which require different combinations of newer PHP versions and installation of various tools. For the purposes of this tutorial, we will look at one manual way, and one automated tool.

### Manual compatibility testing

The manual method involves you setting up a WordPress environment with the PHP version you want to test for, and then testing your plugin in that environment. 

Setting up this environment can be done in a few ways, but the most common option would be to use a local development environment that supports changing PHP version, such as Mamp, Laragon, LocalWP, and DevKinsta.

For the purposes of this example we'll test on PHP 8.0. A quick way to check that you're on the right version, is you create an info.php file in the root of your WordPress install, and use the following code:

```php  
<?php
phpinfo();
```

Then, navigate to the info.php file in your browser, and you should see the PHP version displayed.

Once you have your test environment set up, you need to enable WP debugging. 

To do this, edit the `wp-config.php` file, and update the line which defines the `WP_DEBUG` constant, setting it to `true`

```php
define( 'WP_DEBUG', true );
```

Additionally, disable the `WP_DEBUG_DISPLAY` constant and enable the `WP_DEBUG_LOG` constant, so that errors are logged to a `debug.log` file in the `wp-content` directory.

```php
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );
```

Then, test your plugin, by refreshing the page. Notice that the shortcode functionality breaks.

If you look at the debug.log, you'll see the following error displayed:
    
```
[16-May-2023 12:07:35 UTC] PHP Warning:  foreach() argument must be of type array|object, null given in /home/ubuntu/wp-local-env/sites/learnpress/wp-content/plugins/wp-learn-php8/wp-learn-php8.php on line 21
```

Now, if you go to line 21 of the plugin file, you'll see the following code, you'll see that it's trying to loop through the `$this->posts` property, which is null for some reason. The reason might not be immediately obvious, so you might have to dig into the [Migrating from PHP 7.4.x to PHP 8.0.x](https://www.php.net/manual/en/migration80.php) guide.

In the Backward Incompatible Changes section, you see the following change:

```
Methods with the same name as the class are no longer interpreted as constructors. The __construct() method should be used instead.
```

So in this case, our class constructor method needs to be updated.

Once that's fixed, refresh the page, and you'll see that a more serious error has occured. 

Time to check the log.

This time we have a new error:

```
[16-May-2023 12:14:59 UTC] PHP Fatal error:  Uncaught TypeError: array_key_exists(): Argument #2 ($array) must be of type array, WP_Post given in /home/ubuntu/wp-local-env/sites/learnpress/wp-content/plugins/wp-learn-php8/wp-learn-php8.php:22
Stack trace:
#0 /home/ubuntu/wp-local-env/sites/learnpress/wp-content/plugins/wp-learn-php8/wp-learn-php8.php(42): post_fetcher->fetch_posts()
#1 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/shortcodes.php(355): wp_learn_php8_shortcode_render()
#2 [internal function]: do_shortcode_tag()
#3 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/shortcodes.php(227): preg_replace_callback()
#4 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/class-wp-hook.php(308): do_shortcode()
#5 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/plugin.php(205): WP_Hook->apply_filters()
#6 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/blocks/post-content.php(54): apply_filters()
#7 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/class-wp-block.php(258): render_block_core_post_content()
#8 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/class-wp-block.php(244): WP_Block->render()
#9 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/blocks.php(1051): WP_Block->render()
#10 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/blocks.php(1089): render_block()
#11 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/block-template.php(240): do_blocks()
#12 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/template-canvas.php(12): get_the_block_template_html()
#13 /home/ubuntu/wp-local-env/sites/learnpress/wp-includes/template-loader.php(106): include('...')
#14 /home/ubuntu/wp-local-env/sites/learnpress/wp-blog-header.php(19): require_once('...')
#15 /home/ubuntu/wp-local-env/sites/learnpress/index.php(17): require('...')
#16 {main}
  thrown in /home/ubuntu/wp-local-env/sites/learnpress/wp-content/plugins/wp-learn-php8/wp-learn-php8.php on line 22
```

If you look at that line in the plugin, you'll see that it's trying to use the `array_key_exists()` function on the `$post` variable inside the foreach loop, but this variable is an object, not an array.

If you look at the Backward Incompatible Changes section of the migration guide, and search for `array_key_exists`, you'll see the following change:

```
The ability to use array_key_exists() with objects has been removed. isset() or property_exists() may be used instead.
```

This means that previously it was possible to use array_key_exists() on an object, but now it's not. So we need to update our code to use `property_exists()` instead.

```php
property_exists( $post, 'post_title' )
```

If you refresh the page, you'll see the shortcode is now working again.

### Automated compatibility testing using PHPCompatibility

There are a number of automated or command line tools that you can use to test for PHP Compatibility, but one that is quite useful is the PHPCompatibility tool, which is a set of rules for the PHP_CodeSniffer tool.

What's great about PHPCompatibility is that you don't have to configure a different PHP version to use it. You can use it with your existing PHP version, and it will check your code against the rules for the PHP version you specify.

To install PHPCompatibility, you need to install Composer, which is a dependency manager for PHP. 

Installing Composer is outside the scope of this lesson, but you can find instructions on the [Composer website](https://getcomposer.org/) for both [macOS/Linux](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) and [Windows](https://getcomposer.org/doc/00-intro.md#installation-windows) operating systems.

Once you have Composer installed, you can install PHPCompatibility and the PHP_CodeSniffer with the WordPress coding standards by running the following command in your plugin directory:

``` bash
composer init
```

This will initialise a new Composer project in your plugin directory. You can accept the defaults for most of the questions, but when it asks you to define your dependencies (require) interactively and define your dev dependencies (require-dev) interactively?, you should answer no. You can also skip the PSR-4 autoload mapping.

If you're already using composer for your plugin, you can skip this step.

Next, you need to install the PHPCompatibility, which installs PHP_CodeSniffer, and the WordPress coding standard rules for PHP_CodeSniffer:

```bash
composer require --dev phpcompatibility/php-compatibility:"dev-develop"
composer require --dev wp-coding-standards/wpcs
```

With all this installed, you can run the PHPCompatibility tool on your plugin file:

```bash 
./vendor/bin/phpcs -p wp-learn-php8.php --standard=PHPCompatibility
```

This is what the output looks like 

```bash
E 1 / 1 (100%)



FILE: /Users/jonathanbossenger/wp-local-env/sites/learnpress/wp-content/plugins/wp-learn-php8/wp-learn-php8.php
------------------------------------------------------------------------------------------------------------------
FOUND 1 ERROR AFFECTING 1 LINE
------------------------------------------------------------------------------------------------------------------
15 | ERROR | Declaration of a PHP4 style class constructor is deprecated since PHP 7.0 and removed since PHP 8.0
------------------------------------------------------------------------------------------------------------------

Time: 46ms; Memory: 10MB
```

Notice how the same error is reported, but this time it's a lot more specific. It tells us exactly what line the error is on, and what the error is.

So now we can fix the class constructor error.

However, notice that the second `array_key_exists` error is not reported. This is because the PHPCompatibility tool is an open source project that relies on contributions, based on the changes in PHP versions, and sadly the [array_key_exists removal has not yet been added](https://github.com/PHPCompatibility/PHPCompatibility/issues/808).

#### Pros and Cons of using PHPCompatibility

As noted, one of the downsides of using something like PHPCompatibility is that it's not always up to date with the latest changes in PHP versions. Additionally, it requires familiarity with the command line, which not all developers may have.

However, it does have some benefits, such as being able to scan your entire codebase, without needing to install and configure additioanl PHP versions, and being able to automate the process.

As such, combining something like PHPCompatibility with the manual testing process we've already discussed, is a good step closer to ensuring your plugin is compatible with current and future versions of PHP.