<?php
/*
Plugin Name: WP Booking
Plugin URI: https://wordpress.org/plugins/wp-easy-booking/
Description: This is a schedule booking plugin. Use this plugin as a complete booking solution for your site. Create Locations, Add Schedules to the locations, Let customers book for schedules. Manage bookings from admin panel.
Version: 2.4.5
Author: aviplugins.com
Text Domain: wp-easy-booking
Domain Path: /languages
Author URI: https://www.aviplugins.com/
 */

/*
 *      |||||
 *    <(`0_0`)>
 *    ()(afo)()
 *      ()-()
 */

define('WPEB_PLUGIN_DIR', 'wp-easy-booking');
define('WPEB_PLUGIN_PATH', dirname(__FILE__));

include_once WPEB_PLUGIN_PATH . '/config/config-default.php';

function plug_load_booking()
{

    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if (is_plugin_active('wp-booking-pro/booking.php')) {
        wp_die('It seems you have <strong>WP Booking PRO</strong> plugin activated. Please deactivate to continue.');
        exit;
    }

    include_once WPEB_PLUGIN_PATH . '/autoload.php';
    new Booking_Autoload;
    new WP_Booking_Settings;
    new Booking_Addresses;
    new WP_Booking_Scripts;
    new Booking_Processing;
}

class WP_Booking_Load
{
    public function __construct()
    {
        plug_load_booking();
    }
}
new WP_Booking_Load;

class WP_Booking_Install
{

    public static function wpb_install()
    {
        global $wpdb;
        $create_table1 = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "booking_location_schedule` (
		  `schd_id` int(11) NOT NULL AUTO_INCREMENT,
		  `loc_id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `schd_day` varchar(20) NOT NULL,
		  `schd_specific_date` DATE NOT NULL,
		  `schd_time_fr` varchar(50) NOT NULL,
		  `schd_time_to` varchar(50) NOT NULL,
		  `schd_status` enum('Active','Inactive') NOT NULL,
		  PRIMARY KEY (`schd_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1";
        $wpdb->query($create_table1);

        $create_table2 = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "booking_log` (
		  `book_id` int(11) NOT NULL AUTO_INCREMENT,
		  `loc_id` int(11) NOT NULL,
		  `schd_id` int(11) NOT NULL,
		  `schd_date` date NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `c_name` varchar(255) NOT NULL,
		  `c_email` varchar(100) NOT NULL,
		  `c_phone` varchar(50) NOT NULL,
		  `order_date` datetime NOT NULL,
		  `order_status` varchar(50) NOT NULL,
		  PRIMARY KEY (`book_id`)
		)";
        $wpdb->query($create_table2);

        // updated for version 2.4.3 //
        $altr_qry1 = "ALTER TABLE `" . $wpdb->prefix . "booking_log` CHANGE `order_status` `order_status` varchar(50) NOT NULL";
        $wpdb->query($altr_qry1);
        // updated for version 2.4.3 //

        $create_table3 = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "booking_barcode` (
		  `barcode_id` int(11) NOT NULL AUTO_INCREMENT,
		  `book_id` int(11) NOT NULL,
		  `barcode_image` varchar(255) NOT NULL,
		  PRIMARY KEY (`barcode_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10";
        $wpdb->query($create_table3);

    }
    public static function wpb_uninstall()
    {}
}
register_activation_hook(__FILE__, array('WP_Booking_Install', 'wpb_install'));
register_deactivation_hook(__FILE__, array('WP_Booking_Install', 'wpb_uninstall'));

add_action('plugins_loaded', 'wp_booking_text_domain');
add_action('template_redirect', 'start_session_if_not_started');
