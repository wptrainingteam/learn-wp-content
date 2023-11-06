# WordPress Developer Fundamentals - Custom Database Tables

## Learning Objectives

Upon completion of this lesson the participant will be able to:

Describe when and how to create custom database tables
Create, update, and delete custom database tables

## Comprehension Questions

What is the name of the global object that contains all the methods you need to interact with the database?
What is the name of the function that is generally used during WordPress updates, if default WordPress tables need to be updated or change?
When should your custom database table be created?
What options are available to you when deleting your custom database table?

## Outline
1. Introduction
2. Where to find information
3. Creating Custom Database Tables
4. Creating the table
5. Inserting data
6. Updating data
7. Selecting data
8. Table updates
9. Cleaning up
10. Conclusion

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you'll be learning about creating custom database tables for WordPress.

You will learn where to find information about creating custom database tables, how to create custom database tables, and how to interact with them.

## Why create custom database tables?

The default WordPress database schema is typically enough for all content types. The ability to register custom post types and use post meta usually covers most options.

However, in some cases, you may need to store data that doesn't fit into the default schema. 

For example, in an ecommerce store, a custom post type would work for products, as it has similar fields to a post; for example a title, image, and content. Any additional fields it might need can be stored as post meta. 

However, an order does not use the same fields as a post, and therefore it might be useful to store orders in a custom table.

For the occasions where you need to store data that doesn't fit into the default schema, you can create your own custom database tables.

## Where to find information

While the WordPress developer documentation does not include anything around custom database tables, there is an older version of the developer documentation called the Codex that does. You can find everything you need to know about custom database tables in the [Creating Tables with Plugins](https://codex.wordpress.org/Creating_Tables_with_Plugins ) page of the Codex.

## Creating Custom Database Tables

The first thing you'll notice is that this is typically done in a plugin. 

Additionally, it is possible, and recommended, to create custom tables when the plugin is activated, using the `register_activation_hook` [function](https://developer.wordpress.org/reference/functions/register_activation_hook/).

This allows this functionality to be run once, and not every time the plugin is loaded.

## Creating the table

To create a custom table on plugin activation, you need to use a few things.

To start, create a function to manage the table creation:

```php
function wp_learn_create_database_table() {
}
```

Then, you need to use the `$wpdb` global [WordPress database object](https://developer.wordpress.org/reference/classes/wpdb/), as it contains all the methods you need to interact with the database. 

This will allow you to set up the new table name, using the WordPress database prefix.

```php
function wp_learn_create_database_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_table';
}
````

It will also allow you to access the `get_charset_collate` [method](https://developer.wordpress.org/reference/classes/wpdb/get_charset_collate/), which will return the correct character set and collation for the database.

```php
    $charset_collate = $wpdb->get_charset_collate();
```

To create a table, you need to know SQL to execute a SQL statement on the database. This is done via the `dbDelta` [function](https://developer.wordpress.org/reference/functions/dbdelta/). `dbDelta` is a function that is generally used during WordPress updates, if default WordPress tables need to be updated or change. It examines the current table structure, compares it to the desired table structure, and either adds or modifies the table as necessary.

In order to use `dbDelta`, you need to write your SQL statement in a specific way. 

You can read more about these requirements in the Creating or Updating the Table section of the [Codex](https://codex.wordpress.org/Creating_Tables_with_Plugins#Creating_or_Updating_the_Table) page.

Once you've created the SQL statement, you need to pass it to the `dbDelta` function. This is done by including the `wp-admin/includes/upgrade.php` file, which contains the function declaration.

```php
function wp_learn_create_database_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_table';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name tinytext NOT NULL,
        text text NOT NULL,
        url varchar(55) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
```

In this example, a new table is being created called `custom_table`. It has 5 fields, an `id`, a `time`, a `name`, a `text`, and a `url`. The `id` is an auto incrementing integer, the `time` is a datetime field, the `name` is a tinytext field, the `text` is a text field, and the `url` is a varchar field. The `id` is the primary key.

Hooking this function into your plugin activation hook will ensure that the table is created when the plugin is activated.

```php
register_activation_hook( __FILE__, 'wp_learn_create_database_table' );
```

## Inserting data 

It's also possible to use the plugin activate hook to insert data into your table on plugin activation.

To do this you can use the `insert` method of the `$wpdb` object, passing an array of field names and values to insert.

Here is an example of what this could look like.

```php
register_activation_hook( __FILE__, 'wp_learn_insert_record_into_table' );
function wp_learn_insert_record_into_table(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_table';

    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time( 'mysql' ),
            'name' => 'John Doe',
            'text' => 'Hello World!',
            'url'  => 'https://wordpress.org'
        )
    );
}
```

## Updating data

To update data in your custom table, use the `update` method of the `$wpdb` object, passing an array of field names and values to be updated, as well as an array of field names and values to use to find the record to update.

```php
function wp_learn_update_record_in_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_table';

    $wpdb->update(
        $table_name,
        array(
            'time' => current_time( 'mysql' ),
            'name' => 'Jane Doe',
            'text' => 'Hello Planet!',
            'url'  => 'https://wordpress.org'
        ),
        array( 'id' => 1 )
    );
}
```

In this example, the record with an id of 1 will be updated with the new values.

## Selecting data

Selecting data from your custom table is done using the `get_results` method of the `$wpdb` object. `get_results` will accept a valid SELECT SQL statement.

```php
function wp_learn_select_records_from_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_table';

    $results = $wpdb->get_results( "SELECT * FROM $table_name" );

    foreach ( $results as $result ) {
        echo $result->name . ' ' . $result->text . '<br>';
    }
}
```

By default, get_results will return an array of objects, which you can loop through and access the row fields as properties.

## Cleaning up

It's also possible to delete your custom tables. To do this, you can use the `query` method of the `$wpdb` object, passing a SQL statement to delete the table.

```php
function wp_learn_delete_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_table';

    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}
```

The `query` method will run any valid SQL query, but it's best to only use it for queries that don't insert or update data, as this function does not perform any query sanitization.

Depending on your requirements, or the requirements of your plugin's users, you could delete the table in two ways.

If your users of your plugin do not need the data in this table if they deactivate the plugin, you could trigger this on the [plugin deactivation hook](https://developer.wordpress.org/reference/functions/register_deactivation_hook/).

```
register_deactivation_hook( __FILE__, 'wp_learn_delete_table' );
```

However, if the data in that table is important, and your users might want to keep it, even if the plugin is deactivated, you could delete the table using one of the [two uninstall methods](https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/) available to plugins. 

For example, if you choose to use the register_uninstall_hook.

```
register_uninstall_hook( __FILE__, 'wp_learn_delete_table');
```

Alternatively, you cuold just leave the table intact, and let your users decide what to do with it.

It's generally recommended to check with your user whether they want to keep the table or not when the plugin is uninstalled, and then used one of the uninstallation methods.

## Conclusion

This tutorial only scratches the surface of what's possible with custom tables. In a future tutorial, you'll learn how to implement this in a real world project.

Happy coding. 