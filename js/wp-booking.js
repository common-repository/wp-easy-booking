jQuery(document).on('click','.remove_sc_list', function() { 
	var attr = jQuery(this).attr('data');
	if (typeof attr !== typeof undefined && attr !== false) {
		var schd_id = jQuery(this).attr('data');
		jQuery.ajax({
		type: "POST",
		data: { option: "RemoveSchdData", schd_id: schd_id }
		})
		.done(function() {});
	}
    jQuery(this).parent('div').remove();
});

function AddNewSchedule(){
	jQuery.ajax({
	type: "POST",
	data: { option: "AddNewSchedule" }
	})
	.done(function( data ) {
		jQuery('#schedule_list').append(data);
		jQuery( '#schedule_list div' ).last().css( "background-color", "#DCDB36" );
		jQuery( '#schedule_list div' ).animate({ "background-color": "#FDFDFD" }, { duration: 1000 } );
	});
	return true;
}

function LoadSchedules(loc_id){
	jQuery.ajax({
	type: "POST",
	data: { option: "LoadSchedules", loc_id: loc_id }
	})
	.done(function( data ) {
		jQuery('#load_schd_list').html(data);
	});
	return true;
}

function PrintElem(elem, title){
    var printwindow = window.open('', 'PRINT', 'height=400,width=800');

    printwindow.document.write('<html><head><title>' + title  + '</title>');
    printwindow.document.write('</head><body>');
    printwindow.document.write(document.getElementById(elem).innerHTML);
    printwindow.document.write('</body></html>');

    printwindow.document.close(); // necessary for IE >= 10
    printwindow.focus(); // necessary for IE >= 10*/

    printwindow.print();
    printwindow.close();

    return true;
}

function confirmRemove(){
	var con = confirm( 'Are you sure to remove this?');
	if( con ){
		return true;
	} else {
		return false;
	}
}