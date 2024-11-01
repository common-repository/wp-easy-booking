<?php

if(!function_exists( 'start_session_if_not_started' )){
	function start_session_if_not_started(){
		if(!session_id()){
			@session_start();
		}
	}
}

function wp_booking_text_domain(){
	load_plugin_textdomain('wp-easy-booking', FALSE, basename( WPEB_PLUGIN_PATH ) .'/languages');
}
	