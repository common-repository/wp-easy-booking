 <table width="100%" border="0" cellspacing="10" class="book-list-table">
    <tr>
        <td align="right" class="booking-barcode"><a href="javascript:void(0)" onClick="PrintElem('booking-details','<?php echo 'booking-order-details-' . $id; ?>');"><?php _e('Print', 'wp-easy-booking');?></a></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="10" class="book-list-table">
    <tr>
        <td>
        <h2><?php _e('Order Details', 'wp-easy-booking');?> <?php _e('#');?> <?php echo $id; ?></h2>
        <p> <strong><?php _e('Date', 'wp-easy-booking');?></strong> <?php echo $data['order_date']; ?></p>
        </td>
        <td align="right" class="booking-barcode"><?php
$barcode = get_barcode_image_src($id);
if ($barcode) {
    echo '<img src="' . $barcode . '">';
}
?></td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Booking', 'wp-easy-booking');?></strong></td>
        <td><?php echo $this->get_loc_data($data['loc_id']); ?>
          <p><?php echo nl2br(get_post_meta($data['loc_id'], 'booking_address', true)); ?></p>
          </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Your Information', 'wp-easy-booking');?></strong></td>
        <td>
        <table width="100%" border="0">
          <tbody>
            <tr>
              <td><strong><?php _e('Name');?></strong></td>
              <td><?php echo esc_html($data['c_name']); ?></td>
            </tr>
            <tr>
              <td><strong><?php _e('Email');?></strong></td>
              <td><?php echo esc_html($data['c_email']); ?></td>
            </tr>
            <tr>
              <td><strong><?php _e('Phone');?></strong></td>
              <td><?php echo esc_html($data['c_phone']); ?></td>
            </tr>
          </tbody>
        </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Schedule Details', 'wp-easy-booking');?></strong></td>
        <td><?php echo $this->get_schd_data($data['schd_id'], $data['schd_date']); ?></td>
    </tr>
     <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td><strong><?php _e('Status', 'wp-easy-booking');?></strong></td>
        <td>
            <?php echo ucfirst($data['order_status']); ?>
        </td>
    </tr>
</table>


<!-- required for print -->
<div id="booking-details" style="display:none;">
<table width="100%" border="0" cellspacing="10" class="book-list-table">
    <tr>
        <td>
        <h2><?php _e('Order Details', 'wp-easy-booking');?> <?php _e('#');?> <?php echo $id; ?></h2>
        <p> <strong><?php _e('Date', 'wp-easy-booking');?></strong> <?php echo $data['order_date']; ?></p>
        </td>
        <td align="right"><?php
$barcode = get_barcode_image_src($id);
if ($barcode) {
    echo '<img src="' . $barcode . '">';
}
?></td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Booking', 'wp-easy-booking');?></strong></td>
        <td><?php echo $this->get_loc_data($data['loc_id']); ?>
          <p><?php echo nl2br(get_post_meta($data['loc_id'], 'booking_address', true)); ?></p>
          </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Your Information', 'wp-easy-booking');?></strong></td>
        <td>
        <table width="100%" border="0">
          <tbody>
            <tr>
              <td><strong><?php _e('Name');?></strong></td>
              <td><?php echo esc_html($data['c_name']); ?></td>
            </tr>
            <tr>
              <td><strong><?php _e('Email');?></strong></td>
              <td><?php echo esc_html($data['c_email']); ?></td>
            </tr>
            <tr>
              <td><strong><?php _e('Phone');?></strong></td>
              <td><?php echo esc_html($data['c_phone']); ?></td>
            </tr>
          </tbody>
        </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Schedule Details', 'wp-easy-booking');?></strong></td>
        <td><?php echo $this->get_schd_data($data['schd_id'], $data['schd_date']); ?></td>
    </tr>
     <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td><strong><?php _e('Status', 'wp-easy-booking');?></strong></td>
        <td>
            <?php echo ucfirst($data['order_status']); ?>
        </td>
    </tr>
</table>
</div>
<!-- required for print -->