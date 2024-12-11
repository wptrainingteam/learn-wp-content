# Modern JavaScript

https://youtu.be/mFUM7Ejby7c

## Introduction

As the web has evolved, so has JavaScript. Since it first appeared in 1995, JavaScript has grown from a simple scripting language to a powerful, versatile tool that can be used to build complex web applications.

One of the biggest implementations of JavaScript in WordPress is the Block Editor. In order to develop blocks for the Block Editor, you need to have a good understanding of modern JavaScript, it's syntax, and the tooling that's developed around it.

In this lesson, you'll discover some of these JavaScript technologies and how are used to build blocks for the Block Editor.

## JavaScript technologies

### JavaScript, ECMAScript, and ESNext

Before we continue, let's clarify some terms that are often used interchangeably but have slightly different meanings.

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript) is the programming language that runs in the browser that was created in 1995.

Over time, the term JavaScript has become a bit of an umbrella term that encompasses the language itself, and the browser APIs that are available to it.

The core language of JavaScript is standardized as a something named ECMAScript. These standards are developed by the [ECMA TC39 committee](https://tc39.es/), which collaborates with the JavaScript community to maintain and evolve the definition of JavaScript.

ECMAScript is the term for the language standard, but ECMAScript and JavaScript can be used interchangeably.

The [ECMAScript standard](https://ecma-international.org/publications-and-standards/standards/ecma-262/) is updated with a new edition every year, with new features and syntax being added. These new features are proposed by the TC39 committee.

For example at the time of creating this lesson, the 15th edition of the standard was released in June 2024, and is more commonly referred to as ECMAScript15 or ES15, but can also be referred to as ES2024, after the year it was released. The next version will be ECMAScript16 or ES16, but newer versions are also sometimes also referred to as ESNext. Essentially ESNext refers to whatever the next version of ECMAScript is. You might also see the term's like ES2015+ which refers to all versions of ECMAScript from the 2015 edition onwards.

### ECMAScript and browser support

When a new version of ECMAScript is released, it takes time for browsers to [implement the new features](https://caniuse.com/?search=view%20transitions). This means that you can't always use the latest features in your code and expect it to work in all browsers.

To help with this, tools like [Babel](https://babeljs.io) were created. Babel is a JavaScript compiler that allows you to write code using the latest ECMAScript features, and then compiles it down to an older version of JavaScript that is supported by all browsers.

### Enter node.js and webpack

In addition to the browser, JavaScript can also be run on a server or your computer using [node.js](https://nodejs.org). Node.js is a JavaScript runtime that allows you to run JavaScript code on a computer. The development of node.js has allowed JavaScript to be used in a wide variety of applications, one of which is webpack.

[Webpack](https://webpack.js.org/) is a module bundler that takes multiple related files and bundles them together into a single file that can be loaded by the browser. Webpack not only supports JavaScript, but also other files like stylesheets and images. Using webpack, you can write JavaScript using the latest ECMAScript features, more modern CSS syntax like Syntactically Awesome Stylesheets (SASS) for your styles, and then bundle them all together into a single .js or .css file that can be loaded by the browser.

### React and JSX

All of these technologies helped create JavaScript frameworks like [React](https://react.dev/), [Vue](https://vuejs.org/), and [Angular](https://angular.dev/). React is a JavaScript library for building user interfaces, and is the library that the Block Editor is built on. React makes use of a syntax extension called [JSX](https://react.dev/learn/writing-markup-with-jsx), which allows you to write HTML-like code directly in your JavaScript files.

### @wordpress/scripts

In order to make all off this seamless for the WordPress developer, the WordPress team created the `@wordpress/scripts` [package](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/). This package is a collection of reusable scripts tailored for WordPress development. It includes scripts for building blocks, plugins, and themes, as well as scripts for running tests and linting your code.

If you followed the Introduction to Block development module in the Beginner Developer Learning Pathway you've already used all the tools mentioned in this lesson. You installed node.js, wrote some JSX for the Copyright Date block, and used `@wordpress/scripts` to build your block code, which relies on webpack to bundle everything for you.

## Writing modern JavaScript

If you've been writing JavaScript for a while, you might be familiar with the older syntax and features of the language. This can sometimes mean that switching to using newer features can be a bit of a learning curve.

Let's look at some of the more common features of modern JavaScript that you might come across when developing blocks for the Block Editor.

You can try these examples out yourself, by pasting the code in the console of your browser's developer tools.

### Defining variables with `let` and `const`

In the past, JavaScript only had one way to define variables, using the `var` [keyword](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/var). However, `var` has some quirks that can lead to bugs in your code. For example, `var` variables can be function-scoped or globally scoped, which can lead to unexpected behavior if you're not careful.

To address these issues, the `let` and `const` keywords were introduced in ES6. `let` is similar to `var`, but it is block-scoped, which means that it is only available within the block of code that it is defined in.

```
let x = 10;
if( x === 10 ){
	let y = 20;
	console.log( y ); // 20
}
console.log( y ); // ReferenceError: y is not defined
```

`const` is similar to `let`, but the value of a `const` variable cannot be changed once it has been assigned.

```
const x = 10;
x = 20; // TypeError: Assignment to constant variable.
```

Both `let` and `const` are preferred over `var` when writing modern JavaScript, and can be used as is across all modern browsers.

### Destructuring assignment

[Destructuring assignment](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Destructuring_assignment) is a [feature](https://test.com) of ES6 that allows you to extract values from arrays or objects and assign them to variables in a more concise way.

```
function greet( person ){
	const { firstName, lastName } = person
	console.log( `Hello, ${ firstName } ${ lastName }` );
};

const person = {
	firstName: 'John',
	lastName: 'Doe'
};
greet( person );
```

In this example, the `greet` function takes a `person` object as an argument, and uses destructuring assignment to extract the `firstName` and `lastName` properties from the object into individual variables.

You can take this a step further and destructure the parameters directly in the function signature.

```
function greet( { firstName, lastName } ){
	console.log( `Hello, ${ firstName } ${ lastName }` );
};

const person = {
	firstName: 'John',
	lastName: 'Doe'
};
greet( person );
```

### Arrow functions

[Arrow functions](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Functions/Arrow_functions) are a more concise way to write functions in JavaScript.

They were introduced in ES6 6 and provide a more compact syntax for defining functions.

```
const greet = ( { firstName, lastName } ) => {
	console.log( `Hello, ${ firstName } ${ lastName }` );
};

const person = {
	firstName: 'John',
	lastName: 'Doe'
};
greet( person );
```

It's also possible to write arrow functions without the parentheses around the parameter if there is only one parameter.

```
const greet = name => {
	console.log( `Hello, ${ name }` );
};

greet( 'name' );
```

If the function body is a single expression, you can even omit the curly braces.

```
const greet = name => console.log( `Hello, ${ name }` );
greet( 'name' );
```

Arrow functions are commonly used in modern JavaScript, and are especially useful when working with higher-order functions like `map`, `filter`, and `reduce`.

## Conclusion

For more information on modern JavaScript in WordPress, make sure to read through the [Working with Javascript for the Block Editor ](https://developer.wordpress.org/block-editor/getting-started/fundamentals/javascript-in-the-block-editor/) section of the [Block Editor Handbook](https://developer.wordpress.org/block-editor/). It also includes a links of Additional resources to learn more at the bottom of the page.

Finally, the MDN Web Docs has a detailed [JavaScript Section](https://developer.mozilla.org/en-US/docs/Web/JavaScript) that covers all aspects of the language, from the basics to the more advanced features.

