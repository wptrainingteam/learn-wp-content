# Managing Custom Tables

## Introduction

Welcome to Module 7 of the Intermediate Plugin Developer learning pathway. In this lesson, we will explore how to create, manage, and delete custom database tables in WordPress. As an intermediate developer, this skill is crucial for extending WordPress functionality beyond standard post types and taxonomies.

By the end of this lesson, you’ll understand why you might need a custom table, how to create and version the table schema, and how to safely remove a table when it’s no longer needed.

## Why Create a Custom Database Table?

In most cases, WordPress's default tables are already enough to store your plugin's data. You can use the posts table to manage different types of content, store user meta to further personalize users' experiences, manage custom settings in the options table, and so much more!

Even still, there are times when creating a custom table can be more effective. When thinking about data your plugin will be managing, consider the following:

- **Performance**: If you’re dealing with large datasets or specific relationships between data points, custom tables can improve query performance by giving you control over the data schema and indexing behavior.
- **Data structure**: When your data doesn’t fit the traditional WordPress structure, it can be much easier to write queries if the data is stored in custom tables which are tailored to how the data will be used.
- **Scalability**: As your plugin and the surrounding application grow, you can easily add, update, or remove custom tables as needs change.

Now that you can identify when custom database tables are useful, let's learn how to add one!

## Creating a Custom Database Table

To create a custom table, use the `dbDelta()` function included in WordPress. This function is powerful because it can handle both table creation and schema updates.

To start, hook into your plugin's activation method to create the custom table. This ensures the table immediately exists once your plugin is activated, as your plugin will likely depend on it in order to function properly.

```php
register_activation_hook( __FILE__, 'my_plugin_create_table' );

function my_plugin_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'my_custom_table';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        column_name_1 text NOT NULL,
        column_name_2 varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
```

Notice that you must load the `wp-admin/includes/upgrade.php` file included in WordPress in order to use the `dbDelta()` function. WordPress only uses this function for update processes, so it is not always included like other PHP functions are in common execution.

It's also crucial to format the query following the `dbDelta()` function's specific requirements, such as putting each field on its own line in the SQL statement and putting two spaces after the words `PRIMARY KEY`. Please refer to [the *Creating Tables with Plugins* section of WordPress's Plugin Handbook](https://developer.wordpress.org/plugins/creating-tables-with-plugins/#creating-or-updating-the-table) to review all requirements.

## Updating the Table Schema

As you add more features to your plugin, you may want to update the way your plugin stores data. This is when the `dbDelta()` function really shines! By following the previously mentioned formatting requirements, it can automatically figure out how to alter the installed table into the new schema.

Before we update the custom table's schema, though, let's ensure `dbDelta()` is only checking the schema when it makes sense.

### Table Versioning

