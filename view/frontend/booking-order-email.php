<table width="100%" border="0" align="center">
  <tbody>
    <tr>
        <td>
        <h2><?php _e('Order Details', 'wp-easy-booking');?> <?php _e('#');?> <?php echo $data['book_id']; ?></h2>
        <p> <strong><?php _e('Date', 'wp-easy-booking');?></strong> <?php echo $data['order_date']; ?></p>
        </td>
        <td align="right"><?php
$barcode = get_barcode_image_src($data['book_id']);
if ($barcode) {
    echo '<img src="' . $barcode . '">';
}
?></td>
    </tr>
     <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
      <td valign="top"><strong><?php _e('Booking Details', 'wp-easy-booking');?></strong></td>
      <td><?php echo $blc->get_loc_data($data['loc_id']); ?>
      <p><?php echo nl2br(get_post_meta($data['loc_id'], 'booking_address', true)); ?></p>
      </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
      <td valign="top"><strong><?php _e('Your Details', 'wp-easy-booking');?></strong></td>
      <td><?php echo esc_html($data['c_name']); ?><br><?php echo esc_html($data['c_email']); ?><br><?php echo esc_html($data['c_phone']); ?><br></td>
    </tr>
    <tr>
    <td colspan="2"><hr></td>
</tr>
<tr>
    <td valign="top"><strong><?php _e('Schedule Details', 'wp-easy-booking');?></strong></td>
    <td><?php echo $blc->get_schd_data($data['schd_id'], $data['schd_date']); ?></td>
</tr>
 <tr>
    <td colspan="2"><hr></td>
</tr>
<tr>
    <td><strong><?php _e('Status', 'wp-easy-booking');?></strong></td>
    <td><?php echo ucfirst($data['order_status']); ?></td>
</tr>
  </tbody>
</table>