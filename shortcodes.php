<?php

function schd_booking_calendar_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
		  'no_of_month' => ''		  
     ), $atts ) );
     
	ob_start();
	
	new SCHD_Calendar($post->ID, $no_of_month);
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}
add_shortcode( 'schd_calendar', 'schd_booking_calendar_shortcode' );

function schd_booking_form_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	if(!empty($title)){
		echo '<h2>'.$title.'</h2>';
	}	
	$gc = new Booking_General_Class;
	$gc->schd_booking_form();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}
add_shortcode( 'schd_booking_form', 'schd_booking_form_shortcode' );

function schd_booking_locations_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	 	 'show' => '-1',
		 'cat' => '',
     ), $atts ) );
     
	ob_start();
	$args = array( 
		'posts_per_page' => $show, 
		'post_type' => 'booking_address' 
	);
	
	if( $cat ){
		$cat = trim($cat);
		$cat = explode(",",$cat);
		$args['tax_query'] = array(
            array(
                'taxonomy' => 'booking_category',
                'field' => 'term_id',
                'terms' => $cat,
            )
        );
	}
	
	$locations = get_posts( $args );
	if(is_array($locations)){
		include( WPEB_PLUGIN_PATH . '/view/frontend/booking-locations.php');
	}
	wp_reset_postdata();	
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}
add_shortcode( 'schd_booking_locations', 'schd_booking_locations_shortcode' );

function schd_booking_orders_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	if(!empty($title))
	echo '<h2>'.$title.'</h2>';
	
	$blfc = new Booking_Log_Frontend_Class;
	$blfc->display_list();
	
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}
add_shortcode( 'schd_booking_orders', 'schd_booking_orders_shortcode' );