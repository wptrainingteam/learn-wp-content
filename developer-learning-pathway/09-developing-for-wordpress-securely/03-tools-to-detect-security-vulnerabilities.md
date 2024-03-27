# Tools to detect security vulnerabilities

## Introduction

To assist you in developing secure WordPress plugins and themes, there are a number of tools available that can help you detect security vulnerabilities in your code. 

In this lesson, you'll learn about some tools available to you.

You will learn about two plugins that can be installed on a local WordPress install, a tool that can be run from your terminal, as well as online service that can scan your code for vulnerabilities.

## Plugins

[Plugin Check](https://wordpress.org/plugins/plugin-check/) is a WordPress plugin available in the WordPress.org plugins directory that you can install and activate on any WordPress site.  

You can use Plugin Checker to test whether your plugin meets the required standards for the WordPress.org plugin directory, with one of those requirements being that your plugin code is secure.

Once the plugin is installed, you can access the Plugin Check admin page from the Tools menu in your WordPress admin dashboard.

Select the plugin you want to test, and make sure the Security checkbox is checked. Click the Check it! button to run the test.

The results are displayed in a table, with a list of issues that need to be addressed, including file names and line numbers.

There is also the [Theme Check](https://wordpress.org/plugins/theme-check/) plugin, which is similar to Plugin Check, but for themes.

Once this plugin is installed, you can access the Theme Check admin page from the Appearance menu in your WordPress admin dashboard.

Again, you select the theme to be checked, and click the Check it! button to run the test.

The results are displayed at the bottom of the page, with a list of issues that need to be addressed.

## Command line

If you're looking for something that can be run from the command line, you can use [PHP_CodeSniffer](https://github.com/PHPCSStandards/PHP_CodeSniffer) with the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) rules. 

This combination not only checks your code against the WordPress Coding Standards, but also checks for security vulnerabilities.

When you run PHP_CodeSniffer against your code with the WordPress Coding Standard sniffs, it will output a list of issues that need to be addressed, including file names and line numbers.

## Online service

If you're looking for an online service to scan your code for vulnerabilities, you can use [SonarCloud Scanner](https://www.sonarsource.com/). 

SonarCloud is entirely free for all [open source projects](https://www.sonarsource.com/open-source-editions/). You only pay if you want to analyze private repositories.

## OWASP

While not specifically a tool, it's also a good idea to familiarize yourself with the [Open Web Application Security Project (OWASP)](https://owasp.org/).

OWASP is a nonprofit foundation that works to improve the security of software. They provide a number of resources, including the [OWASP Top 10](https://owasp.org/www-project-top-ten/), which is a list of the top 10 most critical security risks to web applications.

Security is an ever-changing landscape, and vulnerabilities evolve over time. By following the [Open Web Application Security Project (OWASP) Top 10 list](https://owasp.org/www-project-top-ten/) you can stay up-to-date with the latest security risks and best practices.
