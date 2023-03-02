# Common Security Vulnerabilities in WordPress Plugins

## Objectives

Upon completion of this lesson the participant will be able to:

## Outline

- Preventing common security vulnerabilities
  - Common Vulnerabilities
  - SQL Injection
  - Cross Site Scripting (XSS)
  - Cross-site Request Forgery (CSRF)
  - Broken Access Control
  - Bonus round
  - Where to go for more information
    - https://owasp.org/www-project-top-ten/
    - https://developer.wordpress.org/plugins/security

## Introduction

Hey there, and welcome to Learn WordPress.

In this tutorial, you're going to learn how to protect your WordPress plugins against common security vulnerabilities.

You will learn the benefits of ensuring your plugin code is secure, what steps to follow to secure your plugins, and where to find more information around plugin security.

## Common Vulnerabilities

Security is an ever-changing landscape, and vulnerabilities evolve over time. 

See https://owasp.org/www-project-top-ten/ and how things have changed from 2017 to 2021

The following is a discussion of common vulnerabilities you should protect against in WordPress, and the techniques for protecting your theme from exploitation. To do so, we'll be looking at the code of the badly coded form submission plugin, and fixing any security vulnerabilities we find.

https://github.com/jonathanbossenger/wp-learn-plugin-security

## SQL Injection

SQL injection happens when values being inputted are not properly sanitized allowing for any SQL commands in the inputted data to potentially be executed.

The first rule for hardening your theme or plugin against SQL injection is: When there’s a WordPress function, use it.

But sometimes you need to do complex queries, that have not been accounted for in the API. If this is the case, always use the $wpdb functions. These were built specifically to protect your database.

The first place we need to tackle a possible SQL Injection vulnerability is in the `wp_learn_maybe_process_form` function.

```php
function wp_learn_maybe_process_form() {
	if (!isset($_POST['wp_learn_form'])){
		return;
	}
	$name = $_POST['name'];
	$email = $_POST['email'];

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

1. We need to make sure that any `$_POST` data is sanitized before being used in the query. 
2. We need to either use the `wpdb` prepare or insert functions.

```php
function wp_learn_maybe_process_form() {
	if (!isset($_POST['wp_learn_form'])){
		return;
	}
	$name = sanitize_text_field($_POST['name']);
	$email = sanitize_email($_POST['email']);

	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$rows = $wpdb->insert(
		$table_name,
		array(
			'name' => $name,
			'email' => $email,
		)
	);
	if ( 0 < $rows ) {
		wp_redirect( WPLEARN_SUCCESS_PAGE_SLUG );
		die();
	}

	wp_redirect( WPLEARN_ERROR_PAGE_SLUG );
	die();
}
```

The other place we need to prevent SQL injection is in the `wp_learn_delete_form_submission` function.

1. We need to make sure that any `$_POST` data is sanitized before being used in the query.
2. We need to either use the `wpdb` prepare or delete functions.

```php
function wp_learn_delete_form_submission() {
	$id = (int) $_POST['id'];
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$rows_deleted = $wpdb->delete( $table_name, array( 'id' => $id ) );
	if ( 0 < $rows_deleted ) {
		$result = 'success';
	} else {
		$result = 'error';
	}
	return wp_send_json( array( 'result' => $result ) );
}
```

## Cross Site Scripting (XSS)

Cross Site Scripting (XSS) happens when a nefarious party injects JavaScript into a web page.

Avoid XSS vulnerabilities by escaping output, stripping out unwanted data. Your code should escape dynamic content with the proper function depending on the type of the content.

Let's look at some places where data is being outputted, and make sure it's being escaped properly.

The first place is in the wrapper div of the `wp_learn_form_shortcode` shortcode callback.

```php
<div id="wp_learn_form" class="<?php echo $atts['class'] ?>">
```

Here the class attribute of the div is rendered based in the attributes passed to the shortcode. This is a potential XSS vulnerability, as the class attribute is not escaped.

```php
<div id="wp_learn_form" class="<?php echo esc_attr( $atts['class'] ) ?>">
```

Note that you should specifically use the `esc_attr` function to escape HTML attributes.

Next, we have the `wp_learn_render_admin_page` function, which renders the admin page.

```php
function wp_learn_render_admin_page(){
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
					<td><?php echo $submission->name?></td>
					<td><?php echo $submission->email?></td>
					<td><a class="delete-submission" data-id="<?php echo $submission->id?>" style="cursor:pointer;">Delete</a></td>
				</tr>
			<?php } ?>
		</table>
	</div>
	<?php
}
```

Here the $submission->name, $submission->email and $submission->id should be escaped. 

```php
<?php foreach ($submissions as $submission){ ?>
    <tr>
        <td><?php echo esc_html($submission->name)?></td>
        <td><?php echo esc_html($submission->email)?></td>
        <td><a class="delete-submission" data-id="<?php echo (int) $submission->id?>" style="cursor:pointer;">Delete</a></td>
    </tr>
<?php } ?>
```

Here we use esc_html because this is the correct function to use anytime an HTML element encloses a section of data being displayed. Always pay close attention to what each escaping function does, as some will remove HTML while others will permit it. You must use the most appropriate function to the content and context of what you’re echoing.

Finally, we cast the ID to an integer, as it is being used in a data attribute. 

## Cross-site Request Forgery (CSRF)

Cross-site request forgery or CSRF (pronounced sea-surf) is when a nefarious party tricks a user into performing an unwanted action within a web application they are authenticated in. For example, a phishing email might contain a link to a page that would delete a user’s account in the WordPress admin.

When developing with WordPress, becoming familiar with the nonce API is a must. Nonces are Number Used Once and provide a way to verify that the origin of the request is legitimate. 

1. You create a nonce when you need to verify that the request is legitimate.
2. You output or pass the nonce to where ever needs to make a request
3. You verify the nonce when the request is made.

There are to possible CSRF vulnerabilities in this plugin. The first is when the form is submitted, and the data is processed. To fix this, we need to add a nonce to the form being rendered in the shortcode, and then verify it when the form is submitted.

In the form, we use the wp_nonce_field function to add a hidden field with the nonce.

```php
<?php
/**
 * 04 (b). Add a nonce to the form
 * https://developer.wordpress.org/apis/security/nonces/
 */
wp_nonce_field( 'wp_learn_form_nonce_action', 'wp_learn_form_nonce_field' );
?>
```

Then in the form submission function, we verify the nonce. 

```php
	/**
	 * 04 (b). Verify the nonce
	 * https://developer.wordpress.org/apis/security/nonces/
	 */
	if ( ! isset( $_POST['wp_learn_form_nonce_field'] ) || ! wp_verify_nonce( $_POST['wp_learn_form_nonce_field'], 'wp_learn_form_nonce_action' ) ) {
		wp_redirect( WPLEARN_ERROR_PAGE_SLUG );
		die();
	}
```

Here we are checking that the nonce field has been passed in the request, and then verifying that the nonce is valid. If the nonce is invalid, we redirect to the error page.

The other place we need to prevent CSRF is in the ajax callback used to delete a form submission. 

```php
function wp_learn_delete_form_submission() {
	$id = (int) $_POST['id'];
	global $wpdb;
	$table_name = $wpdb->prefix . 'form_submissions';

	$rows_deleted = $wpdb->delete( $table_name, array( 'id' => $id ) );
	if ( 0 < $rows_deleted ) {
		$result = 'success';
	} else {
		$result = 'error';
	}
	return wp_send_json( array( 'result' => $result ) );
}
```

To fix this, first we need to manually create the nonce using wp_create_nonce and then pass the nonce to the JavaScript layer using wp_localize_script.

```php
  /**
   * 04 (a). Add an ajax nonce to the script
   * https://developer.wordpress.org/apis/security/nonces/
   */
  $ajax_nonce = wp_create_nonce( 'wp_learn_ajax_nonce' );
  wp_localize_script(
      'wp_learn-admin',
      'wp_learn_ajax',
      array(
          'ajax_url' => admin_url( 'admin-ajax.php' ),
          'nonce'    => $ajax_nonce,
      )
  );
```

Then, we need to include the nonce in jQuery POST request.

```js
jQuery.post(
    wp_learn_ajax.ajax_url,
    {
        '_ajax_nonce': wp_learn_ajax.nonce,
        'action': 'delete_form_submission',
        'id': id,
    },
    function (response) {
        console.log( response );
        alert( 'Form submission deleted' );
        document.location.reload();
    }
);
```

Note how we specify the nonce in the POST request as _ajax_nonce. This is the name of the nonce that WordPress expects.

Lastly, in the Ajax callback, we verify the nonce, using the handy `check_ajax_referrer` function.

```php
	check_ajax_referer( 'wp_learn_ajax_nonce' );
```

You'll see that the string passed to `check_ajax_referer` is the same string we passed to `wp_create_nonce` when creating the nonce.

## Broken Access Control

Broken access control is when a user is able to access a resource they should not be able to access. For example, a user might be able to access an admin function, even though they are not an administrator.

In our example, we have a broken access control vulnerability in ajax function, at the present moment, anyone could make a request to the ajax request url with the right data, and it would delete a form_submission. 

To fix this, we can use the WordPress Roles and Capabilities API to check that the user has the correct permissions to delete a form submission. In this case, it could just be a simple as checking that the user is an admin user

```php
	if ( ! current_user_can( 'manage_options' ) ) {
		return wp_send_json( array( 'result' => 'Authentication error' ) );
	}
```

Note that we're doing two checks here, one against CSRF and once for access control. In this example the order of execution is not super important, but in general, it's a good idea to check for CSRF first, and then check for access control.

## Bonus round

There's one additional security vulnerability in this plugin. Can you find it?

It's a tough one to spot, but all instances of wp_redirect should be replaced with wp_safe_redirect. This is because the code is redirecting to a local url, and wp_safe_redirect checks whether the $location is using an allowed host, if it has an absolute path. This prevents the possibility of malicious redirects if the redirect $location is ever attacked.

## Where to go for more information

Firstly, make sure to vist the Open Worldwide Application Security Project's (OWASP) [top ten list](https://owasp.org/www-project-top-ten/), to learn about the most common web vulnerabilities. Then, make sure to read the entry in the [WordPress Developer Documentation](https://developer.wordpress.org/plugins/security) on Security, as it includes code examples, additional information on security best practices, and much more.

Happy coding!