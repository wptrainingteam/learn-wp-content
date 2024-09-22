<?php
/**
 * Plugin Name: Module 07 - Custom Database Tables
 * Version:     1.0.0
 * Author:      Learn WordPress
 * Author URI:  https: //learn.wordpress.org/
 */

global $my_plugin_db_version;
$my_plugin_db_version = '2.0'; // Increased database version number.

register_activation_hook( __FILE__, 'my_plugin_create_table' );
function my_plugin_create_table() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	// Added new `url` field to the custom table schema.
	$sql = "CREATE TABLE {$wpdb->prefix}my_plugin_table (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	name tinytext NOT NULL,
	text text NOT NULL,
	url varchar(55) DEFAULT '' NOT NULL,
	PRIMARY KEY  (id)
	) {$charset_collate};";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	// Set or update the currently installed version.
	global $my_plugin_db_version;
	update_option( 'my_plugin_db_version', $my_plugin_db_version );
}

add_action( 'plugins_loaded', 'my_plugin_update_db_check' );
function my_plugin_update_db_check() {
	global $my_plugin_db_version;
	if ( get_option( 'my_plugin_db_version' ) != $my_plugin_db_version ) {
		my_plugin_create_table();
	}
}

register_uninstall_hook( __FILE__, 'my_plugin_delete_table' );
function my_plugin_delete_table() {
    global $wpdb;
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}my_plugin_table" );
    delete_option( 'my_plugin_db_version' );
}
