# MySQL, SQL, and the Database

## Introduction

MySQL is one of the most popular database system for web based applications. 

This lesson will introduce you to the MySQL database system, as well how you can interact with it.

## What is MySQL?

MySQL is an open source relational database management system. It is used to store data in a database, and to retrieve that data when needed.

SQL stands for Structured Query Language, and it is a programming language that is used to interact with data in a MySQL database.  

As you learned in the lesson on WordPress and web servers, WordPress uses a MySQL database to store all of its data. This data includes things like posts, pages, comments, and users. 

One of the tools you can use to interact with a MySQL database is phpMyAdmin. 

phpMyAdmin is a browser based tool that allows you to interact with your MySQL databases using a graphical user interface, but also run SQL queries on them.  

It is often included in the control panel of your web host or local development environment.

## Creating tables

To create a table in a database, you would use the `CREATE TABLE` statement. This statement takes the name of the table, and the columns that the table should have.

For example, to create a table called `colors` with the columns `id`, `type`, and `value` you could use the following SQL statement:

```sql
CREATE TABLE colors (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(30) NOT NULL,
    value VARCHAR(30) NOT NULL
)
```

This will create the colors table, with the relevant columns.

## Adding rows to a table

Now, if you wanted to add a row to the table, you would use the `INSERT` statement. This statement takes the name of the table, and the values for each column.

For example, to add a row to the `colours` table, you would use the following SQL statement:

```sql
INSERT INTO colors (type, value) VALUES ('header', 'red');
```

If you know browse the colors table, you'll see the row you just added.

## Reading rows from a table

If you wanted to read a row from the table, you would use the `SELECT` statement. This statement takes the name of the table, and the row to read.

For example, to read the row with the `type` of `header` from the `colors` table, you would use the following SQL statement:

```sql
SELECT * FROM colors WHERE type = 'header';
```

This will return all the requested rows.

If you just wanted the value of the `value` column, you would use the following SQL statement:

```sql
SELECT value FROM colors WHERE type = 'header';
```

And this would only display the data from the `value` column.

## Updating rows in a table

If you wanted to update a row in the table, you would use the `UPDATE` statement. This statement takes the name of the table, the column to update, the new value, and the row to update.

For example, to update the value of the `value` column in the `colours` table, you could use the following SQL statement:

```sql
UPDATE colors SET value = 'blue' WHERE type = 'header';
```

If you browse the table, you'll see the value of the `value` column has been updated.

## Deleting rows from a table

If you wanted to delete a row from the table, you would use the `DELETE` statement. This statement takes the name of the table, and the row to delete.

For example, to delete the row with the `type` of `header` from the `colors` table, you would use the following SQL statement:

```sql
DELETE FROM colors WHERE type = 'header'
```

Browsing the table shows that the data has been deleted.

## Database Keys

In the previous example, you may have noticed the use of the `value` column to update or delete the row. While this works, it is not the most efficient way to update or delete a row. This is because the `value` column is not unique, and there could be multiple rows with the same value. Additionally, if you wanted to update or delete a row, you would need to know the value of the `value` column, which may not be possible.

For this reason, it's usually a good idea that your database tables have an id column, and that the id is unique, and auto incrementing. It's also a good idea to the ID has an index on it. Indexes allow MySQL to do much quicker selects, updates, and deletes, then if a field does not have an index. 

## Running queries from PHP

Unlike PHP and JavaScript, SQL is a query language that is executed on the database. Additionally, because JavaScript is run in the browser, you generally don't make requests to the database from JavaScript, and would instead do it using PHP, which is executed on the server. 

To run a SQL query, you would create a connection from PHP to the database, prepare and then run a query, and the results of the query would be returned to PHP, as some type of variable.

Fortunately, as you learned in the module on how WordPress works, WordPress includes a Database API that allows you to manage the connection to the database, and run any queries you need to. 

Additionally, if you use the default WordPress data types, you won't even need to worry about executing SQL queries. 

## Additional Resources

For more information about MySQL and SQL statements, you can visit the following online resources:

- [MySQL Tutorial](https://dev.mysql.com/doc/refman/8.2/en/tutorial.html)
- [MySQL 8.2 Reference Manual](https://dev.mysql.com/doc/refman/8.2/en/)
- [Learn MySQL – Beginner's Course on freeCodeCamp](https://www.freecodecamp.org/news/learn-mysql-beginners-course/)

## YouTube chapters

0:00 Introduction
0:15 What is MySQL?
1:11 Creating tables
1:42 Adding rows to a table
2:08 Reading rows from a table
2:51 Updating rows in a table
3:18 Deleting rows from a table
3:45 Database Keys
4:29 Running queries from PHP
5:18 Additional Resources