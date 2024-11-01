<?php
class Booking_Autoload{
	public $includes = array( 'includes/class-settings', 'includes/class-scripts', 'includes/class-form', 'includes/class-booking-address', 'includes/class-general', 'includes/class-message', 'includes/class-schd-calendar', 'includes/class-booking-admin-panel', 'includes/class-paginate', 'includes/class-booking-log', 'includes/class-processing', 'includes/class-booking-log-front', 'barcode', 'shortcodes', 'functions' );
	function __construct(){
		if(is_array($this->includes)){
			foreach($this->includes as $key => $value){
				include_once WPEB_PLUGIN_PATH . '/'.$value.'.php';
			}
		}
	}
}