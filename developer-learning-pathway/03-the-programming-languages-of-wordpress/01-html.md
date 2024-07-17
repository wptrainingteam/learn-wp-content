# HTML, the foundation of the internet

# Introduction

One of the first programming languages that you'll learn as a WordPress developer is HTML. 

In this lesson, you'll learn what HTML is, how it is used in WordPress, and where to find more information on writing HTML.

## What is HTML?

HTML is synonymous with the web. The originator of the internet designed HTML to be used to create web pages. 

Whenever you visit a website in a browser, whether it's one of the [biggest news portal](https://wordpress.org/showcase/time-com/) in the world, or your local [nonprofit's home page](https://wordpress.org/showcase/helpcode/), the document you are viewing is written in HTML. 

HTML stands for HyperText Markup Language and is used to describe the structure of a web page. 

HTML is made up of elements. Elements are the building blocks of HTML documents.

An HTML element usually has a start tag, an end tag, and content in between the tags.

Here's an example of an HTML document:

```html
<html lang="en">
    <head>
        <title>My HTML document</title>
    </head>
    <body class="main">
        <h1>This is the heading of my HTML document</h1>
        <img src="https://picsum.photos/250" alt="A randomly selected image">
        <p>This is the content of my HTML document.</p>
    </body>
</html>
``` 

In the above example, the `<html>` tag at the top is a start tag, and the `</html>` tag at the bottom is the end tag. Notice that the end tag starts with a forward slash. The content in between these tags is the content of the `<html>` element.

HTML elements can be nested inside each other. In the above example, the `<head>` element is nested inside the `<html>` element, and the `<title>` element is nested inside the `<head>` element.

HTML elements are also semantic, which means that each tag has a specific meaning, and should be used in a specific way. For example, the `<h1>` element is a heading element, and the `<p>` element is a paragraph element. 

HTML elements can also have attributes. Attributes are used to provide additional information about an element. In the above example, the `<body>` element has an attribute called `class` with a value of `main`.

Certain elements allow you to include media, such as images, audio, and video. In the above example, the `<img>` element is used to include an image on the page. The `src` attribute is used to specify the location of the image, and the `alt` attribute is used to provide alternative text for the image.

HTML elements can also be self-closing. In the above example, the `<img>` element is self-closing, and you will notice that it doesn't have an end tag. 

When you visit a web page in a browser, the browser reads the HTML document and displays the content in the browser window. The browser reads the HTML document from top to bottom, and left to right.

By default, each of the elements is displayed in a different way. For example, the `<h1>` element is displayed as a heading, and the `<p>` element is displayed as a paragraph.

HTML is used everywhere across a WordPress site, from the dashboard, to the theme that powers the front end. Even plugins make use of HTML to display content. 

## Accessible HTML

When writing HTML, it's important to write accessible HTML. Accessible HTML is HTML that is written in a way that makes it easy for people with disabilities to use.

For example, if you are using an image to display a logo, you should include alternative text for the image. This allows people who are using a screen reader to understand what the image is.

Additionally, you should always use semantic HTML elements, and use them in the correct way. For example, if you are creating a heading, you should use a heading element, not a paragraph element.

## Additional Resources

For more information about HTML, you can visit the following online resources:

- [HTML on MDN](https://developer.mozilla.org/en-US/docs/Web/HTML)
    - [Accessible HTML on MDN](https://developer.mozilla.org/en-US/docs/Learn/Accessibility/HTML)
- [HTML on Web.dev](https://web.dev/learn/html)
    - [Accessible HTML on Web.dev](https://web.dev/learn/accessibility)
- [freeCodeCamp Responsive Web Design Course](https://www.freecodecamp.org/learn/2022/responsive-web-design/)

## YouTube chapters

0:00 Introduction
0:20 What is HTML?
3:11 Accessible HTML
3:50 Additional Resources