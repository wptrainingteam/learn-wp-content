# HTML, the foundation of the internet

## What is HTML?

HTML is synonymous with the internet. Since the inception internet, HTML has been used to create web pages. 

Whenever you visit a web page in a browser, whether it's one of the [biggest news portal](https://wordpress.org/showcase/time-com/) in the world, or your local [nonprofit's home page](https://wordpress.org/showcase/helpcode/), the document you are viewing is written in HTML. 

HTML stands for HyperText Markup Language. HTML is a markup language, which means that it is used to describe the structure of a document. 

HTML is made up of elements. Elements are the building blocks of HTML documents.

An HTML element is made up of a start tag, an end tag, and content in between the tags.

For example, the tag that defines the start of an HTML document is `<html>`. 

Here's an example of an HTML document:

```
<html>
    <head>
        <title>My HTML document</title>
    </head>
    <body class="main">
        <h1>My HTML document</h1>
        <img src="https://picsum.photos/250" alt="A randomly select image">
        <p>This is my HTML document.</p>
    </body>
</html>
``` 

In the above example, the `<html>` tag is the start tag, and the `</html>` tag is the end tag. The content in between the tags is the content of the element.

HTML elements can be nested inside each other. In the above example, the `<head>` element is nested inside the `<html>` element, and the `<title>` element is nested inside the `<head>` element.

HTML elements are also semantic, which means that each tag has a specific meaning, and should be used in a specific way. For example, the `<h1>` element is a heading element, and the `<p>` element is a paragraph element. If you want to create a heading on a web page, you should use a heading element, not a paragraph element.

HTML elements can also have attributes. Attributes are used to provide additional information about an element. In the above example, the `<body>` element has an attribute called `class` with a value of `main`.

Certain elements allow you to include media, such as images, audio, and video. In the above example, the `<img>` element is used to include an image on the page. The `src` attribute is used to specify the location of the image, and the `alt` attribute is used to provide alternative text for the image.

HTML elements can also be self-closing. In the above example, the `<img>` element is self-closing. This means that it doesn't have an end tag. Instead, it has a forward slash at the end of the start tag.

When you visit a web page in a browser, the browser reads the HTML document and displays the content in the browser window. The browser reads the HTML document from top to bottom, and left to right.

By default, each of the elements is displayed in a different way. For example, the `<h1>` element is displayed as a heading, and the `<p>` element is displayed as a paragraph.

HTML is used everywhere across a WordPress site, from the dashboard to the theme that powers the front end. Even plugins make use of HTML to display content. 

## Where to find more information about HTML

If you want to learn more about HTML, here are some resources to get you started:
- [HTML on MDN](https://developer.mozilla.org/en-US/docs/Web/HTML)
- [HTML on Web.dev](https://web.dev/learn/html)
- [HTML on W3Schools](https://www.w3schools.com/html/default.asp)

## Accessible HTML

When writing HTML, it's important to write accessible HTML. Accessible HTML is HTML that is written in a way that makes it easy for people with disabilities to use.

For example, if you are using an image to display a logo, you should include alternative text for the image. This allows people who are using a screen reader to understand what the image is.

Additionally, you should always use semantic HTML elements, and use them in the correct way. For example, if you are creating a heading, you should use a heading element, not a paragraph element.

There are also specific attributes that you can use to make your HTML more accessible. For example, using the `alt` attribute to provide alternative text for an image, and the `title` attribute to provide additional information about an element.

## Where to find more information about Accessible HTML

If you want to learn more about Accessible HTML, here are some useful resources:
- [Accessible HTML on MDN](https://developer.mozilla.org/en-US/docs/Learn/Accessibility/HTML)
- [Accessible HTML on Web.dev](https://web.dev/learn/accessibility)