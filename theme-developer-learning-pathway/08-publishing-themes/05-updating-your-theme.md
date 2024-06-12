# Updating your theme

Once your theme is live on the WordPress.org theme directory, you may need to update it from time to time. This could be to fix bugs, add new features, or generally improve it.

There are two ways to update your theme on WordPress.org, uploading a new zip file, or using Subversion (SVN).

Let's look at both methods, and the pros and cons of each approach.

## Uploading a new zip file

The most straightforward way to update your theme is to upload a new zip file to the WordPress.org theme directory.

Once you've made any changes to your theme files, you'll need to update the version number in the style.css file. 

```css
/*
 * Theme Name: Twenty Twenty-Four
 * Version: 1.0.1
 */
```

This is important, as it tells WordPress that a new version of the theme is available.

You then create a new zip file of your theme directory, using whatever method you used when you first submitted it.

Finally, browse to https://wordpress.org/themes/upload/, and follow the same process to upload the zip file.

## Using Subversion (SVN)

An alternative to the zip upload method is to use Subversion (also known as SVN) to update your theme.

Subversion is a version control system Similar to Git that allows you to manage changes to your code.

When the WordPress [plugin repository was first created](https://wordpress.org/news/2005/01/the-wordpress-plugin-repository/), Subversion was used to allow developers to manage updates to plugins and themes. 

This was mostly because Git and GitHub did not exist, and Subversion was the default version control software open source developers used.

It therefore made sense to use the same system for the WordPress theme directory when it [launched a few years later](https://wordpress.org/news/2008/07/theme-directory/).

To use Subversion to update your theme, you install Subversion on your local machine, and then use the `svn` terminal command to commit your changes to the WordPress.org theme directory.

For macOS users, you can install Subversion using [Homebrew](https://brew.sh/). You will need to install [Homebrew](https://brew.sh/) first if you haven't already, and then run the following command:

```bash
brew install subversion
```

For Windows users, you can download the Subversion command-line client from the [Subversion website](https://subversion.apache.org/).

There are also free Subversion clients available, such as [TortoiseSVN](https://tortoisesvn.net/), which provide a graphical interface for managing your Subversion repositories.

