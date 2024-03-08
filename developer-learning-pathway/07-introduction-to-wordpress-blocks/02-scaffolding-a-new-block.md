# Scaffolding a new block

Once you have the necessary tools installed for block development, you can start developing your first block.

In this lesson, you will learn about a tool called create-block, which will allow you to quickly scaffold your first block plugin. You will learn why you should use create-block, how to use it, and review the code that it generates.

## What is create-block?

Like any software development project, developing a block for WordPress requires a combination of specific files and folders to be set up in a certain way. 

There are multiple ways to do this, but you would ideally want to build your block following certain best practices. 

Create-block is a command line tool that helps you scaffold a new block plugin, following these best practices. 

In software development, scaffolding is the process of creating a basic structure for a project, so that you can build on top of it.

Using create-block, you can run a single terminal command to create a new block plugin, and create-block will set up the necessary files and folders for you, following the best practices for block development.

## Why a plugin?

While it is not a requirement to develop a block as part of a plugin, it's generally the recommended approach.

One of the main reasons for this is that custom blocks are not allowed in themes that are submitted to the WordPress.org theme directory.

However, if you are developing a theme for your own use, or for a client, you can include custom blocks in your theme, the difference is that you register the blocks in a different way.

Ultimately though the block development experience and tooling is designed to work best with plugins, so that's what this series of lessons will follow.

## What are we building?

For the purposes of this series of lessons, you will be building  a “Copyright Date Block”. 

It's a basic yet practical block that has the following features: 

 - It displays the text "Copyright", followed by the copyright symbol (©), then a starting year, and the current year 
 - The user should be able to adjust the alignment of the block on the page
 - It should have the following CSS applied to it at all times `border: 1px solid #111111; padding: 5px;`
 - The user should also be able to adjust the starting year

## Using Create Block

To scaffold your first block, open the terminal on your local computer, and switch to the `/wp-content/plugins` directory of your local WordPress installation.

Generally this is done by running the following command:

```bash
cd /path/to/your/wordpress/install/wp-content/plugins
```

Then, run the `create-block` command, and include a plugin slug. The slug is the unique name of the plugin that you want to create.

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

*   The `build` directory is where the final deployable build of the block code is located. This is the code that powers the block when it's used in the editor. You generally never need to touch this directory or any of the files in it.
*   The `node_modules` directory is where all the Node.js packages are located. These are also known as the project dependencies, and you only need this for local block development. 
*   The `src` directory is where you will spend most of your time writing block code. This is the directory that contains the files that you will use to develop yor block. These are ultimately compiled into the code in the `build` directory. 

Following those three directories are a number of files.

The `.editorconfig` file is for unifying the coding style for different editors and IDEs.

`.gitignore` is used by the Git version control system to manage what code is committed to version control.

The purpose of these files is outside the knowledge needed for block development, so if you don't know what they are needed for, you can safely ignore them for now.

If you can't see these two files, you might have to enable Hidden Files in your directory browser or code editor.

Next up is `copyright-date-block.php`. This is the main plugin file that initiates the execution of the plugin. 

Inside that file you'll see the standard plugin header, and the PHP code that registers the block. 

Here the `copyright_date_block_copyright_date_block_block_init` function calls the `register_block_type()` function passing the path to the previously mentioned `build` directory as a parameter.

This function is then hooked into the `init` action.

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

Dependencies are the external packages or modules that your project needs to run properly. For the purposes of block development, the dependency you need is a development dependency of the `@wordpress/scripts` package. These dependencies are what are installed in your `node_modules` directory.

The `scripts` object contains a list of command line scripts that can be run during development. The most important of these are:

 - `build` which compiles the files from the `src` directory into the `build` directory
 - `start` which starts a development server that watches for changes to the files in the `src` directory and automatically compiles them into the `build` directory

You will learn how to use these scripts when you start developing your block.

The `package-lock.json` contains a list of all the installed dependencies, as well as the version number of that dependency used. It locks the required dependencies to the version number specified in this file. This is another file you can ignore for now.

Finally, there's a `readme.txt` file which you'll only need to edit if you intend to [publish](https://developer.wordpress.org/plugins/wordpress-org/) your plugin to the [WordPress Plugin Directory](https://wordpress.org/plugins/).

## The `src` directory

All of your block development takes place in the `src` directory. Let's take a look at the files that are scaffolded

* `block.json` stores the metadata for the block, defined as a JSON object. You can learn more about block metadata in [the block metadata handbook page](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/). This file allows you to define things like the block name, title, icon, the various files that make up the block, and much more.
* `edit.js` is where you'll spend most of your time as you work on a block. This file exports a React `Edit()` component, that is rendered in the editor and determines how the block appears and functions in the editor. 
* `editor.scss` contains the styles that control the appearance of the block in the block editor. Usually, you will want the block to appear the same in the editor as it does in the front end of the website, so you'll often not need this file at all.
* `index.js` is the starting point for the JavaScript execution of the block. It sets up and executes the `registerBlockType` function to register the block in the editor.
* `save.js` exports a `save()` function, which determines the markup that will be saved to the `post_content` field in the `wp_posts` table when the post or page is saved, and therefore determines how the block appears and functions in the front end. 
* `style.scss` contains the styles that control the appearance of the block in the editor and the front end. Styles here can be overridden by styles in `editor.scss` if you need the block to appear different in the editor.
* `view.js` is a file that is used to add any additional JavaScript to the block in the front end. This is another file that you will often not need. 

## The `build` directory

During development, you will execute the scripts you saw in the `package.json` file to compile the files from the `src` directory into the `build` directory.

The process of building your block code, also known as bundling your code, is the process of converting your block code into a format that is compatible with all browsers. 

When you scaffolded the block, the `create-block` tool also ran the build process, generating the `build` directory for you. 

You will notice that some files are bundled into the `build` directory as is, like the `block.json` file, while others are converted, like the `index.js` file. There are also additional files, like the `index.asset.php` file, that are generated during the build process. 

When WordPress loads your block in the editor or on the front end, it's executing the code from the `build` directory.

The `@wordpress/scripts` package defined as a dev dependency in the `package.json` file uses a tool called Webpack to bundle your block code. 

The details of how this works is outside the scope of this lesson, but you can learn more about it in the [Webpack documentation](https://webpack.js.org/concepts/).

## Additional Resources

To learn more about the `create-block` tool, as well as all the different options it offers, check out the [package reference](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/) for `create-block` in the Block Editor Handbook
