# Development Best Practices

## Introduction

As your plugin's functionality evolves, it is important to maintain a clean and organized codebase. 

This will help you and other developers to easily understand and maintain the code. 

In this lesson, we will discuss some best practices that you can follow to maintain a well-organized plugin.

## Conditional Loading

As discussed in the [Enqueuing CSS and JavaScript lesson](https://learn.wordpress.org/lesson/enqueuing-css-or-javascript/) when developing a plugin that loads CSS or JavaScript assets, you may need to load certain scripts or stylesheets only on specific pages. 

There are a number of ways to achieve this, depending on the context.

For example, if you only need enqueue CSS or JavaScript in the admin area, you can use the `admin_enqueue_scripts` action hook.

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_admin_enqueue_scripts' );
function wp_learn_admin_enqueue_scripts() {
    // Load admin scripts and styles
}
```

To take it a step further, if you only need the asset on a specific admin page, you can use the `get_current_screen()` function to check the current screen ID. For example, to load an asset only on the General Settings page:

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_admin_enqueue_scripts' );
function wp_learn_admin_enqueue_scripts() {
    $screen = get_current_screen();
    if ( 'options-general' === $screen->id ) {
        // load assets only on the General Settings page
    }
}
```

You can perform similar checks on the front end.

For example, to only load an asset for any post type, you can use the `is_singular()` function:

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_enqueue_scripts' );
function wp_learn_enqueue_scripts() {
    if ( is_singular() ) {
        // Load assets only for single posts 
    }
}
```

Additionally, you can check for specific post-types, in this case, a book:

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_enqueue_scripts' );
function wp_learn_enqueue_scripts() {
    if ( is_singular( 'book' ) ) {
        // Load assets only for single books 
    }
}
```

The `is_singular()` function is a special type of function in WordPress called a [Conditional Tag](https://developer.wordpress.org/themes/basics/conditional-tags/). Conditional tags are used to determine the context in which the data being request is displayed. 

Conditional tags will always return a boolean value (true or false). To make use of Conditional tags, the query must have already run, so that the tag can determine the context. There are Conditional tags for pretty much any context, everything from checking if the request is for a specific page, post type, or even an admin page

When developing your plugin, it's a good idea to check to make sure your using the correct hooks or the relevant conditional tags to ensure that your code is only executed when necessary.

## Determining Path and URL values

Developing a WordPress plugin often requires you to reference files located within a WordPress installation, usually located in the `wp-content` directory. This includes any Media Library files, plugin assets, or active theme related files.

While a default WordPress installation will have a predictable file structure, it is important to remember that users can change the location of the `wp-content` directory, as well as rename it.

Therefore, you can never assume that plugins will be in `wp-content/plugins`, that uploads will be in `wp-content/uploads`, or that themes will be in `wp-content/themes`.

Fortunately, WordPress provides a number of [functions](https://developer.wordpress.org/plugins/plugin-basics/determining-plugin-and-content-directories/#available-functions) to help you determine the correct path and URL values for many different types of locations. This includes plugin and theme related locations, as well as other parts of the WordPress installation, even if it's configured as a multisite.

While most of these functions don't require any specific parameters, the plugin related ones do require the plugin's main file path as a parameter. 

To make life easier for the developer, PHP offers a `__FILE__` [magic constant](https://www.php.net/manual/en/language.constants.magic.php#constant.file) that returns the full path and filename of the file it is used in. This can be used to determine the path to the plugin's main file when using these functions.

Here's an example of how you can use the `plugin_dir_path()` function to determine the directory path of the plugin, in order to enqueue a CSS file:

```php
add_action( 'wp_enqueue_scripts', 'wp_learn_plugin_paths_enqueue_scripts' );
function wp_learn_plugin_paths_enqueue_scripts() {
	wp_register_style(
		'wp-learn-plugin-paths-styles',
		plugin_dir_url( __FILE__ ) . 'wp-learn-plugin-paths-styles.css'
	);
	wp_enqueue_style( 'wp-learn-plugin-paths-styles' );
}
```

Note that to use something like `plugin_dir_url()` with the `__FILE___` magic constant, your code needs to be in the main plugin file. 

If you need to use this in a different location, say in a PHP class file located in a subdirectory of your plugin, you'll need to find a way to store the value of the `__FILE__` constant in a variable.

```php
class WP_Learn_Asset_Loader {

	public $plugin_file = ???;

	public function __construct(){
		$this->register_hooks();
	}
	
	public function register_hooks(){
		add_action( 'wp_enqueue_scripts', array( $this ,'enqueue_scripts' ) );
	}

	public function enqueue_scripts(){
		wp_register_style(
			'wp-learn-plugin-paths-styles',
			plugin_dir_url( $this->plugin_file ) . 'wp-learn-plugin-paths-styles.css'
		);
		wp_enqueue_style( 'wp-learn-plugin-paths-styles' );
	}
}
```

Some developers will create a plugin specific constant to store the value of `__FILE__` in the main plugin file, and then use that constant in other files.

```php
define( 'WP_LEARN_PLUGIN_FILE', __FILE__ );

require_once plugin_dir_path( WP_LEARN_PLUGIN_FILE ) . 'classes/wp-learn-class-asset-loader.php';
$asset_loader = new WP_Learn_Asset_Loader();
$asset_loader->enqueue_scripts();
```

```php
class WP_Learn_Asset_Loader {

	public $plugin_file = WP_LEARN_PLUGIN_FILE;

	public function __construct(){
		$this->register_hooks();
	}
	
	public function register_hooks(){
		add_action( 'wp_enqueue_scripts', array( $this ,'enqueue_scripts' ) );
	}

	public function enqueue_scripts(){
		wp_register_style(
			'wp-learn-plugin-paths-styles',
			plugin_dir_url( $this->plugin_file ) . 'wp-learn-plugin-paths-styles.css'
		);
		wp_enqueue_style( 'wp-learn-plugin-paths-styles' );
	}
}
```

There are a number of ways to handle this, and you should choose the one that makes the most sense for your plugin.

## File Organization

Generally, the root level of your plugin directory should contain your main plugin file and, optionally, your `uninstall.php` file. 

It's a good idea to store any other plugin files into subdirectories whenever possible.

Here's an example of a well-organized plugin directory structure:

```
/plugin-name/
     /admin/
          /js/
          /css/
          /images/
     /includes/
     /languages/
     /public/
          /js/
          /css/
          /images/
     plugin-name.php
     uninstall.php
```

In this example structure, all plugin translation files are stored in the `languages` subdirectory, any additional PHP files are stored in the `includes` subdirectory, and all admin-related assets (JavaScript, CSS, or images) are stored in the `admin` subdirectory. Any public facing assets (JavaScript, CSS, or images) are stored in the `public` subdirectory.

Here's another example, taking from a real-world web agency's engineering practices:

```
/plugin-name/
    /assets/
        /css/
        /images/
        /js/
        /svg/
    /includes/
        /classes/
    /languages/
    plugin-name.php
    uninstall.php    
```

In this example, all assets are stored in the `assets` subdirectory, and any PHP classes are stored separately in the `includes/classes` subdirectory.

Neither of these structures is a requirement, but they are good examples of how you can organize your plugin files. You should choose a structure that makes sense for your plugin and stick with it.

## Plugin architecture

Your plugin's architecture is the overall design of your plugin, and should be well thought out before you start coding.

For example, a small, single-purpose plugin that has limited interaction with WordPress core, themes or other plugins does not need a complex structure, or the use of PHP classes; unless you know the plugin is going to expand greatly later on. On the other hand, a plugin that is going to require a lot of functionality might need something more complex.

It is therefore a good idea to familiarize yourself with plugin architecture patterns, and choose the correct one for your needs.

The WordPress plugin developer handbook includes examples of [plugin architecture patterns](https://developer.wordpress.org/plugins/plugin-basics/best-practices/#architecture-patterns), as well as some [links to external resources](https://developer.wordpress.org/plugins/plugin-basics/best-practices/#architecture-patterns-explained). There are also a number of [plugin boilerplates available](https://developer.wordpress.org/plugins/plugin-basics/best-practices/#boilerplate-starting-points) that can help you get started, or simply use for your own learning.