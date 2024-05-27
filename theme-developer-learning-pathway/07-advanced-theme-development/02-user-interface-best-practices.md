# User Interface Best Practices

## Introduction

https://youtu.be/iWi4qqtz7Bs

When designing a WordPress theme, it's important to consider the user interface of the theme.

In this lesson, you're going to learn about user interface design, as well as some best practices for designing the user interface of your theme.

## What is User Interface Design?

The User Interface (UI) of a website is the space where interactions between humans and your website occur. This is more commonly know as the website front end.

The goal of good user interface design is to allow for effective interactions, also known as a good user experience, so that the website provides good feedback that aids the users decision-making process.

Bad user interface design can lead to a frustrating user experience and a potential loss of site visitors, which is not a great outcome for a user of your theme.

When designing a theme, it's important to ensure that the elements of the theme provide a good user experience.

Let's dive into some best practices for designing the user interface of your WordPress theme.

## Logo Homepage Link

The logo of your theme should link to the homepage of your site.

If you are using the `custom_logo()` function in a classic theme, or the site logo block in a block theme, the logo is automatically linked to the homepage.

If you are using any soft of custom header, you should ensure that the logo links to the homepage.

Here is an example of how you can link the logo to the homepage using the home_url() function:

```php
<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/logo.png" alt="<?php esc_attr_e( 'Home Page', 'textdmomain' );?>" />
</a>
```

## Descriptive Anchor Text

When creating links to things that appear in your theme, make sure to use descriptive anchor text.

The anchor text is the visible text for a link. Descriptive anchor text should give the reader an idea of the action that will take place when clicking it.

Here is an example of bad descriptive anchor text:

```php
The best way to learn WordPress is to start using it. To Download WordPress, <a href="https://wordpress.org/download/">click here</a>.
```

"click here" is not really descriptive of what will happen when hou click the link.

Here is a better way to write the anchor text:

```php
The best way to learn WordPress is to start using it. <a href="https://wordpress.org/download/">Download WordPress</a> to get started.
```

The Download WordPress text is descriptive of what will happen when you click the link.

## Style Links with Underlines

While on the topic of links, good theme design means retaining the default link style displaying with underlines. 

Some designers use CSS to disable underlines for links. However, doing so causes usability and accessibility problems, as it makes it more difficult to identify hyperlinks from the surrounding text.

## Different Link Colors

The use of color on links is a visual cue that text is clickable. 

HTML links are one of the few elements that have states. The two primary states are visited and unvisited. Visited means the user has clicked on the link and visited the page it points to, and unvisited means the user has not yet clicked on the link.

By default, visited links are blue and unvisited links are purple. 

Having different colors for these two states helps users identify the pages they’ve visited before. 

A good trick for taking the guess work out of visited links is to color them 10%-20% darker than the unvisited links.

Additionally, are 3 other states that links can have:

- hover, when a mouse is over an element
- focus, similar to hover but for keyboard users
- active, when a user is clicking on a link

Since hover and focus have similar meanings, sometimes designers give them the same styles.

However, hover and focus have different interaction patterns. 

If you choose a subtle hover state, you should have a more easily identifiable focus state. 

Hovering over a link is a directed activity, where the user knows where they are in the page and only needs to identify whether that spot is linked. 

Focus is an undirected activity, where the user needs to discover where their focus has moved to after shifting focus from the previous location.

Notice how the menu links in the Twenty Twenty-Four theme add the underline style when hovering over them with the mouse, but have a border when focus changes via the keyboard.

## Color Contrast

Color contrast refers to the difference between two colors on screen. 

For example, notice how the default button styling in the Twenty Twenty-Four theme has a dark background with light text. If you were to change the text color to a dark color, the button would be harder to read.

WebAIM, a non-profit web accessibility organization, provides a color [contrast calculator](https://webaim.org/resources/contrastchecker/) to help you determine the contrast in your website design. 

You can enter the background color and foreground color to see if the contrast ratio meets the Web Content Accessibility Guidelines (WCAG) 2.0.

The WCAG 2.0 requires a ratio of 4.5:1 on normal text to be [AA compliant](https://www.w3.org/WAI/WCAG22/quickref/?versions=2.0#qr-visual-audio-contrast-contrast).

## Sufficient Font Size

Make your text easy to read. 

By making your text large enough, you increase the usability of your site and make the content easier to understand. 14px is the smallest text should be.

## Associate Labels with Inputs

When working with elements that accept input, make sure to associate labels with inputs using the for attribute. 

```html
<label for="name">Name</label>
<input type="text" id="name" name="name" placeholder="John Smith" />
```

This will allow the user to click the label and focus on the input field.

## Placeholder Text

Developers will often use placeholders on input elements to show the user an example of what to type. 

```html
<label for="name">Name</label>
<input type="text" id="name" name="name" placeholder="John Smith" />
```

When a user puts their cursor in the field and starts typing, the placeholder text will disappear.

However, when done incorrectly, placeholders can be a user experience and accessibility nightmare. 

So make sure to use placeholders correctly, or not at all.

If you do decide to use a placeholder, make sure that it suggests the type of data a field requires, and not as a substitute for the field label.

## Descriptive Buttons

The web is filled with buttons that have unclear meanings. Remember the last time you used ‘OK’ or ‘submit’ on your login form? 

Choosing better words to display on your buttons can make your website easier to use. Try the pattern [verb] [noun]. For example Submit Form, instead of just Submit.

This describes what will happen when the user clicks the button.

A great example of this is the comments form on the single post template, the use of Post Comment, instead of just Submit 

## Further Reading

For more information on user interface best practices during theme development, you can refer to the [UI best practices doc](https://developer.wordpress.org/themes/advanced-topics/ui-best-practices/) under the Advanced Topics section of the WordPress Theme Developer Handbook.