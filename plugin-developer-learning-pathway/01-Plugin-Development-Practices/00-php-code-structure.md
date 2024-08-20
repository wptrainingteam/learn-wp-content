# PHP code structure

## Introduction

As you become more experienced with developing WordPress plugins, you will often need to think about the structure of your code.

This lesson will introduce you to different ways of structuring your PHP code.

For the purposes of this lesson, we're going to take a very simplified example.

## Using functions

Let's start by looking at an example of some PHP code in a single file to render some book information. 

```php
<?php
$book = array(
	"title"     => "PHP for Beginners",
	"author"    => "John Doe",
	"publisher" => "PHP Publishing"
);
?>
<html lang="en">
<head>
	<title>A book</title>
</head>
<body class="main">
    <h1><?php echo $book['title']; ?></h1>
    <div>
        <p><?php echo $book['author']; ?></p>
        <p><?php echo $book['publisher']; ?></p>
    </div>

</body>
</html>
```

This code is fairly straightforward. You have an array called `$book` that contains information about a book, and you're using PHP to output that information in an HTML document.

Now let's take this a step further. 

Let's say, for whatever reason, you want to control the HTML elements that wrap each piece of the book information. You could create a series of functions to handle this.

```php
<?php
function get_book_title( $book ) {
	return "<h1>" . $book["title"] . "</h1>";
}

function get_book_author( $book ) {
	return "<p>" . $book["author"] . "</p>";
}

function get_book_publisher( $book ) {
	return "<p>" . $book["publisher"] . "</p>";
}

$book = array(
	"title"     => "PHP for Beginners",
	"author"    => "John Doe",
	"publisher" => "PHP Publishing"
);
?>
<html lang="en">
<head>
	<title>A book</title>
</head>
<body class="main">
    <?php echo get_book_title( $book ); ?>
    <div>
        <?php echo get_book_author( $book ); ?>
        <?php echo get_book_publisher( $book ); ?>
    </div>
</body>
</html>
```

Here, your're passing the `$book` array to each function, which then returns the appropriate HTML output to be rendered in the HTML.

## Using classes

Now another approach you could use is to create a class to represent a blueprint for a book, with methods that determine how the book information should be returned.

```php
<?php
class Book {
	public $title;
	public $author;
	public $publisher;
	
	public function __construct( $title, $author, $publisher ) {
		$this->title     = $title;
		$this->author    = $author;
		$this->publisher = $publisher;
	}

	public function get_title() {
		return "<h1>$this->title</h1>";
	}

	public function get_author() {
		return "<p>$this->author</p>";
	}

	public function get_publisher() {
		return "<p>$this->publisher</p>";
	}
}
$book = new Book( "PHP for Beginners", "John Doe", "PHP Publishing" );
?>
<html lang="en">
<head>
	<title>A book</title>
</head>
<body class="main">
    <?php echo $book->get_title(); ?>
    <div>
        <?php echo $book->get_author(); ?>
        <?php echo $book->get_publisher(); ?>
    </div>
</body>
</html>
```

In this example, you've created a class called `Book` that has properties for the title, author, and publisher. 

You've also created class methods to output each piece of information.

But the class doesn't actually do anything until you create an instance of the class, also known as an object, and pass some properties to it. 

When you do that, the class constructor method is called which applies the values passed to the class constructor to the relevant properties of the new object.

You can then call the methods on the object to output the relevant information.

One of the benefits of using classes in this way, is that depending on your requirements, you can create multiple instances of the same class, each with its own set of properties. 

That way, the core code of the class stays the same, but it's renders different information based on the properties of the object.

```php
<?php
class Book {
    public $title;
    public $author;
    public $publisher;
    
    public function __construct( $title, $author, $publisher ) {
		$this->title     = $title;
		$this->author    = $author;
		$this->publisher = $publisher;
	}

    public function get_title() {
        return "<h1>$this->title</h1>";
    }

    public function get_author() {
        return "<p>$this->author</p>";
    }

    public function get_publisher() {
        return "<p>$this->publisher</p>";
    }
}
$book1 = new Book( "PHP for Beginners", "John Doe", "PHP Publishing" );
$book2 = new Book( "JavScript for Beginners", "Jane Doe", "JavaScript Publishing" );
?>
<html lang="en">
    <head>
        <title>Some books</title>
    </head>
    <body class="main">
        <?php echo $book1->get_title(); ?>
        <div>
            <?php echo $book1->get_author(); ?>
            <?php echo $book1->get_publisher(); ?>
        </div>
        <?php echo $book2->get_title(); ?>
        <div>
            <?php echo $book2->get_author(); ?>
            <?php echo $book2->get_publisher(); ?>
        </div>
    </body>
</html>
```

## Static class methods

Alternatively, if you don't need to use multiple instances of the object, you can define the class properties in the class, and set the class methods as static. Doing this means you don't need to create an instance of the class, to call the class methods.

```php
class Book {
    public $title = "PHP for Beginners";
    public $author = "John Doe";
    public $publisher = "PHP Publishing";

    public static function get_title() {
        return "<h1>$this->title</h1>";
    }

    public static function get_author() {
        return "<p>$this->author</p>";
    }

    public static function get_publisher() {
        return "<p>$this->publisher</p>";
    }
}
?>
<html lang="en">
    <head>
        <title>A book</title>
    </head>
    <body class="main">
        <?php echo Book::get_title(); ?>
        <div>
            <?php echo Book::get_author(); ?>
            <?php echo Book::get_publisher(); ?>
        </div>
    </body>
</html>
```

Notice how the class methods are called differently in the two examples. 

When you create an instance of the class, you use the `->` operator on the object instance to call the class methods. 

When you define the class methods as static, you use the `::` operator directly on the class name itself to call the class methods.

## Summary

There's a lot more that you can achieve with PHP classes, but for now just consider them an alternative way to structure your PHP code.

Ultimately, how you structure your PHP plugin code will depend on your requirements.