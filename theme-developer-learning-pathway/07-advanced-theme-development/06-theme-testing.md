# Testing your theme

## Introduction

Before releasing your theme to the public, you should test it thoroughly to ensure that it works as expected.

Let's look at some things you should do before releasing your theme, including:

- Testing your theme in a local development environment
- Using Theme Test Data to ensure your theme can handle a variety of content
- Useful plugins for debugging and testing
- Tools for Accessibility and Performance testing

## Testing your theme in a local development environment

All the lessons in the Learn WordPress developer pathways have recommended the use of a local development environment. 

However, it is possible to develop a block theme using a live web server, especially if you're using Create Block Theme.

It's therefore a good idea to also test your theme in a local development environment. One of the benefits of this is that you can enable the WordPress debugging options, and see if your theme generates any errors or warnings.

If you already have a local development environment set up, you can use that. If not, take a look at the [Local Development Environments](https://learn.wordpress.org/lesson/local-development-environment/) lesson for some options.

## Using Theme Test Data

Part of testing your theme includes ensuring that it can handle a variety of content. 

Instead of manually creating different types of content, you can download and install the WordPress project Theme Unit Test Data. 

This test data includes a set of posts, pages, and other content that you can use to test your theme.

To install the Theme Unit Test Data, follow these steps:

1. Download the [Theme Unit Test Data](https://github.com/WordPress/theme-test-data/blob/master/themeunittestdata.wordpress.xml) file from the GitHub repository, by clicking on the **Download raw file** button, and saving the file to your computer's hard drive.
2. In your WordPress admin, go to **Tools > Import**.
3. Under the WordPress section, click on **Install Now**. This will install the WordPress XML importer.
4. Once it's installed, click on the **Run Importer** link.
5. Click on the **Choose File** button, and select the Theme Unit Test Data file you downloaded earlier.
6. Click on the **Upload file and import** button.
7. Assign the content to an existing user or create a new user to assing it to, and make sure to check the **Download and import file attachments** checkbox. Then click on the **Submit** button.

Once the import is complete, you should see a variety of content on your site, including posts, pages, and other content types. 

You can then test your theme with this content to ensure that it displays correctly.

## Useful plugins for debugging and testing 

There are several community plugins that you can use during theme development to help you test and debug your theme.

During theme development there are several plugins that you can use to help find and fix issues, including:

- [Query Monitor](https://wordpress.org/plugins/query-monitor/)
- [Debug Bar](https://wordpress.org/plugins/debug-bar/)
- [Log Deprecated Notices](https://wordpress.org/plugins/log-deprecated-notices/)

When you're ready to distribute your theme, the [Theme Check](https://wordpress.org/plugins/theme-check/) plugin evaluates your theme code against the [WordPress Theme Review Guidelines](https://make.wordpress.org/themes/handbook/review/required/). 

Following these guidelines is a requirement for submitting a theme to the WordPress theme directory.

Even if you don't plan to distribute your theme through the theme directory, it's still a good baseline test of your theme's structure and code.

## Cross browser testing

For effective cross-browser compatibility testing of block themes, it is important test your theme across all major browsers, including Firefox, Chrome, Safari, and Edge.

You can also use third party tools like [BrowserStack](https://www.browserstack.com/) or [BitBar](https://smartbear.com/product/bitbar/) to test your theme on different browsers and devices

Additionally, most modern browsers have developer tools that allow you to inspect everything about the pages being rendered, including JavaScript errors, network usage, and the performance of your theme.

Using the browser developer tools also allows you to use the responsive design mode, which offers you the option of testing your theme on different devices and screen sizes.

## Tools for Accessibility and Performance testing

Ensuring that your theme is accessible is a key aspect of responsible theme development.

You should strive to make sure your theme meets the [WordPress Accessibility Guidelines](https://make.wordpress.org/accessibility/handbook/). This includes aspects like keyboard navigation, screen reader compatibility, and proper use of ARIA roles.

If you haven't already, check out the module on Theme Accessibility to learn more about the importance of making your theme accessible, common accessibility issues, and how to avoid them.

You should also test your theme to ensure that it's not loading unnecessary resources, and that it's optimized for performance.

To do this, you'll need to install your theme on a live website, and use tools like [Google Lighthouse](https://developers.google.com/web/tools/lighthouse), [PageSpeed Insights](https://pagespeed.web.dev/), or [GTmetrix](https://gtmetrix.com/) to test your theme's performance. Be sure to not only test the homepage, but also other templates for things like pages and posts.

## Further reading

For more information on testing your theme, including all the tips and tools mentioned in this lesson, check out the [Testing](https://developer.wordpress.org/themes/advanced-topics/testing/) page under Advanced Topics in the Theme Developer Handbook.