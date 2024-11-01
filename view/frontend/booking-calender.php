<div id="sc_cal" class="sc-cal"></div>
<div id="sd_list" style="margin-top:10px;"></div>
<script>
var disabledDays = [<?php echo $a_days;?>];
jQuery(function(){
	jQuery( "#sc_cal" ).datepicker({
	 dateFormat: "yy-mm-dd",
	 firstDay: 1,
	 dayNamesMin: [ "Su", "Mo", "Tu", "We", "Th", "Fr", "Sa" ],
	 numberOfMonths: <?php echo $no_of_month;?>,
	 onSelect: function(date) {
			jQuery.ajax({
			type: "POST",
			beforeSend: function(){
				jQuery('#sd_list').html('<center>Loading..</center>');
			},
			data: { option: "getSchdInfo", date: date, loc_id: <?php echo $loc_id;?> }
			})
			.done(function( data ) {
				jQuery('#sd_list').html(data);
			});
	 },
	 beforeShowDay: function(date) {
		var y = date.getFullYear().toString(); 
		var m = (date.getMonth() + 1).toString();
		var d = date.getDate().toString();
		if(m.length == 1){ m = '0' + m; }
		if(d.length == 1){ d = '0' + d; }
		var currDate = y+'-'+m+'-'+d;

		if(jQuery.inArray(currDate,disabledDays) != -1) {
			return [true, "ui-highlight"];	
		} else {
			return [false, ""];
	   }
	}
  });
});
</script>