# Block development fundamentals - Create Block tool

## Learning Objectives

Upon completion of this lesson the participant will be able to:

* Describe what the create-block tool is and what it is used for
* List the requirements to install and use the create-block tool
* Scaffold a new block plugin using the create-block tool
* Describe the structure of the scaffolded plugin files
* Describe the options available for the create-block tool

## Comprehension questions

1.  What is the create-block tool and what is it used for?
2.  What tools and software are needed to use create-block?
3.  What is the command run to use create-block?
4.  What is the name of the directory that create-block creates for the final block files?

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you’re going to learn about a command line tool called Create Block.

You will learn what this tool is, the requirements to install and use it, and how you can use this tool to scaffold a new block plugin.

## What is Create Block

Create-block is an official command-line tool developed by WordPress contributors to scaffold or create a new block plugin.

It generates everything you need to start a new block plugin and integrates a modern build setup with no configuration.

You can read more about create-block by visiting the [**Block Editor handbook** page](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/) or on the [npm.js package page](https://www.npmjs.com/package/@wordpress/create-block).

## Requirements

In order to use the create-block tool, you’re going to need a local WordPress installation.

You can use any local WordPress installation you’re comfortable with. If you don’t already have a local WordPress install, please take a look at the [Local WordPress Installations (For Beginners) tutorial](https://learn.wordpress.org/tutorial/local-wordpress-installations-for-beginners/) at learn.wordpress.org. 

You will also need to install [Node.js](https://nodejs.org/en), which includes the `npm` command line tool.

To install Node.js and npm please follow the [Installing Node.js and npm for local WordPress development](https://learn.wordpress.org/tutorial/installing-node-js-and-npm-for-local-wordpress-development/) tutorial at learn.wordpress.org.

Using Create Block
------------------

Now you’re ready to use the create-block tool to create your first block. In your terminal, change the directory to the /wp-content/plugins directory of your local WordPress installation.

```
cd /wp-content/plugins
```

Then, run the `create-block` command, and pass a plugin slug to this command. The slug is the unique name of the plugin.

```
npx @wordpress/create-block@latest wp-learn-todo
```

This will create a new plugin in the `wp-learn-todo` directory, install any required packages and create the necessary files.

Once the new plugin is scaffolded, you should have a new plugin folder in your WordPress install, named after the slug you passed to `create-block`.

[![](https://learn.wordpress.org/files/2022/10/scaffolded-plugin-1024x571.png)](https://learn.wordpress.org/files/2022/10/scaffolded-plugin.png)

Activate this plugin via the WordPress dashboard and if you open the block editor, you can add the WP Learn Todo block, which at the moment just contains a paragraph with some text.

[![](https://learn.wordpress.org/files/2022/10/add-block-to-post-1024x640.gif)](https://learn.wordpress.org/files/2022/10/add-block-to-post.gif)

## Scaffolded plugin files

Let's take a look at the structure of your new block plugin:

*   build - this is the location where the final block asset files will be built for distribution
*   node_modules - any node packages (aka dependencies) you need for development
*   src - the source directory, where you will do most of your coding. All your block code lives here.
*   .gitignore and .editorconfig - files that are used by developers to determine what files should be ignored by Git version control and for unifying the coding style for different editors and IDEs
*   package.json and package-lock.json - these files control the build process and define the project dependencies
*   readme.txt - the file that is used by the WordPress.org plugin repository
*   wp-learn-todo.php - the main plugin file that tells WordPress this is a plugin

## Create-block options

The create-block command has a number of options that you can use to customize the code that is scaffolded.

The variant option allows you to specify that you want to scaffold a block with a specific feature set. At the moment, the available variant is to scaffold a dynamic block, which is a block that uses PHP to render the block front end output.

```
npx @wordpress/create-block@latest --variant dynamic
```

The --no-plugin option allows you to scaffold a block without creating a plugin. This is useful if you want to add a block to a theme.

```
npx @wordpress/create-block@latest --no-plugin
```

## Interactive Mode

If you run the create-block command without passing a plugin slug, it will run in interactive mode and ask you to specify the details of the block including things like:
 
- The plugin variant
- The plugin slug
- The internal namespace
- The display title
- The description
- The dashicon
- The category
- Options to customize the plugin header

## The main plugin file

The main plugin file contains two sections of code.

At the top is the [plugin header](https://developer.wordpress.org/plugins/plugin-basics/header-requirements/), which is what WordPress uses to determine that this a plugin and provides information about the plugin.

Next is the block registration. This is where the block is registered with WordPress.

```
function create_block_wp_learn_todo_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_wp_learn_todo_block_init' );
```

This code registers the block with WordPress and tells WordPress where to find all the block asset files, which are generated in the `build` directory.

## package.json and package-lock.json

This is the file that Node.js/npm uses to manage the project dependencies, as well as the scripts that power the build process. 

At the moment the only dependency is the `@wordpress/scripts` package, which is used to build the block assets, and it's defined as a development dependency. 

The scripts property includes a list of all npm scripts that can be run on this project. This was added to the file by the@wordpress/scripts, and includes a 'build' script, which is used to build the block code, as well as a 'start' script, which is used during block development.

These scripts can be run form the terminal by using the npm run command, for example

```
npm run build
```

This runs the build process and transpiles the code from the src directory into the build directory.

The package-lock.json file is automatically generated by npm and should not be edited. It describes the exact set of dependencies that is required at a specific point in the projects lifecycle. It means that someone else using this code can install the identical set of dependencies regardless of any dependency updates.

## The source directory

The source directory is where you will do most of your coding. It contains the following files:

* block.json stores the metadata for the block, defined as a JSON object. 
* index.js is the starting point for the JavaScript execution of the block. 
* edit.js is the code that contains the functionality for the block in the editor. 
* save.js is the code that contains the functionality for the block when it saves it's output. 
* editor.scss is a file containing CSS that styles the appearance of the block in the both the editor and on the front end. It is a Sass file, but also supports regular CSS syntax
* style.scss is a file containing CSS that additionally adds styles to the appearance of the block in the front end. This is also a Sass file that supports regular CSS syntax

This directory also contains a view.js file, which is an extra file that is enqueued when the block is rendered. This file is not specifically required for block development, so if you don't need it, you can delete it and remove the reference to it in the block.json file.

## The build directory

The build directory is where the final block assets are built for distribution. 

You should not edit any of the files in the build directory, as they will be created whenever you run npm run build, or npm run start

Whenever the build process runs, the files in the src directory are compiled and the output is saved in the build directory.

It contains the following files:
* block.json, this is a copy of the block.json file in the src directory
* index.asset.php, this is a file that is generated by the build process and contains a PHP array of dependencies that this block uses. This is similar to when you enqueue a script, and define the dependencies array for that script. 
* index.js, this is the main block javascript file. During the build process, the index.js, edit.js and save.js files are transpiled into this single file. This is the file that's configured as the editorScript property in the block.json file.
* index.css is the main block CSS file. During the build process, the editor.scss files is transpiled into this  file. This is the file that's configured as the editorStyle property in the block.json file.
* style-index.css is the block CSS file that is only enqueued on the front end. During the build process, the style.scss files is transpiled into this file. This is the file that's configured as the style property in the block.json file.

This directory also contains a view.js file, which is an extra file that can be enqueued when the block is rendered, as well as a view.asset.php file, which contains any dependencies that view.js might be required. If you've removed this file from the src directory and the src/block.json file, it will not be included in the build directory.

