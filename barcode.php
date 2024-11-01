<?php
define('IN_CB',true);
define('IMG_FORMAT_PNG',1);
define('IMG_FORMAT_JPEG',2);
define('IMG_FORMAT_WBMP',4);
define('IMG_FORMAT_GIF',8);

function gen_barcode_image( $log_id = '' ){
	if( $log_id == '' )
	return;
	
	require('barcode/FColor.php');
	require('barcode/BarCode.php');
	require('barcode/FDrawing.php');
	include('barcode/code39.barcode.php');
	
	$color_black = new FColor(0,0,0);
	$color_white = new FColor(255,255,255);
	
	$code_generated = new code39(50,$color_black,$color_white,3,$log_id,2);
	
	$upload_dir = wp_upload_dir();	
	
	$drawing = new FDrawing(1024,1024, $upload_dir['path'].'/'.$log_id.'.png',$color_white);
	$drawing->init();
	$drawing->add_barcode($code_generated);
	$drawing->draw_all();
	$im = $drawing->get_im();
	
	$im2 = imagecreate($code_generated->lastX,$code_generated->lastY);
	imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
	$drawing->set_im($im2);
	
	$drawing->finish(IMG_FORMAT_PNG);
	
	return $upload_dir['subdir'].'/'.$log_id.'.png';
}

function get_barcode_image_src( $log_id = '' ){
	
	if($log_id == '')
	return;
	
	global $wpdb;
	$query = $wpdb->prepare( "SELECT barcode_image FROM ".$wpdb->prefix."booking_barcode WHERE book_id = %d", $log_id );
	$result = $wpdb->get_row( $query, ARRAY_A );
	
	if( is_array($result) ){
		$upload_dir = wp_upload_dir();
		return $upload_dir['baseurl'].$result['barcode_image'];
	} else {
		return;
	}
}