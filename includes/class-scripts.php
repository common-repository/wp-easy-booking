<?php
class WP_Booking_Scripts {
	
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_admin' ) );
	}
	
	public function scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'wp-booking', plugins_url( WPEB_PLUGIN_DIR . '/js/wp-booking.js' ), array(), '1.0.0', true );
		wp_register_style( 'jquery-ui', plugins_url( WPEB_PLUGIN_DIR . '/assets/jquery-ui.css' ) );
		wp_register_style( 'booking_front_styles', plugins_url( WPEB_PLUGIN_DIR . '/css/booking_front_styles.css' ) );
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'booking_front_styles' );
	
		wp_enqueue_script( 'jquery.validate.min', plugins_url( WPEB_PLUGIN_DIR . '/js/jquery.validate.min.js' ) );
		wp_enqueue_script( 'additional-methods', plugins_url( WPEB_PLUGIN_DIR . '/js/additional-methods.js' ) );
	}
	
	public function scripts_admin() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'wp-booking', plugins_url( WPEB_PLUGIN_DIR . '/js/wp-booking.js' ), array(), '1.0.0', true );
		wp_register_style( 'jquery-ui', plugins_url( WPEB_PLUGIN_DIR . '/assets/jquery-ui.css' ) );
		wp_register_style( 'booking_admin_style', plugins_url( WPEB_PLUGIN_DIR . '/css/booking_admin_style.css' ) );
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'booking_admin_style' );
		
		wp_enqueue_script( 'ap.cookie', plugins_url( WPEB_PLUGIN_DIR . '/js/ap.cookie.js' ) );
		wp_enqueue_script( 'ap-tabs', plugins_url( WPEB_PLUGIN_DIR . '/js/ap-tabs.js' ) );
	}
	
}