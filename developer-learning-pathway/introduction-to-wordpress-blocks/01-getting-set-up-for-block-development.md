# Getting set up for block development

WordPress' blocks are the default way in which a WordPress site stores and represents content.

Let's take a quick look at what blocks are, how they work, and what you need to get started developing them.

## WordPress blocks

Blocks are used in the Post and Page Editors when creating and editing content, as well as the Site Editor when creating and editing theme templates or patterns. 

Under the hood, blocks are made up of combination of an HTML comment with a specific format that defines the block, and if required, HTML entities to represent the block content.

## The structure of a block

Let's take a look at an example of a block in a post. 

In your local WordPress installation, create a new post, give it a title, and type some text in the Post Editor. 

Then, click on the options icon on the top right of the post, and select the code view. 

```html
<!-- wp:paragraph -->
<p>This is some content in a paragraph block.</p>
<!-- /wp:paragraph -->
```

As you can see, the HTML paragraph tag is wrapped in HTML comments with the name wp:paragraph. These wp:paragraph comments are how WordPress knows this is a paragraph block. The actual content of the block is everything inside the wp:paragraph tags, in this case the HTML paragraph tag, and the content inside that tag. 

Now click the "Exit code editor", and in the sidebar, apply a background color to the block. 

Now switch back to the code view, and notice how the wp:paragraph tag contains some extra data

```html
<!-- wp:paragraph {"backgroundColor":"accent-5"} -->
<p class="has-accent-5-background-color has-background">This is some content in a paragraph block.</p>
<!-- /wp:paragraph -->```
```

The `backgroundColor` property is added to the block wrapper in a special format called JSON. When this post is rendered on the front end, WordPress converts that to a CSS class to be applied to the block.

## Getting set up

Besides your local WordPress installation and a code editor, there are some additional tools you need to develop blocks.

You need a terminal, to run commands.

And you need an installation of Node.js and npm. 

## All about the terminal

The first thing you will need is access to a terminal to run commands.

The terminal is a tool that allows you to interact with your computer using text commands. It is also known as the command line, or the command prompt. Your operating system will determine what the terminal looks like, and what commands are available to you.

On macOS, the default terminal is called Terminal, and it is located in your **Applications -> Utilities** folder. You can also launch it by clicking on the Launchpad app and searching for Terminal.

On most Linux distributions, the default terminal is also called Terminal, and it is usually located in the **Applications** menu.

On Windows, the default terminal is called Command Prompt, and it is located in the Start menu.

However, we recommend using a terminal application for Windows called PowerShell, because it is possible to configure PowerShell to work similarly to the terminal on MacOS and Linux.

Some Windows versions do come with a version of Powershell installed, but recommend installing the version from the Microsoft site. To download PowerShell, go to https://learn.microsoft.com/en-gb/powershell/ and click on the **Download Powershell** button, and install the executable file that you download.

Once it's installed, you can launch it by searching for PowerShell in the Start menu.

Once you have a working terminal, you will be able to install the software you need to start developing blocks.

## Node.js and npm

Block development relies on the use of a JavaScript framework called [React](https://react.dev). To use React you need to install [Node.js and npm](https://nodejs.org/en) on your local computer.

## Installing Node.js.

Because npm is bundled with Node.js, you just need to install Node.js to get up and running.

While there are a number of ways to install Node.js with npm, we recommend using a tool called nvm, which stands for Node Version Manager. 

You can find details about nvm at [github.com/nvm-sh](https://github.com/nvm-sh).

This will enable you to install and use different versions of Node.js, depending on the requirements of the software you're working with.

## Installing NVM on MacOS and Linux

If you are using MacOS or Linux, you can open your default terminal application, and install nvm by running the nvm install script, which you can copy from the [nvm documentation](https://github.com/nvm-sh/nvm/blob/master/README.md#installing-and-updating).

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
```

Once it is installed, you can use the `nvm install` command to install the Node.js and npm versions you need.

## Installing NVM on Windows

If you are on a Windows machine, you can install nvm via the Chocolatey package manager for Windows, specifically the Chocolatey CLI.

First open Powershell with administrator permissions by right-clicking on the Powershell menu item and selecting Run as administrator.

Browse to the Chocolatey CLI setup documentation, and scroll down to the **Install with PowerShell** Instructions.

Copy the instructions and right click in the Powershell window to paste them.

```
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
```

Hit the Enter key to run the command.

Once the Chocolatey CLI, also known as choco, is installed, use the following command to install nvm.

```
choco install -y nvm
```

Once nvm is installed, use the `nvm install` command to install the required Node.js and npm versions.

## NVM usage

At the time of this recording, the current stable LTS (long term support) version of Node.js is version 20, but check the Node.js website to see if a newer version is out when you're installing Node.js.

To install Node.js and npm, use the `nvm install` command with the version number you want to install.

```bash
nvm install 20
```

It is also possible to install the latest LTS version by running the following command.

```bash
nvm install --lts
```

You can then run `nvm list` to see which versions of Node.js are installed.

```bash
nvm list
```

Because nvm allows you to run multiple versions of Node.js, you need to tell nvm which version you want to use. You can do this by running the `nvm use` command, followed by the version number.

```bash
nvm use 20
```

This will set the version of Node.js for the current terminal instance to version 20.

Additionally, it's possible to run the `npm use` command with the LTS option.

```bash
nvm use --lts
```

Finally, if you do have more than one version of Node.js and npm installed, you can set the default version to use by running the `nvm alias default` command.

```bash
nvm alias default 20
```

And then check which versions of Node.js and npm are enabled by running the following commands

```bash
node -v
npm -v
```

This is useful if you are working on multiple projects that require different versions of Node.js and npm.

Now that you have all the required tools, you can begin your block development journey.

## Additional resources

The WordPress Developer documentation has an entire section dedicated to the [Block Editor](https://developer.wordpress.org/block-editor/), which contains a wealth of information on blocks, block development, as well as the various packages available to block developers. 

It's also a good idea to read the [Fundamentals of Block Development](https://developer.wordpress.org/block-editor/getting-started/fundamentals/) section to get a better understanding of the process. 