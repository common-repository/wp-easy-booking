<?php
Form_Class::form_open('sub_search','','get');
Form_Class::form_input('hidden','page','',$this->plugin_page_base);
Form_Class::form_input('hidden','search','','log_search');
?>
<table width="100%" border="0">
  <tr>
    <td>
        <?php Form_Class::form_input('text','c_email','',sanitize_text_field(@$_REQUEST['c_email']),'','','','','','',false,__('Customer Email','wp-easy-booking'));?>
        <?php Form_Class::form_input('text','book_id','',sanitize_text_field(@$_REQUEST['book_id']),'','','','','','',false,__('Booking ID','wp-easy-booking'));?>
        <?php Form_Class::form_input('submit','submit','',__('Filter','wp-easy-booking'),'button','','','','','',false,'');?>
    </td>
    <td align="right"><a href="https://www.aviplugins.com/wp-booking-pro/" target="_blank">PRO</a> version has Export to XLS option</td>
  </tr>
</table>
<?php Form_Class::form_close(); ?>