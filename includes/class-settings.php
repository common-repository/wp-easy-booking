<?php
class WP_Booking_Settings {
	
	public function __construct() {
		start_session_if_not_started();
		add_action( 'admin_menu', array( $this, 'wp_booking_afo_menu' ) );
	}
	
	public function wp_booking_afo_menu () {
		add_menu_page( 'WP Booking', 'WP Booking', 'activate_plugins', 'wp_booking_afo', array( 'Booking_Admin_Panel', 'wp_booking_afo_options' ));
		add_submenu_page( 'wp_booking_afo','WP Booking Log', 'WP Booking Log', 'activate_plugins', 'wp_booking_log_afo', array( 'Booking_Admin_Panel', 'wp_booking_log_data' ));
	}
	
}