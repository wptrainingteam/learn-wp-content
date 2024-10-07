# Interacting with Custom Tables

Now that we have our own custom table in the WordPress database, let's see how to interact with it. In this lesson, we’ll cover how to safely query custom tables by using the `global $wpdb` object. You'll also learn how to insert, update, and delete data while following best practices to avoid common security vulnerabilities.

A key aspect of interacting with any database is to ensure the data you send and retrieve is secure and validated. One of the most dangerous threats to your plugin’s security is SQL injection. Let's take a look at how that happens and what we can do to safeguard our tables against it.

## SQL Injections and How to Protect Against Them

SQL injection is a security vulnerability where attackers manipulate SQL queries by injecting malicious code into user input fields. If queries are constructed using unsanitized input values, then an attacker could execute arbitrary SQL commands which compromises your data.

Consider this example where user input is inserted directly into a query without validation:

```php
global $wpdb;
$name = $_GET['name'];

$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}my_plugin_table WHERE name = '$name'" );
```

In this example, an attacker could supply a value like `John'; DROP TABLE wp_my_plugin_table;--`, which would delete the entire custom table! Let's see how we can stop this from happening.

### Using Prepared Statements

To protect against SQL injections, always use **prepared statements**. Prepared statements separate SQL logic from data, ensuring that user inputs are properly sanitized and escaped before being inserted into the query. In WordPress, the [`$wpdb->prepare()`](https://developer.wordpress.org/reference/classes/wpdb/prepare/) method is provided for this purpose. Its used similarly to PHP's [`sprintf()`](https://www.php.net/sprintf) and [`vsprintf()`](https://www.php.net/vsprintf) functions where you use placeholders in your SQL query and pass an array of the corresponding values.

Here’s how you can rewrite the previous query to safely handle user input:

```php
global $wpdb;
$name = $_GET['name'];

$prepared_query = $wpdb->prepare( 
    "SELECT * FROM {$wpdb->prefix}my_plugin_table WHERE name = %s", 
    $name 
);

$results = $wpdb->get_results( $prepared_query );
```

In this example:
- `%s` is a placeholder for a string.
- The `prepare()` method ensures that the `$name` value is properly escaped before being included in the SQL query.

Notice that we do not keep the quotes around the `%s` placeholder value. By using the appropriate placeholder, the `prepare()` method automatically handles that for us. The following placeholders per data type are:

- `%d` (integer)
- `%f` (float)
- `%s` (string)
- `%i` (identifier, e.g. table/field names, introduced in WordPress 6.2.0)

### Validating Data

In addition to properly escaping dynamic values used in SQL queries, it's also important to validate the data. Even honest users can enter incorrect data, so it's important for your plugin to check the data before using it. This improves data integrity and it also gives your plugin a chance to alert users of what's expected if certain criteria isn't met.

Some common [data validation techniques](https://developer.wordpress.org/apis/security/data-validation/) are:

- **Safelist** – Accept data only if it matches a known, trusted value. (eg. selecting a favorite color option)
- **Blocklist** – Reject data if it matches a known, untrsted value. (eg. rejecting profanity)
- **Format Detection** – Test that the data matches an accepted format. (eg. formatting phone numbers)

When comparing untrusted data against the safelist, it’s important to **use strict type checking**. Otherwise, an attacker could craft input in a way that will pass validation against a safelist, but still have a malicious effect.

```php
$untrusted_input = '1 malicious string';  // Will evaluate to integer 1 during loose comparisons.
$safe_values     = array( 1, 5, 7 ); // Integer 1 is an allowed value.

if ( in_array( $untrusted_input, $safe_values, true ) ) {  // `true` enables strict type checking.
    echo 'Success!';
} else {
    wp_die( 'The value you submitted is not allowed.' ); // The provided string is not allowed!
}
```

