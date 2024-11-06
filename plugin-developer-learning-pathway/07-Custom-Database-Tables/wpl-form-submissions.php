<?php
/**
 * Plugin Name: Form submissions
 * Version:     1.0.0
 * Author:      Learn WordPress
 * Author URI:  https: //learn.wordpress.org/
 *
 * @package wp-learn-form-submissions
 */

$wp_learn_custom_table_db_version = '1.1.0';

// Only triggered when the plugin is activated, NOT when the plugin is updated.
register_activation_hook( __FILE__, 'wp_learn_custom_table_create_submissions_table' );
function wp_learn_custom_table_create_submissions_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	// Creates table `wp_wpl_submissions`.
	$sql = "CREATE TABLE {$wpdb->prefix}wpl_submissions (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	name tinytext NOT NULL,
	message text NOT NULL,
	url varchar(55) DEFAULT '' NOT NULL,
	PRIMARY KEY  (id)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	// Set or update the currently installed version.
	global $wp_learn_custom_table_db_version;
	update_option( 'wp_learn_custom_table_db_version', $wp_learn_custom_table_db_version );
}

add_action( 'plugins_loaded', 'wp_learn_custom_table_update_db_check' );
function wp_learn_custom_table_update_db_check() {
	global $wp_learn_custom_table_db_version;
	$current_custom_table_db_version = get_option( 'wp_learn_custom_table_db_version', '1.0.0' );
	if ( version_compare( $current_custom_table_db_version, $wp_learn_custom_table_db_version ) ) {
		wp_learn_custom_table_create_submissions_table();
	}
}
