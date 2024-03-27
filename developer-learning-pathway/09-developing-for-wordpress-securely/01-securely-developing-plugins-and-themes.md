# Securely developing plugins and themes

## Introduction

So far, you have become familiar with the basics of developing plugins and themes for WordPress. Now would be a good time to also consider the security implications of writing code. 

In this lesson, you're going to learn how to develop with security in mind. 

You will learn the benefits of ensuring your code is secure, what steps to follow to secure your code, and where to find more information around developing with security in mind.

## Disclaimer

This lesson was created as an introduction to being security minded when developing plugins and themes. The code used in this lesson is very simplified. You should not use any of the code used in this tutorial in your plugins or themes.

Please make sure to read the full documentation on Security in the WordPress Developer handbook at https://developer.wordpress.org/apis/security/ to ensure you follow the correct methods and procedures.

## What is developing with security in mind?

Developing with security in mind is the process of ensuring your code not only works, but also does not introduce any security vulnerabilities. 

If your plugin or theme code has security vulnerabilities, it could make any WordPress site that has your product installed open to potential attacks, and can lead to that site being compromised.

When writing code, it's important to develop a security mindset, and to think about how your code could be used maliciously.

- Don't trust any data, whether it's user input, third party API data, or even data in your database. Always be checking to make sure it's valid and safe to use.
- WordPress has a number of APIs that can help you with common tasks, such as sanitizing user input, validating data, and escaping output. Rely on using these APIs to help validate and sanitize your data instead of writing your own functions.
- Keep up to date with common vulnerabilities and keep your code up to date to prevent them.

## Where does security fit in the development process?

Security should be a consideration at every stage of the development process.

Generally, the largest number of security vulnerabilities that are found take place at the PHP layer, which is excuted on the server. 

Therefore, this lesson will focus on the top most common preventive measures in your PHP code.

## Sanitizing inputs

One of the first steps to take when developing is to ensure that any user input is sanitized. This means that any data that is being passed from the user, such as a form submission, or a URL parameter, is checked to make sure it's safe to use.

In this example code, the name and email fields are being posted from a form that a plugin generates, and then saved in a custom database table called `form_submissions`;

```
$name = $_POST['name'];
$email = $_POST['email'];
global $wpdb;
$table_name = $wpdb->prefix . 'form_submissions';

$sql = "INSERT INTO $table_name (name, email) VALUES ('$name', '$email')";
$result = $wpdb->query($sql);
```

As you can see the data is being saved directly to the database, without any sanitization.

This means that if a user were to submit a name of `John'; DROP TABLE form_submissions;--` the SQL INSERT query would be run, followed by the DROP query, and the table would be deleted from the database!

WordPress has a sanitization API that can be used to sanitize incoming data. You can use the `sanitize_text_field` and `sanitize_email` functions to sanitize the name and email fields before they're used in the query.

```
$name = sanitize_text_field( $_POST['name'] );
$email = sanitize_email( $_POST['email'] );

global $wpdb;
$table_name = $wpdb->prefix . 'form_submissions';

$sql = "INSERT INTO $table_name (name, email) VALUES ('$name', '$email')";
$result = $wpdb->query($sql);
```

Notice that the code follows a key principle of sanitizing data, in that you do so as early as possible.

To read more about the available sanization functions available to WordPress developers, check out the [Sanitizing Data](https://developer.wordpress.org/apis/security/sanitizing/) page in the WordPress Developer Documentation.

## Validating Data

Validating data is the process of testing it against a predefined pattern (or patterns) with a definitive result, either valid or invalid.

Untrusted data can come from many sources, users, third party API data, even your database data can be considered untrusted, especially if something else has modified it. Even site admins can make a mistake and enter incorrect or insecure data, so it's important to always be checking your data.

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

Here the ID is being used directly in the SQL query, without any validation. Again, this means that if a user were to submit an ID of `1; DROP TABLE form_submissions;` the same SQL DROP query would be run after the delete, and the table would be dropped.

To prevent this, you can use [PHP's type casting](https://www.php.net/manual/en/language.types.type-juggling.php#language.types.typecasting) functionality, to ensure that the value of $id is always an integer value. This can be done by adding `(int)` before the variable name, like so:

```
$id = (int) $_POST['id'];
```

Note that this will only work if the first character in the string passed via the $_POST array can be cast as an integer, otherwise the value if $id will be 0. In that case it's a good idea to update the code to handle this case.

The code also follows a key principle of validating data, in that you do so as early as possible.

```php
if ($id === 0){
    // return early with an error
    return wp_send_json( array( 'result' => 'Invalid ID passed' ) );

}
```

To read more about the various ways to validate data, check out the section on [Validating Data](https://developer.wordpress.org/apis/security/data-validation/) in the WordPress Developer Documentation.

## Escaping outputs

Another aspect of security is to ensure that any information you output to the browser is safe, including any text, HTML or JavaScript code, or data from the database. 

Even if your code is not responsible for the source of the data being displayed, it is responsible for displaying it safely.

In this example, the code fetches the form submissions from the database, and then loops through the submissions and outputs the submission data in an admin screen in the WordPress dashboard:

```php
	$submissions = wp_learn_get_form_submissions();
	?>
	<div class="wrap" id="wp_learn_admin">
		<h1>Admin</h1>
		<table>
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
				</tr>
			</thead>
			<?php foreach ($submissions as $submission){ ?>
				<tr>
					<td><?php echo $submission->name; ?></td>
					<td><?php echo $submission->email; ?></td>
					<td><a class="delete-submission" data-id="<?php echo $submission->id?>" style="cursor:pointer;">Delete</a></td>
				</tr>
			<?php } ?>
		</table>
	</div>
	<?php
```

Here we have three pieces of data that need to be escaped, the `$submission->name` and `$submission->email` fields, as well as the `$submission->id`.

```
<td><a class="delete-submission" data-id="<?php echo (int) $submission->id?>" style="cursor:pointer;">Delete</a></td>
```

For the name and email fields, we can use the built-in WordPress escaping function `esc_html()`. The ID can be escaped by casting it to an integer, as you might do for data validation.

```php
  <td><?php echo esc_html( $submission->name ); ?></td>
  <td><?php echo esc_html( $submission->email ); ?></td>
  <td><a class="delete-submission" data-id="<?php echo (int) $submission->id?>" style="cursor:pointer;">Delete</a></td>
```

Notice that this code follows a key principle of escaping data, in that you escape the data as late as possible.

To read more about the various ways to escape outputs, check out the section on [Escaping Data](https://developer.wordpress.org/apis/security/escaping/) in the WordPress Developer Documentation.

## Preventing invalid requests

Whenever a request is made, it's important to check that the request is valid. This means checking that the request is coming from a trusted source.

For example, you might have a shortcode that renders a form, where users can submit their information. 

The function to render the form might look something like this.

```php
add_shortcode( 'wp_learn_form_shortcode', 'wp_learn_form_shortcode' );
function wp_learn_form_shortcode() {
	ob_start();
	?>
	<form method="post">
		?>
		<input type="hidden" name="wp_learn_form" value="submit">
		<div>
			<label for="email">Name</label>
			<input type="text" id="name" name="name" placeholder="Name">
		</div>
		<div>
			<label for="email">Email address</label>
			<input type="text" id="email" name="email" placeholder="Email address">
		</div>
		<div>
			<input type="submit" id="submit" name="submit" value="Submit">
		</div>
	</form>
	<?php
	$form = ob_get_clean();
	return $form;
}
?>
```

When the user submits the form, the data is sent to the server. The data is then processed, sanitized, and stored in the database.

```php
add_action( 'wp', 'wp_learn_maybe_process_form' );
function wp_learn_maybe_process_form() {
	if (!isset($_POST['wp_learn_form'])){
		return;
	}

	$name = sanitize_text_field( $_POST['name'] );
	$email = sanitize_email( $_POST['email'] );

	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$sql = "INSERT INTO $table_name (name, email) VALUES ('$name', '$email')";
	$result = $wpdb->query($sql);
	if ( 0 < $result ) {
		wp_redirect( WPLEARN_SUCCESS_PAGE_SLUG );
		die();
	}

	wp_redirect( WPLEARN_ERROR_PAGE_SLUG );
	die();
}
```

Because the form might appear on any page where the shortcode is used, it's possible that a malicious user could attempt to send a POST request to the form, either looking for a vulnerability in the plugin, or by sending multiple requests to the form.

To prevent this, you can check that the request is coming from a trusted source. To do this, you can implement something called a nonce, or a number used once.

First, in the form itself, you can add a nonce field by using the `wp_nonce_field` function, and passing a nonce action and nonce name to the function:

```php
wp_nonce_field( 'wp_learn_form_nonce_action', 'wp_learn_form_nonce_field' );
```

When the form is rendered on the front end, a hidden field is added to the form, using the nonce name as the id and name attribute of the hidden field, and the generated nonce as the field value. This data is posted when the form is submitted.

Then, in the function that processes the form data, you can verify that the nonce is valid by using the `wp_verify_nonce` function, and by passing the POSTed nonce field and the nonce action to this function. If the result of this verification is false, you can exit early, preventing any further code execution.

```php
	if ( wp_verify_nonce( $_POST['wp_learn_form_nonce_field'], 'wp_learn_form_nonce_action' ) {
		wp_redirect( WPLEARN_ERROR_PAGE_SLUG );
		die();
	}
```

Any time your code makes a web request, be it via redirection to a new URL, POSTing data to a form, or making an AJAX request, you should check that the request is valid.

To read more about how to use nonces in your plugins, check out the section on [Nonces](https://developer.wordpress.org/apis/security/nonces/) in the WordPress Developer Documentation.

## Preventing unauthenticated users

Depending on your code's functionality, it's a good idea to restrict certain features only to users with a specific permission level. For example, you may have a function that deletes data from the database.

```
function wp_learn_delete_form_submission() {
	if ( ! isset( $_POST['id'] ) ) {
		wp_send_json_error( 'Invalid ID' );
	}
	$id = (int) $_POST['id'];

	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$sql    = "DELETE FROM $table_name WHERE id = $id";
	$result = $wpdb->get_results( $sql );

	return wp_send_json( array( 'result' => $result ) );
}
```

While you might do your best to prevent anyone who doesn't have permission to run this function, it's still a good idea to include checks against this situation.

WordPress includes a robust [User Roles and Capabilities](https://developer.wordpress.org/apis/security/user-roles-and-capabilities/) system, which allows you to either use the default user roles and capabilities, or create custom ones.

In this case, it could be as easy as only allowing users who have the capability to manage site options, which is a standard capability that is included with the administrator role.

```php
function wp_learn_delete_form_submission() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json( array( 'result' => 'Authentication error' ) );
	}
    // rest of function code
}
```

The WordPress Developer Documentation has a detailed section on [User Roles and Capabilities](https://developer.wordpress.org/apis/security/user-roles-and-capabilities/), which includes a list of the default capabilities, and how to create custom ones.

## Further reading

To prepare yourself to think about developing with security in mind, make sure to read the entry in the WordPress Developer Documentation on [Security](https://developer.wordpress.org/apis/security/), as it includes all the examples in this lesson, as well as additional information on security best practices, common vulnerabilities, and more example code.