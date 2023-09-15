# TOC
- Welcome
- Using create-block to scaffold your first block
- Why use create-block
- What does create-block generate
- Understanding wp-scripts
- Internationalisation
- Using create-block with an existing plugin
- Activity
- Wrap up

## Welcome

Welcome to the second week of the Learn WordPress course cohort.

[Introduction video]

Hi there, and welcome to week 3 of this course cohort on building WordPress blocks.

Last week you set up your local development environment with Node.js and npm, optionally using nvm. 

This week, you will be using the create-block tool to scaffold your first block.

Create Block is an officially supported tool for scaffolding a WordPress plugin that registers a block. 

It generates PHP, JS, CSS code, and everything you need to start the project. It also integrates a modern build setup with no configuration.

I love tools that make my life easier, and create-block is such a tool. Because it generates your code in a standardised way, it means that you can focus on creating your block, and not worry all the manual set up.

This week, you will also have an activity to complete towards the end of the module. This activity will take everything you've learned this week, and put it into practice. 

As always, if you have any questions about anything, feel free to reach out to me in Slack.

Happy coding!

[ Transcript ]

## Introducing create-block

## Why use create block

Now that you've generated your very first WordPress block, let's discuss why it's a good idea to use create-block to scaffold your blocks.

create-block is officially supported by the WordPress project, and maintained by a team that forms part of the core developers of the block editor. This means that it's actively maintained, and will be kept up to date with the latest changes in WordPress. 

For example, since the block editor was introduced to WordPress in version 5.0, two action hooks have existed to enqueue CSS for the block editor, enqueue_block_editor_assets (to only load assets in the editor) and enqueue_block_assets (to load assets for both the editor and the front end).

Many developers have been using the enqueue_block_editor_assets hook to enqueue their block assets like CSS or JavaScript files, when the editor is loaded (either in the post editor or in the site editor).

In WordPress 6.3, the block editor was updated to be loaded in an iframe to isolate it from the rest of the admin screen. This has a [number of benefits](https://make.wordpress.org/core/2021/06/29/blocks-in-an-iframed-template-editor/#comments), but it does mean that the `enqueue_block_editor_assets` hook should no longer be used to add stylesheets for editor content.

However, you'll notice in the scaffolded block plugin, neither the `enqueue_block_editor_assets` nor the `enqueue_block_assets` hooks are used to enqueue the block stylesheet. Instead, the relevant stylesheets are specified in the block.json file, which is read when you pass the build directory to the `register_block_type` function in PHP. 

create-block also defines your dependencies and sets up your build step automatically for you. You'll learn more about these terms in future lessons, but for now, just know that it saves a lot of time scaffolding your new block this way, compared to doing everything manually yourself.

There are a few other reasons to use create-block, but for now, let's take a look at what it creates, and how all the pieces fit together. 

## What does create-block generate

## Understanding wp-scripts

## Internationalisation

## Using create-block with an existing plugin

## Activity

## Wrap up


