<?php
class Booking_Admin_Panel{

	public static function wp_booking_afo_options() {
		global $wpdb;
		$booking_admin_email 			= get_option('booking_admin_email');
		$booking_form_page 				= get_option('booking_form_page');
		$booking_order_page 			= get_option('booking_order_page');
		$booking_thankyou_page 			= get_option('booking_thankyou_page');
		$book_open_till 				= get_option('book_open_till');
		$booking_admin_email_from_name 	= get_option('booking_admin_email_from_name');
		
		$mc = new Booking_Message_Class;
		
		echo '<div class="wrap">';
		$mc->show_message();
		self :: login_sidebar_widget_add();
		self :: help_support();
		Form_Class::form_open();
		wp_nonce_field( 'wp_booking_save_action', 'wp_booking_save_action_field' );
    	Form_Class::form_input('hidden','option','','wp_booking_save_settings');
  		include( WPEB_PLUGIN_PATH . '/view/admin/settings.php');
		Form_Class::form_close();
		self :: wp_booking_pro_add();
		self :: donate();
		echo '</div>';
	}
	
	public static function login_sidebar_widget_add(){ 
		include( WPEB_PLUGIN_PATH . '/view/admin/login-add.php');
	}
	
	public static function donate(){
		include( WPEB_PLUGIN_PATH . '/view/admin/donate.php');
	}
	
	static function wp_booking_pro_add(){
		include( WPEB_PLUGIN_PATH . '/view/admin/pro-add.php');
	}
	
	public static function help_support(){
		include( WPEB_PLUGIN_PATH . '/view/admin/help.php');
	}
	
	public static function  wp_booking_log_data() {
		$blc = new Booking_Log_Class;
		$blc->display_list();
	}
}
