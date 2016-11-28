<?php

/**
* Get section on ajax call
*/

add_action( 'wp_ajax_get_section_field' , 'wpcb_get_section_field' );
function wpcb_get_section_field(){

	if( defined('DOING_AJAX') && DOING_AJAX ){

		$ignore_class = !empty( $_POST['class'] ) ? sanitize_text_field( $_POST['class'] ) : '';
		$wrapper_class = 'active ' . $ignore_class;
		$parent_unique_key = !empty( $_POST['parent_unique_key'] ) ? sanitize_text_field( $_POST['parent_unique_key'] ) : '';

		$circle_class = !empty( $_POST['circle_class'] ) ? sanitize_text_field( $_POST['circle_class'] ) : '';

		$panel_html = wpcb_get_field_html( 'section' , $wrapper_class , $circle_class , $parent_unique_key ); // Get section html code
		echo wp_json_encode( array( 'result' => 'success' , 'content' => $panel_html ) );
		die;
	}

}

/**
* Get panel on ajax call
*/

add_action( 'wp_ajax_get_panel_field' , 'wpcb_get_panel_field' );
function wpcb_get_panel_field(){

	// If ajax request
	if( defined('DOING_AJAX') && DOING_AJAX ){
		$panel_html = wpcb_get_field_html( 'panel' , $wrapper_class = 'active' , '' ); // Get panel html code
		echo wp_json_encode( array( 'result' => 'success' , 'content' => $panel_html ) );
		die;
	}

}

/**
* Call panel/section function to get html
*/

function wpcb_get_field_html( $field , $wrapper_class = null , $circle_class = null , $parent_unique_key = null ){ 

	ob_start(); 

	switch ( $field ) {
		case 'panel':
			get_panel_section_html_field( 'panel' , $wrapper_class , $circle_class , $parent_unique_key );
			break;

		case 'section':
			get_panel_section_html_field( 'section' , $wrapper_class , $circle_class , $parent_unique_key );
			break;
		
		default:
			# code...
			break;
	}

	$content = ob_get_clean();
	return $content;

}

/**
* return panel/section html
*/

function get_panel_section_html_field( $field , $wrapper_class , $circle_class , $parent_unique_key , $values = null , $random_count = null , $parent_keys = array() ){ 

	if( empty( $random_count ) ){
		$random_count = wp_generate_password( $length = 20, false, false );
	}

	$values_field_expand = empty( $values['field_expand'] ) ? '' : $values['field_expand'];

	if( empty( $parent_unique_key ) ){

		$field_label = 'fields[' . $random_count . '][field_label]';
		$field_status = 'fields[' . $random_count . '][type]';
		$field_name = 'fields[' . $random_count . '][field_name]';
		$field_instruction = 'fields[' . $random_count . '][field_instruction]';
		$field_order = 'fields[' . $random_count . '][field_order]';

	} else {

		$field_label = 'fields[' . $parent_unique_key . '][sub_fields][' . $random_count . '][field_label]';
		$field_status = 'fields[' . $parent_unique_key . '][sub_fields][' . $random_count . '][type]';
		$field_name = 'fields[' . $parent_unique_key . '][sub_fields][' . $random_count . '][field_name]';
		$field_instruction = 'fields[' . $parent_unique_key . '][sub_fields][' . $random_count . '][field_instruction]'; 
		$field_order = 'fields[' . $parent_unique_key . '][sub_fields][' . $random_count . '][field_order]'; 
		$field_expand = 'fields[' . $parent_unique_key . '][sub_fields][' . $random_count . '][field_expand]';

	} ?>

	<table cellspacing="0" class="<?php echo $wrapper_class; ?>">

		<tr>

			<td><span class="circle <?php echo $circle_class; ?>"></span></td>

			<td>
				
				<a 
				href="javascript:void(0)" 
				class="field_title">
					<?php 
					echo ( empty( $values['field_label'] ) ? esc_html__( 'New Field', 'wpcb' ) : esc_html( stripslashes( $values['field_label'] ) ) ); ?>
				</a>

				<div class="row_options">
					<span><a href="javascript:;" title="Edit this Field" class="wpcb_edit_field">Edit</a> | </span>
					<span><a href="javascript:;" title="Delete this Field" class="wpcb_delete_field">Delete</a></span>
				</div>
			</td>
		
			<td class="static_field_name">
				<?php echo ( empty( $values['field_name'] ) ? '' : esc_html( stripslashes( $values['field_name'] )  ) ); ?>
			</td>
		
			<td class="field_type" data-field="<?php echo $field; ?>"><?php echo ucwords( str_replace( '-', ' ', $field ) ); ?></td>

		</tr>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">

			<td colspan="4" style="padding:0px;">

				<table class="field_detail_description" cellspacing="0">

					<tr>

						<td>
							<strong>Field Label <span class="required">*</span></strong> 
							<p class="description">This is the panel name which will be displayed on the customizer.</p>
						</td>

						<td>

							<input 
							autocomplete="off" 
							type="text" 
							name="<?php echo $field_label; ?>" 
							class="field_label" 
							value="<?php echo ( empty( $values['field_label'] ) ? '' : esc_html( stripslashes( $values['field_label'] ) ) ); ?>">

							<input 
							autocomplete="off" 
							type="hidden" 
							value="<?php echo $field; ?>" 
							name="<?php echo $field_status; ?>" />

							<input 
							autocomplete="off" 
							type="hidden" 
							value="<?php echo $random_count; ?>" 
							class="unique_key" />

							<input autocomplete="off" type="hidden" name="<?php echo $field_order; ?>" value="" class="field_order"/>

						</td>

					</tr>

					<tr>
						<td>
							<strong>
								Field Name 
								<span class="required">*</span>
							</strong> 
							<p class="description">
								Single word, no spaces. Underscores and dashes allowed
							</p>
						</td>
						<td>
							<input 
							type="text" 
							autocomplete="off" 
							name="<?php echo $field_name; ?>" 
							class="field_name" 
							value="<?php echo ( empty( $values['field_name'] ) ? '' : esc_html( stripslashes( $values['field_name'] ) ) ); ?>">
						</td>
					</tr>

					<tr>
						<td>
							<strong>Field Instructions</strong> 
							<p class="description">
								Instructions for authors. Shown when submitting data
							</p>
						</td>
						<td>
							<textarea name="<?php echo $field_instruction; ?>" class="field_instruction"><?php echo ( empty( $values['field_instruction'] ) ? '' : esc_html( stripslashes( $values['field_instruction'] ) ) ); ?></textarea>
						</td>
					</tr>

					<!-- 
					Will be displayed on the section inside panel 
					-->

					<?php 
					if( $field == 'section' && !empty( $parent_unique_key ) ){ ?>

						<tr>
							<td>
								<strong>Expand</strong> 							
							</td>
							<td>
								<label>
									<input 
									autocomplete="off" 
									type="checkbox" 
									name="<?php echo $field_expand; ?>" 
									value="1" 
									<?php checked( $values_field_expand , 1 ); ?>>Enable
								</label>
								<p class="description">
									By default the section will slide right to left on click, if this is selected then the section will always be always visible. <a href="https://github.com/aristath/kirki/issues/703" target="_blank">More info</a>
								</p>
							</td>
						</tr>

						<?php 
					} ?>

					<tr>
						<td>
							<strong>
							<?php 
							if( $field == 'section' ){ 
								esc_html_e( 'Custom Field' , 'wpcb' );
							} else {
								esc_html_e( 'Section Field' , 'wpcb' );
							}
							?></strong>
						</td>
						<td>
							<table class="fields_heading">
								<thead>
									<th><?php esc_html_e( 'Field Order', 'wpcb' ); ?></th>
									<th><?php esc_html_e( 'Field Label', 'wpcb' ); ?></th>
									<th><?php esc_html_e( 'Field Name', 'wpcb' ); ?></th>
									<th><?php esc_html_e( 'Field Type', 'wpcb' ); ?></th>
								</thead>
							</table>

							<div class="fields">

								<!-- 
								No fields message
								-->

								<div class="<?php echo ( $field != 'section' ? 'no_section_fields_message' : 'no_custom_fields_message' ); ?>" style="<?php echo ( empty( $values['sub_fields'] ) ? '' : 'display:none' ); ?>">

									<?php 
									if( $field != 'section' ){ 

										printf(
											
											esc_html__( 'No fields. Click the %s button to create your first field.	' , 'wpcb' ),
											'<strong class="label label-primary">' . esc_html__( '+ Add Section' , 'wpcb' ) . '</strong>'

										);	

									} else { 

										printf(
											
											esc_html__( 'No fields. Click on %s button to create your first field.' , 'wpcb' ),
											'<strong class="label label-primary">' . esc_html__( 'Add' , 'wpcb' ) . '</strong>'

										); 

									}?>
										
								</div>

								
								<!-- 
								Panel / Section Wrapper 
								-->
								
								<div class="customizer_section_fields_wrapper">

									<?php 

									if( $values['type'] == 'panel' && !empty( $values['sub_fields'] ) && is_array( $values['sub_fields'] ) ){

										// Get sections
										foreach( $values['sub_fields'] as $key => $value ){

											$parent_keys['parent_key'] = $key;
											get_panel_section_html_field( 'section' , $wrapper_class = 'ignore_append' , $circle_class = null , $parent_keys['grand_parent_key'] , $value , $key , $parent_keys );

										}

									} elseif( $values['type'] == 'section' && !empty($values['sub_fields']) ) { // For sections

										$parent_keys['grand_parent_key'] = empty( $parent_keys['grand_parent_key'] ) ? null : $parent_keys['grand_parent_key'];

										// Get the custom fields text/textarea etc
										echo '<div class="customizer_custom_fields_wrapper">';
										foreach( $values['sub_fields'] as $key => $value ){

											switch ( $value['type'] ) {

												case 'text':
																										
													wpcb_get_text_html( 'text', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'textarea':
																										
													wpcb_get_textarea_html( 'textarea', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'checkbox':
																										
													wpcb_get_checkbox_html( 'checkbox', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'code':
																										
													wpcb_get_code_html( 'code', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'color':
																										
													wpcb_get_color_html( 'color', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'custom':
																										
													wpcb_get_custom_html( 'custom', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'dashicons':
																										
													wpcb_get_dashicons_html( 'dashicons', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'dimension':
																										
													wpcb_get_dimension_html( 'dimension', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'image':
																										
													wpcb_get_image_html( 'image', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'multicheck':
																										
													wpcb_get_multicheck_html( 'multicheck', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'multicolor':
																										
													wpcb_get_multicolor_html( 'multicolor', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'number':
																										
													wpcb_get_number_html( 'number', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'palette':
																										
													wpcb_get_palette_html( 'palette', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'radio-buttonset':
																										
													wpcb_get_radio_buttonset_html( 'radio-buttonset', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'radio-image':
																										
													wpcb_get_radio_image_html( 'radio-image', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'radio':
																										
													wpcb_get_radio_html( 'radio', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'select':
																										
													wpcb_get_select_html( 'select', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'user':
																										
													wpcb_get_user_html( 'user', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'page-id':
																										
													wpcb_get_page_id_html( 'page-id', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'slider':
																										
													wpcb_get_slider_html( 'slider', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'sortable':
																										
													wpcb_get_sortable_html( 'sortable', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'spacing':
																										
													wpcb_get_spacing_html( 'spacing', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'switch':
																										
													wpcb_get_switch_html( 'switch', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'toggle':
																										
													wpcb_get_toggle_html( 'toggle', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'upload':
																										
													wpcb_get_upload_html( 'upload', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'typography':
																										
													wpcb_get_typography_html( 'typography', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;

												case 'taxonomy':
																										
													wpcb_get_taxonomy_html( 'taxonomy', 
														$random_count,//$parent_keys['parent_key'], 
														$parent_keys['grand_parent_key'],
														$value, // All text details 
														$key
													);

													break;
												
												default:
													# code...
													break;
											}

										}
										echo '</div>';
										
									}

									?>

								</div>

							</div>

							<div class="table_footer">

								<div class="order_message"><?php esc_html_e( 'Drag and drop to reorder', 'wpcb' ); ?></div>

								<div class="add_fields_btn">

									<?php 
									if( $field == 'panel' ){ ?>
										<input type="button" class="button button-primary" id="add_section_inside" href="javascript:void(0)" value="<?php esc_html_e( '+ Add Section', 'wpcb' ); ?>" autocomplete="off">
										<?php 
									} else { ?>

										<select class="available_custom_fields" autocomplete="off">
											<option value=""><?php echo esc_html_e( 'Select Custom Field' , 'wpcb' );?></option>

											<optgroup label="Basic">
												<option value="text"><?php echo esc_html_e( 'Text' , 'wpcb' );?></option>
												<option value="textarea"><?php echo esc_html_e( 'Textarea' , 'wpcb' );?></option>
												<option value="number"><?php echo esc_html_e( 'Number' , 'wpcb' );?></option>
											</optgroup>

											<optgroup label="Content">
												<option value="code"><?php echo esc_html_e( 'Code' , 'wpcb' );?></option>
												<option value="image"><?php echo esc_html_e( 'Image' , 'wpcb' );?></option>
												<option value="upload"><?php echo esc_html_e( 'File' , 'wpcb' );?></option>
											</optgroup>

											<optgroup label="Choice">
												<option value="checkbox"><?php echo esc_html_e( 'Checkbox' , 'wpcb' );?></option>
												<option value="multicheck"><?php echo esc_html_e( 'Multicheck' , 'wpcb' );?></option>
												<option value="select"><?php echo esc_html_e( 'Select' , 'wpcb' );?></option>
												<option value="radio"><?php echo esc_html_e( 'Radio' , 'wpcb' );?></option>
												<option value="toggle"><?php echo esc_html_e( 'Toggle' , 'wpcb' );?></option>
												<option value="switch"><?php echo esc_html_e( 'Switch' , 'wpcb' );?></option>
												<option value="radio-buttonset"><?php echo esc_html_e( 'Radio Buttonset' , 'wpcb' );?></option>
												<option value="radio-image"><?php echo esc_html_e( 'Radio Image' , 'wpcb' );?></option>
												<option value="palette"><?php echo esc_html_e( 'Palette' , 'wpcb' );?></option>
											</optgroup>

											<optgroup label="Relational">
												<option value="page-id"><?php echo esc_html_e( 'Page ID' , 'wpcb' );?></option>
												<option value="taxonomy"><?php echo esc_html_e( 'Taxonomy' , 'wpcb' );?></option>
												<option value="user"><?php echo esc_html_e( 'User' , 'wpcb' );?></option>
											</optgroup>

											<optgroup label="Jquery">
												<option value="color"><?php echo esc_html_e( 'Color' , 'wpcb' );?></option>
												<option value="multicolor"><?php echo esc_html_e( 'Multicolor' , 'wpcb' );?></option>
												<option value="slider"><?php echo esc_html_e( 'Slider' , 'wpcb' );?></option>
												<option value="sortable"><?php echo esc_html_e( 'Sortable' , 'wpcb' );?></option>
											</optgroup>

											<optgroup label="Layout">
												<option value="custom"><?php echo esc_html_e( 'Message' , 'wpcb' );?></option>
												<option value="spacing"><?php echo esc_html_e( 'Spacing' , 'wpcb' );?></option>
												<option value="dashicons"><?php echo esc_html_e( 'Dashicons' , 'wpcb' );?></option>
												<option value="dimension"><?php echo esc_html_e( 'Dimension' , 'wpcb' );?></option>
												<option value="typography"><?php echo esc_html_e( 'Typography' , 'wpcb' );?></option>
											</optgroup>	
																						
										</select>

										<input class="button button-primary wpcb_custom_field" id="choose_custom_field" href="javascript:void(0)" type="button" autocomplete="off" value="<?php esc_html_e( 'Add', 'wpcb' ); ?>">

										<?php
									} ?>

								</div>

							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get custom fields HTML
*/

add_action( 'wp_ajax_get_customizer_custom_field' , 'wpcb_get_customizer_custom_field' );
function wpcb_get_customizer_custom_field(){

	$field = !empty( $_POST['field'] ) ? sanitize_text_field( $_POST['field'] ) : '';
	$parent_key = !empty( $_POST['parent_key'] ) ? sanitize_text_field( $_POST['parent_key'] ) : '';
	$grand_parent_key = !empty( $_POST['grand_parent_key'] ) ? sanitize_text_field( $_POST['grand_parent_key'] ) : '';

	if( empty( $field ) ){
		echo json_encode( array( 'result' => 'error' ) );
		die;
	}

	$random_count = wp_generate_password( $length = 20, false, false );

	ob_start();

	switch ( $field ) {

		case 'text':
			wpcb_get_text_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'textarea':
			wpcb_get_textarea_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'checkbox':
			wpcb_get_checkbox_html( $field , $parent_key , $grand_parent_key, null , $random_count );
			break;

		case 'code':
			wpcb_get_code_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'color':
			wpcb_get_color_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'custom':
			wpcb_get_custom_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'dashicons':
			wpcb_get_dashicons_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'dimension':
			wpcb_get_dimension_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'image':
			wpcb_get_image_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'multicheck':
			wpcb_get_multicheck_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'multicolor':
			wpcb_get_multicolor_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'number':
			wpcb_get_number_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'palette':
			wpcb_get_palette_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'radio-buttonset':
			wpcb_get_radio_buttonset_html( $field , $parent_key , $grand_parent_key, null , $random_count );
			break;

		case 'radio-image':
			wpcb_get_radio_image_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'radio':
			wpcb_get_radio_html( $field , $parent_key , $grand_parent_key, null , $random_count  );
			break;

		case 'select':
			wpcb_get_select_html( $field , $parent_key , $grand_parent_key , null , $random_count );
			break;

		case 'user':
			wpcb_get_user_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'page-id':
			wpcb_get_page_id_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'slider':
			wpcb_get_slider_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'sortable':
			wpcb_get_sortable_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'spacing':
			wpcb_get_spacing_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'switch':
			wpcb_get_switch_html( $field , $parent_key , $grand_parent_key, null , $random_count  );
			break;

		case 'toggle':
			wpcb_get_toggle_html( $field , $parent_key , $grand_parent_key, null , $random_count  );
			break;

		case 'upload':
			wpcb_get_upload_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'typography':
			wpcb_get_typography_html( $field , $parent_key , $grand_parent_key );
			break;

		case 'taxonomy':
			wpcb_get_taxonomy_html( $field , $parent_key , $grand_parent_key );
			break;
		
		default:
			# code...
			break;
	}

	$content = ob_get_clean();

	echo json_encode( array( 'result' => 'success' , 'content' => $content ) );
	die;
}

/**
* Get Checkbox field
*/

function wpcb_get_checkbox_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>"
	data-logic-field="true" 
	data-field-type="<?php echo $field; ?>" 
	data-field-id="<?php echo $random_count; ?>">

		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">

			<td colspan="4" style="padding:0px !important;">

				<table class="field_detail_description" cellspacing="0">

					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					wpcb_get_default_value_checkbox( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>

					<textarea class="field_choices" style="display:none"><?php echo "1:True\n0:False"; ?></textarea>

				</table>

			</td>

		</tr>

	</table>

	<?php

}

/**
* Get Textarea field
*/

function wpcb_get_textarea_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">

		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">

			<td colspan="4" style="padding:0px !important;">

				<table class="field_detail_description" cellspacing="0">

					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					wpcb_get_default_value( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>

				</table>

			</td>

		</tr>

	</table>

	<?php

}

/**
* Get code field
*/

function wpcb_get_code_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$msg_default = "Define a default string that will be used, or use an empty string<br> eg. <code> body { background: #fff; } </code> ";
					wpcb_get_default_value( $args , $values , $default_field = 'textarea' , $msg_default );
					?>

					<!-- 
					Languages
					-->

					<?php  
					//wpcb_get_code_languages( $args , $values );
					?>

					<!-- 
					Code Theme
					-->

					<?php
					//wpcb_get_code_themes( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>	

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Color field
*/

function wpcb_get_color_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = 'Define a HEX or RGBA value as default. Eg. <code>rgba(0,0,0,1)</code> or <code>#000000</code>';
					wpcb_get_default_value( $args , $values , 'text' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Custom
*/

function wpcb_get_custom_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					//wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = 'Custom controls allow you to add raw HTML in a control. Mostly used for informative controls, expanatory headers etc, but you can use it for whatever you want.';
					wpcb_get_default_value( $args , $values , 'textarea' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Dashicons
*/

function wpcb_get_dashicons_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = 'Define a default dashicon without the dashicons- prefix. <code>Eg. menu</code>. See the full dashicons list <a target="_blank" href="https://developer.wordpress.org/resource/dashicons/">here</a>';
					wpcb_get_default_value( $args , $values , 'text' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>			

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Dimension field
*/

function wpcb_get_dimension_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = 'Define a valid CSS value. Example 10px, 1em, 90vh etc.';
					wpcb_get_default_value( $args , $values , 'text' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>			

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Text field
*/

function wpcb_get_text_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					wpcb_get_default_value( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Toggle field
*/

function wpcb_get_toggle_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>" 
	data-logic-field="true" 
	data-field-type="<?php echo $field; ?>" 
	data-field-id="<?php echo $random_count; ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = 'eg. 0 or 1';
					wpcb_get_default_value( $args , $values , 'text' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>

					<textarea class="field_choices" style="display:none"><?php echo "1:True\n0:False"; ?></textarea>

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Upload field
*/

function wpcb_get_upload_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>	

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Spacing field
*/

function wpcb_get_spacing_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$message = "The default values determine which of the elements will be displayed. If for example you only want to display top & bottom, then in the default field you only include top & bottom.<br><br>eg. <br>top : 1.5em<br>bottom : 10px<br>left : 40%<br>right : 2rem";
					$default_value = "top : 1.5em\nbottom : 10px\nleft : 40%\nright : 2rem";
					wpcb_get_default_value( $args , $values , 'textarea' , $message, $default_value, $required = true );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get radio-buttonset field
*/

function wpcb_get_radio_buttonset_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>"
	data-logic-field="true" 
	data-field-type="<?php echo $field; ?>" 
	data-field-id="<?php echo $random_count; ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$dafault_msg = 'Please specify both keys and values like this:<br><br>red : Red<br>blue : Blue';
					$default_value = "red : Red\nblue : Blue"; 
					wpcb_get_choices( $args, $values, $dafault_msg, $default_value, $required = true );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					wpcb_get_default_value( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>	

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get radio-image field
*/

function wpcb_get_radio_image_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>"
	data-logic-field="true" 
	data-field-type="<?php echo $field; ?>" 
	data-field-id="<?php echo $random_count; ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$dafault_msg = 'Please specify both keys and values like this:<br><br>red : [template_url]/images/red.png<br>blue : [template_url]/images/blue.png<br><br>where <code>[template_url]</code> is <code>get_template_directory_uri()</code> <br>or direct URL eg. http://example.com/red.png';
					$default_value = "red : [template_url]/images/red.png\nblue : [template_url]/images/blue.png"; 
					wpcb_get_choices( $args, $values, $dafault_msg, $default_value, $required = true );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					wpcb_get_default_value( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>				

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get radio field
*/

function wpcb_get_radio_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>"
	data-logic-field="true" 
	data-field-type="<?php echo $field; ?>" 
	data-field-id="<?php echo $random_count; ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$dafault_msg = 'Please specify both keys and values like this:<br><br>red : Red<br>blue : Blue';
					$default_value = "red : Red\nblue : Blue"; 
					wpcb_get_choices( $args, $values, $dafault_msg, $default_value , $required = true );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					wpcb_get_default_value( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>	

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get select field
*/

function wpcb_get_select_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>" 
	data-logic-field="true" 
	data-field-type="<?php echo $field; ?>" 
	data-field-id="<?php echo $random_count; ?>" >
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$dafault_msg = 'Please specify both keys and values like this:<br><br>red : Red<br>blue : Blue';
					$default_value = "red : Red\nblue : Blue"; 
					wpcb_get_choices( $args, $values, $dafault_msg, $default_value , $required = true );
					?>

					<!-- 
					Selection Limit
					-->

					<?php 
					$message = 'The number of options users will be able to select simultaneously. Use 1 for single-select controls (defaults to 1).';
					wpcb_get_no_of_selection( $args, $values, $message );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = 'For multiple default values enter choices separated by commas. eg apple, banana, mango';
					wpcb_get_default_value( $args , $values , 'text' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>	

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}


/**
* Get User field
*/

function wpcb_get_user_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					wpcb_get_users_choices( $args, $values );
					?>

					<!-- 
					Selection Limit
					-->

					<?php 
					$message = 'The number of options users will be able to select simultaneously. Use 1 for single-select controls (defaults to 1).';
					wpcb_get_no_of_selection( $args, $values, $message );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>		

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Page ID field
*/

function wpcb_get_page_id_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					wpcb_get_post_type_choices( $args, $values );
					?>

					<!-- 
					Filter from Taxonomy
					-->

					<?php 
					wpcb_filter_from_taxonomy( $args, $values );
					?>

					<!-- 
					Selection Limit
					-->

					<?php 
					$message = 'The number of options users will be able to select simultaneously. Use 1 for single-select controls (defaults to 1).';
					wpcb_get_no_of_selection( $args, $values, $message );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Taxonomy field
*/

function wpcb_get_taxonomy_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					wpcb_get_taxonomy_choices( $args, $values );
					?>

					<!-- 
					Selection Limit
					-->

					<?php 
					$message = 'The number of options users will be able to select simultaneously. Use 1 for single-select controls (defaults to 1).';
					wpcb_get_no_of_selection( $args, $values, $message );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Palette field
*/

function wpcb_get_palette_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>"
	data-logic-field="true" 
	data-field-type="<?php echo $field; ?>" 
	data-field-id="<?php echo $random_count; ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$dafault_msg = 'For more control, you may specify both values like this:<br><br>light : #ECEFF1, #333333, #4DD0E1<br>dark : #37474F, #FFFFFF, #F9A825';
					$default_value = "light : #ECEFF1, #333333, #4DD0E1\ndark : #37474F, #FFFFFF, #F9A825"; 
					wpcb_get_choices( $args, $values, $dafault_msg, $default_value, $required = true );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_value_msg = 'eg. light';
					wpcb_get_default_value( $args , $values, 'text' , $default_value_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>	

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Typography field
*/

function wpcb_get_typography_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>


					<!-- 
					Default Value
					-->

					<?php 
					$default_value_msg = 'eg.<br>font-family : Roboto<br>variant : regular<br>font-size : 14px<br>line-height : 1.5<br>letter-spacing : 10<br>subsets  : latin-ext, greek<br>color : #333333<br>text-transform : none<br>text-align : left<br>For more info <a target="_blank" href="https://aristath.github.io/kirki/docs/controls/typography.html">click here</a>';
					wpcb_get_default_value( $args , $values, 'textarea' , $default_value_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>			

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Number field
*/

function wpcb_get_number_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					wpcb_get_default_value( $args , $values );
					?>

					<!-- 
					Minimum Value
					-->

					<?php 
					wpcb_get_min_value( $args , $values );
					?>

					<!-- 
					Maximum Value
					-->

					<?php 
					wpcb_get_max_value( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Slider field
*/

function wpcb_get_slider_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					wpcb_get_default_value( $args , $values );
					?>

					<!-- 
					Minimum Value
					-->

					<?php 
					wpcb_get_min_value( $args , $values );
					?>

					<!-- 
					Maximum Value
					-->

					<?php 
					wpcb_get_max_value( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Multicheck field
*/

function wpcb_get_multicheck_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$dafault_msg = 'Enter each choice on a new line.<br><br>For more control, you may specify both value and label like this:<br><br>option-1 : Option 1<br>option-2 : Option 2';
					$default_value = "option-1 : Option 1\noption-2 : Option 2";
					wpcb_get_choices( $args , $values , $dafault_msg , $default_value , $required = true );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = "Define elements you want activated by default separated by commas. <br>eg <code>option-1, option-3, option-4</code>";
					wpcb_get_default_value( $args , $values , 'text' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>	

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
					?>	

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Switch field
*/

function wpcb_get_switch_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table 
	cellspacing="0" 
	class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>" 
	data-logic-field="true" 
	data-field-type="<?php echo $field; ?>" 
	data-field-id="<?php echo $random_count; ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$dafault_msg = 'You can use this to change the ON/OFF labels.<br><br>1 : Enable<br>0 : Disable';
					$default_value = "1 : ON\n0 : OFF";
					wpcb_get_choices( $args , $values , $dafault_msg, $default_value );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = "Define a default value for this field.<br>1 for ON<br>0 for OFF";
					wpcb_get_default_value( $args , $values , 'text' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get sortable field
*/

function wpcb_get_sortable_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$dafault_msg = 'Enter each choice on a new line.<br><br>For more control, you may specify both a value and label like this:<br><br>option-1 : Option 1<br>option-2 : Option 2';
					$default_value = "option-1 : Option 1\noption-2 : Option 2";
					wpcb_get_choices( $args , $values , $dafault_msg, $default_value, $required = true );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = "Define elements you want activated by default separated by commas. <br>eg <code>option-1, option-3, option-4</code>";
					wpcb_get_default_value( $args , $values , 'text' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>	

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Multicolor field
*/

function wpcb_get_multicolor_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Choices
					-->

					<?php 
					$default_msg = 'Enter each choice on a new line.<br><br>For more control, you may specify both a value and label like this:<br><br>link : Color<br>hover : Hover<br>active : Active';
					$default_value = "link : Color\nhover : Hover\nactive : Active";
					wpcb_get_choices( $args , $values , $default_msg, $default_value, $required = true );
					?>

					<!-- 
					Default Value
					-->

					<?php 
					$default_msg = "Enter each choice on a new line. eg. <br><br>link : #0088cc<br>hover : #00aaff<br>active : #00ffff";
					wpcb_get_default_value( $args , $values , 'textarea' , $default_msg );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Get Image field
*/

function wpcb_get_image_html( $field , $parent_key , $grand_parent_key , $values = null , $random_count = null ){ 

	$args = wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ); ?>

	<table cellspacing="0" class="wpcb_custom_field_wrapper <?php echo ( $values == null ? 'active' : '' ); ?>">
		
		<?php 
		get_fields_headings( $values , $field );
		?>

		<tr style="<?php echo ( $values == null ? '' : 'display:none' ); ?>">
			<td colspan="4" style="padding:0px !important;">
				<table class="field_detail_description" cellspacing="0">
					
					<!-- 
					Field Label
					-->

					<?php 
					wpcb_get_field_label( $args , $values , $field ); 
					?>

					<!-- 
					Field Name
					-->

					<?php 
					wpcb_get_field_name( $args , $values );
					?>

					<!-- 
					Field Instructions
					-->

					<?php 
					wpcb_get_field_instructions( $args , $values );
					?>

					<!-- 
					Tooltip
					-->

					<?php 
					wpcb_get_tooltip( $args , $values );
					?>

					<!-- 
					Option Type
					-->

					<?php 
					wpcb_option_type( $args , $values );
					?>

					<!-- 
					Option Name
					-->

					<?php 
					wpcb_option_name( $args , $values );
					?>

					<!-- 
					Condition Logic
					-->	

					<?php 
					wpcb_condition_logic( $args , $values );
					?>		

					<!-- 
					Transport Logic
					-->	

					<?php 
					wpcb_get_transport( $args , $values ); 
 					?>		

				</table>
			</td>
		</tr>
	</table>

	<?php
}

/**
* Save options to the database
*/

add_action( 'wp_ajax_save_kirki_customizer_builder' , 'save_kirki_customizer_builder' );
function save_kirki_customizer_builder(){

	parse_str( $_POST['data'] , $output );

	if( empty( $output ) || !is_array( $output ) ){
		update_option( 'wpcb_options' , null );
		echo wp_json_encode( array( 'result' => 'success' ) );
		die;
	}

	$filtered_data = wpcb_add_default_names( $output );

	update_option( 'wpcb_options' , $filtered_data );

	// Update customizer status ( published/unpublished )

	$status = ( !empty( $_POST['status'] ) && is_numeric( $_POST['status'] ) ) ? sanitize_text_field( $_POST['status'] ) : 1; 
	update_option( 'wpcb_customizer_status' , $status );

	echo wp_json_encode( array( 'result' => 'success' ) );
	die;

}

/**
* Import options 
*/

add_action( 'wp_ajax_wpcb_import_settings' , 'wpcb_import_settings' );
function wpcb_import_settings(){

	if( empty( $_POST ) || empty( $_POST['import_options'] ) ){
		echo wp_json_encode( array( 'status' => 'error' ) );
		die;
	}
	
	$str = preg_replace('/\\\\\"/',"\"", $_POST['json_data'] );

	if( isJson( $str ) == false ){
		echo wp_json_encode( array( 'status' => 'error' ) );
		die;
	}
	$string = stripslashes($str);
	$json = utf8_encode($string);
	$json = json_decode($json);

	// Convert object to array
	$array = json_decode(json_encode($json), true);

	// Replace all option with new one
	if( $_POST['import_options'] == 1 ){

		update_option( 'wpcb_options', $array );

	} else {

		$db_options = get_option( 'wpcb_options' );

		foreach( $array['fields'] as $key => $value ){

			$db_options['fields'][$key] = $value;

		}

		update_option( 'wpcb_options', $db_options );

	}

	echo wp_json_encode( array( 'status' => 'success' ) );
	die;

}

function isJson($string) {
 	$decode = json_decode($string);
 	$encode = json_encode($decode);
 	
 	if( $encode == 'null' || is_numeric( $encode ) ){
 		return false; // not valid json;
 	}
 	return true; // valid json;
}