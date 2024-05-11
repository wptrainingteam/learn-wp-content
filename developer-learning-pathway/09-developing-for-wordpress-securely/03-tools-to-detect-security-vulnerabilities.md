# Tools to detect security vulnerabilities

## Introduction (0:00)

https://youtu.be/xq79225wAvU

To assist you in developing secure WordPress plugins and themes, there are a number of tools available that can help you detect security vulnerabilities in your code. 

In this lesson, you'll learn about some tools available to test your code for security vulnerabilities, and a brief introduction on how to use them.

You will also learn where to find more information about critical security risks to web applications.

## Plugins (0:24)

There are two plugins available in the WordPress.org repository that can help you validate your code.

[Plugin Check](https://wordpress.org/plugins/plugin-check/) is a WordPress plugin that you can use to test whether your plugin meets the required standards for the WordPress.org plugin directory, with one of those requirements being that your plugin code is secure.

Once the plugin is installed and active, you can access the Plugin Check admin page from the Tools menu in your WordPress admin dashboard.

Select the plugin you want to test, and make sure the Security checkbox is checked. 

Click the Check it! button to run the test.

The results are displayed in a table, with a list of issues that need to be addressed, including file names and line numbers.

There is also the [Theme Check](https://wordpress.org/plugins/theme-check/) plugin, which is similar to Plugin Check, but for themes.

Once this plugin is installed, you can access the Theme Check admin page from the Appearance menu in your WordPress admin dashboard.

Again, you select the theme to be checked, and click the Check it! button to run the test.

The results are displayed at the bottom of the page, with a list of issues that need to be addressed.

## Command line (1:42)

If you're looking for something that can be run from the command line, you can use [PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) with the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) rules. 

This combination not only checks your code against the WordPress Coding Standards, but also checks for security vulnerabilities.

To use PHP_CodeSniffer with the WordPress Coding Standards, you need to install [Composer](https://getcomposer.org/), which is a dependency manager for PHP projects.

Installing Composer is outside the scope of this lesson, but you can find instructions on the [Composer website](https://getcomposer.org/) for both [macOS/Linux](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) and [Windows](https://getcomposer.org/doc/00-intro.md#installation-windows) operating systems.

For Composer to work, you also need [PHP](https://www.php.net/) installed on your computer, so that you can use the PHP CLI binary, which allows you to run PHP scripts in the terminal, instead of just in a browser.

You can find ways to install PHP on your system on the [PHP manual](https://www.php.net/manual/en/install.php) under the Installation and Configuration section.

Once you install PHP, make sure to add the path to the PHP CLI binary to the operating system path of your computer, so that you can run PHP commands from anywhere on your computer.

To test this, you can run the following command in your terminal:

```php
php -v
```

And it should output the PHP version.

Once you have Composer installed, you run the following command in your terminal to initialize a new Composer project inside your plugin or theme directory:

```bash
composer init
```

Follow the in terminal instructions to create a new Composer project. 

This will create a `composer.json` file in the current directory, which will contain a list of the dependencies for your project.

Next, follow the instructions in the WordPress Coding Standards rules repository to install all the required dependencies inside your plugin directory:

```bash
composer config allow-plugins.dealerdirect/phpcodesniffer-composer-installer true
composer require --dev wp-coding-standards/wpcs:"^3.0"
```

With this installed, you can run PHP_CodeSniffer against your code using the WordPress standard, it will output a list of issues that need to be addressed.

```bash
vendor/bin/phpcs wp-learn-plugin-security.php --standard=WordPress
```

## Code Editor (4:06)

Depending on your code editor, there are a number of ways to check for vulnerabilities while you code.

For example, Visual Studio Code has a number of extensions that run the PHP_CodeSniffer tool inside the editor, which you can find by [searching phpcs in the VS Code extensions' marketplace](https://marketplace.visualstudio.com/search?term=phpcs&target=VSCode&category=All%20categories&sortBy=Relevance).

Additionally, there are third party services that can scan your code for vulnerabilities inside your code editor, like [Sonar's](https://www.sonarsource.com/) SonarLint tool. 

SonarLint is entirely free for all [open source projects](https://www.sonarsource.com/open-source-editions/). You only pay if you want to analyze private repositories.

SonarLint is available as a plugin for [Visual Studio Code](https://marketplace.visualstudio.com/items?itemName=SonarSource.sonarlint-vscode), [Jetbrains Editors](https://plugins.jetbrains.com/plugin/7973-sonarlint#JetBrains), and [Eclipse](https://marketplace.eclipse.org/content/sonarlint).

When configured correctly, these extensions can highlight issues in your code as you write it, so you can fix them before you commit your code.

## OWASP (5:00)

While not specifically a tool, it's also a good idea to familiarize yourself with the [Open Web Application Security Project (OWASP)](https://owasp.org/).

OWASP is a nonprofit foundation that works to improve the security of software. They provide a number of resources, including the [OWASP Top 10](https://owasp.org/www-project-top-ten/), which is a list of the top 10 most critical security risks to web applications.

Security is an ever-changing landscape, and vulnerabilities evolve over time. By following the [Open Web Application Security Project (OWASP) Top 10 list](https://owasp.org/www-project-top-ten/) you can stay up-to-date with the latest security risks and best practices.


## YouTube chapters

0:00 Introduction
0:24 Plugins
1:42 Command line
4:06 Code Editor
5:00 OWASP