# Best practices for developing an accessible theme

## Intro

If you watched [the lesson What is accessibility, and why it's important](https://docs.google.com/document/d/1XpGTn5MviVYzND08eLpF__k0_nDJBeGzxuFIuR9F6fQ/edit), you know that digital accessibility is a broad term that means ensuring that as many people as possible can use the web.

There are legal and business implications, but more importantly, accessibility guidelines guarantee a better browsing experience for everyone.

The easiest way to deliver accessible themes, plugins, or sites is to think about it from the outset. Include it in the planning phase and educate coworkers (and clients). This way, you won’t only spot the errors early on, you’ll actually prevent many.

In this lesson, you’ll learn:
 
* How to use HTML and CSS to build accessible themes
* What WordPress does to help
* Which WordPress plugins and other developer tools to use for automated accessibility tests
* And how to run manual accessibility tests

## The fundamentals of web making

HTML is as powerful as it is accessible. 

Try it yourself: open your code editor and create a valid HTML page—marked properly with landmarks, headings, text, buttons, links, forms, etc. 

You can use the code below if you prefer.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample HTML Page</title>
</head>
<body>

<header>
    <div class="container">
        <h1>Welcome to My Website</h1>
    </div>
</header>

<nav>
    <div class="container">
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
</nav>

<main>
    <div class="container">
        <section id="home">
            <h2>Home</h2>
            <p>This is the home section where introductory content can be found.</p>
            <button>Click Me!</button>
        </section>

        <section id="about">
            <h2>About</h2>
            <p>This section contains information about the website or the person/company behind it.</p>
            <a href="https://example.com" target="_blank">Learn more</a>
        </section>

        <section id="services">
            <h2>Services</h2>
            <p>Details about the services offered can be found here.</p>
        </section>

        <section id="contact">
            <h2>Contact</h2>
            <form action="#" method="post">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name"><br><br>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email"><br><br>
                <label for="message">Message:</label><br>
                <textarea id="message" name="message" rows="4" cols="50"></textarea><br><br>
                <input type="submit" value="Submit">
            </form>
        </section>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; 2024 My Website</p>
    </div>
</footer>

</body>
</html>
```

Now run the code through an online accessibility checker like [Web Accessibility Checker](https://websiteaccessibilitychecker.com/checker/index.php) and notice how it detects zero accessibility issues.

The problems typically come later, as you add styles, scripts, or media assets. As a developer of modern WordPress themes, these are exactly the technologies you’ll work with—React-based Blocks, PHP-injected Patterns, and CSS compiled from `theme.json`.

The key is to be mindful of accessibility while you code and design.

Let’s explore some techniques and best practices that developers and designers can adopt when creating custom Blocks, Templates, and Patterns.

## Semantic HTML

Instead of wrapping everything with a `<div>` element, take advantage of the semantically meaningful elements of HTML. It’ll save you from hacks designed to reinvent the wheel as a rectangle.

Instead of this

```html
<div class="header">
    <div class="container">
        <h1>Welcome to My Website</h1>
    </div>
</div>

<div class="main">
    <div class="container">
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
</div>
```

Use this

```html
<header>
    <div class="container">
        <h1>Welcome to My Website</h1>
    </div>
</header>

<nav>
    <div class="container">
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
</nav>
```

You can do it when building inside the Site Editor, too. To define Group, Row, and Stack blocks as _content sectioning elements_, select the block, and click **Settings** > **Advanced**. Scroll down, and set the **HTML ELEMENT** to `header`, `main`, `section`, `article`, `aside`, or `footer`, according to the block’s functionality and position.

Always use headings in the right order, starting from `H2` and continuing in a descending sequence up to `H6`. 

In the WordPress editor, click on **Document Overview > Outline** to check whether you skipped a level.

## Buttons versus links

* If you want visitors to perform an action use a `button` element.
* When you want them to navigate to another page, use the `anchor` element (`<a>`).
* If the link should resemble a button, say, for a call-to-action on a landing page, style it with CSS.

## Forms

* Wrap the form in a `form` element. Use the `search` element for search fields.
* Pick an appropriate `input` type and set the matching attributes.
    * `<input type="tel">`, for example, displays a numeric keypad on mobile devices.
    * `<input type="password" autocomplete="current-password">`, for example, allows password managers to automatically fill it out.
* Don’t rely on placeholder text—provide accessible labels.
* Don’t remove focus rings entirely. Use `:focus-visible` instead.

## Colors

* Create an accessible color palette with sufficient contrast. WordPress alerts you when the text and background color combination you set fails to do that.
* Don't use color alone to convey information. Links, for example, should be marked by more than color. The same goes for focus states.

## Typography

* Set proper font sizes using relative units—avoid pixels (`px`).
* Use adequate line spacing based on the font size.
* Limit the content width to 50–70 characters; `ch` is perfect for that.

## Respect user preferences

A cornerstone of responsive design, [media queries](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries/Using_media_queries) help create a better user experience. Some media query types—like `prefers-color-scheme` or `prefers-reduced-motion` are explicitly accessibility-driven, but there’s also the `pointer`, `hover`, or `scripting` that adjusts components’ behavior to the user’s device.

## The ARIA workaround

Short for _Accessible Rich Internet Applications_ framework, the first rule of ARIA is don’t use it. HTML does it better. Misused `aria` attributes make things **less** accessible, so avoid them unless you don’t have control over the HTML or need to handle dynamically generated content.

For more information, visit MDN’s[ WAI-ARIA basics](https://developer.mozilla.org/en-US/docs/Learn/Accessibility/WAI-ARIA_basics) section.

## Test, fix, repeat

Testing early and repeatedly helps you detect potential violations in new components or pages before they launch.

## Automated tests

Chromium-based browsers come with [Google Lighthouse](https://developer.chrome.com/docs/lighthouse/overview) built into DevTools. Firefox has the [Accessibility Inspector](https://firefox-source-docs.mozilla.org/devtools-user/accessibility_inspector/index.html#accessibility-inspector), and Safari offers [Audit](https://webkit.org/blog/8935/audits-in-web-inspector/).

WebAIM’s [Wave](https://wave.webaim.org/extension/) is a browser extension available for Firefox and Chromium-based.

Lighthouse is also available as a standalone webpage and an NPM package.

Deque Systems’ axe is a [set of accessibility testing tools](https://www.deque.com/axe/): a browser extension, Figma plugin, VS Code extension, code linter, and more.

[Pa11y](https://pa11y.org) is a free open-source alternative for developers.

## WordPress plugins

[Sa11y](https://wordpress.org/plugins/sa11y/), [WP Tota11y](https://wordpress.org/plugins/wp-tota11y/), and [Editoria11y](https://wordpress.org/plugins/editoria11y-accessibility-checker/) are variations of the now-deprecated JavaScript library [Tota11y](https://github.com/Khan/tota11y). The former is also available as an NPM package, and the other two are wrappers for WordPress. Each has a slightly different approach to accessibility checks; test which one fits your needs.

Finally, there’s [Accessibility Checker](https://wordpress.org/plugins/accessibility-checker/) that works in the Post Editor and the front end, surfacing errors, and providing detailed explanations (including the relevant code snippet) and potential fixes. As robust as it is, Accessibility Checker currently only works reliably on production sites.

## Manual testing

1. Try browsing your website with a **keyboard**—no mouse. Use the `tab` key to navigate between links, buttons, and form fields. Press `enter` or `space` to activate these interactive elements.
2. Change the **color scheme** (via the browser’s DevTools) to ensure things look well in dark mode or high contrast mode.
3. Finally, navigate around the site using your device’s built-in **voice control** feature or a dedicated **screen reader** software. This is the preferred—sometimes only—way many visually impaired people use the web.

## Outro

Accessibility is forever a work in progress. Even one small improvement can make a big difference for your site visitors, and it has zero negative effects. It will never make their experience worse.

To learn more, visit the W3C Web Accessibility Initiative’s (WAI) [Tutorials](https://www.w3.org/WAI/tutorials/) section.
