# Installing Node.js and npm for WordPress development

## Learning Outcomes

Why you should install Node.js and npm.
What is required to install Node.js and npm.
How to install Node.js and npm.

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you’re going to learn how to install Node.js and npm on your computer for local WordPress development.

You will learn why you should install this software, what the requirements are, and how to install Node.js and npm on your computer.

## Why install node.js and npm?

Node.js is a JavaScript runtime environment that allows you to run JavaScript code outside of a web browser. While this might not be specifically applicable to developing with WordPress, Node.js also comes bundled with npm. npm is both a command line tool and package manager for JavaScript, and it allows you to install and manage JavaScript packages, aka dependencies, as well as run build tools, execute code linters and automate many other processes during development. Many WordPress development projects rely on npm including Gutenberg, the create-block tool, the wp-env local WordPress environment and the new wp-now local WordPress environment.

Getting Node.js and npm installed on your computer is just like installing other development tooling like SVN or Git. You don't have to install them to develop with WordPress, but ultimately it will make your life easier if you do.

## Requirements

In order to use Node.js and npm, you're going to need access to a terminal to run commands. Depending on your operating system, you might already have an existing terminal installed. 

MacOS and or any Linux users can use the terminal that ships with the operating system. 

If you are a Windows user, and you don't already have a terminal solution, we recommend installing and using PowerShell, as it's more beginner-friendly, and more tailored to the Windows operating system.

To install PowerShell, either open the Microsoft Store, and search for PowerShell, or browse to https://microsoft.com/powershell, browse to Setup and Installation, and then follow the instructions to download and install it.

## Installing Node.js.

Because npm is bundled with Node.js, you just need to install Node.js to get up and running.

While you can browse to the Node.js website and download the installer, we recommend using a tool called nvm, which stands for Node Version Manager, which you can find at github.com/nvm-sh. 

https://github.com/nvm-sh/nvm

This will enable you to install and use different versions of Node.js, depending on the requirements of the software you're working with.

## Installing NVM

### MacOS and Linux

If you are using MacOS or Linux, you can open your default terminal application, and install nvm by running the nvm install script, which you can copy from the [nvm documentation](https://github.com/nvm-sh/nvm/blob/master/README.md#installing-and-updating).

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
```

Once it is installed, you can use the nvm install command to install the Node.js and npm versions you need. 

### Windows

If you are on a Windows machine, you will need to install the Chocolatey package manager for Windows to install nvm.

First open Powershell with administrator permissions by right-clicking on the Powershell menu item and selecting Run as administrator.

Browse to the Chocolatey setup documentation, and scroll down to the **Install with PowerShell** Instructions.

Copy the instructions and right click in the Powershell window to paste them.

```
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
```

Hit the Enter key to run the command.

Once Chocolatey is installed, use the following command to install nvm.

```
choco install -y nvm
```

Once nvm is installed, use the nvm install command to install the required Nodejs and npm versions.

## NVM usage

To install Node.js and npm, use the nvm install command with the version number you want to install. At the time of this tutorial, the current stable version of Node.js is version 18.

```bash
nvm install 18
```

You can also run nvm list to see which versions of Node.js are installed.

```bash
nvm list
```

Because nvm allows you to run multiple versions of Node.js, you need to tell nvm which version you want to use. You can do this by running the nvm use command, followed by the version number.

```bash
nvm use 18
```

This will set the version of node.js for the current terminal instance to version 18. 

You can also set the default version of node.js and npm to use by running the nvm alias command.

```bash
nvm alias default 18
```

And then check which versions of node.js and npm are enabled by running the following commands

```bash
node -v
npm -v
```

This is useful if you are working on multiple projects that require different versions of Node.js and npm.

## Conclusion

And that wraps up this tutorial on installing Node.js and npm. 

Happy coding!


