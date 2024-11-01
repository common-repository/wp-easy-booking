<div id="book_forms" class="book_forms">
<?php 
Form_Class::form_open( 'book', 'book' );
Form_Class::form_input('hidden','option','','SchdBookingSubmit');
Form_Class::form_input('hidden','schd_id','',$schd_id);
Form_Class::form_input('hidden','loc_id','',$loc_id);
Form_Class::form_input('hidden','schd_date','',$date);
?>
<div class="form-group">
	<label for="name"><?php _e('Name','wp-easy-booking');?> </label>
	<input type="text" name="c_name" placeholder="<?php _e('Name','wp-easy-booking');?>" <?php echo apply_filters( 'booking_form_name_required', 'required' );?> <?php do_action('booking_form_name_field'); ?> />
</div>
<div class="form-group">
	<label for="email"><?php _e('Email','wp-easy-booking');?> </label>
	<input type="email" name="c_email" placeholder="<?php _e('Email','wp-easy-booking');?>" <?php echo apply_filters( 'booking_form_email_required', 'required' );?> <?php do_action('booking_form_email_field'); ?>/>
</div>
<div class="form-group">
	<label for="phone"><?php _e('Phone','wp-easy-booking');?> </label>
	<input type="text" name="c_phone" placeholder="<?php _e('Phone','wp-easy-booking');?>" <?php echo apply_filters( 'booking_form_phone_required', 'required' );?> <?php do_action('booking_form_phone_field'); ?>/>
</div>

<div class="form-group"><input name="submit" type="submit" value="<?php _e('Book Now','wp-easy-booking');?>" <?php do_action('booking_form_submit_field'); ?>/></div>
<?php Form_Class::form_close();?>
</div>