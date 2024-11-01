<table border="0" class="ap-table">
    <tr>
      <td><h3><?php echo __('WP Booking','wp-easy-booking');?></h3></td>
    </tr>
    <tr>
      <td>
      <div class="ap-tabs">
          <div class="ap-tab">
            <?php _e('General','wp-easy-booking');?>
          </div>
          <div class="ap-tab">
            <?php _e('Email Settings','wp-easy-booking');?>
          </div>
          <div class="ap-tab">
            <?php _e('Shortcodes','wp-easy-booking');?>
          </div>
        </div>
        <div class="ap-tabs-content">
          <div class="ap-tab-content">
            <table width="100%">
              <tr>
                <td width="250"><h3>
                    <?php _e('General','wp-easy-booking');?>
                  </h3></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><strong><?php echo __('Booking Open Till','wp-easy-booking');?>:</strong></td>
                <td><?php Form_Class::form_input('number','book_open_till','',$book_open_till,'','','','','2');?>
                  <i>
                  <?php _e('Enter the month number till the booking is open. Example - "2". If set to "2" then Booking will be available for next 2 months. Default is for 1 Month.','wp-easy-booking');?>
                  </i></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><strong><?php echo __('Booking Form Page','wp-easy-booking');?>:</strong></td>
                <td><?php
                    $args = array(
                    'depth'            => 0,
                    'selected'         => $booking_form_page,
                    'echo'             => 1,
                    'show_option_none' => '-',
                    'id' 			   => 'booking_form_page',
                    'name'             => 'booking_form_page'
                    );
                    wp_dropdown_pages( $args ); 
                ?>
                  <i><font color="#FF0000">
                  <?php _e('Important','wp-easy-booking');?>
                  </font></i> <br />
                  <i>
                  <?php _e('Please create a new page, put this shortcode <strong>[schd_booking_form]</strong> in the page and select the page as the "Booking Form Page". You don\'t have to put that page in navigation.','wp-easy-booking');?>
                  </i></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><strong><?php echo __('Booking Orders Frontend Page','wp-easy-booking');?>:</strong></td>
                <td><?php
                    $args = array(
                    'depth'            => 0,
                    'selected'         => $booking_order_page,
                    'echo'             => 1,
                    'show_option_none' => '-',
                    'id' 			   => 'booking_order_page',
                    'name'             => 'booking_order_page'
                    );
                    wp_dropdown_pages( $args ); 
                ?>
                  <i><font color="#FF0000">
                  <?php _e('Important','wp-easy-booking');?>
                  </font></i> <br />
                  <i>
                  <?php _e('Please create a new page, put this shortcode <strong>[schd_booking_orders]</strong> in the page and select the page as the "Booking Orders Frontend Page". From this page users will be able to view the Bookings they have.','wp-easy-booking');?>
                  </i></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
               <tr>
                <td valign="top"><strong><?php echo __('Booking Thankyou Page','wp-easy-booking');?>:</strong></td>
                <td><?php
                    $args = array(
                    'depth'            => 0,
                    'selected'         => $booking_thankyou_page,
                    'echo'             => 1,
                    'show_option_none' => '-',
                    'id' 			   => 'booking_thankyou_page',
                    'name'             => 'booking_thankyou_page'
                    );
                    wp_dropdown_pages( $args ); 
                ?>
                  <br />
                  <i>
                  <?php _e('Please create a new page, put the thankyou message in that page. Users will be redirected to this page after successful booking.','wp-easy-booking');?>
                  </i></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><?php Form_Class::form_input('submit','submit','',__('Save','wp-easy-booking'),'button button-primary button-large button-ap-large','','','','','',false,'');?></td>
              </tr>
            </table>
          </div>
          <div class="ap-tab-content">
            <table width="100%">
              <tr>
                <td width="250"><h3>
                    <?php _e('Email Settings','wp-easy-booking');?>
                  </h3></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><strong><?php echo __('Admin Email','wp-easy-booking');?>:</strong></td>
                <td><?php Form_Class::form_input('text','booking_admin_email','',$booking_admin_email,'widefat','','','','','',false,__('admin@example.com'));?>
                  <i>
                  <?php _e('Booking related emails will be sent here.','wp-easy-booking');?>
                  </i></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><strong><?php echo __('Admin Email From Name','wp-easy-booking');?>:</strong></td>
                <td><?php Form_Class::form_input('text','booking_admin_email_from_name','',$booking_admin_email_from_name,'widefat','','','','','',false,__('no-reply@example.com'));?>
                  <i>
                  <?php _e('This will ensure that the Emails are not treated as SPAM','wp-easy-booking');?>
                  </i></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><?php Form_Class::form_input('submit','submit','',__('Save','wp-easy-booking'),'button button-primary button-large button-ap-large','','','','','',false,'');?></td>
              </tr>
            </table>
          </div>
          <div class="ap-tab-content">
            <table width="100%">
              <tr>
                <td><h3>Shortcodes</h3></td>
              </tr>
              <tr>
                <td><p>1. <strong>[schd_booking_locations cat="4,5"]</strong> to display all the available locations.</p>
                  <p>2. <strong>[schd_calendar no_of_month="2"]</strong> Put this in the <a href="edit.php?post_type=booking_address"><strong>Booking</strong></a> page. This will let users to book a schedule with a jQuery UI Calendar. Shortcode instructions are in the <a href="edit.php?post_type=booking_address"><strong>Booking </strong></a> page as well.</p>
                  <p>3. <strong>[schd_booking_orders]</strong> to display booking logs in frontend. Loggedin users will be able to view the bookings they have / booked.</p></td>
              </tr>
            </table>
          </div>
        </div></td>
    </tr>
  </table>