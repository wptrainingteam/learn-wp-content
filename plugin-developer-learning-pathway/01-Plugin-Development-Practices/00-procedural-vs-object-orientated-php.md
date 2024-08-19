# Procedural vs Object-Oriented PHP

## Introduction

As you become more familiar with PHP, you will notice that there are two main coding styles: procedural and object-oriented.

This lesson will introduce you to both styles, and help you understand the differences between them.

## Procedural Style

Let's start by looking at a simplified example of some procedural PHP code in a single file:

```php
<?php
function get_book_title() {
	return "Title: PHP for Beginners";
}
function get_book_author() {
	return "Author: John Doe";
}
function get_book_publisher() {
	return "Publisher: PHP Publishing";
}
?>
<html lang="en">
<head>
	<title>My Book</title>
</head>
<body class="main">
<h1><?php echo get_book_title(); ?></h1>
<p>
	<ul>
		<li><?php echo get_book_author(); ?></li>
		<li><?php echo get_book_publisher(); ?></li>
	</ul>
</p>
</body>
</html>
```

In this example, we have three functions that return the title, author, and publisher of a book. These functions are then called in the HTML document to display the book information.

This is a simple example of typical procedural PHP code. All the functions are defined at the top of the file, and then called wherever they are needed to display the their information.

Now let's say we have a database table called `books` with columns `id`, `title`, `author`, and `publisher`. We can create a function that fetches the book information from the database, and use that in our other functions:

```php
<?php
function get_book_from_database( $book_id ) {
    $conn = new mysqli("localhost", "username", "password", "myDB");
    $result = $conn->query("SELECT title, author, publisher FROM books WHERE id=$book_id");
    $book = $result->fetch_assoc();
    return $book;
}

function get_book_title( $book_id ) {
	$book = get_book_from_database( $book_id );
	return $book['title'];
}
function get_book_author( $book_id ) {
	$book = get_book_from_database( $book_id );
	return $book['author'];
}
function get_book_publisher( $book_id ) {
	$book = get_book_from_database( $book_id );
	return $book['publisher'];
}
?>