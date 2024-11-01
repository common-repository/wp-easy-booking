<ul class="add-lists columns-2">
	<?php
foreach ($locations as $loc) {?>
        <li>
            <h3><a href="<?php echo get_permalink($loc->ID); ?>"><?php echo esc_html($loc->post_title); ?></a></h3>
            <p><?php echo nl2br(esc_html(get_post_meta($loc->ID, 'booking_address', true))); ?></p>
            <p><a href="<?php echo get_permalink($loc->ID); ?>"><?php _e('Book Now', 'wp-easy-booking');?></a></p>
        </li>
	<?php }?>
</ul>