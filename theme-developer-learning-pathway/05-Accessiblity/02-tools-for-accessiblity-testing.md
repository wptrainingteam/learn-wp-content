# Tools for Accessibility testing

## Introduction

Accessibility testing is a crucial part of the theme development process. 

It ensures that a website with your theme installed is usable by everyone, regardless of their abilities. 

This lesson will introduce you to some tools and techniques for testing your theme’s accessibility.

## Test, fix, repeat

Testing early and repeatedly helps you detect potential violations in new components or pages before they launch.

Let's look at some ways to test your theme. 

## Automated tests

Chromium-based browsers come with [Google Lighthouse](https://developer.chrome.com/docs/lighthouse/overview) built into DevTools.
Lighthouse is also available as a standalone webpage and an NPM package.

Firefox has the [Accessibility Inspector](https://firefox-source-docs.mozilla.org/devtools-user/accessibility_inspector/index.html#accessibility-inspector), and Safari offers [Audit](https://webkit.org/blog/8935/audits-in-web-inspector/).

WebAIM’s [Wave](https://wave.webaim.org/extension/) is a browser extension available for Firefox and Chromium-based.

Deque Systems’ axe is a [set of accessibility testing tools](https://www.deque.com/axe/): a browser extension, Figma plugin, VS Code extension, code linter, and more.

[Pa11y](https://pa11y.org) is a free open-source alternative for developers.

## WordPress plugins

[Sa11y](https://wordpress.org/plugins/sa11y/), [WP Tota11y](https://wordpress.org/plugins/wp-tota11y/), and [Editoria11y](https://wordpress.org/plugins/editoria11y-accessibility-checker/) are plugins you can install to test for accessibility issues on a WordPress site. Each has a slightly different approach to accessibility checks; so test them all to see which one fits your needs.

Finally, there’s [Accessibility Checker](https://wordpress.org/plugins/accessibility-checker/) that works in the Post Editor and the front end, surfacing errors, and providing detailed explanations (including the relevant code snippet) and potential fixes. As robust as it is, Accessibility Checker currently only works reliably on production sites.

## Manual testing

Additionally, there are some manual tests you can perform.

1. Try navigating your theme with a **keyboard**—no mouse. Use the `tab` key to navigate between links, buttons, and form fields. Press `enter` or `space` to activate these interactive elements.
2. Change the **color scheme** (via the browser’s DevTools) to ensure things look well in dark mode or high contrast mode.
3. Finally, navigate around the site using your device’s built-in **voice control** feature or a dedicated **screen reader** software. This is the preferred—sometimes only—way, as many visually impaired people use the web.

## Conclusion

Combining an accessibility first approach to development, with the right tools and techniques for testing during development, will help ensure that you create themes that are usable by everyone on the web.
