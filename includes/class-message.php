<?php

if(!class_exists('Booking_Message_Class')){
class Booking_Message_Class {
	public function __construct(){
		start_session_if_not_started();
	}
	
	public function show_message(){
		if(isset($_SESSION['add_booking_msg']) and $_SESSION['add_booking_msg']){
			echo '<div class="'.$_SESSION['add_booking_msg_class'].'"><p>'.$_SESSION['add_booking_msg'].'</p></div>';
			unset($_SESSION['add_booking_msg']);
			unset($_SESSION['add_booking_msg_class']);
		}
	}
	
	public function add_message($msg = '', $class = ''){
		$_SESSION['add_booking_msg'] = $msg;
		$_SESSION['add_booking_msg_class'] = $class;		
	}
}
}