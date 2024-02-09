# CSS, styling the web

## Introduction

If HTML is the structure, then CSS is the style of the web. 

In this lesson, you'll learn how CSS is used with HTML, and where to find more information on CSS.

## What is CSS?

CSS stands for Cascading Style Sheets. If HTML describes the structure of a web page then CSS describes the style of a document.

CSS is used to define the colors, fonts, and layout of a web page.

Let's take a look at the HTML document from the HTML lesson:

```
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

In the above example, the HTML document is unstyled. It is displayed in the browser using the default browser styles. But you can alter the styles using CSS.

For example, you can change the color of the heading element to red, and change the font size of the paragraph element to 20 pixels.

```
<html lang="en">
    <head>
        <title>My HTML document</title>
    </head>
    <body class="main">
        <h1 style="color: red;">This is the heading of my HTML document</h1>
        <img src="https://picsum.photos/250" alt="A randomly selected image">
        <p style="font-size: 20px;">This is the content of my HTML document.</p>
    </body>
</html>
```

Adding CSS to elements using the `style` attribute is known as inline styles, but it is not the best way to style an HTML document. 

Instead, you can use a `<style>` element to add CSS to the document.

```
<html lang="en">
    <head>
        <title>My HTML document</title>
        <style>
            h1 {
                color: red;
            }
            p {
                font-size: 20px;
            }
        </style>
    </head>
    <body class="main">
        <h1>This is the heading of my HTML document</h1>
        <img src="https://picsum.photos/250" alt="A randomly selected image">
        <p>This is the content of my HTML document.</p>
    </body>
</html>
```

It's also possible to add CSS to a document using an external stylesheet. This is the preferred way to add CSS to a document.

``` 
<html lang="en">
    <head>
        <title>My HTML document</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="main">
        <h1>This is the heading of my HTML document</h1>
        <img src="https://picsum.photos/250" alt="A randomly selected image">
        <p>This is the content of my HTML document.</p>
    </body>
</html>
```

In the above example, the `style.css` file would contain the following CSS:

```
h1 {
    color: red;
}
p {
    font-size: 20px;
}
```

In these examples, the CSS has been targeting specific elements. But it's also possible to target elements based on their attributes.

For example, you can target the `class` attribute of the body element, and add a border to it.

```
.main {
    border: 5px solid black; 
}
```

CSS can do a lot more than just change the color and font size of elements. It can be used to create complex layouts, animations, and more.

Like HTML, CSS is used all across a WordPress site. The dashboard has its own core set of CSS to control its look and feel. 

Themes will ship with a custom set of CSS to style the theme elements. 

Plugins that add content to the front end of a site will also use CSS to style that content. 

WordPress also allows you the flexibility to add your own custom CSS, or to use external CSS frameworks, such as Bootstrap or Tailwind.

## Additional Resources

For more information about CSS, you can visit the following online resources:

- [CSS on MDN](https://developer.mozilla.org/en-US/docs/Web/CSS)
- [CSS on Web.dev](https://web.dev/learn/css)
- [freeCodeCamp Responsive Web Design Course](https://www.freecodecamp.org/learn/2022/responsive-web-design/)


## YouTube chapters

(0:00) Introduction
(0:00) What is CSS?
(0:00) Additional Resources