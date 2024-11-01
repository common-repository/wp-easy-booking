<?php
class Booking_Addresses {
	
	public function __construct(){
		add_action( 'init', array( $this, 'codex_booking_address_init' ) );
		add_action( 'add_meta_boxes_booking_address', array( $this, 'booking_extra_boxes' ) );
		add_action( 'save_post', array( $this, 'booking_save_meta_box_data' ) );
		add_action( 'init', array( $this, 'codex_booking_tax' ), 0 );
	}
	
	public function booking_save_meta_box_data( $post_id ) {
 		global $wpdb;
		if ( ! isset( $_POST['booking_meta_box_nonce'] ) ) {
			return;
		}
	
		if ( ! wp_verify_nonce( sanitize_text_field($_POST['booking_meta_box_nonce']), 'booking_meta_box' ) ) {
			return;
		}
	
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
	
		if ( isset( $_POST['post_type'] ) && 'page' == sanitize_text_field($_POST['post_type']) ) {
	
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
	
		} else {
	
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		
		update_post_meta($post_id, 'booking_address', sanitize_text_field($_REQUEST['booking_address']));
		
		$schd_ids = $_REQUEST['schd_ids'];
		$sc_users = $_REQUEST['sc_users'];
		$open_days = $_REQUEST['open_days'];
		$open_time_fr = $_REQUEST['open_time_fr'];
		$open_time_to = $_REQUEST['open_time_to'];
		$sc_status = $_REQUEST['sc_status'];
		
		if(is_array($schd_ids)){
			foreach($schd_ids as $key => $value){
				if($value == ''){ // insert new data 
					$data['loc_id'] = $post_id;
					$data['user_id'] = sanitize_text_field($sc_users[$key]);
					$data['schd_day'] = sanitize_text_field($open_days[$key]);
					$data['schd_time_fr'] = sanitize_text_field($open_time_fr[$key]);
					$data['schd_time_to'] = sanitize_text_field($open_time_to[$key]);
					$data['schd_status'] = sanitize_text_field($sc_status[$key]);
					
					$data_format = array(
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					);
					$wpdb->insert( $wpdb->prefix.'booking_location_schedule', $data, $data_format );
				} else { // update data 
					$data['loc_id'] = $post_id;
					$data['user_id'] = sanitize_text_field($sc_users[$key]);
					$data['schd_day'] = sanitize_text_field($open_days[$key]);
					$data['schd_time_fr'] = sanitize_text_field($open_time_fr[$key]);
					$data['schd_time_to'] = sanitize_text_field($open_time_to[$key]);
					$data['schd_status'] = sanitize_text_field($sc_status[$key]);
					$where = array('schd_id' => $value );
					
					$data_format = array(
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					);
					
					$data_format1 = array(
					'%d',
					);
					
					$wpdb->update( $wpdb->prefix.'booking_location_schedule', $data, $where, $data_format, $data_format1 );
				}
			}
		}
	}

	public function codex_booking_tax() {
		$labels = array(
			'name'              => _x( 'Category', 'taxonomy general name' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Category' ),
			'all_items'         => __( 'All Category' ),
			'parent_item'       => __( 'Parent Category' ),
			'parent_item_colon' => __( 'Parent Category:' ),
			'edit_item'         => __( 'Edit Category' ),
			'update_item'       => __( 'Update Category' ),
			'add_new_item'      => __( 'Add New Category' ),
			'new_item_name'     => __( 'New Category' ),
			'menu_name'         => __( 'Category' ),
		);
	
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => 'booking-category',
			'rewrite'           => array( 'slug' => 'booking-category' ),
		);
	
		register_taxonomy( 'booking_category', array( 'booking_address' ), $args );
	}
	
	public function booking_extra_boxes() {
		add_meta_box(
			'booking_sectionid',
			__( 'Schedule', 'wp-easy-booking' ),
			array( $this, 'booking_meta_box_callback' ) 
		);
		add_meta_box(
			'booking_address',
			__( 'Address', 'wp-easy-booking' ),
			array( $this, 'booking_address_meta_box_callback' ),
			'booking_address',
			'side'
		);
		add_meta_box(
			'booking_cal_help',
			__( 'Shortcode', 'wp-easy-booking' ),
			array( $this, 'booking_help_meta_box_callback' ),
			'booking_address',
			'side'
		);
	}

	public function get_schedule_data($loc_id = ''){
		global $wpdb;
		$gc = new Booking_General_Class;
		$ret = '';
		if($loc_id == ''){
			return;
		}
		
		$ret .= '<p><i>Booking on a specific Date, Make specific Date Unbookable are available with PRO version.</i></p>';
		
		$query = $wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."booking_location_schedule WHERE loc_id = %d ORDER BY schd_id", $loc_id );
		$schds = $wpdb->get_results( $query );
		if(is_array($schds)){
			foreach($schds as $key => $value){
				
				$ret .= '
					<div class="sc_list">
					'.Form_Class::form_input('hidden','schd_ids[]','',$value->schd_id,'','','','','','','','','','',false).'
					<label for="sc_user">'.__('User','wp-easy-booking').'</label>
					'.Form_Class::form_select('sc_users[]','',$gc->sc_user_selected($value->user_id),'','','','',false).'
						
					<label for="open_days">'.__('Day','wp-easy-booking').'</label>
					'.Form_Class::form_select('open_days[]','',$gc->booking_day_selected($value->schd_day),'','','','',false).'		  
					
					<label for="time_fr">'.__('From','wp-easy-booking').'</label>
					'.Form_Class::form_input('text','open_time_fr[]','',$value->schd_time_fr,'','','','','','','',__('Time','wp-easy-booking'),'','',false).'
					
					<label for="time_to">'.__('To','wp-easy-booking').'</label>
					'.Form_Class::form_input('text','open_time_to[]','',$value->schd_time_to,'','','','','','','',__('Time','wp-easy-booking'),'','',false).'
					
					<label for="status">'.__('Status','wp-easy-booking').'</label>
					'.Form_Class::form_select('sc_status[]','',$gc->sc_status_selected($value->schd_status),'','','','',false).'
					
					<a href="javascript:void(0);" class="remove_sc_list" data="'.$value->schd_id.'"><img src="'.plugins_url( WPEB_PLUGIN_DIR . '/images/delete.png').'" alt="X"></a>
					</div>';
			}
		}
		return $ret;
	}
	
	public function booking_meta_box_callback( $post ) {
	   wp_nonce_field( 'booking_meta_box', 'booking_meta_box_nonce' );
	   $sc_data = $this->get_schedule_data($post->ID);
	   include( WPEB_PLUGIN_PATH . '/view/admin/add-schedule.php');
	}
	
	public function booking_address_meta_box_callback( $post ) {
	   wp_nonce_field( 'booking_meta_box', 'booking_meta_box_nonce' );
	   $booking_address = get_post_meta($post->ID,'booking_address',true);
	   include( WPEB_PLUGIN_PATH . '/view/admin/booking-address-form.php');
	}
	
	public function booking_help_meta_box_callback( $post ) {
	   include( WPEB_PLUGIN_PATH . '/view/admin/shortcode-help.php');
	}
	
	public function codex_booking_address_init() {
		$labels = array(
			'name'               => _x( 'Booking', 'post type general name', 'wp-easy-booking' ),
			'singular_name'      => _x( 'Booking', 'post type singular name', 'wp-easy-booking' ),
			'menu_name'          => _x( 'Booking', 'admin menu', 'wp-easy-booking' ),
			'name_admin_bar'     => _x( 'Booking', 'add new on admin bar', 'wp-easy-booking' ),
			'add_new'            => _x( 'Add New Booking', 'wp-easy-booking' ),
			'add_new_item'       => __( 'Add New Booking', 'wp-easy-booking' ),
			'new_item'           => __( 'New Booking', 'wp-easy-booking' ),
			'edit_item'          => __( 'Edit Booking', 'wp-easy-booking' ),
			'view_item'          => __( 'View Booking', 'wp-easy-booking' ),
			'all_items'          => __( 'All Booking', 'wp-easy-booking' ),
			'search_items'       => __( 'Search Booking', 'wp-easy-booking' ),
			'not_found'          => __( 'No Booking found.', 'wp-easy-booking' ),
			'not_found_in_trash' => __( 'No Booking found in Trash.', 'wp-easy-booking' )
		);
	
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'booking' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'			 => 'dashicons-location',
			'supports'           => array( 'title', 'editor' )
		);
	
		register_post_type( 'booking_address', $args );
	}
}


