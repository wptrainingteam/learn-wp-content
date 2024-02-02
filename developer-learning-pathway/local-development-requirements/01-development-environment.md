# Development environment

## Introduction

To develop with WordPress, there are a few things you will need to install on your computer, the first of which is a local development environment.

There are many options for local development environments, and in this lesson, you'll learn about some of the most popular options.

## A quick intro to terminology

Developers tend to use different terms to refer to the different WordPress environments they might use.

The WordPress site that lives on the internet, and that users interact with, is often referred to as the live or production environment. This is the site where all the final content is published and any custom code is live.

Sometimes, developers will create a separate server environment for testing new features or changes to the site. This is often referred to as the staging environment. This is a copy of the live site that is accessible via the internet, but usually only to the site owner or the developer. It is used to test new features or changes before they are deployed to the live site.

Finally, developers will often have a local environment. This is a copy of the live or staging site that is installed on their computer. This is where they will do the majority of their development work.

## Local development environments

Having a local development environment you are comfortable with allows you to focus on writing code, without having to worry about the complexities of setting up a web server and a database server on your computer. It also allows you to work offline, and test things out without the need to upload the code to a live site. 

Some local development environments also offer additional features, such as a built-in database management tool, or the ability to quickly change PHP versions. 

In the WordPress space, there are typically two types of local development environments: those that are created and maintained by members of the WordPress community, and those that are created and maintained by non-profits or companies. 

[wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) is the local development environment currently recommended by the WordPress developer documentation. 

It requires a working knowledge of the command line, an installation of [Docker](https://www.docker.com/), and [Node.js](https://nodejs.org/en/).

[VVV or Varying Vagrant Vagrants](https://varyingvagrantvagrants.org/) is another local development environment maintained by members of the WordPress community. It also requires a working knowledge of the command line, and an installation of [VirtualBox](https://www.virtualbox.org/) and [Vagrant](https://www.vagrantup.com/).

Another free and open source option is [XAMPP](https://www.apachefriends.org/). XAMPP is a local development environment that is maintained by Apache Friends, a non-profit project created to promote the Apache web server. While not specifically a WordPress local development environment, it includes everything you need, the Apache web server, a database server, and PHP.

Like XAMPP, [MAMP](https://www.mamp.info/en) is another local development environment that is not specifically designed for WordPress, but it includes everything you need to get started. Unlike XAMPP, MAMP is not open source, and is available in both a free and a paid version.

Other WordPress local development environments include [Local WP](https://localwp.com/), and [DevKinsta](https://kinsta.com/devkinsta/). 

Each of these products is created and maintained by their parent company, and includes built in support for deploying your local site to a live site hosted with that company.

## Choosing a local development environment

Choosing a local development environment is a personal choice, and there is no right or wrong answer. 

You will need to review all the features of each option, the pros and cons, and decide which one is best for you.