# WordPress plugin security

## Outline

- All about plugin security
  -  Sanitizing inputs
  -  Data validation
  -  Escaping outputs
  -  Preventing invalid requests
  -  Preventing unauthenticated users

- Where to go for more information

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn how to secure your WordPress plugins.

You will learn the benefits of ensuring your plugin code is secure, what steps to follow to secure your plugins, and where to find more information around plugin security.

## What is plugin security?

In short, plugin security is the process of ensuring your plugin code not only works, but also does not introduce any security vulnerabilities. If your plugin has security vulnerabilities, it could make WordPress site that has your plugin installed open to potential attacks, and can lead to that site being compromised.

When developing, it's important to develop a security mindset, and to think about how your code could be used maliciously. 

- Don't trust any data, whether it's user input, third party API data, or even data in your database. Always be checking to make sure it's valid and safe to use.
- WordPress has a number of APIs that can help you with common tasks, such as sanitizing user input, validating data, and escaping output. Rely on using these APIs to help validate and sanitize your data instead of writing your own functions.
- Keep up to date with common vulnerabilities and keep your code up to date to prevent them.

## [Sanitizing inputs](https://developer.wordpress.org/apis/security/sanitizing/)

One of the first steps to take when developing a plugin is to ensure that any user input is sanitized. This means that any data that is being passed to your plugin from the user, such as a form submission, or a URL parameter, is checked to make sure it's safe to use.

In this example code, the name and email fields are being posted from a form that a plugin generates, and then saved in a custom database table called `form_submissions`;

```
$name = $_POST['name'];
$email = $_POST['email'];
global $wpdb;
$table_name = $wpdb->prefix . 'form_submissions';

$sql = "INSERT INTO $table_name (name, email) VALUES ('$name', '$email')";
$result = $wpdb->query($sql);
```

In this example, the data is being saved directly to the database, without any sanitization. This means that if a user were to submit a name of `John'; DROP TABLE form_submissions;` the SQL query would be run, and the table would be dropped. This is a common SQL injection attack, and can be prevented by sanitizing the data before it's used in the query.

WordPress has a sanitization API that can be used to sanitize data. In this example, you can use the `sanitize_text_field` and `sanitize_email` functions to sanitize the name and email fields before they're used in the query.

```
$name = sanitize_text_field( $_POST['name'] );
$email = sanitize_email( $_POST['email'] );

global $wpdb;
$table_name = $wpdb->prefix . 'form_submissions';

$sql = "INSERT INTO $table_name (name, email) VALUES ('$name', '$email')";
$result = $wpdb->query($sql);
```

## Validating Data

Validating data is the process of testing it against a predefined pattern (or patterns) with a definitive result, either valid or invalid.

Untrusted data can come from many sources, users, third party API data, even your database data can be considered untrusted, especially if another plugin has modified it. Even site admins can make a mistake and enter incorrect or insecure data, so it's important to always be checking your data.

In this example, a deletion function requires that a numeric ID is posted to an admin-ajax callback:

```
add_action( 'wp_ajax_delete_form_submission', 'wp_learn_delete_form_submission' );
function wp_learn_delete_form_submission() {
	if ( ! isset( $_POST['id'] ) ) {
		wp_send_json_error( 'Invalid ID' );
	}
	$id = $_POST['id'];

	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$sql    = "DELETE FROM $table_name WHERE id = $id";
	$result = $wpdb->get_results( $sql );

	return wp_send_json( array( 'result' => $result ) );
}
```

In this example, the ID is being used directly in the SQL query, without any validation. This means that if a user were to submit an ID of `1; DROP TABLE form_submissions;` the SQL query would be run, and the table would be dropped. This is a common SQL injection attack, and can be prevented by validating the data before it's used in the query.