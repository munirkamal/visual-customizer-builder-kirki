jQuery(document).ready( function(){

	/* Add number to the fields */
	rearrange_field_order();


	// Panel sortable
	jQuery('.customizer_fields_wrapper').sortable({ 
		handle: '.circle',
		axis:   'y',
		items: '> table',
		cancel : '.customizer_section_fields_wrapper .circle',
		update: function(event, ui) {
			rearrange_field_order();
		}
	});

	// Section sortable
	jQuery('.customizer_section_fields_wrapper').sortable({
		//appendTo: 'body',
		//cursorAt: { cursor: "move", top: 0, left: 0 },
		//helper: "clone",
		axis:   'y',
        //containment: 'parent',
        //revert: 50,
         //tolerance: 'pointer',
         //cursor: 'move',
		handle: '.circle',
		items: '> table',
		cancel : '.wpcb_custom_field_wrapper .circle',
		update: function(event, ui) {
			rearrange_field_order();
			//alert();
		},
		sort: function(event, ui) {
	        var $target = jQuery(event.target);
	        if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
	            var top = event.pageY - $target.offsetParent().offset().top - (ui.helper.outerHeight(true) / 2);
	            ui.helper.css({'top' : top + 'px'});
	        }
	    },
	});

	jQuery('.customizer_custom_fields_wrapper').sortable({
		handle: '.circle',
		appendTo: 'body',
		axis:   'y',
		items: '> table',
		//cursorAt: { cursor: "move", top: 0, left: 0 },
		sort: function(event, ui) {
	        var $target = jQuery(event.target);
	        if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
	            var top = event.pageY - $target.offsetParent().offset().top - (ui.helper.outerHeight(true) / 2);
	            ui.helper.css({'top' : top + 'px'});
	        }
	    },
	});

});

function rearrange_order_no_delete( selected ){

	var count = 0;
	var selector_div;
	
	if( selected.closest('table').closest('div').hasClass('customizer_fields_wrapper') ){
		selector_div = 'customizer_fields_wrapper';
	} else {
		selector_div = 'customizer_section_fields_wrapper';
	}

	var selected_fields = selected.closest('.fields');

	//console.log( selected );
	//console.log( selector_div );
	//console.log( selected_fields );

	// Delete the field
	selected.closest( 'table' ).remove();
	//console.log(selected_fields);

	if( selector_div == 'customizer_fields_wrapper' ){

		selected_fields.find( 'div.' + selector_div + ' > table' ).each( function( i ){
			count++;
		});

	} else {

		if( selected_fields.find( 'div.' + selector_div + ' > div' ).hasClass( 'customizer_custom_fields_wrapper' ) ){

			selected_fields.find( 'div.' + selector_div + '> .customizer_custom_fields_wrapper > table' ).each( function( i ){
				count++;
			});

		} else {

			selected_fields.find( 'div.' + selector_div + '> table' ).each( function( i ){
				count++;
			});

		}

	}

	//console.log( count );

	// If no fields are available to show then show the no field message
	if( count == 0  ){
		//console.log(selected.closest('.fields').find('.no_fields_message'));
		selected_fields.find('.no_fields_message').show();
		selected_fields.find('.no_section_fields_message').show();
		selected_fields.find('.no_custom_fields_message').show();
	}

	rearrange_field_order();

}

function rearrange_order_no_add( selected , selector_div , custom_field_status ){

	var ignore_class_circle = ( custom_field_status == true ? null : '.ignore_custom_field_circle' );
	var ignore_class_table = ( custom_field_status == true ? null : '.wpcb_custom_field_wrapper' );

	//console.log( ignore_class );
	//console.log( selected.closest('.table_footer').prev('.fields').find( selector_div + '> table' ) );

	//console.log( selected );
	//console.log( selector_div );
	//console.log( selector_div + ' > table' );
	//console.log( selected.closest('.table_footer').prev('.fields').eq(0).find( selector_div + ' > table' ) );

	if( selector_div == '.customizer_fields_wrapper' ){

		selected.closest('.table_footer').prev('.fields').find( selector_div ).eq(0).not('.customizer_section_fields_wrapper').find( 'table' ).not('.fields_heading,.field_detail_description,.ignore_append,.wpcb_custom_field_wrapper').each( function( i ){

			jQuery( this ).find( 'tr > td > .circle' ).not( '.circle_ignore,.ignore_custom_field_circle' ).text( (i + 1) ); // Start from number 1

			//console.log( (i + 1) );

		});

	} else {

		//console.log( selected.closest('.table_footer').prev('.fields').find( selector_div + '> table' ).not(ignore_class_table) );
		selected.closest('.table_footer').prev('.fields').find( selector_div + '> table' ).not(ignore_class_table).each( function( i ){
			jQuery(this).find( 'tr > td > .circle' ).not(ignore_class_circle).text( (i + 1) ); // Start from number 
		});

	}

}

/*
* Adding new section
*/

jQuery( document ).on( 'click' , '#add_section,#add_section_inside' , function(){

	var selected = jQuery(this);
	var section;
	jQuery.ajax({
		url : wpcb.ajaxurl,
		dataType : 'json',
		type : 'post',
		data : {
			action : 'get_section_field',
			class : ( selected.attr('id') == 'add_section_inside' ? 'ignore_append' : '' ),
			circle_class : ( selected.attr('id') == 'add_section_inside' ? 'circle_ignore' : '' ),
			inside_section : ( selected.attr('id') == 'add_section_inside' ? true : false ),
			parent_unique_key : selected.closest('table').find('.unique_key').val()
		},
		beforeSend : function(){
			selected.attr( 'disabled' , true ).val( 'Adding Section ...' );
		},
		success : function( data ){
			
			if( data.result == 'success' ){
				section = data.content;
			}

			if( selected.attr('id') == 'add_section' ){

				jQuery( '.customizer_fields_wrapper' ).append( section );

				// Hide the no field message
				jQuery('.no_fields_message').hide();

				//rearrange_order_no_add( selected , '.customizer_fields_wrapper' );
				rearrange_field_order();

			} else {

				/**
				* When there is no section on the inside it will create the first section 
				*/

				if( !selected.closest('td').find( '.customizer_section_fields_wrapper table' ).hasClass( 'ignore_append' ) ){

					selected.parent().parent().parent().find('.fields > .customizer_section_fields_wrapper').append( section );

				} else {

					/**
					* After creation of the first section inside all other secions will be append 
					* after thet section.
					*/

					jQuery(section).insertAfter( selected.parent().parent().parent().find('.fields > .customizer_section_fields_wrapper table.ignore_append:last-child') );

				}
				
				// Hide the no field message
				selected.closest('td').find( '.fields .no_section_fields_message' ).hide();

				//rearrange_order_no_add( selected , '.customizer_section_fields_wrapper' );
				rearrange_field_order();

			}			

			selected.attr( 'disabled' , false ).val( '+ Add Section' );
		}
	});
});

/*
* Adding new panel
*/

jQuery( document ).on( 'click' , '#add_panel' , function(){

	var selected = jQuery(this);
	var panel;
	var count_parent_table = jQuery('.customizer_fields_wrapper > table').length;
	//console.log();

	jQuery.ajax({
		url : wpcb.ajaxurl,
		dataType : 'json',
		type : 'post',
		data : {
			action : 'get_panel_field',
			//count : count_parent_table
		},
		beforeSend : function(){
			jQuery('#add_panel').attr( 'disabled' , true ).val( 'Adding Panel ...' );
		},
		success : function( data ){

			if( data.result == 'success' ){
				panel = data.content;
			}

			var selected_table = jQuery( '.customizer_fields_wrapper' ).append( panel );

			//rearrange_order_no_add( selected , '.customizer_fields_wrapper' );
			rearrange_field_order();

			// Hide the no field message
			jQuery('.no_fields_message').hide();

			jQuery('#add_panel').attr( 'disabled' , false ).val( '+ Add Panel' );
		}
	});
});

/*
* Deleting Fields
*/

jQuery( document ).on( 'click' , '.wpcb_delete_field' , function(){

	var selected = jQuery(this);
	
	var status = confirm( 'Are you sure you want to delete this field ?' );

	// If user press no donot proceed further
	if( status == false ){
		return;
	}

	rearrange_order_no_delete( selected );

});

jQuery( document ).on( 'click' , '.field_title,.wpcb_edit_field' , function(){

	jQuery(this).closest('tr').next('tr').slideToggle( 'fast' , function(){
		if ( jQuery(this).is(':hidden')){
            jQuery(this).closest('table').removeClass( 'active' );
        } else {
        	jQuery(this).closest('table').addClass( 'active' );
        }
	});

	get_condition_logic_fields();

	// Allow only for custom fields
	if( jQuery(this).closest('table').hasClass( 'wpcb_custom_field_wrapper' ) ){
		setDefaultConditionValue( jQuery(this).closest('table.wpcb_custom_field_wrapper').find('.condition_radio_wrapper input[value="yes"]') );
	}

});

function get_condition_logic_fields(){

	// Get all logic fields eg. select, checkbox, radio
	jQuery( 'table[data-logic-field="true"]' ).each( function(){

		var tableField = jQuery(this);

		var fieldName = jQuery(this).find( '.field_title' ).text().trim(); // get field name
		var fieldValue = jQuery(this).find( '.field_name' ).val().trim();
		var uniqueId = jQuery(this).attr( 'data-field-id' ); // get unique id

		//console.log( uniqueId );
		
		jQuery('.condition_field').each( function(){

			var status = false;

			var selectField = jQuery(this);

			// If select has no option insert first data
			if( selectField.find('option').length < 1 ){
				selectField.append( '<option value="' + fieldValue + '" data-field-id="' + uniqueId + '">' + fieldName + '</option>' );
			}

			// Do no insert duplicate options
			jQuery(this).find('option').each(function(){

				var optionField = jQuery(this);

				if( optionField.attr( 'data-field-id' ) == uniqueId ){
					status = true;
				}

			});

			if( status == false ){
				selectField.append( '<option value="' + fieldValue + '" data-field-id="' + uniqueId + '">' + fieldName + '</option>' );
			}

		});

	});

	// If the names changed then replace old names
	get_new_field_names(); 

}

function get_new_field_names(){

	jQuery( 'table[data-logic-field="true"]' ).each( function(){

		var fieldName = jQuery(this).find( '.field_title' ).text().trim(); // get field name
		var fieldValue = jQuery(this).find( '.field_name' ).val().trim();
		var uniqueId = jQuery(this).attr( 'data-field-id' ); // get unique id

		jQuery('.condition_field').each( function(){

			jQuery(this).find('option').each(function(){

				if( jQuery(this).attr( 'data-field-id' ) == uniqueId ){
					jQuery(this).attr( 'value' , fieldValue );
					jQuery(this).text( fieldName );
				}

			});

		});

	});

}

jQuery(document).on( 'change' , '.condition_field' , function(){

	var field_id = jQuery(this).find('option:checked').attr( 'data-field-id' );
	
	//console.log( 'table[data-field-id="' + field_id + '"]' );
	//console.log( jQuery( 'table[data-field-id="' + field_id + '"]' ) );

	var selectedTable = jQuery( 'table[data-field-id="' + field_id + '"]' );
	var fieldType = selectedTable.attr( 'data-field-type' );
	var choices;
	var selected = jQuery(this);
	//console.log(fieldType);

	selected.closest('.conditional-logic-rules').find('.condition_value option').remove();

	switch( fieldType ){

		case 'select':
		case 'radio':
		case 'radio-buttonset':
		case 'switch':
		case 'radio-image':
		case 'palette':
		
			choices = selectedTable.find( '.field_choices' ).val().split("\n");
			jQuery.each( choices , function(i){
				var choices_key_val = choices[i].split(":");
				selected.closest('.conditional-logic-rules').find('.condition_value').append( '<option value="' + choices_key_val[0].trim() + '">' + choices_key_val[1].trim() + '</option>' );
			});

			break;

		case 'checkbox':
		case 'toggle':		

			var options = '<option value="1">True</option><option value="0">False</option>';
			selected.closest('.conditional-logic-rules').find('.condition_value').append( options );

			break;

		default:
			break;

	}

});

jQuery(document).on( 'click' , '.wpcb_custom_field' , function(){

	var field = jQuery( this ).prev( '.available_custom_fields' ).val();

	if( field == '' ){
		alert( 'Please select one custom field' );
		return;
	}

	var selected = jQuery(this);

	//console.log( selected.closest('table').find('.unique_key').val() );
	//console.log( selected.closest('table').closest('.customizer_section_fields_wrapper').closest('table').find('.unique_key').val() );

	jQuery.ajax({
		url : wpcb.ajaxurl,
		dataType : 'json',
		type : 'post',
		data : {
			action : 'get_customizer_custom_field',
			field : field,
			parent_key : selected.closest('table').find('.unique_key').val(),
			grand_parent_key : selected.closest('table').closest('.customizer_section_fields_wrapper').closest('table').find('.unique_key').val()
		},
		beforeSend:function(){
			selected.val( 'Adding ...' ).prop( 'disabled' , true );
		},
		success : function( data ){

			if( data.result == 'error' ){
				return;
			}

			if( selected.closest('.table_footer').prev('.fields').find( '.customizer_section_fields_wrapper > div' ).hasClass( 'customizer_custom_fields_wrapper' ) ){

				selected.closest('.table_footer').prev('.fields').find('.customizer_section_fields_wrapper > .customizer_custom_fields_wrapper').append( data.content );

			} else {
				selected.closest('.table_footer').prev('.fields').find('.customizer_section_fields_wrapper').append( data.content );
			}

			selected.closest('.table_footer').prev('.fields').find('.no_custom_fields_message').hide();

			//rearrange_order_no_add( selected , '.customizer_section_fields_wrapper' , true );
			rearrange_field_order();
			selected.val( 'Add' ).prop( 'disabled' , false );
		}
	});

});

function rearrange_field_order(){

	jQuery('.customizer_fields_wrapper .field_order').each( function( i ){

		//console.log( jQuery(this) );
		jQuery(this).val( (i+1) );

	});

}

jQuery(document).on( 'click' , '.wpcb_save' , function(){

	var selected = jQuery(this);

	var getdata = jQuery('.kirki_builer_form').serialize();
	//console.log( getdata );

	jQuery.ajax({
		url : wpcb.ajaxurl,
		type : 'post',
		dataType : 'json',
		data : {
			action : 'save_kirki_customizer_builder',
			data : getdata,
			status : jQuery('[name="wpcb_status"]:checked').val()
		},
		beforeSend : function(){
			jQuery('.spinner img').css( 'visibility' , 'visible' );
			selected.prop( 'disabled' , true );
		},
		success : function( data ){

			if( data.result == 'success' ){
				location.reload();
			}

		}
	});

	// jQuery('.customizer_fields_wrapper > table').each( function(){

	// 	var parent_field_type = jQuery(this).find( 'tbody > tr:first-child > td.field_type' ).attr( 'data-field' );

	// 	if( parent_field_type == 'panel' ){

	// 	} else {

	// 	}

	// });

	// switch( field ){

	// 	case 'section':
	// 		get_section_field_details( field );
	// 		break;

	// 	default:
	// 		break;

	// }

});

function get_section_field_details( field ){

	//console.log( field.closest('tr').next('tr') );

}

jQuery(document).on('keyup','.field_label',function(){

	var field_name = ( jQuery(this).val() == '' ? 'New Field' : jQuery(this).val() );
	//console.log( jQuery(this).closest('table').closest('tr').closest('table') );
	jQuery(this).closest('table').closest('tr').closest('table').find('tbody > tr:first-child > td > a.field_title').eq(0).text(field_name);

	//jQuery(this).closest('tr').next('tr').find('.field_name').val( field_name.replace(/\W+/g, '-').toLowerCase() );
});

jQuery(document).on( 'click focus' , '.field_name' , function(){
	//console.log(jQuery(this).closest('tr'));

	if( jQuery(this).val() != '' ){
		return;
	}

	//var value = jQuery(this).closest('tr').prev('tr').find('.field_label').val().replace(/\W+/g, '-').toLowerCase();
	var value = jQuery(this).closest('tr').prev('tr').find('.field_label').val().toLowerCase();
	jQuery(this).val( value );

	jQuery(this).closest('table').closest('tr').prev('tr').find('.static_field_name').text(value);

});

jQuery(document).on( 'keyup' , '.field_name' , function(){

	//var value = jQuery(this).val().replace(/\W+/g, '-').toLowerCase();
	var value = jQuery(this).val().toLowerCase();
	//jQuery(this).val( value );
	jQuery(this).closest('table').closest('tr').prev('tr').find('.static_field_name').text(value);

});

jQuery(document).on( 'click' , '.wpcb_settings_tabs a' , function(){

	var id = jQuery(this).attr( 'value' );
	//alert(id);

	jQuery(this).parent().find('a').removeClass('active');
	jQuery(this).addClass('active');
	jQuery('.wpcb_settings_wrap').hide();
	jQuery( '#'+id ).show();

});

jQuery(document).on( 'click' , '.btn_import_json' , function(){

	var json_data = jQuery('.import_from_json_data').val();

	if( tryParseJSON( json_data ) == false ){
		alert( 'Not valid json data.' );
		return;
	}

	var import_options = jQuery('[name="import_options"]:checked').val();

	jQuery.ajax({
		url : wpcb.ajaxurl,
		dataType : 'json',
		type : 'post',
		data : {
			action : 'wpcb_import_settings',
			import_options : import_options,
			json_data : json_data

		},
		beforeSend : function(){
			jQuery('.btn_import_json').prop( 'disabled' , true ).val('Importing ...');
			jQuery('.wpcb_import_success').hide();
		},
		success : function( data ){

			if( data.status == 'error' ){
				alert( 'Something went wrong. Please try again' );
				return;
			} else {

				jQuery('.import_from_json_data').val('');
				jQuery('.btn_import_json').prop( 'disabled' , false ).val('Import from JSON');
				jQuery('.wpcb_import_success').show();

			}

		}
	});

});

function tryParseJSON(jsonString){
    try {
        var o = JSON.parse(jsonString);

        // Handle non-exception-throwing cases:
        // Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
        // but... JSON.parse(null) returns null, and typeof null === "object", 
        // so we must check for that, too. Thankfully, null is falsey, so this suffices:
        if (o && typeof o === "object") {
            return o;
        }
    }
    catch (e) { }

    return false;
};

jQuery( document ).on( 'click' , '.condition_radio_wrapper input[type="radio"]' , function(){

	get_condition_logic_fields(); // Add new conditions fields on the condition logic

	if( jQuery(this).is(':checked') && jQuery(this).val() == 'yes' ){
		jQuery(this).closest('.condition_radio_wrapper').nextAll('ul.conditional-logic-rules').show();
		jQuery(this).closest('.condition_radio_wrapper').nextAll('.condition_logic_msg').show();
		setDefaultConditionValue( jQuery(this) );
	} else {
		jQuery(this).closest('.condition_radio_wrapper').nextAll('ul.conditional-logic-rules').hide();
		jQuery(this).closest('.condition_radio_wrapper').nextAll('.condition_logic_msg').hide();
	}

});

function setDefaultConditionValue( selected ){

	selected.closest('.condition_radio_wrapper').nextAll('ul.conditional-logic-rules').each( function(){
		
		var firstOptionID = jQuery(this).find('.condition_field option:selected').attr('data-field-id');
		var value = jQuery( 'table[data-field-id="' + firstOptionID + '"]' ).find('.field_choices').val();

		if( value == '' || value == undefined ){
			return;
		}

		var select_field = jQuery(this);

		// get the database selected value
		var db_selected = jQuery(this).find('.condition_value').val();
		
		jQuery(this).find('.condition_value').empty();

		choices = value.split("\n");
		var default_selected;

		jQuery.each( choices , function(i){

			var choices_key_val = choices[i].split(":");

			if( i == 0 ){
			 	default_selected = choices_key_val[0].trim();
			}

			select_field.find('.condition_value').append( '<option value="' + choices_key_val[0].trim() + '">' + choices_key_val[1].trim() + '</option>' );
			
		});

		// Default selected value on the select
		if( db_selected != null ){
			// Select the database value on the select field
			select_field.find('.condition_value').val( db_selected );
		} 
		// If no database value then select the first value as default
		else {
			select_field.find('.condition_value').val( default_selected );
		}

	});

}

jQuery(document).on( 'click' , '.wpcb-button-add' , function(){

	var conditionsClone = jQuery(this).closest('.conditional-logic-rules').clone();

	var prevSelected = jQuery(this).closest('.conditional-logic-rules').find( '.condition_field' ).val();

	//console.log( jQuery(this).closest('.conditional-logic-rules').find( '.condition_field' ).val() );
	
	conditionsClone.find( '.condition_field' ).val( prevSelected );
	conditionsClone.find( '.condition_operator' ).prop( 'selectedIndex',0 );
	conditionsClone.find( '.condition_value' ).prop( 'selectedIndex',0 );

	conditionsClone.insertAfter( jQuery(this).closest('.conditional-logic-rules') );// Add new cloned 

	// Get input names
	var fieldNameFirstPart = jQuery(this).closest('td').find('.condition_logic_keys').attr( 'data-first-part' );

	// Add new names
	resetNameCounter( jQuery(this).closest('td') , fieldNameFirstPart );

	var closest_td = jQuery(this).closest('td');
	var count = closest_td.find('.conditional-logic-rules').length;

	if( count > 1 ){
		closest_td.find('.wpcb-button-remove').show();
	}

});

jQuery( document ).on( 'click' , '.wpcb-button-remove' , function(){
	
	var closest_td = jQuery(this).closest('td');
	
	// Get input names
	var fieldNameFirstPart = jQuery(this).closest('td').find('.condition_logic_keys').attr( 'data-first-part' );
	var selected = jQuery(this);

	jQuery(this).closest('.conditional-logic-rules').remove();
	var count = closest_td.find('.conditional-logic-rules').length;
	
	if( count == 1 ){
		closest_td.find('.wpcb-button-remove').hide();
	}

	// Add new names
	resetNameCounter( closest_td , fieldNameFirstPart );

});

function resetNameCounter( selected , fieldNameFirstPart ){

	selected.find('.conditional-logic-rules').each(function(i){
		jQuery(this).find('.condition_field').attr( 'name' , fieldNameFirstPart + '[' + i + "][field]" );
		jQuery(this).find('.condition_operator').attr( 'name' , fieldNameFirstPart + '[' + i + "][operator]" );
		jQuery(this).find('.condition_value').attr( 'name' , fieldNameFirstPart + '[' + i + "][value]" );
	});

}

jQuery( document ).on( 'click' , '.add_transport' , function(){

	var new_field = jQuery(this).closest( '.field_transport_wrapper' ).clone();
	new_field.find( "input[type='text']" ).val("");

	jQuery( new_field ).insertAfter( jQuery(this).closest( '.field_transport_wrapper' ) );

	// Get the transport first part name
	var first_part = jQuery(this).closest( '.field_transport' ).attr( 'data-first-type' );

	resetTransportCounter( jQuery(this).closest( '.field_transport' ) , first_part );

	var parent = jQuery(this).closest( '.field_transport' );
	var count = parent.find( '.field_transport_wrapper' ).length;

	if( count > 1 ){
		parent.find( '.remove_transport' ).show();
	}

});

function resetTransportCounter( selected , first_part ){

	selected.find( '.field_transport_wrapper' ).each( function( i ){

		jQuery(this).find( '.transport_element' ).attr( 'name' , first_part + '[' + i + '][element]' );
		jQuery(this).find( '.transport_function' ).attr( 'name' , first_part + '[' + i + '][function]' );
		jQuery(this).find( '.transport_property' ).attr( 'name' , first_part + '[' + i + '][property]' );
		jQuery(this).find( '.transport_units' ).attr( 'name' , first_part + '[' + i + '][units]' );
		jQuery(this).find( '.transport_prefix' ).attr( 'name' , first_part + '[' + i + '][prefix]' );
		jQuery(this).find( '.transport_suffix' ).attr( 'name' , first_part + '[' + i + '][suffix]' );
		jQuery(this).find( '.transport_attr' ).attr( 'name' , first_part + '[' + i + '][attr]' );

	});

}

jQuery(document).on( 'click' , '.remove_transport' , function(){

	// Get the transport first part name
	var first_part = jQuery(this).closest( '.field_transport' ).attr( 'data-first-type' );

	var parent = jQuery(this).closest( '.field_transport' );
	jQuery(this).closest( '.field_transport_wrapper' ).remove();
	var count = parent.find( '.field_transport_wrapper' ).length;

	if( count == 1 ){
		parent.find( '.remove_transport' ).hide();
	}

	resetTransportCounter( parent , first_part );

});

jQuery( document ).on( 'change' , '.condition_transport_wrapper input' , function(){

	if( jQuery(this).is( ':checked' ) && jQuery(this).val() == 'refresh' ){
		jQuery(this).closest('.condition_transport_wrapper').nextAll( '.field_transport' ).hide();
	} else {
		jQuery(this).closest('.condition_transport_wrapper').nextAll( '.field_transport' ).show();
	}

});