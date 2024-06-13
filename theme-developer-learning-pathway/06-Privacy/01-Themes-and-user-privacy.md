# Themes and user privacy

## Introduction (0:00)

https://youtu.be/fsWaaC4ajFI

As the internet has evolved, so have the ways in which user privacy is protected.

In this lesson, you'll discover how themes can impact user privacy, and what you can do to ensure that your theme respects this privacy.

## Internet privacy (0:16)

Various laws and regulations around the world govern how websites can collect and use personal data. 

These laws require companies and site owners to be transparent about how they collect, use, and share personal data. 

They also give individuals more access and choice when it comes to how their own personal data is collected, used, and shared.

In [version 4.9.6](https://wordpress.org/news/2018/05/wordpress-4-9-6-privacy-and-maintenance-release/), WordPress introduced a various privacy related features, primarily to help site owners comply with the European Union's General Data Protection Regulation (GDPR).

The main features introduced in WordPress 4.9.6 included an option to set a Privacy Policy page, a way for users interacting with a site via comments to exclude their personal information from being captured, and tools that site admins can use to better handle user's personal information.

Of these features, the Privacy Policy page and the comment data handling are the most relevant to theme developers.

## Privacy Policy page (1:22)

The Privacy Policy page feature allows site owners to create a Privacy Policy page that explains how their site collects, uses, and shares personal data. This page is automatically created in draft status on new WordPress installs.

Site owners can edit and publish the page, and then the page can then be configured as the privacy page under **Settings > Privacy**.

As a theme developer, you can help site owners by providing a way to link to the Privacy Policy page from the theme. 

This can be done by adding a link to the Privacy Policy page in the footer, or by adding a Privacy Policy link to the site's main menu.

There are a couple of ways to add a Privacy Policy link to a theme.

You can use the following PHP functions:

 - [get_privacy_policy_url()](https://developer.wordpress.org/reference/functions/get_privacy_policy_url/): Retrieves the URL to the Privacy Policy page.
 - [the_privacy_policy_link()](https://developer.wordpress.org/reference/functions/the_privacy_policy_link/): Displays the Privacy Policy page link with formatting, when applicable.
 - [get_the_privacy_policy_link()](https://developer.wordpress.org/reference/functions/get_the_privacy_policy_link/): Returns the Privacy Policy page link with formatting, when applicable.

For example, the following code would display a Privacy Policy link in a div:

```php
<?php the_privacy_policy_link( '<div>', '</div>' ); ?>
```

There are currently no core blocks for adding a Privacy Policy link in the Site Editor.

However, you could create a custom pattern that includes a link to the Privacy Policy page.

```php
<?php
/**
 * Title: Privacy Policy Link
 * Slug: twentytwentyfour/privacy-link
 * Categories: featured
 */
?>
<!-- wp:paragraph {"fontSize":"small"} -->
<?php the_privacy_policy_link() ?>
<!-- /wp:paragraph -->
```

You could then include this pattern in your theme's available block patterns for site admins to add if needed.

## Comment data (2:57)

WordPress posts allow visitors to leave comments. 

This functionality is handled by the Comment Form block in block themes, and the `comment_form()` [function](https://developer.wordpress.org/reference/functions/comment_form/) in classic themes.

When a visitor who does not have a specific account on the site comments on a post, they are asked for their name, email, and website. 

This information is stored locally in the commenter's browser as a browser cookie for two purposes:

1. If they leave another comment on the site, their name, email, and website will be pre-populated into the respective fields.
2. If their comment is held for moderation, they can return to that post and remove the comment before it is approved.

The information stored in this cookie is not essential for the functionality of the site. Therefore, the user needs to be given the choice to opt in to the storage of this personal information. 

For this reason, WordPress outputs a checkbox under the comment form that allows commenters to opt-in to storing this data in the cookie. 

This checkbox will be unchecked by default, as opt-in is an action the user must specifically approve.

Fortunately, this new checkbox field is automatically added to comment forms displayed using the Comment Form block or the `comment_form()` function.

However, as a theme developer, it is important that you test your theme to ensure that this checkbox is displayed correctly anywhere the comments form is displayed, and that it functions as expected.

## Further reading  (4:31)

For more information on user privacy and how themes can impact it, check out the [Privacy](https://developer.wordpress.org/themes/advanced-topics/privacy/) page under Advanced Topics in the Theme Developer Handbook.

## Youtube Chapters

00:00 Introduction
00:16 Internet privacy
01:22 Privacy Policy page
02:57 Comment data
04:31 Further reading
