<?php

/**
* Enable or disable the customizer fields
* 1 is publish, 2 is unpublished
*/

if( get_option( 'wpcb_customizer_status' , 1 ) == 2 ){
	return;
}

/**
* Initialize kirki's sections and panels functions
*/

add_action( 'init' , 'wpcb_generate_kirki_customizer_fields' , 20 );

function wpcb_generate_kirki_customizer_fields(){

	$data = get_option( 'wpcb_options' );

	if( empty( $data ) || !is_array( $data ) ){
		return;
	}

	$wpcb_option_type = get_option( 'wpcb_option_type' );
	$wpcb_option_name = get_option( 'wpcb_option_name' );

	$my_theme = wp_get_theme();

	$config_args = array(
		'capability'    => 'edit_theme_options',
		'option_type'   => (empty( $wpcb_option_type ) ? 'theme_mod' : sanitize_text_field( $wpcb_option_type )),
	);

	if( $wpcb_option_type != 'theme_mod' && !empty( $wpcb_option_name ) ){

		$config_args['option_name'] = ( empty( $wpcb_option_name ) ? '' : sanitize_text_field( $wpcb_option_name ) );

	}

	Kirki::add_config( 'wpcb_my_config', $config_args );

	foreach( $data as $key => $value ){

		foreach( $value as $key_2 => $value_2 ){

			if( !empty( $value_2['type'] ) && $value_2['type'] == 'panel' ){

				/**
				* Initialize Panel
				*/

				$panel_id = sanitize_text_field( stripslashes($value_2['field_name']) );

				Kirki::add_panel( 
					$panel_id, 
					array(
				    	'priority'    => sanitize_text_field( $value_2['field_order'] ),
				    	'title'       => sanitize_text_field( stripslashes( $value_2['field_label'] ) ),
				    	'description' => sanitize_text_field( stripslashes( $value_2['field_instruction'] ) ),
					) 
				);

				if( !empty( $value_2['sub_fields'] ) && is_array( $value_2['sub_fields'] ) ){

					/**
					* Initialize Section
					*/

					foreach( $value_2['sub_fields'] as $key_3 => $value_3 ){

						if( !empty( $value_3['type'] ) && $value_3['type'] == 'section' ){

							$section_id = sanitize_text_field( stripslashes($value_3['field_name']) );

							Kirki::add_section( 
								$section_id, 
								array(
									'priority'       => sanitize_text_field( $value_3['field_order'] ),
							    	'title'          => sanitize_text_field( stripslashes($value_3['field_label']) ),
							    	'description'    => sanitize_text_field( stripslashes($value_3['field_instruction']) ),
							    	'panel'          => $panel_id,
							    	'type'           => empty( $value_3['field_expand'] ) ? '' : 'expanded', // The section will be always visible
								) 
							);

							if( !empty( $value_3['sub_fields'] ) && is_array( $value_3['sub_fields'] ) ){

								get_customizer_custom_fields( $value_3 , $section_id );

							}

						}

					}

				}

			} else {

				/**
				* Initialize Section
				*/

				$section_id = sanitize_text_field( $value_2['field_name'] );

				Kirki::add_section( 
					$section_id, 
					array(
						'priority'       => sanitize_text_field( $value_2['field_order'] ),
				    	'title'          => sanitize_text_field( stripslashes( $value_2['field_label'] ) ),
				    	'description'    => sanitize_text_field( stripslashes( $value_2['field_instruction'] ) ),
					) 
				);

				if( !empty( $value_2['sub_fields'] ) && is_array( $value_2['sub_fields'] ) ){
					get_customizer_custom_fields( $value_2 , $section_id );
				}

			}

		}

	}

}

/**
* Get kirki's custom fields
*/

function get_customizer_custom_fields( $value , $section_id ){

	foreach( $value['sub_fields'] as $key_custom_field => $value_custom_field ){

		//echo '<pre>'; print_r( $value ); echo '</pre>';
		//echo '<pre>'; print_r( $section_id ); echo '</pre>';

		switch ( $value_custom_field['type'] ) {

			case 'text':
				get_kirki_text_field( $value_custom_field , $section_id );
				break;

			case 'textarea':
				get_kirki_textarea_field( $value_custom_field , $section_id );
				break;

			case 'checkbox':
				get_kirki_checkbox_field( $value_custom_field , $section_id );
				break;

			case 'code':
				get_kirki_code_field( $value_custom_field , $section_id );
				break;

			case 'color':
				get_kirki_color_field( $value_custom_field , $section_id );
				break;

			case 'custom':
				get_kirki_custom_field( $value_custom_field , $section_id );
				break;

			case 'dashicons':
				get_kirki_dashicons_field( $value_custom_field , $section_id );
				break;

			case 'dimension':
				get_kirki_dimension_field( $value_custom_field , $section_id );
				break;

			case 'image':
				get_kirki_image_field( $value_custom_field , $section_id );
				break;

			case 'multicheck':
				get_kirki_multicheck_field( $value_custom_field , $section_id );
				break;

			case 'multicolor':
				get_kirki_multicolor_field( $value_custom_field , $section_id );
				break;

			case 'number':
				get_kirki_number_field( $value_custom_field , $section_id );
				break;

			case 'palette':
				get_kirki_palette_field( $value_custom_field , $section_id );
				break;

			case 'radio-buttonset':
				get_kirki_radio_buttonset_field( $value_custom_field , $section_id );
				break;

			case 'radio-image':
				get_kirki_radio_image_field( $value_custom_field , $section_id );
				break;

			case 'radio':
				get_kirki_radio_field( $value_custom_field , $section_id );
				break;

			case 'select':
				get_kirki_select_field( $value_custom_field , $section_id );
				break;

			case 'taxonomy':
				get_kirki_taxonomy_field( $value_custom_field , $section_id );
				break;

			case 'user':
				get_kirki_user_field( $value_custom_field , $section_id );
				break;

			case 'page-id':
				get_kirki_page_id_field( $value_custom_field , $section_id );
				break;

			case 'slider':
				get_kirki_slider_field( $value_custom_field , $section_id );
				break;

			case 'sortable':
				get_kirki_sortable_field( $value_custom_field , $section_id );
				break;

			case 'spacing':
				get_kirki_spacing_field( $value_custom_field , $section_id );
				break;

			case 'switch':
				get_kirki_switch_field( $value_custom_field , $section_id );
				break;

			case 'toggle':
				get_kirki_toggle_field( $value_custom_field , $section_id );
				break;

			case 'upload':
				get_kirki_upload_field( $value_custom_field , $section_id );
				break;

			case 'typography':
				get_kirki_typography_field( $value_custom_field , $section_id );
				break;
			
			default:
				# code...
				break;

		}

	}

}

function wpcp_get_default_val( $data , $field_type ){

	$multiple = ( !empty( $data['multiple'] ) && is_numeric( $data['multiple'] ) ) ? $data['multiple'] : 1;

	switch ( $field_type ) {

		case 'select':
			
			$default_val = array_values( array_filter( array_map( 'sanitize_text_field' , explode( ',' , $data['field_default_value'] ) ) ) );

			if( count( $default_val ) == 1 && $multiple == 1 ){
				return sanitize_text_field( stripslashes($data['field_default_value']) );
			} else {
				return $default_val;
			}

			break;

		case 'custom':
			return stripslashes( $data['field_default_value'] );
			break;
		
		default:
			return wp_kses_post( stripslashes( $data['field_default_value'] ) );
			break;
	}	

}

function wpcb_remove_empty( $array ) {
  	return array_filter( $array, 'wpcb_remove_empty_internal' );
}

function wpcb_remove_empty_internal( $value ) {
  	return !empty($value) || $value === 0;
}

function get_postmessage( $data ){

	if( !empty( $data['transport_condition'] ) && $data['transport_condition'] == 'postMessage' && is_array( $data['transport_fields'] ) ){

		$js_vars = array();

		foreach( $data['transport_fields'] as $key => $value ){

			$js_vars[$key] = wpcb_remove_empty( $value );

		}
		
		return $js_vars;

	}

	return array();

}


function get_kirki_fields_args( $field_type , $section_id , $data ){

	$unique_id = sanitize_text_field( $data['field_name'] );
	$wpcb_option_type = get_option( 'wpcb_option_type' );

	$args = array(
		'type'     => $field_type,
		'settings' => $unique_id,
		'label'    => sanitize_text_field( stripslashes( $data['field_label'] ) ),
		'section'  => $section_id,
		'description'  => !empty( $data['field_instruction'] ) ? wp_kses_post( stripslashes( $data['field_instruction'] ) ) : '',
		'tooltip' => !empty( $data['field_tooltip'] ) ? sanitize_text_field( stripslashes($data['field_tooltip']) ) : '',
		'default' => !empty( $data['field_default_value'] ) ? wpcp_get_default_val( $data , $field_type ) : '',
		'transport' => ( empty( $data['transport_condition'] ) || $data['transport_condition'] == 'refresh' ) ? 'refresh' : 'postMessage',
		'js_vars' => get_postmessage( $data )
	);

	if( !empty( $data['field_option_type'] ) && $data['field_option_type'] == 'option' ){

		//if( $data['field_option_type'] != $wpcb_option_type ){

			$args['option_type'] = sanitize_text_field( $data['field_option_type'] );
			$args['option_name'] = sanitize_text_field( $data['field_option_name'] );

		//}

	} elseif( !empty( $data['field_option_type'] ) && $data['field_option_type'] == 'theme_mod' ){

		$args['option_type'] = sanitize_text_field( $data['field_option_type'] );

	}

	$args['active_callback'] = wpcb_check_condition_logic( $data );

	//echo '<pre>'; print_r($args); echo '</pre>';
	return array( 
		'args' => $args, 
		'unique_id' => $unique_id 
	);

}

function wpcb_check_condition_logic( $data ){

	if( empty( $data['condition'] ) || $data['condition'] != 'yes' ){
		return;
	}

	if( empty( $data['condition_logic']['rules'] ) || !is_array( $data['condition_logic']['rules'] ) ){
		return;
	}

	$condition_logic = array();
	foreach( $data['condition_logic']['rules'] as $key => $value ){

		$condition_logic[] = array(
			'setting' => sanitize_text_field( $value['field'] ),
			'operator' => sanitize_text_field( $value['operator'] ),
			'value' =>  (!empty( $value['value'] ) ? sanitize_text_field( $value['value'] ) : 0),
		);

	}

	return $condition_logic;

}


function wpcb_call_kirki_add_field( $content ){

	Kirki::add_field( 
		'wpcb_my_config', 
		apply_filters( 'wpcb_custom_field_' . $content['unique_id'] , $content['args'] )
	);

}

function get_kirki_text_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'text' , $section_id , $data );
	//echo '<pre>'; print_r($content); echo '</pre>';
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_radio_buttonset_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'radio-buttonset' , $section_id , $data );

	/**
	* Get choices into array
	*/

	$choicesArray = explode(PHP_EOL, $data['choices'] );
	//echo '<pre>'; print_r($choicesArray); echo '</pre>';

	if( empty( $choicesArray ) || !is_array( $choicesArray ) ){
		// Do nothing
	} else {

		$explode_key_value = array();
		
		$kirki_choices = array();

		foreach( $choicesArray as $value ){

			$explode_key_value = explode( ':' , $value );

			if( !empty( $explode_key_value[0] ) && !empty( $explode_key_value[1] ) ){
				
				$kirki_key =  sanitize_text_field( $explode_key_value[0] );
				$kirki_value = sanitize_text_field( $explode_key_value[1] );

				$kirki_choices[ $kirki_key ] = $kirki_value;

			}			

		}

		$content['args']['choices'] = $kirki_choices;

	}

	//echo '<pre>'; print_r($content); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_radio_image_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'radio-image' , $section_id , $data );

	/**
	* Get choices into array
	*/

	$content['args']['choices'] = wpcb_choices_array( $data['choices'] );

	//echo '<pre>'; print_r($content); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_radio_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'radio' , $section_id , $data );

	/**
	* Get choices into array
	*/

	$content['args']['choices'] = wpcb_choices_array( $data['choices'] );

	//echo '<pre>'; print_r($content); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_select_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'select' , $section_id , $data );

	/**
	* Get choices into array
	*/

	$content['args']['choices'] = wpcb_choices_array( $data['choices'] );

	$content['args']['multiple'] = is_numeric( $data['multiple'] ) ? sanitize_text_field( $data['multiple'] ) : 1;

	//echo '<pre>'; print_r($content); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_taxonomy_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'select' , $section_id , $data );

	/**
	* Get choices into array
	*/

	$choices = array();
	if( !empty( $data['taxonomy'] ) && is_array( $data['taxonomy'] ) ){

		foreach( $data['taxonomy'] as $taxonomy ){

			$terms = get_terms( 
				array(
			    	'taxonomy' => $taxonomy,
			    	'hide_empty' => false,
				) 
			);

			if( !empty( $terms ) && is_array( $terms ) ){

				foreach( $terms as $term ){

					$choices[$term->term_id] = '('. $taxonomy .') ' . $term->name;

				}

			}

			//echo '<pre>'; print_r( $choices ); echo '</pre>';

		}

	}

	$content['args']['choices'] = $choices;
	$content['args']['default'] = '';
	//print_r($data['taxonomy']);

	$content['args']['multiple'] = is_numeric( $data['multiple'] ) ? sanitize_text_field( $data['multiple'] ) : 1;

	//echo '<pre>'; print_r($content); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_user_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'select' , $section_id , $data );

	/**
	* Get choices into array
	*/

	$choices = array();
	//echo '<pre>'; print_r( $data['multiple_choices'] ); echo '</pre>';
	if( !empty( $data['multiple_choices'] ) && is_array( $data['multiple_choices'] ) ){

		foreach( $data['multiple_choices'] as $key => $value ){

			$args = array( 'role' => $value );
			$blogusers = get_users( $args );

			if( !empty( $blogusers ) && is_array( $blogusers ) ){

				foreach( $blogusers as $user ){

					$choices[$user->data->ID] = $user->data->user_login; 

				}
				
			}

		}

	}

	//echo '<pre>'; print_r( $choices ); echo '</pre>';

	$content['args']['choices'] = $choices;
	$content['args']['default'] = '';
	//print_r($data['taxonomy']);

	$content['args']['multiple'] = is_numeric( $data['multiple'] ) ? sanitize_text_field( $data['multiple'] ) : 1;

	//echo '<pre>'; print_r($content); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function wpcb_filter_taxonomies($categories){

	$tax_query = array();

	if( !empty( $categories ) && is_array( $categories ) ){

		$data = array();
		foreach( $categories as $cat ){

			$break_tax = explode( ':' , $cat );

			$post_type = $break_tax[0] . ':' . $break_tax[1];

			$data[$post_type]['field'] = 'slug';


			if( !array_key_exists( 'terms' , $data[$post_type] ) ){
				$data[$post_type]['terms'] = array();
			}

			array_push( $data[$post_type]['terms'] , $break_tax[2] );

		}

		//$tax_query['relation'] = 'AND';

		foreach( $data as $key => $value ){

			$data_2 = array();
			$explode_tax = explode( ':' , $key );
			
			$data_2['taxonomy'] = $explode_tax[1];
			$data_2['field'] = 'slug';
			$data_2['terms'] =  $value['terms'];
			$data_2['post_type'] =  $explode_tax[0];

			array_push( $tax_query , $data_2 );

		}

		//echo '<pre>'; print_r( $tax_query ); echo '</pre>';

	}

	return $tax_query;

}

function wpcb_select_only_related_taxonomy( $taxomonies_filter , $post_type ){

	$tax_query = array();
	$tax_query['relation'] = 'AND';

	foreach( $taxomonies_filter as $key => $value ){

		if( is_array( $value ) && $value['post_type'] == $post_type ){

			array_push( $tax_query , $value );

		}

	}

	return $tax_query;
	//echo '<pre>'; print_r( $tax_query ); echo '</pre>';

}

function wpcb_get_selected_posts( $selected_posts , $categories ){

	$taxomonies_filter = wpcb_filter_taxonomies($categories);

	$allposts = array();
	foreach( $selected_posts as $value ){

		$taxonomies = wpcb_select_only_related_taxonomy( $taxomonies_filter , $value );

		$args = array( 
			'posts_per_page' => -1 , 
			'post_type' => $value,
			'post_status' => ( $value == 'attachment' ? 'inherit' : 'publish' ),
			'tax_query' => $taxonomies
		);

		$query = new WP_Query( $args );
		//echo '<pre>'; print_r( $args ); echo '</pre>';

		if( $query->have_posts() ):

			while( $query->have_posts() ): $query->the_post();

				global $post;

				$allposts[$post->ID] = '(' . ucfirst( $value ) . ') ' . $post->post_title;

			endwhile;

		endif;

	}
	//echo '<pre>'; print_r( $allposts ); echo '</pre>';
	return $allposts;

}

function get_kirki_page_id_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'select' , $section_id , $data );

	if( !array_key_exists( 'filter_taxonomy' , $data ) ){
		$data['filter_taxonomy'] = array();
	}

	//echo '<pre>'; print_r( $data['multiple_choices'] ); echo '</pre>';
	$allposts = array();
	if( !empty( $data['multiple_choices'] ) && is_array( $data['multiple_choices'] ) ){

		if( in_array( 'all' , $data['multiple_choices'] ) ){

			foreach( wpcb_get_all_post_types() as $key => $value ){
				$allposts[] = $key;
			}

			$content['args']['choices'] = wpcb_get_selected_posts( $allposts , $data['filter_taxonomy'] );

		} else{

			$content['args']['choices'] = wpcb_get_selected_posts( $data['multiple_choices'] , $data['filter_taxonomy'] );

		}

	}

	$content['args']['multiple'] = is_numeric( $data['multiple'] ) ? sanitize_text_field( $data['multiple'] ) : 1;

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_number_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'number' , $section_id , $data );

	$content['args']['choices']['min'] = ( is_numeric( $data['min_val'] ) ? sanitize_text_field( $data['min_val'] ) : '' );
	$content['args']['choices']['max'] = ( is_numeric( $data['max_val'] ) ? sanitize_text_field( $data['max_val'] ) : '' );

	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_slider_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'slider' , $section_id , $data );

	$content['args']['choices']['min'] = ( is_numeric( $data['min_val'] ) ? sanitize_text_field( $data['min_val'] ) : '' );
	$content['args']['choices']['max'] = ( is_numeric( $data['max_val'] ) ? sanitize_text_field( $data['max_val'] ) : '' );

	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function wpcb_default_value_array( $default_value ){

	$defaultArray = explode( ',', $default_value );

	if( !empty( $defaultArray ) && is_array( $defaultArray ) ){

		$kirki_default = array();
		foreach( $defaultArray as $value ){

			$kirki_default[] = sanitize_text_field( $value );

		}
		//print_r( $kirki_default );
		return array_values( array_filter( $kirki_default ) );

	}

	return array_values( array_filter( $default_value ) );

}

function wpcb_choices_array( $choices ){

	$choicesArray = explode( PHP_EOL, $choices );

	if( empty( $choicesArray ) || !is_array( $choicesArray ) ){
		return null;
	} else {

		$explode_key_value = array();
		
		$kirki_choices = array();

		foreach( $choicesArray as $value ){

			$explode_key_value = explode( ':' , $value , 2 );

			if( !empty( $explode_key_value[0] ) && !empty( $explode_key_value[1] ) ){
				
				$kirki_key =  sanitize_text_field( $explode_key_value[0] );
				$kirki_value =  str_replace( '[template_url]' , get_template_directory_uri() , sanitize_text_field( $explode_key_value[1] ) );

				$kirki_choices[ $kirki_key ] = $kirki_value;

			}			

		}

		return $kirki_choices;

	}

}

function get_kirki_multicheck_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'multicheck' , $section_id , $data );
	
	/**
	* Get choices into array
	*/

	$content['args']['choices'] = wpcb_choices_array( $data['choices'] );

	/**
	* Get Default values into array
	*/

	$content['args']['default'] = wpcb_default_value_array( $data['field_default_value'] );


	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_sortable_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'sortable' , $section_id , $data );
	
	/**
	* Get choices into array
	*/

	$content['args']['choices'] = wpcb_choices_array( $data['choices'] );

	/**
	* Get Default values into array
	*/

	$content['args']['default'] = wpcb_default_value_array( $data['field_default_value'] );

	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_spacing_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'spacing' , $section_id , $data );

	/**
	* Get Default values into array
	*/

	$content['args']['default'] = wpcb_choices_array( $data['field_default_value'] );

	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_switch_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'switch' , $section_id , $data );

	/**
	* Get Default values into array
	*/

	$content['args']['choices'] = wpcb_choices_array( $data['choices'] );

	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_toggle_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'toggle' , $section_id , $data );

	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_upload_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'upload' , $section_id , $data );

	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_multicolor_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'multicolor' , $section_id , $data );
	
	/**
	* Get choices into array
	*/

	$choicesArray = explode(PHP_EOL, $data['choices'] );

	if( empty( $choicesArray ) || !is_array( $choicesArray ) ){
		// Do nothing
	} else {
		
		$kirki_choices = array();

		foreach( $choicesArray as $value ){

			$explode_key_value = array_map( 'sanitize_text_field' , explode( ':' , $value ) );

			if( empty( $explode_key_value[0] ) || empty( $explode_key_value[1] ) ){
				// Do nothing
			} else {
				
				$kirki_key =  sanitize_text_field( $explode_key_value[0] );
				$kirki_value = sanitize_text_field( $explode_key_value[1] );

				$kirki_choices[ $kirki_key ] = $kirki_value;

			}			

		}

		$content['args']['choices'] = $kirki_choices;

	}

	/**
	* Get Default values into array
	*/

	$defaultArray = explode(PHP_EOL, $data['field_default_value'] );
	$kirki_default = array();

	if( empty( $defaultArray[0] ) || !is_array( $defaultArray ) ){
		// Do nothing
	} else {
		
		foreach( $defaultArray as $value ){

			$explode_key_value = array_map( 'sanitize_text_field' , explode( ':' , $value ) );

			if( empty( $explode_key_value[0] ) || empty( $explode_key_value[1] ) ){
				// Do Nothing
			} else {
				
				$kirki_key =  sanitize_text_field( $explode_key_value[0] );
				$kirki_value = sanitize_text_field( $explode_key_value[1] );

				$kirki_default[ $kirki_key ] = $kirki_value;

			}			

		}

		$content['args']['default'] = $kirki_default;

	} 

	if( !empty( $kirki_choices ) && is_array( $kirki_choices ) && empty( $kirki_default ) ){

		foreach( $kirki_choices as $key => $value ){

			$kirki_default[$key] = '#fff';

		}

		$content['args']['default'] = $kirki_default;

	}

	

	//echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}

function get_kirki_palette_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'palette' , $section_id , $data );

	/**
	* Get choices into array
	*/

	$choicesArray = explode(PHP_EOL, $data['choices'] );

	if( empty( $choicesArray ) || !is_array( $choicesArray ) ){
		// Do nothing
	} else {
		
		$kirki_choices = array();

		foreach( $choicesArray as $value ){

			$explode_key_value = array_map( 'sanitize_text_field' , explode( ':' , $value ) );

			//echo '<pre>'; print_r($explode_key_value); echo '</pre>';

			if( empty( $explode_key_value[0] ) || empty( $explode_key_value[1] ) ){
				// Do not do anything
			} else {

				$kirki_key = sanitize_text_field( $explode_key_value[0] );
				//echo $explode_key_value[1];
				$kirki_value = array_map( 'sanitize_text_field' , explode( ',' , $explode_key_value[1] ) );

				$kirki_choices[ $kirki_key ] = $kirki_value;

			}

			//echo '<pre>'; print_r($kirki_choices); echo '</pre>';
		}

		$content['args']['choices'] = $kirki_choices;

	}
	//echo '<pre>'; print_r( $content ); echo '</pre>';
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_typography_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'typography' , $section_id , $data );

	/**
	* Get choices into array
	*/

	$choicesArray = explode(PHP_EOL, $data['field_default_value'] );

	if( empty( $choicesArray ) || !is_array( $choicesArray ) ){
		// Do nothing
	} else {
		
		$kirki_choices = array();

		foreach( $choicesArray as $value ){

			$explode_key_value = array_map( 'sanitize_text_field' , explode( ':' , $value ) );

			//echo '<pre>'; print_r($explode_key_value); echo '</pre>';

			if( empty( $explode_key_value[0] ) || empty( $explode_key_value[1] ) ){
				// Do not do anything
			} else {

				$kirki_key = sanitize_text_field( $explode_key_value[0] );
				
				// Save as array
				$kirki_value = array_map( 'sanitize_text_field' , explode( ',' , $explode_key_value[1] ) );

				// Save as string
				if( count( $kirki_value ) < 2 ){
					$kirki_value = sanitize_text_field( $explode_key_value[1] );
				}

				$kirki_choices[ $kirki_key ] = $kirki_value;

			}

			//echo '<pre>'; print_r($data); echo '</pre>';
		}

		$content['args']['default'] = $kirki_choices;

	}
	//echo '<pre>'; print_r( $content ); echo '</pre>';
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_image_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'image' , $section_id , $data );
	//echo '<pre>'; print_r($content); echo '</pre>';
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_dashicons_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'dashicons' , $section_id , $data );
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_dimension_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'dimension' , $section_id , $data );
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_custom_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'custom' , $section_id , $data );
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_color_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'color' , $section_id , $data );
	$content['args']['alpha'] = true;
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_textarea_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'textarea' , $section_id , $data );
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_checkbox_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'checkbox' , $section_id , $data );
	//echo '<pre>'; print_r( $content ); echo '</pre>';
	wpcb_call_kirki_add_field( $content );

}

function get_kirki_code_field( $data , $section_id ){

	$content = get_kirki_fields_args( $field_type = 'code' , $section_id , $data );

	// $content['args']['choices']['language'] = sanitize_text_field( $data['code_language'] );
	// $content['args']['choices']['theme'] = sanitize_text_field( $data['code_theme'] );
	// $content['args']['choices']['height'] = 250;

	// echo '<pre>'; print_r( $content ); echo '</pre>';

	wpcb_call_kirki_add_field( $content );

}