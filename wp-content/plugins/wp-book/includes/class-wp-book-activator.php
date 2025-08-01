<?php

/**
 * Fired during plugin activation
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Book
 * @subpackage Wp_Book/includes
 * @author     Arghadeep Dey <deyarghadeep23@gmail.com>
 */
class Wp_Book_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb; 
		$table_name = $wpdb->prefix."book_meta";
		$charset_collate = $wpdb->get_charset_collate(); 

		$sql = "CREATE TABLE $table_name (
		book_id BIGINT(20) UNSIGNED NOT NULL,
		author VARCHAR(255),
		price VARCHAR(50),
		publisher VARCHAR(255),
		year VARCHAR(10),
		edition VARCHAR(50),
		url TEXT, 
		PRIMARY KEY (book_id)
		) $charset_collate;";
		require_once(ABSPATH."wp-admin/includes/upgrade.php");
		dbDelta($sql);
	}
}
