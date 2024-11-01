<?php
//*********************//
// Booking Log class V3
//********************//

class Booking_Log_Class
{

    public $plugin_page;

    public $plugin_page_base;

    public $table;

    public $table2;

    public $table3;

    public $colums_count;

    public function __construct()
    {
        $this->plugin_page_base = 'wp_booking_log_afo';
        $this->plugin_page = admin_url('admin.php?page=' . $this->plugin_page_base);
        $this->table = 'booking_log';
        $this->table2 = 'booking_location_schedule';
        $this->table3 = 'booking_barcode';
    }

    public function get_table_colums()
    {
        $colums = array(
            'book_id' => __('ID', 'wp-easy-booking'),
            'loc_id' => __('Booking', 'wp-easy-booking'),
            'schd_id' => __('Schedule', 'wp-easy-booking'),
            'c_email' => __('Customer', 'wp-easy-booking'),
            'order_date' => __('Booked On', 'wp-easy-booking'),
            'order_status' => __('Status', 'wp-easy-booking'),
            'action' => __('Action', 'wp-easy-booking'),
            'delete' => __('Delete', 'wp-easy-booking'),
        );

        $this->colums_count = count($colums);
        return $colums;
    }

    public function wrap_div_start()
    {
        return '<div class="wrap">';
    }

    public function wrap_div_end()
    {
        return '</div>';
    }

    public function table_start()
    {
        return '<table class="wp-list-table widefat ap-table">';
    }

    public function table_end()
    {
        return '</table>';
    }

    public function wrap_table_start()
    {
        return '<table width="100%" border="0" cellspacing="10" class="ap-table"><tr><td>';
    }

    public function wrap_table_end()
    {
        return '</td></tr></table>';
    }

    public function get_table_header()
    {
        $header = $this->get_table_colums();
        $ret = '';
        $ret .= '<thead>';
        $ret .= '<tr>';
        foreach ($header as $key => $value) {
            $ret .= '<td>' . $value . '</td>';
        }
        $ret .= '</tr>';
        $ret .= '</thead>';
        return $ret;
    }

    public function table_td_column($value)
    {
        $ret = '';
        if (is_array($value)) {
            foreach ($value as $vk => $vv) {
                $ret .= $this->row_data($vk, $vv, $value);
            }
        }

        $ret .= $this->row_actions($value['book_id']);
        $ret .= $this->row_delete($value['book_id']);
        return $ret;
    }

    public function row_actions($id)
    {
        return '<td><a href="' . $this->plugin_page . '&action=edit&id=' . $id . '">' . __('View/Notify User', 'wp-easy-booking') . '</a></td>';
    }

    public function row_delete($id)
    {
        return '<td><a onclick="return confirmRemove();" href="' . $this->plugin_page . '&action=booking_delete&id=' . $id . '"><img src="' . plugins_url(WPEB_PLUGIN_DIR . '/images/delete.png') . '" alt="X"></a></td>';
    }

    public function row_data($key, $value, $fullvalue)
    {
        $v = '';
        switch ($key) {
            case 'book_id':
                $v = $value;
                break;
            case 'loc_id':
                $v = $this->get_loc_data($value);
                break;
            case 'schd_id':
                $v = $this->get_schd_data($value, $fullvalue['schd_date']);
                break;
            case 'c_email':
                $v = $this->get_customer_data($fullvalue);
                break;
            case 'order_date':
                $v = date("Y-m-d", strtotime($value));
                break;
            case 'order_status':
                $v = $value;
                break;
            default:
                //$v = $value; uncomment this line at your own risk
                break;
        }
        if ($v) {
            return '<td>' . $v . '</td>';
        }
    }

    public function get_customer_data($fullvalue)
    {
        $data = '';
        $data .= '<strong>' . __('Email', 'wp-easy-booking') . '</strong> : ' . $fullvalue['c_email'];
        $data .= '<br>';
        $data .= '<strong>' . __('Name', 'wp-easy-booking') . '</strong> : ' . $fullvalue['c_name'];
        $data .= '<br>';
        $data .= '<strong>' . __('Phone', 'wp-easy-booking') . '</strong> : ' . $fullvalue['c_phone'];
        return $data;
    }

    public function get_schd_data($schd_id = '', $schd_date = '')
    {
        if ($schd_id == '') {
            return;
        }

        global $wpdb;
        $data = '';
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $this->table2 . " WHERE schd_id = %d", $schd_id);
        $result = $wpdb->get_row($query, OBJECT);

        if (!$result) {
            return 'NA';
        }

        $user_info = get_userdata($result->user_id);

        if (!$user_info) {
            return 'NA';
        }

        $data .= '<strong>' . __('Booked To', 'wp-easy-booking') . '</strong> : ' . $user_info->display_name . ' (' . $user_info->user_email . ')';
        $data .= '<br>';
        $data .= '<strong>' . __('Booking Name', 'wp-easy-booking') . '</strong> : ' . $this->get_loc_data($result->loc_id);
        $data .= '<br>';
        $data .= '<strong>' . __('Booking Date', 'wp-easy-booking') . '</strong> : ' . Booking_General_Class::dateformat($schd_date) . ', ' . ucfirst($result->schd_day);
        $data .= '<br>';
        $data .= '<strong>' . __('Time Slot', 'wp-easy-booking') . '</strong> : ' . Booking_General_Class::booking_time_display($result->schd_time_fr, $result->schd_time_to);
        return $data;
    }

    public function get_loc_data($loc_id = '', $data = 'post_title')
    {
        if ($loc_id == '') {
            return 'NA';
        }

        $loc_data = get_post($loc_id);
        if (empty($loc_data)) {
            return 'Deleted';
        }
        $title = $loc_data->$data;
        return $title;
    }

    public function get_table_body($data)
    {
        $cnt = 0;
        $ret = '';
        if (is_array($data) and count($data)) {
            $ret .= '<tbody id="the-list">';
            foreach ($data as $k => $v) {
                $ret .= '<tr class="' . ($cnt % 2 == 0 ? 'alternate' : '') . '">';
                $ret .= $this->table_td_column($v);
                $ret .= '</tr>';
                $cnt++;
            }
            $ret .= '</tbody>';
        } else {
            $ret .= '<tbody id="the-list">';
            $ret .= '<tr>';
            $ret .= '<td align="center" colspan="' . $this->colums_count . '">' . __('No records found', 'wp-easy-booking') . '</td>';
            $ret .= '</tr>';
            $ret .= '</tbody>';
        }
        return $ret;
    }

    public function get_single_row_data($id)
    {
        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $this->table . " WHERE book_id = %d", $id);
        $result = $wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function search_form()
    {
        include WPEB_PLUGIN_PATH . '/view/admin/booking-search.php';
    }

    public function add_link()
    {
        return '<a href="' . $this->plugin_page . '&action=add" class="add-new-h2">' . __('Add', 'wp-easy-booking') . '</a>';
    }

    public function get_schedule_details($id = '')
    {
        if ($id == '') {
            return;
        }

        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . $this->table . " WHERE schd_id = %d", $id);
        $result = $wpdb->get_row($query, ARRAY_A);
        return $result;
    }

    public function get_location_selected($sel = '')
    {
        $ret = '';
        $args = array('posts_per_page' => -1, 'post_type' => 'booking_address');
        $loclist = get_posts($args);
        foreach ($loclist as $key => $value) {
            if ($sel == $value->ID) {
                $ret .= '<option value="' . $value->ID . '" selected="selected">' . esc_html($value->post_title) . '</option>';
            } else {
                $ret .= '<option value="' . $value->ID . '">' . esc_html($value->post_title) . '</option>';
            }
        }
        return $ret;
    }

    public function get_user_selected($sel = '')
    {
        $ret = '';
        $allusers = get_users(array('fields' => array('display_name', 'ID', 'user_email')));
        foreach ($allusers as $usr) {
            if ($sel == $usr->ID) {
                $ret .= '<option value="' . $usr->ID . '" selected="selected">' . $usr->display_name . ' (' . $usr->user_email . ')' . '</option>';
            } else {
                $ret .= '<option value="' . $usr->ID . '">' . $usr->display_name . ' (' . $usr->user_email . ')' . '</option>';
            }
        }
        return $ret;
    }

    public function edit()
    {
        $gc = new Booking_General_Class;
        $id = sanitize_text_field(@$_REQUEST['id']);
        $data = $this->get_single_row_data($id);
        if ($data['user_id'] == '0') {
            $data['user'] = __('Booked as Visitor', 'wp-easy-booking');
        } else {
            $data['user'] = __('Registered User', 'wp-easy-booking') . ' <a href="user-edit.php?user_id=' . $data['user_id'] . '">' . __('View', 'wp-easy-booking') . '</a>';
        }
        include WPEB_PLUGIN_PATH . '/view/admin/booking-edit.php';
    }

    public function lists()
    {
        global $wpdb;
        $srch_extra = '';
        if (isset($_REQUEST['search']) and sanitize_text_field($_REQUEST['search']) == 'log_search') {
            if (sanitize_text_field($_REQUEST['c_email'])) {
                $srch_extra .= " AND c_email = '" . sanitize_text_field($_REQUEST['c_email']) . "'";
            }
            if (sanitize_text_field($_REQUEST['book_id'])) {
                $srch_extra .= " AND book_id = '" . intval(sanitize_text_field($_REQUEST['book_id'])) . "'";
            }

        }
        $query = "SELECT * FROM " . $wpdb->prefix . $this->table . " WHERE book_id <> 0 " . $srch_extra . " ORDER BY book_id DESC";
        $ap = new AP_Paginate(10);
        $data = $ap->initialize($query, sanitize_text_field(@$_REQUEST['paged']));

        echo '<h3>' . __('Booking Log', 'wp-easy-booking') . '</h3>';
        echo $this->search_form();
        echo $this->table_start();
        echo $this->get_table_header();
        echo $this->get_table_body($data);
        echo $this->table_end();

        echo '<div style="margin-top:10px;">';
        echo $ap->paginate();
        echo '</div>';
    }

    public function display_list()
    {

        echo $this->wrap_div_start();

        $mc = new Booking_Message_Class;

        $mc->show_message();

        Booking_Admin_Panel::help_support();

        echo $this->wrap_table_start();

        if (isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'edit') {
            $this->edit();
        } elseif (isset($_REQUEST['action']) and sanitize_text_field($_REQUEST['action']) == 'add') {
            //$this->add();
        } else {
            $this->lists();
        }

        echo $this->wrap_table_end();

        Booking_Admin_Panel::donate();

        echo $this->wrap_div_end();

    }

}
