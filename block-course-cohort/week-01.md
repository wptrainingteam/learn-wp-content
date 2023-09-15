# TOC
- Welcome
- Getting set up
- All about the terminal
- Package managers for your operating system
- nvm, node, npm, who, what, why?
- Wrap up

## Welcome

Welcome to the first week of the Learn WordPress course cohort. 

[Introduction video]

[ Transcript ]

Important links
- https://make.wordpress.org/handbook/community-code-of-conduct/
- Community incident response team email - reports@wordpress.org

## Getting set up

The focus for this week's module is to get your development environment set up.

The first thing you will need is a way to run WordPress locally on your computer, also known as a local WordPress install or local WordPress development environment. There are a number of ways to do this, you might even already be using one. 

If you don't have one set up yet, there are a few options available to you. Check out this tutorial which covers some popular options. 

https://learn.wordpress.org/tutorial/local-wordpress-installations-for-beginners/

If you still can't decide, we suggest the following two:
- LocalWP
- WampServer (on Windows)

The next thing you will need is to prepare this environment for block development. 

Because block development relies on the use of React, you will need to install Node.js and npm. You will also need a working terminal to run any build commands, to transpile your code.

To start with, take a look at this tutorial which gives you an overview of what you will need. 

Don't worry if any of this is unfamiliar to you, during the next few lessons we will be covering everything in more detail.

## Installing Node.js and npm

https://learn.wordpress.org/tutorial/installing-node-js-and-npm-for-local-wordpress-development/

## All about the terminal

The terminal is a tool that allows you to interact with your computer using text commands. It is also known as the command line, or the command prompt. Your operating system will determine what the terminal looks like, and what commands are available to you.

On macOS, the default terminal is called Terminal, and it is located in your Applications -> Utilities folder. You can also launch it by clicking on the Launchpad app and searching for Terminal.

[image]

On Linux distributions, the default terminal is also called Terminal, and it is usually located in the Applications menu.

[image]

On Windows, the default terminal is called Command Prompt, and it is located in the Start menu. 

However, we recommend using a terminal application for Windows called PowerShell, because it is possible to configure PowerShell to work similarly to the terminal on MacOS and Linux.

To download PowerShell, go to https://learn.microsoft.com/en-gb/powershell/ and click on the Download Powershell button, and install the executable file that you download.

Once it's installed, you can launch it by searching for PowerShell in the Start menu.

[image]

Once you have a working terminal, you will be able to install the software you need to start developing blocks.

## Why install Chocolatey for Windows?

If you are running macOS or Linux, you can skip this lesson.

In the Installing Node.js and npm lesson, we suggested using Chocolatey on Windows to install nvm, to then install Node.js and npm. But we also mentioned that you can use something called [nvm-windows](https://github.com/coreybutler/nvm-windows). 

So why recommend Chocolatey?

The main reason we suggested Chocolatey is to get you comfortable using the terminal. Later on in this course, there will be times when you will need to use the terminal to run commands, and so we wanted to get you used to using it as soon as possible.

There are however a few advantages to using a package manager like Chocolatey on Windows:

- It makes it easier to install and update software, as long as the software is available as a Chocolatey pacakge, you can install, update or delete it with a single command.
- When installing anything, Chocolatey, like other package managers, will also install any dependencies that the software needs to run.
- It also makes it easier to keep track of what software you have installed on your computer. 

## Why install nvm to install node.js, and npm

å
## Wrap up

