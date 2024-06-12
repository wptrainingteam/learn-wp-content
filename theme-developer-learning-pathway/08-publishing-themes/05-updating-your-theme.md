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

