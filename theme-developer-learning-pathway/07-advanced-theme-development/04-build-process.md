# Build Process

## Introduction

When developing a WordPress theme, it's useful to consider whether you will need a build process to help you manage your theme's assets and optimize your theme for performance.

In this lesson, you'll learn more about build processes, why you should use them, and how to set up a basic build process using the @wordpress/scripts package.

## What is a build process?

A build process is a method of converting source code files into a final build/production version that can be read by the computer. 

In particular, themes will most often be minifying or converting source code into CSS or JavaScript so that they can be read by the browser.

## Why use a build process?

Depending on what technologies you use in your theme, you may need a build process to help you manage your theme's assets and optimize your theme for performance.

For example, if you choose to use Sass for your styling, you will need a build process to compile your Sass files into CSS files that can be read by the browser.

If you chose to write your JavaScript using the more modern ES6 syntax, you will need a build process to transpile your JavaScript files into a format that all browsers can execute.

Even if you don't use Sass or ES6, a build process can still be useful for optimizing your theme's assets, such as minifying your CSS and JavaScript files, and optimizing images.

When creating a WordPress theme, you may find yourself in need of a build process to handle more complex projects. There are many systems to choose from, and you can use whatever you prefer. 

But WordPress also offers the @wordpress/scripts package that you can be assured is continually updated and should cover most of your needs.

## Prerequisites

Most of WordPress theme development doesn't require any additional software. You just need a code editor, a local development environment, and a WordPress installation. 

But to work with a build process, there are some other requirements:

 - You need to have [Node.js and npm](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm) installed on your local machine, which is also a [requirement for building WordPress blocks](https://learn.wordpress.org/lesson/getting-set-up-2/).
 - A basic understanding of [webpack](https://webpack.js.org/concepts/) is also recommended.
 - Some familiarity with the [@wordpress/scripts package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/).

 - These are more advanced tools than what is normally required to build themes, but they are necessary if you want to work with the standard WordPress build process.

## Setting up your files and folders

The @wordpress/scripts package was originally created for block development. Over time, it has evolved to also work with themes. 

By default, it expects that development files live in the /src folder and will output build files in the /build folder. 

However, most theme authors utilize a custom system for working with assets.

Let's say you have the following structure for your theme:

```
my-theme/
├── public/
├── resources/
│   ├── js/
│   ├── scss/
```

Your development JavaScript and Sass files reside in the resources/js and resources/scss folders, respectively. when the build process runs, you want them to be output to the public/js and public/css folders.

## Setting up your package.json file

Your first step, if you haven't done this already, is to initialise the npm project. This will create a package.json file in the root of your theme.

To do this, open your terminal, navigate to the root of your theme, and run the following command:

```bash
npm init
```

You will be prompted to enter some information about your project. You can press enter to accept the default values or change the values to your liking.

Once you have completed the setup, you will have a package.json file in the root of your theme.

```json
{
  "name": "javascript-enabled",
  "version": "1.0.0",
  "description": "JavaScript Enabled Theme",
  "main": "index.js",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "author": "Jonathan Bossenger",
  "license": "GPL-2.0-or-later"
}
```

Next, you need to install the @wordpress/scripts package as a development dependency.

To do this, run the following command in your terminal, in the root of your theme:

```bash
npm install @wordpress/scripts path webpack-remove-empty-scripts --save-dev
```

Once that's done, you'll see that the package.json file has been updated with the new dependencies.

```json
{
  "name": "javascript-enabled",
  "version": "1.0.0",
  "description": "JavaScript Enabled Theme",
  "main": "index.js",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "author": "Jonathan Bossenger",
  "license": "GPL-2.0-or-later",
  "devDependencies": {
    "@wordpress/scripts": "^27.8.0",
    "path": "^0.12.7",
    "webpack-remove-empty-scripts": "^1.0.4"
  }
}
```

Last but not least, update the scripts section of the package.json file to include the following:

```json
{
  "name": "javascript-enabled",
  "version": "1.0.0",
  "description": "JavaScript Enabled Theme",
  "main": "index.js",
  "scripts": {
    "start": "wp-scripts start --webpack-src-dir=resources --output-path=public",
    "build": "wp-scripts build --webpack-src-dir=resources --output-path=public"
  },
  "author": "Jonathan Bossenger",
  "license": "GPL-2.0-or-later",
  "devDependencies": {
    "@wordpress/scripts": "^27.8.0",
    "path": "^0.12.7",
    "webpack-remove-empty-scripts": "^1.0.4"
  }
}
```

This creates two npm script commands, `start` and `build`, that will run the @wordpress/scripts package with the correct configuration for your theme. 

In this case it will look for files in the resources folder and output them to the public folder.

If you have a different folder structure, you can adjust the `--webpack-src-dir` and `--output-path` parameters to match your setup.

## Configuring webpack

The @wordpress/scripts package is built on top of webpack. If you were building a block, everything would already be in place for you. 

However, because you are building a theme, you need to overwrite some default configuration of the @wordpress/scripts package with your own.

To do this, create a custom webpack.config.js file in the root of your theme.

```javascript
// WordPress webpack config.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

// Plugins.
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );

// Utilities.
const path = require( 'path' );

// Add any new entry points by extending the webpack config.
module.exports = {
	...defaultConfig,
	...{
		entry: {
			'js/editor':  path.resolve( process.cwd(), 'resources/js',   'editor.js'   ),
			'css/screen': path.resolve( process.cwd(), 'resources/scss', 'screen.scss' ),
			'css/editor': path.resolve( process.cwd(), 'resources/scss', 'editor.scss' ),
		},
		plugins: [
			// Include WP's plugin config.
			...defaultConfig.plugins,

			// Removes the empty `.js` files generated by webpack but
			// sets it after WP has generated its `*.asset.php` file.
			new RemoveEmptyScriptsPlugin( {
				stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS
			} )
		]
	}
};
```

This configuration file sets up the entry points for your custom JavaScript and Sass files, and tells webpack where to find them.

It also configures the webpack-remove-empty-scripts, so that there are no leftover JavaScript files mapped to your CSS.

## Running the build process

With everything set up, you can now run the build process.

To start the development server, run the following command in your terminal:

```bash
npm run start
```

This will start the development server and watch your files for changes. When you make changes to your JavaScript or Sass files, the build process will automatically recompile them.

To build your theme for production, run the following command in your terminal:

```bash
npm run build
```

## Loading scripts and styles

You have already learned how to enqueue scripts and styles in your theme in the lesson on including assets.

When using a build process, you will need to enqueue the compiled files instead of the source files.

If you open up the public/js folder, you will see that the build process has created the following files:

```
editor.js
editor.asset.php
```

The asset file returns a PHP array which contains an array of dependencies and a version number for the editor.js file. 

You can then use this array to enqueue the script in your theme.

First, use the relevant hook and specify the hook callback in your functions.php file

```php
// Load editor scripts.
add_action( 'enqueue_block_editor_assets', 'themeslug_editor_assets' );

function themeslug_editor_assets() {

}
```

Inside the hook callback, include the asset file

```php
$script_asset = include get_theme_file_path( 'public/js/editor.asset.php'  );
```

Finally, enqueue the script, using the values from the asset file

```php
	wp_enqueue_script(
		'themeslug-editor',
		get_theme_file_uri( 'public/js/editor.js' ),
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);
```

Your final code should look like this:

```php
add_action( 'enqueue_block_editor_assets', 'themeslug_editor_assets' );
function themeslug_editor_assets() {
    $script_asset = include get_theme_file_path( 'public/js/editor.asset.php'  );
	wp_enqueue_script(
		'themeslug-editor',
		get_theme_file_uri( 'public/js/editor.js' ),
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);
}
```

The process is similar for any stylesheets you want to enqueue.

## Further reading

For the full guide on how to set up the build process using the @wordpress/scripts package, you can refer to the [Build process](https://developer.wordpress.org/themes/advanced-topics/build-process/) page under the Advanced Topics section of the WordPress Developer Handbook..