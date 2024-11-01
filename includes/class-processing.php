<?php

class Booking_Processing{
	
	public function __construct(){
		add_action('init', array($this, 'booking_front_user_data') );
		add_action('admin_init', array($this, 'booking_admin_ajax') );
	}
	
	public function booking_admin_ajax(){
		if( isset($_REQUEST['option']) and sanitize_text_field($_REQUEST['option']) == 'RemoveSchdData'){
			global $wpdb;
			$schd_id = sanitize_text_field($_REQUEST['schd_id']);
			$where = array( 'schd_id' => $schd_id );
			$where_format = array( '%d' );
			$wpdb->delete( $wpdb->prefix.'booking_location_schedule', $where, $where_format );
			exit;
		}
		
		if( isset($_REQUEST['option']) and sanitize_text_field($_REQUEST['option']) == 'AddNewSchedule'){
			$gc = new Booking_General_Class;
			echo '
			<div class="sc_list">
			'.Form_Class::form_input('hidden','schd_ids[]','','','','','','','','','','','',false).'
			<label for="sc_user">'.__('User','wp-easy-booking').'</label>
			'.Form_Class::form_select('sc_users[]','',$gc->sc_user_selected(),'','','','',false).'
			  
			<label for="open_days">'.__('Day','wp-easy-booking').'</label>
			'.Form_Class::form_select('open_days[]','',$gc->booking_day_selected(),'','','','',false).'  
			
			<label for="time_fr">'.__('From','wp-easy-booking').'</label>
			'.Form_Class::form_input('text','open_time_fr[]','','','','','','','','','',__('Time','wp-easy-booking'),'','',false).'
			
			<label for="time_to">'.__('To','wp-easy-booking').'</label>
			'.Form_Class::form_input('text','open_time_to[]','','','','','','','','','',__('Time','wp-easy-booking'),'','',false).'
			
			<label for="status">'.__('Status','wp-easy-booking').'</label>
			'.Form_Class::form_select('sc_status[]','',$gc->sc_status_selected(),'','','','',false).'
			
			<a href="javascript:void(0);" class="remove_sc_list"><img src="'.plugins_url( WPEB_PLUGIN_DIR . '/images/delete.png').'" alt="X"></a>
			</div>';
			exit;
		}
		
		if( isset($_REQUEST['option']) and sanitize_text_field($_REQUEST['option']) == 'LoadSchedules'){
			global $wpdb;
			$gc = new Booking_General_Class;
			$ret = '';
			$loc_id = sanitize_text_field($_REQUEST['loc_id']);
			$query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."booking_location_schedule WHERE loc_id = %d ORDER BY schd_id", $loc_id );
			$schds = $wpdb->get_results( $query );
			if(is_array($schds)){
				foreach($schds as $key => $value){
					  $ret .= Form_Class::form_radio('schd_id','',$value->schd_id,'','','','','','',false).'<label for="'.$value->schd_id.'">'.$value->user_id. ' ' .$value->schd_day. ' '.__('From','wp-easy-booking').' '.$value->schd_time_fr. ' '.__('To','wp-easy-booking').' ' .$value->schd_time_to.'</label>';
					$ret .= '<br>';
				}
			}
			echo $ret;
			exit;
		}
	
		if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'booking_log_edit'){
			global $wpdb;
			$blc = new Booking_Log_Class;
			$mc = new Booking_Message_Class;
			$gc = new Booking_General_Class;
			$update = array('order_status' => sanitize_text_field($_REQUEST['order_status']));
			$data_format = array( '%s' );
			$where = array('book_id' => sanitize_text_field($_REQUEST['book_id']));
			$data_format1 = array( '%d' );
			$wpdb->update( $wpdb->prefix.$blc->table, $update, $where, $data_format, $data_format1 );
			// send order update mail to user 
				$gc->sendOrderEmail(sanitize_text_field($_REQUEST['book_id']));
			// send order update mail to user 
			$mc->add_message(__('Booking order successfully updated.','wp-easy-booking'), 'updated');
			wp_redirect($blc->plugin_page."&action=edit&id=".sanitize_text_field($_REQUEST['book_id']));
			exit;
		}
		
		if(isset($_POST['option']) and sanitize_text_field($_POST['option']) == "wp_booking_save_settings"){
			
			if ( ! isset( $_POST['wp_booking_save_action_field'] ) || ! wp_verify_nonce( $_POST['wp_booking_save_action_field'], 'wp_booking_save_action' ) ) {
			   wp_die( 'Sorry, your nonce did not verify.');
			} 
			global $booking_payment_methods;
			$mc = new Booking_Message_Class;
			
			if( isset($_POST['booking_admin_email']) ){
				update_option( 'booking_admin_email', sanitize_text_field($_POST['booking_admin_email']) );
			} else {
				delete_option( 'booking_admin_email' );
			}
			
			if( isset($_POST['booking_admin_email_from_name']) ){
				update_option( 'booking_admin_email_from_name', sanitize_text_field($_POST['booking_admin_email_from_name']) );
			} else {
				delete_option( 'booking_admin_email_from_name' );
			}
			
			if( isset($_POST['booking_form_page']) ){
				update_option( 'booking_form_page', sanitize_text_field($_POST['booking_form_page']) );
			} else {
				delete_option( 'booking_form_page' );
			}
			
			if( isset($_POST['booking_order_page']) ){
				update_option( 'booking_order_page', sanitize_text_field($_POST['booking_order_page']) );
			} else {
				delete_option( 'booking_order_page' );
			}
			
			if( isset($_POST['booking_thankyou_page']) ){
				update_option( 'booking_thankyou_page', sanitize_text_field($_POST['booking_thankyou_page']) );
			} else {
				delete_option( 'booking_thankyou_page' );
			}
			
			if( isset($_POST['book_open_till']) ){
				update_option( 'book_open_till', sanitize_text_field($_POST['book_open_till']) );
			} else {
				delete_option( 'book_open_till' );
			}
			
			$mc->add_message(__('Plugin data updated.','wp-easy-booking'), 'updated');
		}
		
		if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'booking_delete'){
			global $wpdb;
			$blc = new Booking_Log_Class;
			$mc = new Booking_Message_Class;
			$id = sanitize_text_field($_REQUEST['id']);
			$wpdb->delete( $wpdb->prefix.$blc->table, array( 'book_id' => $id ) );
			$wpdb->delete( $wpdb->prefix.$blc->table3, array( 'book_id' => $id ) );
			$mc->add_message(__('Booking order successfully deleted.','wp-easy-booking'), 'updated');
			wp_redirect($blc->plugin_page);
			exit;
		}
	}

	public function booking_front_user_data(){
		
		if( isset($_REQUEST['option']) and sanitize_text_field($_REQUEST['option']) == 'getSchdInfo'){
			global $wpdb;
			$gc = new Booking_General_Class;
			$ret = '<table width="100%" border="0" class="schd-list-table">';
			$date = sanitize_text_field($_REQUEST['date']);
			$loc_id = sanitize_text_field($_REQUEST['loc_id']);
			$day = strtolower(date('l',strtotime($date)));
			
			$query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."booking_location_schedule WHERE loc_id = %d AND schd_day = %s AND schd_status = 'Active' ORDER BY schd_id", $loc_id, $day );
			$schds = $wpdb->get_results( $query );
			if(is_array($schds)){
				foreach($schds as $key => $value){	
				  $user_info = get_userdata($value->user_id);
				  $ret .= '<tr>
				  <td align="center"><strong>'.$user_info->display_name.'</strong>'.($user_info->description != ''?'<p>'.nl2br($user_info->description).'</p>':'').'</td>
				  <td>'.ucfirst($value->schd_day).' ('.Booking_General_Class::dateformat($date).') '.Booking_General_Class::booking_time_display($value->schd_time_fr, $value->schd_time_to).'</td>
				  <td><a href="'.$gc->get_booking_url(array( 'schd_id' => $value->schd_id, 'loc_id' => $loc_id, 'date' => $date)).'">'.__('Book Now','wp-easy-booking').'</a></td>
				</tr>';
				}
			}
			$ret .= '</table>';
			echo $ret;		
			exit;
		}
		
		if( isset($_REQUEST['option']) and sanitize_text_field($_REQUEST['option']) == 'SchdBookingSubmit'){
			start_session_if_not_started();
			
			if(sanitize_text_field($_REQUEST['loc_id']) == '' or sanitize_text_field($_REQUEST['schd_id']) == ''){
				wp_die('Booking not selected.');
			}
			
			global $wpdb,$booking_payment_methods;
			$gc = new Booking_General_Class;
			$mc = new Booking_Message_Class;
			$blc = new Booking_Log_Class;
			$log_data['loc_id'] = sanitize_text_field($_REQUEST['loc_id']); 
			$log_data['schd_id'] = sanitize_text_field($_REQUEST['schd_id']);
			$log_data['schd_date'] = sanitize_text_field($_REQUEST['schd_date']); 
			$log_data['user_id'] = get_current_user_id();
			$log_data['c_name'] = sanitize_text_field($_REQUEST['c_name']); 
			$log_data['c_email'] = sanitize_text_field($_REQUEST['c_email']); 
			$log_data['c_phone'] = sanitize_text_field($_REQUEST['c_phone']); 
			$log_data['order_date'] = current_time( 'mysql' ); 
			$log_data['order_status'] = 'Processing';
			
			$data_format = array(
			'%d',
			'%d',
			'%s',
			'%d',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			);
			$wpdb->insert( $wpdb->prefix."booking_log", $log_data, $data_format );
			$log_id = $wpdb->insert_id;
			
			// create barcode //
			$barcode_image = gen_barcode_image($log_id);
			
			$bar_data['book_id'] = $log_id;
			$bar_data['barcode_image'] = $barcode_image;
			
			$bar_data_format = array(
			'%d',
			'%s',
			);
			$wpdb->insert( $wpdb->prefix."booking_barcode", $bar_data, $bar_data_format );
			// create barcode //
			
			// put data in session //
				$_SESSION['b_order']['name'] = $blc->get_loc_data($log_data['loc_id']);
				$_SESSION['b_order']['price'] = get_option('schd_booking_price');
				$_SESSION['b_order']['log_id'] = $log_id;
			// put data in session //
			
			if( get_option('schd_booking_price') == '' || 
			    get_option('schd_booking_price') == '0' || 
			    get_option('schd_booking_price') == '0.00'
			   ){ // booking is free
				 
				// send email to user //
				$gc->sendOrderEmail($log_id);
						
				$booking_form_page = get_option('booking_form_page');
				$booking_thankyou_page = get_option('booking_thankyou_page');
				
				if( $booking_thankyou_page != '' ){
					wp_redirect(get_permalink($booking_thankyou_page));
					exit;
				}
				
				$mc->add_message(__('Booking successfully registered. Please check your email for details.','wp-easy-booking'), 'success');
				wp_redirect(get_permalink($booking_form_page));
				exit;
			} else {
				wp_die(__('Payment not allowed!','wp-easy-booking'));		
			}
		}
	}
}
