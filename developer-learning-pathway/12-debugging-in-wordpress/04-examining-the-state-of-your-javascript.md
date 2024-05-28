# Examining the state of your JavaScript

When you're debugging JavaScript, it can be helpful to examine the state of your code at various points to see what's happening. 

Let's look at some ways you can examine the state of your JavaScript code.

## Server side vs client side

One of the main differences between debugging PHP and JavaScript is that PHP is a server-side language, so you can log messages to a file on the server. 

JavaScript, on the other hand, is a client-side language, so you can't log messages to a file in the same way.

Fortunately there are other ways to examine the state of your JavaScript code, such as using the browser's developer tools, specifically the Console tab.

## Opening the browser's developer tools

Most modern browsers come with built-in developer tools that allow you to inspect and debug the elements of a web page. 

Depending on the browser you're using and the operating system you're on, you can usually open the developer tools by pressing specific keys, like `F12`,  `Ctrl+Shift+I` or `Cmd+Option+I`.

If you're not sure how to open the developer tools in your browser, you can find handy instructions on [MDN Web Docs](https://developer.mozilla.org/en-US/docs/Learn/Common_questions/Tools_and_setup/What_are_browser_developer_tools).

Once option, switch to the Console tab, which is where any errors on the page are logged. 

It is also where you can log messages from your JavaScript code to help you debug it.

## The console object

The console object is what allows you to log messages to the browser's console. It has several methods you can use to log different types of messages.

Here are some of the most commonly used methods:

## `console.log()`

The `console.log()` method is used to log messages to the console. You can pass any type of data to it, such as strings, numbers, objects, or arrays.

```javascript
(function() {
	let hello = 'Hello, World!';
	console.log(hello)
})(); // Outputs: "Hello, World!"
```