# SQL

## What is SQL?

SQL stands for Structured Query Language. It is a programming language that is used to interact with databases. It is used to create, read, update, and delete data from a database. 

As you learned in the lesson on WordPress and web servers, WordPress uses a database to store all of its data. This data includes things like posts, pages, comments, and users. Generally the database software used is MySQL.

One of the tools you can use to interact with a MySQL database is phpMyAdmin. phpMyAdmin is a web based tool that allows you to create, read, update, and delete data from a MySQL database. It is often included in the control panel of your web host or local development environment.

## Creating tables

To create a table in a database, you would use the `CREATE TABLE` statement. This statement takes the name of the table, and the columns that the table should have.

For example, to create a table called `colours` with the columns `id`, `type`, and `value` you would use the following SQL statement:

```
CREATE TABLE colors (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(30) NOT NULL,
    value VARCHAR(30) NOT NULL
)
```

## Adding rows to a table

Now, if you wanted to add a row to the table, you would use the `INSERT INTO` statement. This statement takes the name of the table, and the values for each column.

For example, to add a row to the `colours` table, you would use the following SQL statement:

```
INSERT INTO colors (type, value) VALUES ('header', 'red')
```

## Reading rows from a table

If you wanted to read a row from the table, you would use the `SELECT` statement. This statement takes the name of the table, and the row to read.

For example, to read the row with the `id` of `1` from the `colors` table, you would use the following SQL statement:

```
SELECT * FROM colors WHERE type = 'header';
```

If you just wanted the value of the `value` column, you would use the following SQL statement:

```
SELECT value FROM colors WHERE type = 'header';
```

## Updating rows in a table

If you wanted to update a row in the table, you would use the `UPDATE` statement. This statement takes the name of the table, the column to update, the new value, and the row to update.

For example, to update the value of the `value` column in the `colours` table, you could use the following SQL statement:

```
UPDATE colors SET value = 'blue' WHERE type = 'header';
```

## Deleting rows from a table

If you wanted to delete a row from the table, you would use the `DELETE FROM` statement. This statement takes the name of the table, and the row to delete.

For example, to delete the row with the `type` of `header` from the `colors` table, you would use the following SQL statement:

```
DELETE FROM colors WHERE type = 'header'
```

## Database Keys

In the above query examples, you may have noticed the use of the `value` column to update or delete the row. While this works, it is not the most efficient way to update or delete a row. This is because the `value` column is not unique, and there could be multiple rows with the same value. Additionally, if you wanted to update or delete a row, you would need to know the value of the `value` column, which may not be possible.

## Running queries from PHP

Unlike PHP and JavaScript, SQL is a query language that is executed on the database. Additionally, because JavaScript is run in the browser, you generally don't make requests to the database from JavaScript, and would instend do it using PHP, which is excuted on the server. 

To run a SQL query, you would create a connection from PHP to the database, prepare and then run a query, and the results of the query would be returned to PHP, as some type of variable.

Fortunately, as you learned in the module on how WordPress works, WordPress includes a Database API that allows you to manage the connection to the database, and run any queries you need to. Additionally, if you use the default WordPress data types, you won't even need to worry about executing SQL queries. 