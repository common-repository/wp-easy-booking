<?php

class SCHD_Calendar
{

    public function __construct($loc_id = '', $no_of_month = '')
    {
        if (!$loc_id) {
            return;
        }

        if ($no_of_month == '') {
            $no_of_month = 1;
        }

        global $wpdb;

        $book_till = get_option('book_open_till');
        if ($book_till == '') {
            $book_till = 30;
        } else {
            $book_till = $book_till * 30;
        }

        $a_days = [];
        $query = $wpdb->prepare("SELECT `schd_day` FROM " . $wpdb->prefix . "booking_location_schedule WHERE loc_id = %d GROUP BY `schd_day` ORDER BY schd_id", $loc_id);
        $schds = $wpdb->get_results($query);
        if (is_array($schds)) {
            foreach ($schds as $key => $value) {
                $next = date("Y-m-d", strtotime("next " . $value->schd_day));
                $a_days[] = str_pad($next, 12, '"', STR_PAD_BOTH);
                $loop = 0;
                while ($loop <= $book_till) {
                    $loop = $loop + 7;
                    $next_str = strtotime($next . " +7 day");
                    $next = date("Y-m-d", $next_str);
                    $a_days[] = str_pad($next, 12, '"', STR_PAD_BOTH);
                }
            }
        }
        if (is_array($a_days)) {
            $a_days = implode(',', $a_days);
        }

        include WPEB_PLUGIN_PATH . '/view/frontend/booking-calender.php';
    }
}
