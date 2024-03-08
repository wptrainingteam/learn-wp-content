# JavaScript, creating great experiences

## Introduction

JavaScript is a programming language that is used to add interactivity to web pages. 

Let's learn more about how JavaScript works, and how it can be used in a WordPress site.

## What is JavaScript?

JavaScript is a client side scripting language, which means that it is interpreted by the browser. 

To see a simple example of what JavaScript can do, let's look at the HTML page from the previous lessons, and add a button which uses JavaScript change the color of the heading element:

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
        <button>Change heading color to blue</button>
    </body>
    <script>
        const button = document.querySelector('button');
        const heading = document.querySelector('h1');
        
        button.addEventListener('click', () => {
            heading.style.color = 'blue';
        });
    </script>
</html>
```

In this example:
1. A button element has been added to the document, with the text "Change heading color to blue".
2. At the bottom of the html document there is a new `<script>` element. This element is used to add JavaScript to the document.
3. A variable called `button` has been created, which stores a reference to the button element by using the `document.querySelector()` method, and passing in the CSS selector for the button element, in this case button
4. A variable called `heading` has been created which stores a reference to the heading element by using the `document.querySelector()` method, and passing in the CSS selector for the heading element, in this case h1
5. An event listener has been attached to the button element. This event listener listens for the click event, and when the event is fired (the button is clicked), it changes the color of the heading element to blue.

You will notice that the JavaScript has been added to the document after the rest of the HTML elements. This is because the JavaScript needs to be able to access the HTML elements in order to work. 

Because the browser reads the HTML document from top to bottom, adding the JavaScript at the bottom of the document ensures that the HTML elements have been loaded into the browser before the JavaScript is run.

You will also notice that if you refresh the page, the heading reverts back to its original color. This is because the JavaScript can only affect the page once it's loaded in the browser, but those changes are not persistent. 

Like CSS, it is possible to add JavaScript to a document using an external file. This is the preferred way to add JavaScript to a document.

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
        <button>Change heading color to blue</button>
    </body>
    <script src="script.js"></script>
</html>
```

Then you would move the JavaScript from the `<script>` element in the document to the `script.js` file.

```
const button = document.querySelector('button');
const heading = document.querySelector('h1');
        
button.addEventListener('click', () => {
    heading.style.color = 'blue';
});
```

JavaScript is used in quite a number of places across a WordPress site. One of the biggest uses of JavaScript is in the new block editor, which is powered by a JavaScript framework called React. 

There are also a number of external JavaScript libraries that are used in WordPress, which you can find listed at the bottom of the credits page in the WordPress dashboard. 

Additionally, like with CSS, WordPress allow you the flexibility to add custom JavaScript or external JavaScript libraries to your plugins and themes.

## Additional Resources

For more information about JavaScript, you can visit the following online resources:

- [JavaScript on MDN](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
- [freeCodeCamp JavaScript Algorithms and Data Structures certification](https://www.freecodecamp.org/learn/javascript-algorithms-and-data-structures/)


## YouTube chapters

0:00 Introduction
0:15 What is JavaScript?
0:35 Using JavaScript
3:16 Additional Resources