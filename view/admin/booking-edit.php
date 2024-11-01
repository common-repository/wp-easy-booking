<?php
Form_Class::form_open();
Form_Class::form_input('hidden','book_id','',$id);
Form_Class::form_input('hidden','action','','booking_log_edit');
?>
<table width="100%" border="0" cellspacing="10" class="ap-table">
    <tr>
        <td align="right"><a href="javascript:void(0)" onClick="PrintElem('booking-details','<?php echo 'booking-order-details-'.$id;?>');" class="button"><?php _e('Print','wp-easy-booking');?></a></td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="10" class="ap-table">
    <tr>
        <td>
        <h3><?php _e('Order Details','wp-easy-booking');?> <?php _e('#');?> <?php echo $id;?></h3>
        <p> <strong><?php _e('Date','wp-easy-booking');?></strong> <?php echo $data['order_date'];?></p>
        </td>
        <td align="right" class="booking-barcode"><?php 
        $barcode = get_barcode_image_src( $id );
        if( $barcode ){
            echo '<img src="'.$barcode.'">';
        }
        ?></td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Booking','wp-easy-booking');?></strong></td>
        <td><?php echo $this->get_loc_data($data['loc_id']);?>
          <p><?php echo nl2br(get_post_meta($data['loc_id'],'booking_address',true));?></p>
          </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top" width="300"><strong><?php _e('User Info','wp-easy-booking');?></strong></td>
        <td>
        <table width="100%" border="0">
          <tbody>
            <tr>
              <td width="100"><strong><?php _e('User');?></strong></td>
              <td><?php echo $data['user'];?></td>
            </tr>
            <tr>
              <td><strong><?php _e('Name');?></strong></td>
              <td><?php echo $data['c_name'];?></td>
            </tr>
            <tr>
              <td><strong><?php _e('Email');?></strong></td>
              <td><a href="mailto:<?php echo $data['c_email'];?>"><?php echo $data['c_email'];?></a></td>
            </tr>
            <tr>
              <td><strong><?php _e('Phone');?></strong></td>
              <td><a href="tel:<?php echo $data['c_email'];?>"><?php echo $data['c_phone'];?></a></td>
            </tr>
          </tbody>
        </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Schedule Details','wp-easy-booking');?></strong></td>
        <td><?php echo $this->get_schd_data($data['schd_id'],$data['schd_date']);?></td>
    </tr> 
     <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td><strong><?php _e('Status','wp-easy-booking');?></strong></td>
        <td><?php Form_Class::form_select('order_status','',$gc->order_status_selected( $data['order_status'] ));?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><?php Form_Class::form_input('submit','submit','',__('Submit','wp-easy-booking'),'button','','','','','',false,'');?></td>
    </tr>
    <tr>
        <td colspan="2">Once you update <strong>Order Status</strong> user will be notified by <strong>Email</strong>. 
        <p><a href="https://www.aviplugins.com/wp-booking-pro/" target="_blank">PRO</a> version has option to update booking details and Send / Store <strong>Notes</strong> to user in their <strong>Email</strong>.</p></td>
    </tr>
</table>
<?php
Form_Class::form_close();
?>
<!-- required for print -->
<div id="booking-details" style="display:none;">
<table width="100%" border="0" cellspacing="10" style="background-color:#FFFFFF; border:1px solid #CCCCCC; margin:5px 0px;">
    <tr>
        <td width="300">
        <h3><?php _e('Order Details','wp-easy-booking');?> <?php _e('#');?> <?php echo $id;?></h3>
        <p> <strong><?php _e('Date','wp-easy-booking');?></strong> <?php echo $data['order_date'];?></p>
        </td>
        <td align="right"><?php 
        $barcode = get_barcode_image_src( $id );
        if( $barcode ){
            echo '<img src="'.$barcode.'">';
        }
        ?></td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top" width="300"><strong><?php _e('Booking','wp-easy-booking');?></strong></td>
        <td><?php echo $this->get_loc_data($data['loc_id']);?>
          <p><?php echo nl2br(get_post_meta($data['loc_id'],'booking_address',true));?></p>
          </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('User Info','wp-easy-booking');?></strong></td>
        <td>
        <table width="100%" border="0">
          <tbody>
            <tr>
              <td width="100"><?php _e('User ID');?></td>
              <td><?php echo ($data['user_id'] == 0?'Booked as Visitor':$data['user_id']);?></td>
            </tr>
            <tr>
              <td><?php _e('Name');?></td>
              <td><?php echo $data['c_name'];?></td>
            </tr>
            <tr>
              <td><?php _e('Email');?></td>
              <td><?php echo $data['c_email'];?></td>
            </tr>
            <tr>
              <td><?php _e('Phone');?></td>
              <td><?php echo $data['c_phone'];?></td>
            </tr>
          </tbody>
        </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td valign="top"><strong><?php _e('Schedule Details','wp-easy-booking');?></strong></td>
        <td><?php echo $this->get_schd_data($data['schd_id'],$data['schd_date']);?></td>
    </tr> 
     <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td><strong><?php _e('Status','wp-easy-booking');?></strong></td>
        <td><?php echo ucfirst($data['order_status']); ?></td>
    </tr>
</table>
</div>
<!-- required for print -->