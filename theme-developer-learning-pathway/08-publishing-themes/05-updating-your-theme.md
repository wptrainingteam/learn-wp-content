# Updating your theme

Once your theme is live on the WordPress.org theme directory, you may need to update it from time to time. This could be to fix bugs, add new features, or generally improve it.

There are two ways to update your theme on WordPress.org, uploading a new zip file, or using Subversion (SVN).

Let's look at both methods.

## Uploading a new zip file

The most straightforward way to update your theme is to upload a new zip file to the WordPress.org theme directory.

Once you've made any changes to your theme files, you'll need to update the version number in the `style.css` file. 

```css
/*
 * Theme Name: Twenty Twenty-Four
 * Version: 1.2
 */
```

This is important, as it tells WordPress that a new version of the theme is available.

You then create a new zip file of your theme directory, using whatever method you used when you first submitted it.

Finally, browse to https://wordpress.org/themes/upload/, and follow the same process to upload the zip file.

## Using Subversion (SVN)

An alternative to the zip upload method is to use Subversion (also known as SVN) to update your theme.

[Subversion](https://subversion.apache.org/) is a version control system similar to Git that allows you to manage changes to your code.

When the WordPress [plugin repository was first created](https://wordpress.org/news/2005/01/the-wordpress-plugin-repository/), Subversion was used to allow developers to manage updates to plugins and themes. 

This was mostly because Git and GitHub did not exist, and Subversion was the default version control software open source developers used.

It therefore made sense to use the same system for the WordPress theme directory when it [launched a few years later](https://wordpress.org/news/2008/07/theme-directory/).

To use Subversion to update your theme, you install Subversion on your local machine, and then use it to commit your changes to the WordPress.org theme directory.

One of the benefits of using Subversion is that it allows you to keep track of changes to your theme over time, and easily roll back to a previous version if needed.

You can find the Subversion Repository URL on your theme's page in the directory, under Browse the code.

For example, the URL for the Twenty Twenty-Four theme is:

```
https://themes.svn.wordpress.org/twentytwentyfour/
```

## macOS and Linux

For macOS users, you can install Subversion using [Homebrew](https://brew.sh/). 

You will need to install [Homebrew](https://brew.sh/) first if you haven't already

Once Homebrew is installed you then run the following command in your terminal to install Subversion:

```bash
brew install subversion
```

Linux users can install Subversion using their package manager. For example, on Ubuntu, you can run:

```bash
sudo apt install subversion
```

Once you have Subversion installed, you can use it check out the theme repository from WordPress.org. 

In your terminal, navigate to the directory where you want to store your theme's files, and run the `svn co` (or checkout) command:

```bash
svn co https://themes.svn.wordpress.org/twentytwentyfour/
```

This will download the theme files to your local machine from the Subversion Repository.

The next step is to create a copy of the most recent version of the theme, to create the updated version.

First, navigate to the directory for your theme

```bash
cd twentytwentyfour
```

Then, create a copy of the most recent version of the theme, using the `svn cp` (or copy) command::

```bash
```

```bash
svn cp 1.1 1.2
```

Now, you can make changes to the theme files in the new directory. 

Make sure to update the version number in the `style.css` file to match the new version, and update the changelog in the `readme.txt`.

Once you're ready to commit the new version of the theme, you can run the `svn commit` command:

```bash
svn commit -m “Fix typo on readme.txt”
```

During the commit process, you will be asked for your username and password. This is the same username and password you use to log in to the WordPress.org theme directory.

## Windows

For Windows users, you can download and install [TortoiseSVN](https://tortoisesvn.net/), which provide a graphical interface for managing your Subversion repositories.

TortoiseSVN integrates with Windows Explorer, so you can right-click inside a folder and select the **TortoiseSVN -> Checkout** option to download the theme files to your local machine. 

It's a good idea to create a folder specifically for your theme files, and check out the repository into that folder.

It will ask you to provide the URL of the Subversion Repository, which you can find on your theme's page in the directory.

Once it's checked out, you can create the new version of the theme by either creating a new folder and copying the files across, or by copying and pasting the existing folder, and then renaming it.

Now, you can make changes to the theme files in the new directory.

Make sure to update the version number in the `style.css` file to match the new version, and update the changelog in the `readme.txt`.

Once you're ready to commit the new version of the theme, you can right-click inside the main folder, and select the **TortoiseSVN -> Commit** option.

This will open a dialog box where you can enter a commit message, select all the files to commit, and then click OK to commit the changes.

As with macOS and Linux, you will be asked for your username and password during the commit process. This is the same username and password you use to log in to the WordPress.org theme directory.

## After a successful commit

After a successful commit, you will receive an email from WordPress.org to confirm that the new version of your theme has been uploaded. 

It may take some time to reflect on the WordPress.org directory, but the updated version is usually available within a few hours.
