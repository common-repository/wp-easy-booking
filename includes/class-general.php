<?php
class Booking_General_Class {
	
	public function __construct(){
		global $booking_ai_status_array, $booking_order_status_array;
		$this->sc_statuses = $booking_ai_status_array;
		$this->order_statuses = $booking_order_status_array;
	}
	
	public static function dateformat( $date = '' ){
		if(empty($date)){
			return;
		}
		return date('jS M, Y', strtotime($date));
	}
	
	public static function timeformat( $time = '', $format = '12H'){
		if(empty($time)){
			return;
		}
		if($format == '12H'){
			return date('g:i a', strtotime($time));
		} else {
			return date('H:i', strtotime($time)).' Hrs';
		}
	}
	
	public static function booking_time_display( $from = '', $to = ''){
		if( $from == '' and $to == '' ){
			return;
		} else if( $from != '' and $to == '' ){
			return __('At','wp-easy-booking').' '.$from;
		} else if( $from == '' and $to != '' ){
			return;
		} else if( $from != '' and $to != '' ){
			return __('From','wp-easy-booking').' '.$from.' '.__('To','wp-easy-booking').' '.$to;
		} else {
			return;
		}
	}
	
	public function get_time( $i, $j ){
		$time = str_pad($i, 2, '0', STR_PAD_LEFT).'.'.str_pad($j, 2, '0', STR_PAD_LEFT);            
		return $time;
	}
	
	public function booking_time_selected( $sel = '' ){
		$ret = $sel.'<option value="">-</option>';
		for ($i = 1; $i <= 23; $i++){
		  for ($j = 0; $j < 60; $j += 30){
			$time = $this->get_time( $i, $j );
			if($sel == $time){
				$ret .= '<option value="'.$time.'" selected="selected">'.$time.'</option>';
			} else {
				$ret .= '<option value="'.$time.'">'.$time.'</option>';
			}	
		  }
		}
		$time = $this->get_time( 24, 0 );
		if($sel == $time){
			$ret .= '<option value="'.$time.'" selected="selected">'.$time.'</option>';
		} else {
			$ret .= '<option value="'.$time.'">'.$time.'</option>';
		}	
		return $ret;
	}
	
	public function booking_day_selected($sel = ''){
		global $booking_days_array;
		$ret = '';
		foreach( $booking_days_array as $key => $value ){
			if($sel == $key){
				$ret .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
			} else {
				$ret .= '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		return $ret;
	}
	
	public function sc_status_selected($sel = ''){
		$sc_statuses = $this->sc_statuses;
		$ret = '';
		foreach( $sc_statuses as $key => $value ){
			if($sel == $value){
				$ret .= '<option value="'.$value.'" selected="selected">'.$value.'</option>';
			} else {
				$ret .= '<option value="'.$value.'">'.$value.'</option>';
			}
		}
		return $ret;
	}
	
	public function order_status_selected($sel = ''){
		$order_statuses = $this->order_statuses;
		$ret = '';
		foreach( $order_statuses as $key => $value ){
			if($sel == $value){
				$ret .= '<option value="'.$value.'" selected="selected">'.ucfirst($value).'</option>';
			} else {
				$ret .= '<option value="'.$value.'">'.ucfirst($value).'</option>';
			}
		}
		return $ret;
	}
	
	public function sc_user_selected($sel = ''){
		global $wpdb;
		$ret = '';
		$sc_users = get_users( array( 'fields' => array( 'ID','display_name' ) ) );
		foreach( $sc_users as $key => $value ){
			if($sel == $value->ID){
				$ret .= '<option value="'.$value->ID.'" selected="selected">'.$value->display_name.'</option>';
			} else {
				$ret .= '<option value="'.$value->ID.'">'.$value->display_name.'</option>';
			}
		}
		return $ret;
	}
	
	public function get_booking_url($data = array()){
		$schd_id = base64_encode($data['schd_id']);
		$loc_id = base64_encode($data['loc_id']);
		$date = base64_encode($data['date']);
		$booking_page_id = get_option('booking_form_page');
		if(!$booking_page_id)
		return '#';
		$booking_page_url = get_permalink($booking_page_id);
		if(get_option('permalink_structure')){
			$booking_page_url = $booking_page_url.'?schd_id='.$schd_id.'&loc_id='.$loc_id.'&date='.$date;
		} else {
			$booking_page_url = $booking_page_url.'&schd_id='.$schd_id.'&loc_id='.$loc_id.'&date='.$date;
		}
		return $booking_page_url;
	}
	
	public function expMonthOptions(){
		$ret = '';
		for($i = 1; $i <= 12; $i++ ){
			$ret .= '<option value="'.str_pad($i, 2, '0', STR_PAD_LEFT).'">'.$i.'</option>';
		}
		return $ret;
	}
	
	public function expYearOptions(){
		$current = date('Y');
		$loop = $current + 6;
		$ret = '';
		for($i = $current; $i <= $loop; $i++ ){
			$ret .= '<option value="'.$i.'">'.$i.'</option>';
		}
		return $ret;
	}
	
	public function load_script(){?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery('#book').validate({ errorClass: "rw-error" });
			});
		</script>
	<?php }
	
	public function schd_booking_form(){
		global $booking_payment_methods;
		$schd_id 	= base64_decode(sanitize_text_field(@$_REQUEST['schd_id']));
		$loc_id 	= base64_decode(sanitize_text_field(@$_REQUEST['loc_id']));
		$date 		= base64_decode(sanitize_text_field(@$_REQUEST['date']));
		$mc = new Booking_Message_Class;
		$mc->show_message();
		if($schd_id == '' or $loc_id == '' or $date == ''){
			include( WPEB_PLUGIN_PATH . '/view/frontend/booking-orders-page-link.php');
		} else {
			$this->load_script();
			include( WPEB_PLUGIN_PATH . '/view/frontend/booking-form.php');
		}
	}
	
	public function set_html_content_type() {
		return 'text/html';
	}
	
	public function sendOrderEmail($book_id = ''){
		if($book_id == '')
		return;
		$blc = new Booking_Log_Class;
		$data = $blc->get_single_row_data($book_id);
		if(!is_array($data))
		return;
		
		ob_start();
		include( WPEB_PLUGIN_PATH . '/view/frontend/booking-order-email.php');
		$body = ob_get_contents();	
		ob_end_clean();		
		
		add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type') );
		$to = $data['c_email'];
		$multiple_tos = array(
			$data['c_email'],
			get_option('booking_admin_email')
		);
					
		$headers[] = 'From: '.get_option('booking_admin_email_from_name').' <'.get_option('booking_admin_email').'>';
		$subject = __('Booking Order Status');
		wp_mail( $multiple_tos, $subject, $body, $headers );
		remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type') );
		return;
	}
}