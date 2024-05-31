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

If you're not sure how to open the developer tools in your browser, you can find handy instructions on the MDN Web Docs site under [Guides > Common questions > Tools and setup
What are browser developer tools?](https://developer.mozilla.org/en-US/docs/Learn/Common_questions/Tools_and_setup/What_are_browser_developer_tools).

[Note: take the user through this navigation journey to where the page exists]

Once you have developer tools open, switch to the Console tab, which is where any errors on the page are logged. 

It is also where you can log messages from your JavaScript code to help you debug it.

## The console object

The console object is what allows you to log messages to the browser's console. It has several methods you can use to log different types of messages.

Here are a few of the most commonly used methods:

The `console.log()` method is the most popular, as it can be used to log pretty much anything to the console. You can pass all sorts of data types to it, such as strings, numbers, arrays, or objects.

Here is an example of logging a simple string variable to the console.

```javascript
(function() {
	let hello = 'Hello, World!';
	console.log(hello)
})(); 
```

[Note: for the below examples of the output, it would be ideal if you showed the WordPress site and the Developer tools side by side]

If you open Developer Tools and select the Console tab, the contents of the variable will be logged to the console.

The console output also includes a link to the location of the code in the source file, which can be helpful for tracking down where the message was logged from.

You can also log more complex data types, such as arrays or objects.

```js
(function() {
	let fruits = [ 'apple', 'banana', 'orange' ];
	console.log( fruits );
	
	let person = {
		firstName: "Jane",
		lastName: "Smith",
		age: 30,
		eyeColor: "brown"
	}
	console.log( person );
})();
```

When viewing this in the console, you'll see the array and object logged as expected. You're also able to expand the array or object to see its properties, as well as any JavaScript methods that are available on that data type.

There are a number of other methods you can use to log messages to the console, such as `console.error()`, `console.warn()`, and `console.info()`.

One of the most underused methods is `console.table()`, which logs an array or object as a table, making it easier to read.

```js
(function() {
    let people = [
        { name: 'Jane', age: 30 },
        { name: 'John', age: 40 },
        { name: 'Alice', age: 25 }
    ];
    console.table( people );
})();
```

This is especially useful when you have an array of objects, and you want to see the data in a more readable format.

## Further reading

To read more about the browser's console object and its methods, make sure to visit the [MDN Web Docs page](https://developer.mozilla.org/en-US/docs/Web/API/Console). It contains a full list of all the console methods, as well as useful examples.