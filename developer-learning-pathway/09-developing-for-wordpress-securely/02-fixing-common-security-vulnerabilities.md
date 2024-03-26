# Fixing Common Security Vulnerabilities

## Introduction

In the first lesson of this module, you learned about the main security principles that you should follow when developing a custom plugin or theme. 

In this lesson, you're going to learn how to apply these principles when developing your WordPress plugins and themes, by fixing a badly coded form submission plugin.

## The badly coded form submission plugin

To start, browse to the [badly coded form submission plugin](https://github.com/jonathanbossenger/wp-learn-plugin-security), and download and install the plugin to your local development environment.

Then, open the main plugin file in your code editor, and take a look at the code.

1. At the top of the main plugin PHP file , some constants are set up, which are used elsewhere in the plugin. The first two are used to define page slugs that the plugin will use to redirect to. For this plugin functionally to work, these pages need to exist, with the correct slugs.
2. Next a callback function is registered on the plugin activation hook. This sets up a custom form_submissions table in the database.
3. After that, the plugin's admin JavasScript and front end style CSS files are enqueued
4. Next, a shortcode is registered, which is used to display a form on the front end.
5. After that, a callback function is hooked into the wp action, which is what the plugin uses to process a form submission.
6. Next, the plugin registers an admin submenu, which displays a list of form submissions.
7. There is a function that the admin submenu uses to fetch the form submissions from the database.
8. Lastly there is a callback function that's hooked into a wp_ajax function, which is the ajax endpoint the plugin uses to delete form submissions from the admin submenu page

The admin JavaScript file handles the ajax request to delete form submissions from the submissions page.

The front end style CSS file is used when the user enters a class attribute for the shortcode. It defaults to red, but the user can change this to blue. It simply adds a border to the form.

When the shortcode is added to a post or page, it renders the form, and users can submit their details. when the form is submitted, it will redirect either to the success or error page, depending on whether the form submission was successful or not. Then in the dashboard, admins can view the form submissions, and delete the submission using Ajax.

## SQL Injection

The first common vulnerability we're going to look for is SQL injection.

SQL injection happens when values being inputted are not properly sanitized allowing for any SQL commands using the inputted data to potentially be executed on the database.

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

This will ensure that the name and email field values are both sanitized as they are accepted from the form submission request and that they are sanitized before being used to store the record in the database. While this might seem like overkill, if you just sanitize the inputs, and the code is later changed to use the values in a different way, you could still be vulnerable to a SQL injection.

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

Because this is an integer, we can use the PHP type casting functionality to make sure it's always cast as an integer.

## Cross Site Scripting (XSS)

The next common vulnerability we're going to look for is Cross Site Scripting (XSS).

Cross Site Scripting (XSS) happens when a nefarious party injects JavaScript into a web page, which can be used to launch multiple different attacks or malicious activities from the website.

You can avoid XSS vulnerabilities by escaping output, stripping out unwanted data. Your code should escape dynamic content with the proper function depending on the type of the content being escaped.

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

In this example you can use esc_html because this is the correct function to use anytime an HTML element encloses a section of data being displayed. Always pay close attention to what each escaping function does, as some will remove HTML while others will permit it.

You must use the most appropriate function to the content and context of what you’re echoing.

Finally, you cast the ID to an integer, as it is being used in a data attribute.

## Cross-site Request Forgery (CSRF)

The next vulnerability to prevent is Cross-site request forgery. CSRF is when a nefarious party tricks a user into performing an unwanted action within a web application they are authenticated in.

When developing with WordPress, becoming familiar with WordPress nonces is a must to help prevent CSRF.

A Nonce is a Number Used Once and provides a way to verify that the origin of the request is legitimate.

1. You create a nonce when you need to verify that the request is legitimate.
2. You output or pass the nonce to whereever needs to make a request
3. You verify the nonce when the request is made.

There are to possible CSRF vulnerabilities in this plugin.

The first is when the form is submitted, and the data is processed. To fix this, we need to add a nonce to the form being rendered in the shortcode, and then verify it when the form is submitted.

In the form, we use the wp_nonce_field function to add a hidden field with the nonce.

```php
<?php
wp_nonce_field( 'wp_learn_form_nonce_action', 'wp_learn_form_nonce_field' );
?>
```

Notice how you pass in an action and a name. The action is used to identify the nonce, and the name is the name of the field that will be added to the form.

If you inspect the form, you can see the nonce field, which using the name you passed to the funciton, and the nonce value.

Then in the form submission function, you verify the nonce, using the wp_verify_nonce function, passing in the value of the nonce field, and the action.

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

Here we are checking that the nonce field has been passed in the request, and then verifying that the nonce is valid. If the nonce is not pass, or is invalid, we redirect to the error page.

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

To fix this, first we need to manually create a nonce using wp_create_nonce and then pass the nonce to the JavaScript layer using wp_localize_script.

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

Note how we specify the nonce in the POST request as _ajax_nonce. This is the name of the nonce that WordPress expects when processing an Ajax request.

Lastly, in the Ajax callback, we verify the nonce, using the handy `check_ajax_referrer` function.

```php
	check_ajax_referer( 'wp_learn_ajax_nonce' );
```

You'll see that the string passed to `check_ajax_referer` is the same string we passed to `wp_create_nonce` when creating the nonce.

If check_ajax_referrer fails, it will cause execution to stop, so we don't need to check the result of the function.

## Broken Access Control

There's one more vulnerability in this plugin, and it's a broken access control vulnerability. BAC is when a user is able to access a resource they should not be able to access. For example, a user might be able to access an admin function, even though they are not an administrator.

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

It's a tough one to spot, but all instances of `wp_redirect` should be replaced with `wp_safe_redirect`. This is because the code is redirecting to a local url, and wp_safe_redirect checks whether the $location its using an allowed host, if it has an absolute path. This prevents the possibility of malicious redirects if the redirect $location is ever attacked.