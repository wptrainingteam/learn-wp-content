# PHP, dynamic web pages

## Introduction

PHP is a programming language that is used to create dynamic web pages. 

According to web statistics, PHP powers over 75% of the modern web. 

Let's take a look at how PHP works. 

## What is PHP?

PHP is often used to create administrative interfaces for websites, such as the WordPress dashboard, but it is also used to populate the front end of a website with content.

PHP is a server side scripting language, which means that it is interpreted by the server, and the results are rendered in the browser. 

To see a simple example of what PHP can do, let's take at the HTML page from the previous lessons, but use PHP to change the color of the heading element:

```
<?php
if ( isset( $_GET['color'] ) ) {
	$color = $_GET['color'];
} else {
	$color = 'red';
}
?>
<html lang="en">
    <head>
        <title>My HTML document</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="main">
        <h1 style="color: <?php echo $color; ?>;">This is the heading of my HTML document</h1>
        <img src="https://picsum.photos/250" alt="A randomly selected image">
        <p>This is the content of my HTML document.</p>
        <a href="/index.php?color=blue">Change heading color to blue</a>
    </body>
</html>
```

The first thing you will notice is that the file extension of the document changed from `.html` to `.php`. This tells the server that the document contains PHP code. 

Then, any PHP code you want to add to the document needs to be wrapped in PHP tags.

Before we dive into the code, try out the example by clicking on the link.

So, in this example:

1. The PHP code at the top of the file is being wrapped in PHP tags, this tells the server to execute this code as PHP.
2. A variable called `$color` has been created, which stores the value of the `color` query string parameter from the `$_GET` global array, if it exists, or the value `red` if it doesn't.
3. The value of the `$color` variable has been added to the `style` attribute of the heading element. You'll see this is also wrapped in PHP tags, and uses the `echo` function to output the value of the `$color` variable.
4. A link has been added to the document which changes the value of the `color` query string parameter to `blue`.

You will notice that the PHP code which detects the query string and sets up the color variable has been added to the document before the rest of the HTML elements. This is because this PHP code needs to run before the HTML elements are rendered in the browser. 

Additionally, you will see that the new header color is output inside php tags as an inline style. This is because PHP can't change the CSS file, so the only way to change the color of the heading element is to add the color as an inline style.

Finally, take note of the use of the query string on the anchor tag, to pass data from one page to another. This is a common way to pass data from one page to another in PHP. 

At the same time, you'll see that the button was changed to an anchor tag. This is done to ensure that the HTML is used semantically. The button element is generally used to trigger an action, such as submitting a form, or triggering a JavaScript function. In this case, the anchor tag is used to link to a page, which just happens to be the same page being rendered.

This is also the first time you might have seen an if statement. If statements are used to check if a condition is true, and if it is, run the code inside the if statement. This is also known as a conditional statement.

Both PHP and Javascript support conditional statements. In fact many features of PHP and JavaScript are similar. However, there are some key differences between the two languages, and it's important to understand these differences.

## Additional Resources

For more information about PHP, you can visit the following online resources:

- [PHP Getting Started guide](https://www.php.net/manual/en/getting-started.php)
- [Official PHP Documenation](https://www.php.net/docs.php)
- [Learn PHP for Beginners on freeCodeCamp](https://www.freecodecamp.org/news/the-php-handbook/)

## YouTube chapters

(0:00) Introduction
(0:00) What is PHP?
(0:00) Additional Resources