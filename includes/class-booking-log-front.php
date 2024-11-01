<?php
//*********************//
// Booking Log Frontend class V2
// Print option added
// Barcode added
//********************//

class Booking_Log_Frontend_Class {
    
	public $plugin_page;
	
	public $plugin_page_base;
	
	public $table;
	
	public $table2;
	
    public function __construct(){
	  $this->plugin_page = get_permalink(get_option('booking_order_page'));
	  $this->table = 'booking_log';
	  $this->table2 = 'booking_location_schedule';
    }
	
	public function get_table_colums(){
		$colums = array(
		'loc_id' => __('Booking','wp-easy-booking'),
		'schd_id' => __('Schedule','wp-easy-booking'),
		'schd_date' => __('Booking Date','wp-easy-booking'),
		'order_status' => __('Status','wp-easy-booking'),
		'action' => __('Action','wp-easy-booking')
		);
		return $colums;
	}
	
	public function table_start(){
		return '<table class="book-list-table">';
	} 
    
	public function table_end(){
		return '</table>';
	}
	
	public function get_table_header(){
		$ret = '';
		$header = $this->get_table_colums();
		$ret .= '<thead>';
		$ret .= '<tr>';
		foreach($header as $key => $value){
			$ret .= '<td>'.$value.'</td>';
		}
		$ret .= '</tr>';
		$ret .= '</thead>';
		return $ret;		
	}
	
	public function table_td_column($value){
		$ret = '';
		if(is_array($value)){
			foreach($value as $vk => $vv){
				$ret .= $this->row_data($vk,$vv,$value);
			}
		}
		
		$ret .= $this->row_actions($value['book_id']);
		return $ret;
	}
	
	public function row_actions($id){
		$link = '';
		if(empty($id)){
			return;
		}
		if ( get_option('permalink_structure') ) {
			$link = $this->plugin_page.'?action=view&id='.$id;
		} else {
			$link = $this->plugin_page.'&action=view&id='.$id;
		}
		return '<td><a href="'.$link.'">'.__('Details','wp-easy-booking').'</a></td>';
	}
	
	public function row_data($key,$value,$fullvalue){
		$v = '';		
		switch ($key){
			case 'loc_id':
			$v = $this->get_loc_data($value);
			break;
			case 'schd_id':
			$v = $this->get_schd_data($value,$fullvalue['schd_date']);
			break;
			case 'schd_date':
			$v = $value;
			break;
			case 'order_status':
			$v = $value;
			break;
			default:
			//$v = $value; uncomment this line at your own risk
			break;
		}
		if($v){
			return '<td>'.$v.'</td>';
		}
	}
	
	public function get_customer_data($fullvalue){
		$data = '';
		$data .= '<strong>'.__('Email','wp-easy-booking').'</strong> : '.$fullvalue['c_email'];
		$data .= '<br>';
		$data .= '<strong>'.__('Name','wp-easy-booking').'</strong> : '.$fullvalue['c_name'];
		$data .= '<br>';
		$data .= '<strong>'.__('Phone','wp-easy-booking').'</strong> : '.$fullvalue['c_phone'];
		return $data;
	}
	
	public function get_schd_data($schd_id = '', $schd_date = ''){
		if($schd_id == '')
		return;
		global $wpdb;
		$data = '';
		$query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix.$this->table2." WHERE schd_id = %d", $schd_id );
		$result = $wpdb->get_row( $query, OBJECT );
		$user_info = get_userdata($result->user_id);
		
		if( !$user_info ){
			return 'NA';			
		}
		
		$data .= '<strong>'.__('Booked To','wp-easy-booking').'</strong> : '.$user_info->display_name.' ('.$user_info->user_email.')';
		$data .= '<br>';
		$data .= '<strong>'.__('Booking Name','wp-easy-booking').'</strong> : '.$this->get_loc_data($result->loc_id);
		$data .= '<br>';
		$data .= '<strong>'.__('Booking Date','wp-easy-booking').'</strong> : '.Booking_General_Class::dateformat($schd_date).', '.ucfirst($result->schd_day);
		$data .= '<br>';
		$data .= '<strong>'.__('Time Slot','wp-easy-booking').'</strong> : '.Booking_General_Class::booking_time_display($result->schd_time_fr,$result->schd_time_to);
		return $data;
	}
	
	public function get_loc_data($loc_id = '', $data = 'post_title'){
		if($loc_id == '')
		return;
		$loc_data = get_post($loc_id); 
		$title = $loc_data->$data;
		return $title;
	}
	
	public function get_table_body($data){
		$cnt = 1;
		$ret = '';
		if(is_array($data) && !empty($data)){
			$ret .= '<tbody id="the-list">';
			foreach($data as $k => $v){
				$ret .= '<tr class="'.($cnt%2==0?'alternate':'').'">';
				$ret .= $this->table_td_column($v);
				$ret .= '</tr>';
				$cnt++;
			}
			$ret .= '</tbody>';
		} else {
			$ret .= '<tbody id="the-list">';
			$ret .= '<tr>';
			$ret .= '<td colspan="'.count($this->get_table_colums()).'" align="center">'.__('No booking added yet','wp-easy-booking').'</td>';
			$ret .= '</tr>';
			$ret .= '</tbody>';
		}
		return $ret;
	}
	
	public function get_single_row_data($id){
		global $wpdb;
		$user_id = get_current_user_id();
		$query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix.$this->table." WHERE book_id = %d AND user_id = %d", $id, $user_id );
		$result = $wpdb->get_row( $query, ARRAY_A );
		return $result;
	}
		
	public function get_schedule_details($id = ''){
		if($id == '')
		return;
		global $wpdb;
		$query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix.$this->table." WHERE schd_id = %d", $id );
		$result = $wpdb->get_row( $query, ARRAY_A );
		return $result;
	}
	
	public function view(){
		$gc = new Booking_General_Class;
		$id = (int)sanitize_text_field($_REQUEST['id']);
		$data = $this->get_single_row_data($id);
		if(is_array($data) && !empty($data)){
			include( WPEB_PLUGIN_PATH . '/view/frontend/booking-log.php');
		}
	}
	
	public function lists(){
		global $wpdb;
		$user_id = get_current_user_id();
		$srch_extra = '';
		if(isset($_REQUEST['search']) and sanitize_text_field($_REQUEST['search']) == 'log_search'){
			if(sanitize_text_field($_REQUEST['c_email'])){
				$srch_extra .= " AND c_email = '".sanitize_text_field($_REQUEST['c_email'])."'";
			}
			if(sanitize_text_field($_REQUEST['book_id'])){
				$srch_extra .= " AND book_id = '".intval(sanitize_text_field($_REQUEST['book_id']))."'";
			}
			
		}
		$query = "SELECT * FROM ".$wpdb->prefix.$this->table." WHERE book_id <> 0 AND user_id = '".$user_id."' ".$srch_extra." ORDER BY order_date DESC";
		$ap = new AP_Paginate(10);		
		$data = $ap->initialize($query,get_query_var('paged'));
		
		echo $this->table_start();
		echo $this->get_table_header();
		echo $this->get_table_body($data);
		echo $this->table_end();
		
		echo '<div style="margin-top:10px;">';
		echo $ap->paginate();
		echo '</div>';
	}
	
    public function display_list() {
		if(is_user_logged_in()){
			if(isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'view'){
				$this->view();
			} else{
				$this->lists();
			}
		} else {
			_e('Please login to view your bookings','wp-easy-booking');
			do_action('booking_log_not_loggedin_below');
		}
    }

}