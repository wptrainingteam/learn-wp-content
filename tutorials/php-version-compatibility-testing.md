# Testing your plugins for PHP version compatibility

# Learning Objectives

Upon completion of this lesson the participant will be able to:

Explain why it's important to test for PHP version compatibility
Identify where to find information about PHP version changes
Demonstrate how to test manually for PHP version compatibility
Execute scanning of code for PHP version compatibility using PHPCompatibilityWP

## Outline

1. Introduction
2. Why test for PHP version compatibility?
3. Where to find information on PHP version changes
4. Example plugin
5. How to test for PHP version compatibility
    1. Manual compatibility testing
    2. Scanning your code using PHPCompatibilityWP
    3. A note on PHPCompatibility versions.
        1. Considerations

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn about testing your WordPress products for PHP version compatibility.

You will learn why it's important to test for PHP version compatibility, where to find information about PHP version changes, as well as two methods to test your plugins and themes for PHP version compatibility.

## Why test for PHP version compatibility?

WordPress is written in PHP, and as such, it needs to be able to run on at least the minimum supported version of PHP that is available to web hosts. While WordPress recommends a specific minimum version of PHP, older PHP versions will eventually reach end of life, and will not receive any security updates in the near future. 

For example, the current minimum recommended PHP version to run WordPress is 7.4, which reached end of life status on the 28th November 2022

WordPress core itself is considered compatible (with select explicit exceptions) with PHP 8.0 and PHP 8.1 and beta-compatible with PHP 8.2 and the upcoming PHP 8.3 release. However, they cannot guarantee that all plugins will be compatible with current or future versions of PHP.

As a plugin developer, it's therefore important to have a process in place to test your plugins for PHP version compatibility.

## Where to find information on PHP version changes

In order to know when and how PHP versions are going to change, it's a good idea to refer to the official PHP website at https://www.php.net/. 

On the [Supported Versions](https://www.php.net/supported-versions.php) page, you can find information about which versions are currently supported, at what level of support, and which versions are end of life.

At the time of this recording, all PHP 7.x versions are end of life, PHP 8.0 is supported for security fixes only, and PHP 8.1 and PHP 8.2 are actively supported, meaning bug and security flaws will be fixed. Note that PHP 8.0 will only be supported for security fixes till November 2023, which is around the time PHP 8.4 will be released, and then PHP 8.0 will be considered end of life. 

In the Appendices section of the PHP documentation you can find the guides on migrating from older PHP versions, which list the most important changes between the old version and the new one. For example, the [Migrating from PHP 7.4.x to PHP 8.0.x](https://www.php.net/manual/en/migration80.php) guide lists all the changes between PHP 7.4 and PHP 8.0.

## Example plugin

For the purposes of this tutorial, let's imagine you've developed a simple plugin. 

```php
<?php
/**
 * Plugin Name: WP Learn Compatibility
 * Description: Learn to test a plugin for PHP Version Compatibility
 * Version: 1.0.1
 *
 * @package wp-learn-compatibility
 */

/**
 * Posts fetcher class
 */
class Post_Fetcher {

	/**
	 * Array posts
	 *
	 * @var array
	 */
	protected $posts;

	/**
	 * Fetch the WordPress posts
	 */
	public function post_fetcher() {
		$this->posts = get_posts();
	}

	/**
	 * Fetch the posts and return the formatted HTML
	 *
	 * @return string
	 */
	public function fetch_posts() {
		$post_html = '<div class="post">';
		foreach ( $this->posts as $post ) {
			if ( property_exists( $post, 'post_title' ) ) {
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

add_shortcode( 'wp_learn_php_compatibility', 'wp_learn_php_compatibility_shortcode_render' );

/**
 * Shortcode callback function for wp_learn_php_compatibility shortcode
 *
 * @return string
 */
function wp_learn_php_compatibility_shortcode_render() {
	$post_fetcher = new post_fetcher();
	$post_html    = $post_fetcher->fetch_posts();
	return $post_html;
}

```

The plugin registers a shortcode, which fetches a list of posts and displays the post title of each post whenever the shortcode is used. The post_fetcher class handles the fetching of the posts.

Testing the shortcode on a page, you can see that it works as expected when running PHP 7.4.

## How to test for PHP version compatibility

There are a few ways to test for PHP version compatibility, which require different combinations of newer PHP versions and installation of various tools. For the purposes of this tutorial, we will look at three possible methods, each with their own pros and cons.

### Manual compatibility testing

The manual method involves you setting up a WordPress environment with the PHP version you want to test for, and then testing your plugin in that environment. 

Setting up this environment can be done in a few ways, but the most common option would be to use a local development environment that supports changing PHP version, such as Mamp, Laragon, LocalWP, and DevKinsta.

For the purposes of this example we'll test on PHP 8.0. 

A quick way to check that you're on the right version, is you create an info.php file in the root of your WordPress install, and use the following code:

```php
<?php
phpinfo();
```

Then, navigate to the info.php file in your browser, and you should see the PHP version displayed.

Once you have your test environment set up, you need to enable WordPress debugging. 

To do this, edit the `wp-config.php` file, and update the line which defines the `WP_DEBUG` constant, setting it to `true`

```php
define( 'WP_DEBUG', true );
```

Additionally, add the `WP_DEBUG_DISPLAY` constant and set it to false and add the `WP_DEBUG_LOG` constant and set it to true, so that errors are logged to a `debug.log` file in the `wp-content` directory.

```php
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );
```

You can also set your `WP_DEBUG_LOG` constant to a custom location, by specifying the path to the file. For example:

```php
define( 'WP_DEBUG_LOG', '/home/ubuntu/wp-local-env/sites/learnpress/logs/debug.' . date( 'Y-m-d' ) . '.log' );
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

Once that's fixed, refresh the page, and you'll see that the plugin is working again as expected. 

While manual testing does work, it's a tedious process. Fortunately you can also automate most tests using a tool called PHPUnit. This will allow you to continuously safeguard your code both against bugs and for PHP compatibility issues, but that's outside of the scope of this tutorial.

### Scanning your code using PHPCompatibilityWP

There are also tools you can use to test for PHP Compatibility, the most useful being the aptly named [PHPCompatibility](https://github.com/PHPCompatibility/PHPCompatibility) tool, which is a set of rules for the [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) tool. 

PHP_CodeSniffer is a command line tool that can be used to scan your code for errors and warnings, and the PHPCompatibility tool is a set of rules that can be used with PHP_CodeSniffer to scan your code for PHP version compatibility.

For WordPress developers, there is a specific ruleset called [PHPCompatibilityWP](https://github.com/PHPCompatibility/PHPCompatibilityWP), which is a PHPCompatibility ruleset for WordPress projects.

What's great about PHPCompatibility/PHPCompatibilityWP is that you don't have to configure a different PHP version to use it. You can use it with your existing PHP version, and it will check your code against the rules for the PHP version you specify.

To install and use PHPCompatibilityWP, you need to install Composer, which is a dependency manager for PHP projects. 

For Composer to work, you also need PHP installed on your computer, so that you can use the PHP CLI binary, which allows you to run PHP scripts in the terminal, instead of just in a browser.

Installing Composer is outside the scope of this lesson, but you can find instructions on the [Composer website](https://getcomposer.org/) for both [macOS/Linux](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) and [Windows](https://getcomposer.org/doc/00-intro.md#installation-windows) operating systems. 

You can find ways to install PHP on your system on the [PHP website](https://www.php.net/manual/en/install.php) under the Installation and Configuration section.

Once you install PHP, make sure to add the path to the PHP CLI binary to the operating system path of your computer, so that you can run PHP commands from anywhere on your computer.

You can check that you have PHP installed by running the following command in your terminal:

```bash
php -v
```

Similarly, you can check that you have Composer installed by running the following command in your terminal:

```bash
composer -V
```

Once you have Composer installed, you can initialise the composer project by running the following command inside the plugin directory. If you’re already using Composer for your plugin, you can skip this step.

``` bash
composer init
```

This will initialise a new Composer project in your plugin directory. You can accept the defaults for most of the questions, but when it asks you to define your dependencies (require) interactively and define your dev dependencies (require-dev) interactively?, you should answer no. You can also skip the PSR-4 autoload mapping.

Once this is done, you will have a `composer.json` file, which is the file Composer uses to manage your project dependencies.

Next, you will need to install a Composer plugin to manage the installed_paths setting for PHP_CodeSniffer by running the following from the command. If you already have this plugin installed, you can ignore this.

```bash
composer config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
```

Then you can install the Composer installer plugin, and the PHPCompatibilityWP tool by running the following commands:

```bash
composer require --dev dealerdirect/phpcodesniffer-composer-installer:"^1.0" 
composer require --dev phpcompatibility/phpcompatibility-wp:"*"
```

This will both setup and install the required dependencies in your package.json file.

### A note on PHPCompatibility and PHPCompatibilityWP versions.

Currently, the stable release of PHPCompatibility is [9.3.5](https://github.com/PHPCompatibility/PHPCompatibility/releases/tag/9.3.5), and the most recent sniffs [are part of the upcoming version 10.0.0. release](https://github.com/PHPCompatibility/PHPCompatibility/issues/1236#issuecomment-708443602). The current stable version of PHPCompatibilityWP is [2.1.4](https://github.com/PHPCompatibility/PHPCompatibilityWP/releases/tag/2.1.4)

When version 10.0 of PHPCompatibility is released, version 3.0 of PHPCompatibilityWP will be released, which will depend on PHPCompatibility version 10.0.

In the meantime, it is possible to install the dev-develop branch of PHPCompatibility to run PHPCS with the cutting-edge additions of PHP 8 sniffs before their release in version 10.0.0 of PHPCompatibility as detailed in this [WordPress VIP documentation](https://docs.wpvip.com/technical-references/php/version-updates/phpcs-scans/#Upcoming-releases-of-PHPCompatibility).

To do this, run the following commands to alias the dev-develop branch of PHPCompatibility:

```bash
composer config minimum-stability dev
composer require --dev phpcompatibility/phpcompatibility-wp:"^2.1"
composer require --dev phpcompatibility/php-compatibility:"dev-develop as 9.99.99"
```

These commands will alias the `develop` branch of PHPCompatibility to a 9.x version which is within the allowed range for PHPCompatibility, and set PHPCompatibilityWP to install the latest stable 2.1 version.

Once PHPCompatibility 10 and PHPCompatibilityWP 3 are released, it should be possible to update the PHPCompatibilityWP version constraint to "^3.0", which will depend on version 10 of PHPCompatibility.

With all this installed, you can run the PHPCompatibility tool on your plugin file. 

The recommended way to do this is run PHPCompatibility against a specific base version of PHP. In this example you can run it against version 7.4 of PHP and above by setting the `testVersion` runtime variable to `7.4-`.

```bash
./vendor/bin/phpcs --runtime-set testVersion 7.4- -p wp-learn-php-compatibility.php --standard=PHPCompatibilityWP
```

And you will see this output:

```bash
W 1 / 1 (100%)



FILE: /Users/jonathanbossenger/wp-local-env/sites/learnpress/wp-content/plugins/wp-learn-php-compatibility/wp-learn-php-compatibility.php
-----------------------------------------------------------------------------------------------------------------------------------------
FOUND 0 ERRORS AND 1 WARNING AFFECTING 1 LINE
-----------------------------------------------------------------------------------------------------------------------------------------
 15 | WARNING | Use of deprecated PHP4 style class constructor is not supported since PHP 7.
-----------------------------------------------------------------------------------------------------------------------------------------

Time: 33ms; Memory: 8MB
```

Notice how the same error is reported as the manual method, but this time it's a lot more specific. It tells us exactly what line the error is on, and what the error is.

So now we can fix the class constructor error.

#### Considerations

One of the considerations when using something like PHPCompatibilityWP is that it can't pick up every single compatibility error.

For example, one of the other changes from PHP 7.4 to PHP 8.0 is the removal of the ability to use `array_key_exists()` with objects, and instead something like `property_exists()` should be used.

However, the PHPCompatibilityWP tool doesn't know if the variable you're passing to `array_key_exists()` is an array or an object, so it can't warn you about this.

This is where automating your manual tests would come in handy. When you run the tests in a new PHP environment, the tests would fail, altering you to a possible problem. And with logging enabled, you'd see the error logged to the log file. 

Ultimately combining a tool like PHPCompatibility with automated testing and the manual testing process we've discussed, will allow you to ensure that your plugin is compatible with current and future versions of PHP.