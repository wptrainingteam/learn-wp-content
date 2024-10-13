# Interacting with Custom Tables

Once you have your own custom table in the WordPress database, you need to know how to interact with it. In this lesson, we’ll cover how to safely query custom tables by using the `global $wpdb` object. You'll also learn how to insert, update, and delete data while following best practices to avoid common security vulnerabilities.

A key aspect of interacting with any database is to ensure the data you send and retrieve is secure and validated. One of the most dangerous threats to your plugin’s security is SQL injection. Let's take a look at how that happens and what you can do to safeguard your tables against it.

## Protecting Against SQL Injections

SQL injection is a security vulnerability where attackers manipulate SQL queries by injecting malicious code into user input fields. If unsanitized input values are used in queries directly, then an attacker could execute arbitrary SQL commands which compromises your data.

Consider this example where user input is inserted directly into a query without validation:

```php
global $wpdb;
$name = $_GET['name'];

$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}submissions WHERE name = '$name'" );
```

In this example, an attacker could supply a value like `John'; DROP TABLE wp_submissions;--`, which would delete the entire custom table! Let's see how we can prevent this from happening.

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

- **Safelist** – Accept data only if it matches a known, trusted value. (eg. selecting a color option)
- **Blocklist** – Reject data if it matches a known, untrusted value. (eg. rejecting profanity)
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

## Inserting Data

To store information in your custom table, use the [`$wpdb->insert()`](https://developer.wordpress.org/reference/classes/wpdb/insert/) method to add records to the table. This method helps you securely add data while ensuring input is sanitized and properly formatted for the database.

For example, suppose you’re building a plugin that records customer reviews. Each review includes a customer name, the review text, and a star rating from 1 to 5. The table schema could look like this:

```php
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE {$wpdb->prefix}reviews (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  review_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  customer_name tinytext NOT NULL,
  review_text text NOT NULL,
  star_rating tinyint(1) NOT NULL,
  PRIMARY KEY (id)
) {$charset_collate};";
```

When a user submits a product review, you can record a new entry securely into the custom table like this:

```php
global $wpdb;

// Sanitize input submitted by the user.
$customer_name = sanitize_text_field( $_POST['customer_name'] );
$review_text   = sanitize_textarea_field( $_POST['review_text'] );
$star_rating   = intval( $_POST['star_rating'] );

// Check that the star rating is between 1 and 5.
if ( $star_rating < 1 || $star_rating > 5 ) {
  wp_die( 'Invalid star rating. Please submit a value between 1 and 5.' );
}

// Add the new entry into the custom table.
$wpdb->insert(
  "{$wpdb->prefix}reviews",
  array(
    'customer_name' => $customer_name,
    'review_text'   => $review_text,
    'star_rating'   => $star_rating,
  ),
  array(
    '%s', // customer_name : string.
    '%s', // review_text : string.
    '%d', // star_rating : integer.
  )
);
```

As you can see, this example demonstrates the data security techniques we previously discussed:

1. **Sanitizing Inputs:** We use [`sanitize_text_field()`](https://developer.wordpress.org/reference/functions/sanitize_text_field/) and [`sanitize_textarea_field()`](https://developer.wordpress.org/reference/functions/sanitize_textarea_field/) to clean user input. The star rating is also sanitized by using [`intval()`](https://www.php.net/manual/en/function.intval.php) which casts it to an integer.

2. **Validating Data:** We ensure that the star rating falls between 1 and 5. If the input fails, the [`wp_die()`](https://developer.wordpress.org/reference/functions/wp_die/) function halts execution and shows an informative error message.

3. **Using** `$wpdb->insert()`**:** This method inserts data into the custom table using three parameters:
   - The table name.
   - An associative array with column names as keys and the corresponding values to be inserted.
   - An optional array specifying the corresponding data type placeholders for each value.

Since `$wpdb->insert()` handles the underlying SQL `INSERT` query, you do not need to use `$wpdb->prepare()` in this instance.

## Querying Data

After successfully storing reviews in the custom table, you’ll want to retrieve them for display on your website. To do this safely and efficiently, you can use the [`$wpdb->get_results()`](https://developer.wordpress.org/reference/classes/wpdb/get_results/) method, which allows you to fetch multiple records at once.

To display the 10 most recent reviews, you can query them from the custom table like this:

```php
global $wpdb;

// Retrieve the 10 latest reviews.
$reviews = $wpdb->get_results( 
  "SELECT customer_name, review_text, star_rating, review_time 
   FROM {$wpdb->prefix}reviews 
   ORDER BY review_time DESC
   LIMIT 10"
);

// Display each customer review.
if ( ! empty( $reviews ) ) {
  foreach ( $reviews as $review ) {
    echo '<div class="review">';
    echo '<h3>' . esc_html( $review->customer_name ) . '</h3>';
    echo '<p>' . esc_html( $review->review_text ) . '</p>';
    echo '<p>Rating: ' . intval( $review->star_rating ) . ' / 5</p>';
    echo '<small>Submitted on: ' . esc_html( $review->review_time ) . '</small>';
    echo '</div>';
  }
} else {
  echo '<p>No reviews found.</p>';
}
```

As you can see, the `get_results()` method returns an array of objects where each object is a single row retrieved from the table. Alternatively, you can provide a second parameter to the function to specify the desired output type. For example, to retrieve rows as associative arrays instead of objects, you can use the `ARRAY_A` constant.

### Dynamic Queries

Let's assume you now want to add a new feature that allows user to filter reviews by their star rating. The SQL query will ultimately be the same except for which star rating the user selects.

Here's how to securely query results dynamically based on the user's request:

```php
$star_rating = intval( $_GET['star_rating'] );

$prepared_query = $wpdb->prepare(
  "SELECT customer_name, review_text, review_time 
   FROM {$wpdb->prefix}reviews 
   WHERE star_rating = %d 
   ORDER BY review_time DESC",
  $star_rating
);

$reviews = $wpdb->get_results( $prepared_query );
```

Since user-submitted data is incorporated into this SQL query, we must use the `prepare()` method to prevent SQL injection vulnerabilities.
