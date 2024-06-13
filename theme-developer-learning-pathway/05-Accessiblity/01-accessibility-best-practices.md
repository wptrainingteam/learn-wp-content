# Best practices for developing an accessible theme

## Introduction

If you watched [the lesson What is accessibility, and why it's important](#), you know that digital accessibility is a broad term that means ensuring that as many people as possible can use the web.

There are legal and business implications, but more importantly, accessibility guidelines guarantee a better browsing experience for everyone.

The easiest way to deliver accessible themes, plugins, or sites is to think about it from the outset. Include it in the planning phase and educate coworkers (and clients). This way, you won’t only spot the errors early on, you’ll actually prevent many.

In this lesson, you’ll learn about the fundamentals of accessible HTML, and how to apply them to your WordPress themes.

## The fundamentals

HTML is as powerful as it is accessible. 

Try it yourself: open your code editor and create a valid HTML page—marked properly with landmarks, headings, text, buttons, links, forms, etc. 

You can use the code below if you prefer.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My website</title>
</head>
<body>

<header>
    <h1>Welcome to My Website</h1>
    <nav>
        <ul>
            <li><a href="#home">Home</a>
            <li><a href="#about">About</a>
            <li><a href="#services">Services</a>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
</header>

<main>
    <article id="home">
        <h2>Home</h2>
        <p>This is the home section where introductory content can be found.</p>
        <form name="Just a button">
			<button type="button">Click Me!</button>
		</form>
    </article>

    <article id="about">
        <h2>About</h2>
        <p>This section contains information about the website or the person/company behind it.<p><a href="https://example.com">Visit their full profile</a></p>
    </article>

    <article id="services">
        <h2>Services</h2>
        <p>Details about the services offered can be found here.</p>
    </article>

    <article id="contact">
        <h2>Contact</h2>
            <form action="#" method="post" name="contact" autocomplete="on">
				<fieldset>
					<legend>Let's chat</legend>
					<div><label for="name">Name:</label></div>
					<div><input type="text" id="name"></div>
					<div><label for="email">Email:</label></div>
					<div><input type="email" id="email"></div>
					<div><label for="message">Message:</label></div>
					<div><textarea id="message" rows="4" cols="50" spellcheck="true"></textarea></div>
					<div><input type="submit" value="Submit"></div>
				</fieldset>
            </form>
        </article>
</main>

<footer>
    <p>&copy; 2024 My Website</p>
</footer>

</body>
</html>
```

Now run the code through an online accessibility checker like [Web Accessibility Checker](https://websiteaccessibilitychecker.com/checker/index.php) and notice how it detects zero accessibility issues.

The problems typically come later, as you add styles, scripts, or media assets. As a developer of modern WordPress themes, these are exactly the technologies you’ll work with—React-based Blocks, PHP-injected Patterns, and CSS compiled from `theme.json`.

The key is to be mindful of accessibility while you code and design.

Let’s explore some techniques and best practices that developers and designers can adopt when creating custom Blocks, Templates, and Patterns.

## Semantic HTML

Instead of wrapping everything with a `<div>` element, take advantage of the semantically meaningful elements of HTML.

It’ll save you from hacks designed to reinvent the wheel as a rectangle.

## Landmarks

Landmarks are a way to define the structure of a page. They help screen readers navigate the content more easily.

For example, instead of just defining a bunch of divs with classes:

```html
<div class="header">
    <h1>Welcome to My Website</h1>
</div>

<div class="nav">
    <ul>
        <li><a href="#home">Home</a>
        <li><a href="#about">About</a>
        <li><a href="#services">Services</a>
        <li><a href="#contact">Contact</a></li>
    </ul>
</div>
```

You can use landmarks for relevant sections.

```html
<header>
    <h1>Welcome to My Website</h1>
</header>

<nav>
    <ul>
        <li><a href="#home">Home</a>
        <li><a href="#about">About</a>
        <li><a href="#services">Services</a>
        <li><a href="#contact">Contact</a></li>
    </ul>
</nav>
```

If you're designing or building your theme inside the Site Editor, it's also possible to set landmarks there. 

To define Group, Row, and Stack blocks as _content sectioning elements_, select the block, open the block **Settings** and expand the **Advanced** section. 

Scroll down, and set the **HTML ELEMENT** to `header`, `main`, `section`, `article`, `aside`, or `footer`, according to the block’s functionality and position.

## Headings

When adding headings to templates always use headings in the right order, starting from `H2` and continuing in a descending sequence up to `H6`. 

In the Site editor, click on **Document Overview > Outline** to check whether you skipped a level, or if everything is correctly set.

## Buttons versus links

When designing user actions, consider the following as it relates to using buttons or links:

* If you want visitors to perform an action use a `button` element.
* When you want them to navigate to another page, use the `anchor` element (`<a>`).
* If the link should resemble a button, say, for a call-to-action on a landing page, then style it with CSS.

## Forms

If you're designing any forms in your theme, follow these guidelines:

Always wrap the form in a `form` element.

On `input` fields, always set the appropriate type and matching attributes. For example, defining an input type of `tel`, will display a numeric keypad on mobile devices.
 
`<input type="tel" name="phone" id="phone" required>`

Don’t rely on placeholder text to indicate to the user what the input field is for, always provide accessible labels.

Use the `search` element for any search forms.

When designing login forms, you can use the `autocomplete` attribute to help password managers fill out the form.
 
`<input type="password" autocomplete="current-password">`

By default, most browsers will display a focus ring on elements if the element has focus. 

If you want to change the display of these focus rings, use the `:focus-visible` pseudo class, and don't remove focus rings entirely.

## Colors

Be sure to create an accessible color palette with sufficient contrast. 

When editing templates, WordPress alerts you when the text and background color combination you set fails to do that.

Don't use color alone to convey information. Links, for example, should be marked by more than color, and the same goes for focus states. When in doubt, look at the default HTML link and focus styles, and follow those. 

## Typography (https://developer.mozilla.org/en-US/docs/Web/CSS/length)

Set proper font sizes using relative units like `rem`, try to avoid using `px`.

Limit the content width to between 50 and 70 characters; the character or `ch` unit is perfect for that.

Use adequate line spacing based on the font size.
 
## Respect user preferences

A cornerstone of responsive design, [media queries](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_media_queries/Using_media_queries) help create a better user experience. 

Some media query types—like `prefers-color-scheme` or `prefers-reduced-motion` are explicitly accessibility-driven, but there’s also the `pointer`, `hover`, or `scripting` that adjusts components’ behavior to the user’s device.

## To ARIA or not to ARIA

ARIA is short for _Accessible Rich Internet Applications_ framework, and it is often cited as a quick way to make HTML content more accessible to screen readers.

However, the most important rule of ARIA is that you should only use it when you absolutely need to. Ideally, you should always use HTML features to provide the semantics required by screen readers to tell users what is going on.

Misused `aria` attributes make things **less** accessible, so avoid them unless you don’t have control over the HTML or need to handle dynamically generated content.

For more information on this, make sure to visit MDN’s [WAI-ARIA basics](https://developer.mozilla.org/en-US/docs/Learn/Accessibility/WAI-ARIA_basics) section.

## Summary and further reading

Accessibility is forever a work in progress. Even one small improvement can make a big difference for your site visitors, and it has zero negative effects. It will never make their experience worse.

To learn more, visit the W3C Web Accessibility Initiative’s (WAI) [Tutorials](https://www.w3.org/WAI/tutorials/) section.
