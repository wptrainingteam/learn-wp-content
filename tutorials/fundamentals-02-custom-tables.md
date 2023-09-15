# WordPress Developer Fundamentals - The WordPress Database

## Learning Objectives

Upon completion of this lesson the participant will be able to:

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

The default WordPress database schema is typically enough for all content types. The ability to register custom post types and use post meta usually covers most options.

However, in some cases, you may need to store data that doesn't fit into the default schema. For example, in an ecommerce store, a custom post type would work for products, as it has similar fields to a post (title, featured image, content, author etc). However, and order does not have these types of fields, and therefore it might be useful to store orders in a custom table.

For the occasions where you need to store data that doesn't fit into the default schema, you can create your own custom database tables.

## Where to find information

While the WordPress developer documentation does not include anything around custom database tables, there is an older version of the developer documentation called the Codex that does. You can find everything you need to know about custom database tables in the [Creating Tables with Plugins](https://codex.wordpress.org/Creating_Tables_with_Plugins ) page of the Codex

## Creating Custom Database Tables

The first thing you'll notice is that this is typically done in a plugin. 

Additionally, it is possible, and recommended, to create custom tables when the plugin is activated, using the `register_activation_hook` [function](https://developer.wordpress.org/reference/functions/register_activation_hook/), and if you delete them to do that when the plugin is deactivated using the `register_deactivation_hook` [function](https://developer.wordpress.org/reference/functions/register_deactivation_hook/). This allows this functionality to be run once, and not every time the plugin is loaded.

## Creating the table

To create a custom table on plugin activation, you need to use a few things

First, you need to use the `$wpdb` global [WordPress database object](https://developer.wordpress.org/reference/classes/wpdb/), as it contains all the methods you need to interact with the database. 

This will allow you to set up the new table name, using the WordPress database prefix.

```php
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_table';
````

It will also allow you to access the `get_charset_collate` [method](https://developer.wordpress.org/reference/classes/wpdb/get_charset_collate/), which will return the correct character set and collation for the database.

```php
    $charset_collate = $wpdb->get_charset_collate();
```

To create a table, you need to know SQL to execute a SQL statement on the database. This is done via the `dbDelta` [function](https://developer.wordpress.org/reference/functions/dbdelta/). dbDelta is a function that is generally used during WordPress updates, if default WordPress tables need to be updated or change. It examines the current table structure, compares it to the desired table structure, and either adds or modifies the table as necessary.

In order to use `dbDelta`, you need to write your SQL statement in a specific way. 

You can read more about these requirements here:

https://codex.wordpress.org/Creating_Tables_with_Plugins#Creating_or_Updating_the_Table

Once you've created the SQL statement, you need to pass it to the `dbDelta` function. This is done by including the `wp-admin/includes/upgrade.php` file, which contains the function declaration.

```php
	function create_database_table() {
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

Hooking this function into your plugin activation hook will ensure that the table is created when the plugin is activated.

```php
    register_activation_hook( __FILE__, 'create_database_table' );
```

## Inserting data 

It's also possible to use the plugin activate hook to insert data into your table on plugin activation.

To do this you can use the `insert` method of the `$wpdb` object, passing an array of field names and values.

```php
    function insert_record_into_table(){
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

To update data in your custom table, use the `update` method of the `$wpdb` object, passing an array of field names and values, as well as an array of field names and values to match.

```php
    function update_record_in_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_table';

        $wpdb->update(
            $table_name,
            array(
                'time' => current_time( 'mysql' ),
                'name' => 'John Doe',
                'text' => 'Hello World!',
                'url'  => 'https://wordpress.org'
            ),
            array( 'ID' => 1 )
        );
    }
```

## Selecting data

Selecting data from your custom table is done using the `get_results` method of the `$wpdb` object.

```php
    function select_records_from_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_table';

        $results = $wpdb->get_results( "SELECT * FROM $table_name" );

        foreach ( $results as $result ) {
            echo $result->name . ' ' . $result->text . '<br>';
        }
    }
```

## Table updates

It's also a good idea to store a db version number as a WordPress option, in case you ever need to update the table. While the process of doing this is outside the scope of this tutorial, the basic process is:

1. Store the version number in the options table
2. Create an upgrade routine that triggers when the plugin is updated, perhaps using something like https://developer.wordpress.org/reference/hooks/upgrader_process_complete/
2. Based on the version, upgrade the table, by creating a separate function which contains the full SQL statement to update the table
3. Once the upgrade has run, update the version number

## Cleaning up

It's also possible to use the plugin deactivate hook to delete the custom table when the plugin is deactivated. To do this, you can use the `query` method of the `$wpdb` object, passing a SQL statement to delete the table.

`query` will run any SQL query, but it's best to only use it for queries that don't insert or update data, as those functions include built in sanitization.

```php
    function delete_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_table';

        $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
    }
```

Remember to do this on plugin deactivation, not plugin uninstall, as plugin uninstall is only run when the plugin is deleted, not deactivated.

```php
    register_deactivation_hook( __FILE__, 'delete_table' );
```

## Conclusion

This tutorial only scratches the surface of what's possible with custom tables, but hopefully it gives you an idea of what's possible.

Happy coding. 