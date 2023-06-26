# WordPress Developer Fundamentals - The WordPress Database

https://gist.github.com/jonathanbossenger/d96520acd6225ea969f091752a3bca8b

## Introduction

WordPress uses a database to store, retrieve, and display all of the content that you create on your website. In this lesson, we'll take a look at the WordPress database and how it works. We will dive into each of the tables in the database, learn about core WordPress functions to interact with these database talbes, and learn how to create and interact with custom database tables.

## The WordPress Database

WordPress uses a database to store all of the content that you create on your website. This includes posts, pages, comments, and more. The database is also used to store information about your website's users, such as their usernames, passwords, and email addresses.

WordPress uses a database management system called MySQL. MySQL is a free, open-source database management system that is used by many popular websites, including Facebook, Twitter, and YouTube.

## Database Tables

The WordPress database is made up of many different tables. Each table stores a different type of information about your website. For example, the `wp_posts` table stores information about your website's posts, while the `wp_users` table stores information about your website's users.

### wp_comments and wp_commentmeta

The `wp_comments` table stores information about your website's comments. Each row in the `wp_comments` table represents a single comment. The `wp_commentmeta` table can stores additional information about each comment.

### Functions to interact with comments

https://developer.wordpress.org/?s=comment

### wp_links

The `wp_links` table stores information about your website's links. Each row in the `wp_links` table represents a single link. Links was a feature that was removed from WordPress in version 3.5.

https://core.trac.wordpress.org/ticket/21307

However, the `wp_links` table is still included in the WordPress database for backwards compatibility, and it is still possible to add links to your website using the Links Manager plugin.

https://wordpress.org/plugins/link-manager/
https://downloads.wordpress.org/plugin/link-manager.zip

### wp_options

The `wp_options` table stores information about your website's settings. Each row in the `wp_options` table represents a single setting. For example, the `siteurl` option stores the URL of your website, and the `blogdescription` option stores the tagline of your website. The `wp_options` table also stores information about your website's active theme and active plugins. Data is stored in the `wp_options` table using a key-value format. The key is the name of the option, and the value is the value of the option. It is also possible to store serialized data in the `wp_options` table. Serialized data is a string that contains multiple values. Serialized data is often used to store arrays and objects of data.

### Functions to interact with options

https://developer.wordpress.org/apis/options/

get_option
update_option
delete_option

The Options API is typically used along with the Settings API to create settings pages for plugins and themes.

## wp_terms, wp_termmeta, wp_term_relationships, and wp_term_taxonomy

The `wp_terms` table stores information about your website's terms. Each row in the `wp_terms` table represents a single term. The `wp_termmeta` table can stores additional information about each term. The `wp_term_relationships` table stores information about the relationships between terms and posts. The `wp_term_taxonomy` table stores information about the taxonomies that are used by your website's terms.

### Functions to interact with terms and taxonomies

https://developer.wordpress.org/?s=term

https://developer.wordpress.org/?s=taxonomy 

## wp_user and wp_usermeta

The `wp_users` table stores information about your website's users. Each row in the `wp_users` table represents a single user. The `wp_usermeta` table can stores additional information about each user.

### Functions to interact with users

https://developer.wordpress.org/?s=user

## Custom Database Tables

https://developer.wordpress.org/reference/functions/dbdelta/
https://codex.wordpress.org/Creating_Tables_with_Plugins

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

https://developer.wordpress.org/reference/classes/wpdb/

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

## Conclusion