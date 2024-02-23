# Scaffolding a new block with create-block

Once you have the necessary tools installed for block development, you can start developing your first block.

In this lesson, you will learn how to use a tool called create-block to scaffold your first block plugin.

## What is create-block?

Like any software development project, developing a block for WordPress requires a combination of specific files and folders to be set up in a certain way. 

There are multiple ways to do this, but you would ideally want to build your block following certain best practices. 

Create-block is a command line tool that helps you scaffold a new block plugin, following these best practices. 

In software development, scaffolding is the process of creating a basic structure for a project, so that you can build on top of it.

Using create-block, you can run a single terminal command to create a new block plugin, and create-block will set up the necessary files and folders for you.

## Why a plugin?

While it is not a requirement to develop a block as part of a plugin, it's generally the recommended approach.

One of the main reasons for this is that custom blocks are not allowed in themes that are submitted to the WordPress.org theme repository.

However, if you are developing a theme for your own use, or for a client, you can include custom blocks in your theme, the difference is that you register the blocks in a different way.

Ultimately though the block development experience and tooling is designed to work best with plugins, so that's what this series of lessons will follow.

## What are we building?

For the purposes of this series of lessons, you will be building  a “Copyright Date Block”. It's a basic yet practical block that displays 
 - the text "Copyright"
 - the copyright symbol (©) 
 - the current year 
 - an optional starting year

## Using Create Block

To scaffold your first block, open the terminal on your local computer, and switch to the `/wp-content/plugins` directory of your local WordPress installation.

Generally this is done by running the following command:

```bash
cd /path/to/your/wordpress/install/wp-content/plugins
```

Then, run the create-block command, and include a plugin slug. The slug is the unique name of the plugin that you want to create.

```bash
npx @wordpress/create-block@latest copyright-date-block
```

If you are asked to confirm the installation of the create-block package, type `y` and press Enter.

This will create a new plugin in the `copyright-date-block` directory, install any required packages and create the necessary files.

Once the new plugin is scaffolded, you should have a new plugin folder in your WordPress install, named after the slug you defined.

If you browse to your WordPress dashboard and navigate to the Plugins screen, you should see your new plugin listed there.

Activate it, and once it's active, create a new Post.

You should be able to add your newly scaffolded block in the editor.

## The scaffolded plugin

Take a look at what the scaffolded block plugin looks like by opening the `copyright-date-block` directory in your code editor. 

First, there are three directories:

*   The `build` directory is where the final deployable build of the block will go. This is the code that powers the block when it's used in the editor. You should never need to touch this directory or any of the files in it directly.
*   The `node_modules` directory is where all the modules that the build process depends on live. These are also known as the project dependencies. Again you will in all likelihood never look in here.
*   The `src` directory is where you will spend most of your time. This is the directory that contains the files that you will work with to create the markup and the functionality of your block which are ultimately compiled into the `build` directory. 

Following those three directories are a number of files.

The `.editorconfig` file is for unifying the coding style for different editors and IDEs.

`.gitignore` is used by the Git version control system to manage what code is committed to version control.

The purpose of these files is outside the knowledge needed for block development, so if you don't know what they are needed for, you can safely ignore them for now.

If you can't see these two files, you might have to enable Hidden Files in your directory browser or code editor.

Next up is `wp-learn-new-block.php`. This is the main plugin file that initiates the execution of the plugin. Open up the file and look at the code inside.

After the standard plugin header, this file contains just a few lines of executable PHP code consisting of a single function that is hooked onto the `init` action. 

This function calls the `register_block_type()` function passing the path to the previously mentioned `build` directory as a parameter.

Next, you'll see `package-lock.json` file and the `package.json` file.

The `package.json` file is a file that npm uses when developing a JavaScript project.

```
    {
    	"name": "wp-learn-todo",
    	"version": "0.1.0",
    	"description": "Example static block scaffolded with Create Block tool.",
    	"author": "The WordPress Contributors",
    	"license": "GPL-2.0-or-later",
    	"main": "build/index.js",
    	"scripts": {
    		"build": "wp-scripts build",
    		"format": "wp-scripts format",
    		"lint:css": "wp-scripts lint-style",
    		"lint:js": "wp-scripts lint-js",
    		"packages-update": "wp-scripts packages-update",
    		"plugin-zip": "wp-scripts plugin-zip",
    		"start": "wp-scripts start"
    	},
    	"devDependencies": {
    		"@wordpress/scripts": "^26.12.0"
    	}
    }
```

It contains important details about your project, including any scripts that can be run on the project, and any dependencies.

Dependencies are the external packages or modules that your project needs to run properly. For the purposes of block development, the only dependency you need is a development dependency of the `@wordpress/scripts` package. These dependencies are what are installed in your `node_modules` directory.

The `scripts` object contains a list of command line scripts that can be run during development. The most important of these are:

 - `build` which compiles the JavaScript and SASS files in the `src` directory into the `build` directory
 - `start` which starts a development server that watches for changes to the files in the `src` directory and automatically compiles them into the `build` directory

You will learn how to use these scripts when you start developing your block.

The `package-lock.json` contains a list of all the installed dependencies, as well as the version number of that dependency used. It locks the required dependencies to the version number specified in this file. This is another file you can ignore for now.

Finally, there's a `readme.txt` file which you'll only need to edit if you intend to [publish](https://developer.wordpress.org/plugins/wordpress-org/) your plugin to the [WordPress Plugin Directory](https://wordpress.org/plugins/).

### The `src` directory

For block development the most important directory is the `src` directory. This is where you'll spend most, if not all, of your time when developing a block.

*   `block.json` stores the metadata for the block, defined as a JSON object. You can learn more about block metadata in [the block metadata handbook page](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/). This file allows you to define things like the block name, title, icon, the various files that make up the block, and much more.
*   `edit.js` is where you'll spend most of your time as you work on a block. This file exports a React `Edit()` component, that is rendered in the editor and determines how the block appears and functions in the editor. The Edit component will usually accept an `attributes` parameter which is an object containing any attributes defined in the block metadata.
*   `editor.scss` is a file containing CSS that styles the appearance of the block in the block editor. Usually, you will want the block to appear the same in the editor as it does in the front end of the website, so you'll probably make little or no changes to this file. You will notice that this example merely adds a border when the block is selected in the editor.
*   `index.js` is the starting point for the JavaScript execution of the block. It imports the functions exported by `edit.js` and `save.js` and then executes `registerBlockType` passing as parameters the name of the block and an object containing the two imported functions.
*   `save.js` exports a **function**, `save()`, which determines the markup that will be saved to the `post_content` field in the `wp_posts` table when the post or page is saved, and hence determines how the block appears and functions in the front end. Like with `edit.js`, the function defined here will usually accept an `attributes` parameter which is an object containing the attributes defined in `block.json`.
*   `style.scss` is another file containing CSS. In this case, the CSS determines the styling of the block in the front end but also in the editor. Styles here can be overridden by styles in `editor.scss` if you need the block to appear different in the editor. However, it would be unusual to do this.

Note that `editor.scss` and `style.scss` are actually SASS files and not standard CSS files. These files require the build step which you'll learn about in the next lesson.

Don't worry if some of the above is confusing at this stage. It will all become much, much clearer and make much more sense to you as you progress through the rest of the lessons.

## Additional Resources

For more information on the topics covered in this lesson, see the following resources:

 - The block editor [Quick Start Guide](https://developer.wordpress.org/block-editor/getting-started/quick-start-guide/) in the Block Editor handbook
 - The [build your first block tutorial](https://developer.wordpress.org/block-editor/getting-started/tutorial/) in the Block Editor Handbook 
 - The [register_block_type](https://developer.wordpress.org/reference/functions/register_block_type/) function reference


