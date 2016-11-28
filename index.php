<?php
/*
Plugin Name: Visual Customizer Builder PRO
Description: Create customizer fields with drag and drop options and with 27 custom fields.
Version: 0.1
Author: Ravi Shakya
Author URI: http://www.ravishakya.com.np/
*/

/**
* Dismiss admin notice of kirki installation 
*/

add_action( 'wp_ajax_dismiss_kirki_notice' , 'dismiss_kirki_notice' );
function dismiss_kirki_notice(){

	if( empty( $_POST ) ){
		echo wp_json_encode( array( 'status' => 'error' ) );
		die;
	}

	update_option( 'wpcb_kirki_notice' , true );
	echo wp_json_encode( array( 'status' => 'success' ) );
	die;

}

/**
* Set defaults value on activation
*/

register_activation_hook( __FILE__ , 'wpcb_registration_hook' );
function wpcb_registration_hook(){
	update_option( 'wpcb_kirki_notice' , false );
	update_option( 'wpcb_option_type' , 'theme_mod' );
	update_option( 'wpcb_customizer_status' , true );
}

// No need to proceed if Kirki is not installed.
if( !class_exists( 'Kirki' ) ){
	add_action( 'admin_notices', 'wpcb_check_kirki' );
	return;
}

// If already installed do not proceed below
if( !function_exists( 'wpcb_scripts_method' ) ){
	return;
}

include 'ajax.php'; // All ajax functions
include 'backend-options.php'; // Backend Options
include 'kirki.php'; // Initialize kirki options

/**
* Include necessary scripts and styles
*/

add_action( 'admin_enqueue_scripts', 'wpcb_scripts_method' );
function wpcb_scripts_method() {

	if( !empty( $_GET['page'] ) && ( $_GET['page'] == 'wpcb_builder' || $_GET['page'] == 'vcbs' ) ){
		wp_enqueue_style( 'wpcb_style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array() );
    	wp_register_script( 'wpcb_scripts', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array() );
    	wp_enqueue_script( 'jquery-ui-sortable' );

    	// Localize the script with new data
		$translation_array = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
		wp_localize_script( 'wpcb_scripts', 'wpcb', $translation_array );
		wp_enqueue_script( 'wpcb_scripts' );

	}
    
}

function wpcb_get_field_names( $random_count , $parent_key , $grand_parent_key ){

	if( $random_count == null ){
		$random_count = wp_generate_password( $length = 20, false, false );
	}

	$args = array();

	if( !empty( $parent_key ) && !empty( $grand_parent_key ) ){

		$args['field_label'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][field_label]';

		$args['field_status'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][type]';

		$args['field_name'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][field_name]';

		$args['field_instruction'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][field_instruction]';

		$args['field_tooltip'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][field_tooltip]';

		$args['field_option_type'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][field_option_type]';

		$args['field_option_name'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][field_option_name]';

		$args['field_default_value'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][field_default_value]';

		$args['field_order'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][field_order]';

		$args['choices'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][choices]';
		$args['multiple_choices'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][multiple_choices][]';

		$args['filter_taxonomy'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][filter_taxonomy][]';

		$args['min_val'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][min_val]';

		$args['max_val'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][max_val]';

		$args['multiple'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][multiple]';

		$args['taxonomy'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][taxonomy][]';

		$args['condition'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][condition]';

		$args['condition_logic'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][condition_logic][rules][0][field]';

		$args['condition_operator'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][condition_logic][rules][0][operator]';

		$args['condition_value'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][condition_logic][rules][0][value]';

		$args['condition_logic_keys'] = '<input class="condition_logic_keys" type="hidden" data-first-part="' . 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][condition_logic][rules]">';

		$args['transport_condition'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_condition]';

		/* Begin Transport Fields */

		$args['transport_fields_element'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][element]';

		$args['transport_fields_function'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][function]';

		$args['transport_fields_property'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][property]';

		$args['transport_fields_units'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][units]';
		
		$args['transport_fields_prefix'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][prefix]';
		
		$args['transport_fields_suffix'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][suffix]';

		$args['transport_fields_attr'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][attr]';

		$args['transport_fields_first_part'] = 'fields[' . $grand_parent_key . '][sub_fields][' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields]';

		/* End Transport Fields */

	} else {

		$args['field_label'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][field_label]';

		$args['field_status'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][type]';

		$args['field_name'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][field_name]';

		$args['field_instruction'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][field_instruction]';

		$args['field_tooltip'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][field_tooltip]';

		$args['field_option_type'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][field_option_type]';

		$args['field_option_name'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][field_option_name]';

		$args['field_default_value'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][field_default_value]';

		$args['field_order'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][field_order]';

		$args['choices'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][choices]';
		$args['multiple_choices'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][multiple_choices][]';

		$args['filter_taxonomy'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][filter_taxonomy][]';

		$args['min_val'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][min_val]';

		$args['max_val'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][max_val]';

		$args['multiple'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][multiple]';

		$args['taxonomy'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][taxonomy][]';

		$args['condition'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][condition]';

		$args['condition_logic'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][condition_logic][rules][0][field]';

		$args['condition_operator'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][condition_logic][rules][0][operator]';

		$args['condition_value'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][condition_logic][rules][0][value]';

		$args['condition_logic_keys'] = '<input class="condition_logic_keys" type="hidden" data-first-part="' . 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][condition_logic][rules]">';

		$args['transport_condition'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_condition]';

		/* Begin Transport Fields */

		$args['transport_fields_element'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][element]';
		$args['transport_fields_function'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][function]';
		$args['transport_fields_property'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][property]';
		$args['transport_fields_units'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][units]';
		$args['transport_fields_prefix'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][prefix]';
		$args['transport_fields_suffix'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][suffix]';
		$args['transport_fields_attr'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields][0][attr]';

		$args['transport_fields_first_part'] = 'fields[' . $parent_key . '][sub_fields][' . $random_count . '][transport_fields]';

		/* End Transport Fields */
 

	}

	return $args;

}

function wpcb_get_field_label( $args , $values , $field ){ ?>

	<tr>

		<td>
			<strong><?php esc_html_e( 'Field Label' , 'wpcb' ); ?> <span class="required">*</span></strong> 
			<p class="description">
				<?php 
				esc_html_e( 'This is the panel name which will be displayed on the customizer.' , 'wpcb' ); 
				?>
			</p>
		</td>

		<td>
			
			<input 
			autocomplete="off" 
			type="text" 
			name="<?php echo $args['field_label']; ?>" 
			class="field_label"
			value="<?php echo ( empty( $values['field_label'] ) ? '' : esc_html( stripslashes($values['field_label']) ) ); ?>">
			
			<input type="hidden" value="<?php echo $field; ?>" name="<?php echo stripslashes( $args['field_status'] ); ?>" />
			<input type="hidden" name="<?php echo sanitize_text_field( $args['field_order'] ); ?>" value="" class="field_order" />
			
		</td>

	</tr>

	<?php

}

function wpcb_get_no_of_selection( $args , $values, $message = mull ){ ?>

	<tr>

		<td>
			<strong><?php esc_html_e( 'No of selctions' , 'wpcb' ); ?></strong> 
			<p class="description">
				<?php 
				echo $message; 
				?>
			</p>
		</td>

		<td>
			
			<input 
			type="number" 
			name="<?php echo $args['multiple']; ?>" 
			class="field_multiple"
			value="<?php echo ( empty( $values['multiple'] ) ? '' : esc_html( stripslashes( $values['multiple'] ) ) ); ?>">
			
		</td>

	</tr>

	<?php

}

function wpcb_get_field_name( $args , $values ){ ?>

	<tr>
		<td>
			<strong>
				<?php 
				esc_html_e( 'Field Name' , 'wpcb' ); ?> <span class="required">*</span>
			</strong> 
			<p class="description">
				<?php 
				esc_html_e( 'Single word, no spaces. Underscores and dashes allowed' , 'wpcb' ); 
				?>								
			</p>
		</td>
		<td>
			<input 
			type="text" 
			autocomplete="off" 
			name="<?php echo $args['field_name']; ?>" 
			class="field_name" 
			value="<?php echo ( empty( $values['field_name'] ) ? '' : esc_html( stripslashes( $values['field_name'] ) ) ); ?>">
		</td>
	</tr>

	<?php
}

function wpcb_get_field_instructions( $args , $values ){ ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Field Instructions' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php esc_html_e( 'Instructions for authors. Shown when submitting data' , 'wpcb' ); ?>		
			</p>
		</td>
		<td>
			<textarea name="<?php echo $args['field_instruction']; ?>" class="field_instruction"><?php echo ( empty( $values['field_instruction'] ) ? '' : esc_html( stripslashes( $values['field_instruction'] ) ) ); ?></textarea>
		</td>
	</tr>

	<?php
}

function get_languages_code(){

	$languages = array(
		'apl' => 'APL',
		'asterisk' => 'Asterisk dialplan',
		'clike' => 'C, C++, C#',
		'clojure' => 'Clojure',
		'cobol' => 'COBOL',
		'coffeescript' => 'CoffeeScript',
		'commonlisp' => 'Common Lisp',
		'css' => 'CSS'
	);

	return $languages;

}

function wpcb_get_code_languages( $args , $values ){ 

	$selected = empty( $values['code_language'] ) ? '' : esc_html( $values['code_language'] ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Language' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php 								
				esc_html_e( 'Set a language for this field.' , 'wpcb' );									
				?>		
			</p>
		</td>
		<td>

			<select name="<?php echo $args['code_language']; ?>">

				<?php
				foreach( get_languages_code() as $key => $value ){

					echo '<option ';
					selected( $selected, $key );
					echo 'value="' . $key . '">' . $value . '</option>';

				}
				?>

			</select>

		</td>
	</tr>

	<?php
}

function wpcb_get_codemirror_themes(){

	return array(
	    'default',
	    '3024-day',
	    '3024-night',
	    'abcdef',
	    'ambiance',
	    'base16-dark',
	    'base16-light',
	    'bespin',
	    'blackboard',
	    'cobalt',
	    'colorforth',
	    'dracula',
	    'eclipse',
	    'elegant',
	    'erlang-dark',
	    'hopscotch',
	    'icecoder',
	    'isotope',
	    'lesser-dark',
	    'liquibyte',
	    'material',
	    'mbo',
	    'mdn-like',
	    'midnight',
	    'monokai',
	    'neat',
	    'neo',
	    'night',
	    'panda-syntax',
	    'paraiso-dark',
	    'paraiso-light',
	    'pastel-on-dark',
	    'railscasts',
	    'rubyblue',
	    'seti',
	    'solarized dark',
	    'solarized light',
	    'the-matrix',
	    'tomorrow-night-bright',
	    'tomorrow-night-eighties',
	    'ttcn',
	    'twilight',
	    'vibrant-ink',
	    'xq-dark',
	    'xq-light',
	    'yeti',
	    'zenburn'
	);

}

function wpcb_get_code_themes( $args , $values ){ 

	$selected = empty( $values['code_theme'] ) ? '' : esc_html( $values['code_theme'] ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Theme' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php 								
				esc_html_e( 'Set a theme for this field.' , 'wpcb' );									
				?>		
			</p>
		</td>
		<td>

			<select name="<?php echo $args['code_theme']; ?>">

				<?php
				foreach( wpcb_get_codemirror_themes() as $value ){

					echo '<option ';
					selected( $selected, $value );
					echo 'value="' . $value . '">' . $value . '</option>';

				}
				?>

			</select>

		</td>
	</tr>

	<?php
}

function wpcb_get_max_value( $args , $values ){ 

	$db_field_maxval = ( !empty( $values['max_val'] ) ? esc_html( stripslashes( $values['max_val'] ) ) : '' ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Maximum Value' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php 								
				esc_html_e( 'Set a maximum value for this field.' , 'wpcb' );								
				?>		
			</p>
		</td>
		<td>
			<input type="number" name="<?php echo $args['max_val']; ?>" class="field_max_val" value="<?php echo $db_field_maxval; ?>">
		</td>
	</tr>

	<?php
}


function wpcb_get_min_value( $args , $values ){ 

	$db_field_minval = ( !empty( $values['min_val'] ) ? esc_html( stripslashes( $values['min_val'] ) ) : '' ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Minimum Value' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php 								
				esc_html_e( 'Set a minimum value for this field.' , 'wpcb' );								
				?>		
			</p>
		</td>
		<td>
			<input type="number" name="<?php echo $args['min_val']; ?>" class="field_min_val" value="<?php echo $db_field_minval; ?>">
		</td>
	</tr>

	<?php
}

function wpcb_get_default_value( $args , $values , $default_field = 'text' , $message = null , $default_value = null, $required = false ){

	$db_field_default_value = ( !empty( $values['field_default_value'] ) ? esc_html( stripslashes( $values['field_default_value'] ) ) : '' ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Default Value' , 'wpcb' ); ?>
				<span class="required"><?php echo ( $required == true ? '*' : '' ); ?></span>								
			</strong> 
			<p class="description">
				<?php 								
				esc_html_e( 'Set a default value for this field.' , 'wpcb' );	

				if( !empty( $message ) ){

					echo '<br><p class="description">' . $message . '</p>';

				}								
				?>		
			</p>
		</td>
		<td>

			<?php 

			switch ( $default_field ) {

				case 'text': ?>
					
					<input 
					autocomplete="off"
					type="text" 
					name="<?php echo $args['field_default_value']; ?>" 
					class="field_default_value"
					value="<?php echo ( !empty( $db_field_default_value ) ? $db_field_default_value : $default_value ); ?>">

					<?php

					break;

				case 'textarea': ?>

					<textarea 
					autocomplete="off" 
					style="height:190px;" 
					name="<?php echo $args['field_default_value']; ?>" 
					class="field_default_value"><?php echo ( !empty( $db_field_default_value ) ? $db_field_default_value : $default_value ); ?></textarea>

					<?php

					break;
				
				default:
					# code...
					break;
			} ?>

			
		</td>
	</tr>

	<?php

}

function wpcb_get_default_value_checkbox( $args , $values ){

	$db_field_default_value = ( !empty( $values['field_default_value'] ) ? esc_html( stripslashes( $values['field_default_value'] ) ) : '' ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Default Value' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php 								
				esc_html_e( 'Set a default value for this field.' , 'wpcb' );									
				?>		
			</p>
		</td>
		<td>
			<select name="<?php echo $args['field_default_value']; ?> class="field_default_value_checkbox" autocomplete="off">
				<option value="0" <?php selected( $db_field_default_value , '0' ); ?>>False</option>
				<option value="1" <?php selected( $db_field_default_value , '1' ); ?>>True</option>
			</select>
		</td>
	</tr>

	<?php

}

function wpcb_get_tooltip( $args , $values ){ ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Tooltip' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php esc_html_e( 'Add a localized string to show an informative tooltip.' , 'wpcb' ); ?>		
			</p>
		</td>
		<td>
			<input type="text" 
			name="<?php echo $args['field_tooltip']; ?>" 
			class="field_tooltip" 
			value="<?php echo ( empty( $values['field_tooltip'] ) ? '' : esc_html( stripslashes( $values['field_tooltip'] ) ) ); ?>">
		</td>
	</tr>

	<?php
}

function wpcb_get_transport( $args , $values ){ 

	$count_transport_field = empty( $values['transport_fields'] ) ? 1 : count( $values['transport_fields'] ); 

	$chceked = empty( $values['transport_condition'] ) ? 'refresh' : $values['transport_condition']; ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Transport ' , 'wpcb' ); ?>									
			</strong> 
		</td>
		<td>

			<ul class="condition_transport_wrapper">
				<li>
					<label>

						<input 
						name="<?php echo $args['transport_condition']; ?>" 
						value="refresh" 
						autocomplete="off" 
						<?php checked( $chceked , 'refresh' ); ?> 
						type="radio">
						Refresh

					</label>
				</li>
				<li>
					<label>

						<input 
						name="<?php echo $args['transport_condition']; ?>" 
						value="postMessage" 
						autocomplete="off" 
						<?php checked( $chceked , 'postMessage' ); ?> 
						type="radio">
						PostMessage

					</label>
				</li>
			</ul>

			<div 
			class="field_transport" 
			data-first-type="<?php echo $args['transport_fields_first_part']; ?>"
			style="<?php echo ( $chceked == 'refresh' ? 'display:none' : '' ); ?>">

				<?php 
				if( $count_transport_field > 1 ){
					get_all_field_transport( $args , $values );
				} else {
					get_single_field_transport( $args , $values );
				} ?>
				
			</div>
		</td>
	</tr>

	<?php
}

function get_all_field_transport( $args , $fields ){

	$count = 0;
	foreach( $fields['transport_fields'] as $value ){ ?>

		<div class="field_transport_wrapper">
			
			<ul>

				<li>
					<input 
					type="text" 
					autocomplete="off" 
					placeholder="Element" 
					class="transport_element" 
					value="<?php echo esc_html( $value['element'] );?>" 
					name="<?php echo $args['transport_fields_first_part'] . '['. $count ."][element]"; ?>">
				</li>

				<li>
					<input 
					type="text" 
					autocomplete="off" 
					placeholder="Function" 
					class="transport_function" 
					value="<?php echo esc_html( $value['function'] );?>" 
					name="<?php echo $args['transport_fields_first_part'] . '['. $count ."][function]"; ?>">
				</li>

				<li>
					<input 
					type="text" 
					autocomplete="off" 
					placeholder="Property" 
					class="transport_property" 
					value="<?php echo esc_html( $value['property'] );?>" 
					name="<?php echo $args['transport_fields_first_part'] . '['. $count ."][property]"; ?>">
				</li>

				<li>
					<input 
					type="text" 
					autocomplete="off" 
					placeholder="Units" 
					class="transport_units" 
					value="<?php echo esc_html( $value['units'] );?>" 
					name="<?php echo $args['transport_fields_first_part'] . '['. $count ."][units]"; ?>">
				</li>

				<li>
					<input 
					type="text" 
					autocomplete="off" 
					placeholder="Prefix" 
					class="transport_prefix" 
					value="<?php echo esc_html( $value['prefix'] );?>" 
					name="<?php echo $args['transport_fields_first_part'] . '['. $count ."][prefix]"; ?>">
				</li>

				<li>
					<input 
					type="text" 
					autocomplete="off" 
					placeholder="Suffix" 
					class="transport_suffix" 
					value="<?php echo esc_html( $value['suffix'] );?>" 
					name="<?php echo $args['transport_fields_first_part'] . '['. $count ."][suffix]"; ?>">
				</li>

				<li>
					<input 
					type="text" 
					autocomplete="off" 
					placeholder="Attr" 
					class="transport_attr" 
					value="<?php echo esc_html( $value['attr'] );?>" 
					name="<?php echo $args['transport_fields_first_part'] . '['. $count ."][attr]"; ?>">
				</li>

			</ul>

			<ul class="transport_buttons">
				<li>
					<a 
					href="javascript:void(0)" 
					class="remove_transport"></a>
				</li>
				<li><a href="javascript:void(0)" class="add_transport"></a></li>
			</ul>

		</div>

		<?php

		$count++;

	}

}

function get_single_field_transport( $args , $values ){ ?>
	
	<div class="field_transport_wrapper">
		<ul>
			<li>
				<input 
				type="text" 
				autocomplete="off" 
				placeholder="Element" 
				class="transport_element" 
				value="<?php echo ( !empty( $values['transport_fields'][0]['element'] ) ? esc_html( $values['transport_fields'][0]['element'] ) : '' ); ?>" 
				name="<?php echo $args['transport_fields_element']; ?>">
			</li>
			<li>
				<input 
				type="text" 
				autocomplete="off" 
				placeholder="Function" 
				class="transport_function" 
				value="<?php echo ( !empty( $values['transport_fields'][0]['function'] ) ? esc_html( $values['transport_fields'][0]['function'] ) : '' ); ?>" 
				name="<?php echo $args['transport_fields_function']; ?>">
			</li>
			<li>
				<input 
				type="text" 
				autocomplete="off" 
				placeholder="Property" 
				class="transport_property" 
				value="<?php echo ( !empty( $values['transport_fields'][0]['property'] ) ? esc_html( $values['transport_fields'][0]['property'] ) : '' );?>" 
				name="<?php echo $args['transport_fields_property']; ?>">
			</li>
			<li>
				<input 
				type="text" 
				autocomplete="off" 
				placeholder="Units" 
				class="transport_units" 
				value="<?php echo ( !empty( $values['transport_fields'][0]['units'] ) ? esc_html( $values['transport_fields'][0]['units'] ) : '' ); ?>" 
				name="<?php echo $args['transport_fields_units']; ?>">
			</li>
			<li>
				<input 
				type="text" 
				autocomplete="off" 
				placeholder="Prefix" 
				class="transport_prefix" 
				value="<?php echo ( !empty( $values['transport_fields'][0]['prefix'] ) ? esc_html( $values['transport_fields'][0]['prefix'] ) : '' );?>" 
				name="<?php echo $args['transport_fields_prefix']; ?>">
			</li>
			<li>
				<input 
				type="text" 
				autocomplete="off" 
				placeholder="Suffix" 
				class="transport_suffix" 
				value="<?php echo ( !empty( $values['transport_fields'][0]['suffix'] ) ? esc_html( $values['transport_fields'][0]['suffix'] ) : '' );?>" 
				name="<?php echo $args['transport_fields_suffix']; ?>">
			</li>
			<li>
				<input 
				type="text" 
				autocomplete="off" 
				placeholder="Attr" 
				class="transport_attr" 
				value="<?php echo ( !empty( $values['transport_fields'][0]['attr'] ) ? esc_html( $values['transport_fields'][0]['attr'] ) : '' );?>" 
				name="<?php echo $args['transport_fields_attr']; ?>">
			</li>
		</ul>
		<ul class="transport_buttons">
			<li>
				<a 
				href="javascript:void(0)" 
				class="remove_transport" 
				style="display:none"></a>
			</li>
			<li><a href="javascript:void(0)" class="add_transport"></a></li>
		</ul>
	</div>

	<?php
}


function wpcb_option_type( $args , $values ){

	$db_field_option_type = ( !empty( $values['field_option_type'] ) ? esc_html( stripslashes( $values['field_option_type'] ) ) : '' ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Option Type' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php 
				printf(
					esc_html__( 'This option is set in your %s but can be overridden on a per-field basis. %s for details on this.' , 'wpcb' ),
					'<a target="_blank" href="'. site_url('wp-admin/admin.php?page=vcbs') .'">' . esc_html__( 'settings', 'wpcb' ) . '</a>',
					'<a href="https://aristath.github.io/kirki/docs/arguments/option_type.html" target="_blank">' . esc_html__( 'Click Here', 'wpcb' ) . '</a>'
				); 
				?>		
			</p>
		</td>
		<td>
			<select name="<?php echo $args['field_option_type']; ?>" class="field_option_type">

				<option 
				value="theme_mod" 
				<?php selected( !empty( $db_field_option_type ) ? $db_field_option_type : get_option( 'wpcb_option_type' ) , 'theme_mod' ); ?>>
					<?php esc_html_e( 'Theme Mod' , 'wpcb' ); ?>									
				</option>

				<option 
				value="option" 
				<?php selected( !empty( $db_field_option_type ) ? $db_field_option_type : get_option( 'wpcb_option_type' ) , 'option' ); ?>>
					<?php esc_html_e( 'Option' , 'wpcb' ); ?>									
				</option>

			</select>
		</td>
	</tr>

	<?php

}

function wpcb_get_choices( $args , $values , $dafault_msg, $default_value = null , $required = false ){ 

	$db_field_choices = ( !empty( $values['choices'] ) ? stripslashes( $values['choices'] ) : '' ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Choices' , 'wpcb' ); ?>	
				<span class="required"><?php echo ( $required == true ? '*' : '' ); ?></span>								
			</strong> 
			<p class="description">
				<?php 
				echo $dafault_msg;
				?>		
			</p>
		</td>
		<td>
			<textarea 
			autocomplete="off" 
			style="height:190px" 
			class="field_choices" 
			name="<?php echo $args['choices']; ?>"><?php echo ( empty($db_field_choices) ? esc_html( $default_value ) : esc_html( $db_field_choices ) ) ; ?></textarea>
		</td>
	</tr>

	<?php
}

function wpcb_get_post_type_choices( $args , $values , $dafault_msg = null , $default_value = null ){ 

	$db_field_choices = ( !empty( $values['multiple_choices'] ) ? array_map( 'sanitize_text_field' ,  $values['multiple_choices'] )  : array() ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Post Type' , 'wpcb' ); ?>	
				<span class="required">*</span>								
			</strong> 
			<p class="description">
				<?php 
				echo $dafault_msg;
				?>		
			</p>
		</td>
		<td>
			<select name="<?php echo $args['multiple_choices']; ?>" multiple autocomplete="off">
				<option value="all" <?php echo ( in_array( 'all' , $db_field_choices ) ? 'selected' : '' ) ;?> >All</option>
				<?php 
				foreach( wpcb_get_all_post_types() as $key => $value ){

					echo '<option value="' . $key . '"';

					if( in_array( $key , $db_field_choices ) ){
						echo ' selected="selected" ';
					}

					echo '>' . $value . '</option>';

				}
				?>
			</select>
		</td>
	</tr>

	<?php
}

function wpcb_get_all_roles(){

	global $wp_roles;
	$all_roles = $wp_roles->roles;

	$wpcb_roles = array();

	if( !empty( $all_roles ) && is_array( $all_roles ) ){	

		foreach( $all_roles as $key => $value ){

			$wpcb_roles[$key] = $value['name'];

		}

	}

	return $wpcb_roles;

}

function wpcb_get_users_choices( $args , $values , $dafault_msg = null , $default_value = null ){ 

	$db_field_choices = ( !empty( $values['multiple_choices'] ) ? array_map( 'sanitize_text_field' ,  $values['multiple_choices'] )  : array() ); 

	//wpcb_get_all_roles(); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Filter by role' , 'wpcb' ); ?>
				<span class="required">*</span>								
			</strong> 
			<p class="description">
				<?php 
				echo $dafault_msg;
				?>		
			</p>
		</td>
		<td>
			<select name="<?php echo $args['multiple_choices']; ?>" multiple autocomplete="off">
				<?php 
				foreach( wpcb_get_all_roles() as $key => $value ){

					echo '<option value="' . $key . '"';

					if( in_array( $key , $db_field_choices ) ){
						echo ' selected="selected" ';
					}

					echo '>' . $value . '</option>';

				}
				?>
			</select>
		</td>
	</tr>

	<?php
}

function wpcb_get_taxonomy_choices( $args , $values , $dafault_msg = null , $default_value = null ){ 

	$db_taxonomy = ( !empty( $values['taxonomy'] ) ? array_map( 'sanitize_text_field' ,  $values['taxonomy'] )  : array() ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Taxonomy' , 'wpcb' ); ?>
				<span class="required">*</span>							
			</strong> 
			<p class="description">
				<?php 
				echo $dafault_msg;
				?>		
			</p>
		</td>
		<td>
			<select name="<?php echo $args['taxonomy']; ?>" multiple autocomplete="off">
				<?php 
				foreach( wpcb_get_all_taxonomies() as $key => $value ){

					echo '<option value="' . $key . '"';

					if( in_array( $key , $db_taxonomy ) ){
						echo ' selected="selected" ';
					}

					echo '>' . $value . '</option>';

				}
				?>
			</select>
		</td>
	</tr>

	<?php
}

function wpcb_filter_from_taxonomy( $args , $values , $dafault_msg = null , $default_value = null ){ 

	$db_filter_taxonomy = ( !empty( $values['filter_taxonomy'] ) ? array_map( 'sanitize_text_field' ,  $values['filter_taxonomy'] )  : array() ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Filter from Taxonomy' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php 
				echo $dafault_msg;
				?>		
			</p>
		</td>
		<td>
			<select name="<?php echo $args['filter_taxonomy']; ?>" multiple autocomplete="off" class="filter_tax">
				<?php 
				wpcb_get_all_taxonomy( $db_filter_taxonomy );
				?>
			</select>
		</td>
	</tr>

	<?php
}

function wpcb_get_all_taxonomy( $db_filter_taxonomy ){

	$post_types = wpcb_get_all_post_types();
	unset( $post_types['page'] );
	unset( $post_types['attachment'] );

	foreach( $post_types as $key => $post_type ){

		$tax = get_object_taxonomies( $key );

		foreach( $tax as $key_tax => $tax_tax_value ){

			if( $tax_tax_value == 'post_tag' || $tax_tax_value == 'post_format' ){
				unset( $tax[$key_tax] );
			}

		}

		unset( $tax['post_tag'] );

		foreach( $tax as $taxonomy ){

			echo '<optgroup label=" ' . ucfirst( $post_type ) . ': ' . ucfirst( $taxonomy ) . ' ">';

				$terms = get_terms( array(
				    'taxonomy' => $taxonomy,
				    'hide_empty' => false,
				) );

				foreach( $terms as $term ){

						$value = $post_type . ':' . $taxonomy.':'.$term->slug;

						echo '<option value="' . $value . '"';

						if( in_array( $value , $db_filter_taxonomy ) ){
							echo ' selected="selected" ';
						}

						echo '>' . $term->name . '</option>';

				}		

			echo '</optgroup>';

		}

	}

}

function wpcb_get_all_taxonomies(){
	
	$args = array(
	  'public'   => true,
	  //'_builtin' => false
	  
	);

	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'

	$taxonomies = get_taxonomies( $args, $output, $operator ); 
	unset( $taxonomies['post_format'] );
	return $taxonomies;

}

function wpcb_get_all_post_types(){

	$args = array(
	   'public'   => true,
	   '_builtin' => false,
	);

	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'

	$post_types = get_post_types( $args, $output, $operator ); 
	$post_types['post'] = 'post';
	$post_types['page'] = 'page';
	$post_types['attachment'] = 'attachment';
	return $post_types;

}

 
function wpcb_condition_logic( $args , $values ){ 

	$db_condition_option = ( !empty( $values['condition'] ) ? esc_html( stripslashes( $values['condition'] ) ) : 'no' ); ?>

	<tr>
		
		<td>
			<strong>
				<?php esc_html_e( 'Conditional Logic' , 'wpcb' ); ?>									
			</strong> 
		</td>

		<td>
			<ul class="condition_radio_wrapper">
				<li>
					<label>

						<input 
						type="radio" 
						name="<?php echo $args['condition']; ?>" 
						value="yes" 
						autocomplete="off" 
						<?php checked( $db_condition_option , 'yes' ); ?>>

						<?php esc_html_e( 'Yes' , 'wpcb' ); ?>

					</label>
				</li>
				<li>
					<label>

						<input 
						type="radio" 
						name="<?php echo $args['condition']; ?>" 
						value="no" 
						autocomplete="off" 
						<?php checked( $db_condition_option , 'no' ); ?>>

						<?php esc_html_e( 'No' , 'wpcb' ); ?>

					</label>
				</li>
			</ul>

			<?php 

			echo $args['condition_logic_keys'];  

			if( !empty( $values['condition_logic']['rules'] ) && is_array( $values['condition_logic']['rules'] ) ){
				
				$count = 0;
				foreach( $values['condition_logic']['rules'] as $key => $condition_logic_value ){

					get_condition_logic( $values , $args , $condition_logic_value , $count++ , count( $values['condition_logic']['rules'] ) );

				}

			} else { 
				?>

				<ul 
				class="conditional-logic-rules" 
				style="<?php echo ( ( empty($values['condition']) || $values['condition'] == 'no' ) ? 'display:none' : '' ); ?>">
					<li>
						<select name="<?php echo $args['condition_logic']; ?>" class="condition_field" autocomplete="off">
							<?php 
							get_all_conditions_labels(); 
							?>
						</select>
					</li>
					<?php 
					get_condition_operator( $args , null , 0 );
					?>
					<li>
						<select name="<?php echo $args['condition_value']; ?>" class="condition_value" autocomplete="off">
							
						</select>
					</li>
					<li>
						<?php get_condition_add_remove_btn( $count = 1 ); ?>
					</li>
				</ul>

				<?php
			}
			 ?>

			<div 
			class="condition_logic_msg alert alert-info" 
			style="<?php echo ( ( empty($values['condition']) || $values['condition'] == 'no' ) ? 'display:none' : '' ); ?>">
				Showing this field when all these rules are met
			</div>

		</td>

	</tr>

	<?php
}

function get_condition_add_remove_btn( $count = null ){ ?>

	<div class="condition_add_remove_btn">
		<a class="wpcb-button-remove" href="javascript:void(0)" style="<?php echo ( $count == 1 ? 'display:none' : '' ); ?>"></a>
		<a class="wpcb-button-add" href="javascript:void(0)"></a>
	</div>

	<?php
}

function get_condition_operator( $args , $selected = null, $count ){ ?>

	<li>
		<select name="<?php get_filtered_condition_name( $args['condition_operator'] , $count ); ?>" class="condition_operator" autocomplete="off">
			<option value="==" <?php selected( $selected, '==' ); ?>>is equal to</option>
			<option value="!=" <?php selected( $selected, '!=' ); ?>>is not equal to</option>
			<!-- <option value=">=" <?php selected( $selected, '>=' ); ?>>is greater or equal to</option>
			<option value="<=" <?php selected( $selected, '<=' ); ?>>is smaller or equal to</option>
			<option value=">" <?php selected( $selected, '>' ); ?>>is greater than</option>
			<option value="<" <?php selected( $selected, '<' ); ?>>is smaller than</option> -->
		</select>
	</li>

	<?php
}

function get_filtered_condition_name( $name , $count ){

	$replace_brackets = str_replace( array('[',']') , ' ', $name ); // Convert all '[]' to spaces
	$arr = array_filter( preg_split('/\s+/', $replace_brackets ) , 'strlen'  ); // convert to array
	$arr[count($arr)-2] = $count;
	$join = '';

	foreach( $arr as $key => $value ){
		
		if( $key == 0 ){
			$join .= $value;
		} else{
			$join .= '[' . $value . ']';
		}
	}

	echo $join;

	//echo '<pre>'; print_r( $join ); echo '</pre>';

}

function get_condition_logic( $values , $args , $condition_logic_value , $count, $no_of_array ){ 

	$condition_logic_value['field'] = !empty( $condition_logic_value['field'] ) ? $condition_logic_value['field'] : '';
	$condition_logic_value['value'] = !empty( $condition_logic_value['value'] ) ? $condition_logic_value['value'] : '';
	?>

	<ul 
	class="conditional-logic-rules" 
	style="<?php echo ( ( empty($values['condition']) || $values['condition'] == 'no' ) ? 'display:none' : '' ); ?>">
		<li>
			<select name="<?php get_filtered_condition_name( $args['condition_logic'] , $count ); ?>" class="condition_field" autocomplete="off">
				<?php 
				get_all_conditions_labels( $condition_logic_value ); 
				?>
			</select>
		</li>
		<?php 
		get_condition_operator( $args , $condition_logic_value['operator'] , $count ); ?>

		<li>
			<select 
			name="<?php get_filtered_condition_name( $args['condition_value'] , $count ); ?>" 
			class="condition_value" 
			autocomplete="off">
				<?php get_condition_value( $condition_logic_value['field'] , $condition_logic_value['value'] ); ?>
			</select>
		</li>
		<li>
			<?php get_condition_add_remove_btn( $no_of_array ); ?>
		</li>
	</ul>

	<?php 
}

function get_condition_value( $condition_logic_value, $selected ){

	$data = get_option( 'wpcb_options' , false );
	$condition_fields = array( 'palette' , 'select' , 'checkbox' , 'radio' , 'toggle' , 'switch' , 'radio-buttonset' , 'radio-image' );
	$condotion_field_2 = array( 'checkbox' , 'toggle' ); 

	if( $data == false || !is_array( $data ) ){
		return;
	}

	foreach( $data as $key1 => $value1 ){

		foreach( $value1 as $key2 => $value2 ){

			/**
			* For parent section
			*/

			if( $value2['type'] == 'section' ){ 

				get_conditions_options_value( $value2, $condition_fields , $condition_logic_value , $condotion_field_2 , $selected );

			} 

			/**
			* For parent panel
			*/

			else {

				if( !empty( $value2['sub_fields'] ) && is_array( $value2['sub_fields'] ) ){

					foreach( $value2['sub_fields'] as $key3 => $value3 ){

						// For sections
						get_conditions_options_value( $value3 , $condition_fields , $condition_logic_value , $condotion_field_2 , $selected );

					}

				}

			}			

		}

	}

}

function get_conditions_options_value( $value2 , $condition_fields , $condition_logic_value , $condotion_field_2 , $selected ){

 	if( !empty( $value2['sub_fields'] ) && is_array( $value2['sub_fields'] ) ){ 

		foreach( $value2['sub_fields'] as $key3 => $value3 ){

			if( in_array( $value3['type'] , $condition_fields ) && $condition_logic_value == $value3['field_name'] ){

				if( in_array( $value3['type'] , $condotion_field_2 ) ){

					$selected = empty( $selected ) ? 0 : sanitize_text_field( $selected );

					echo '<option value="1" ';
					selected( $selected, 1 );
					echo ' >True</option>';

					echo '<option value="0" ';
					selected( $selected, 0 );
					echo ' >False</option>';

				} else{

					$arrayBreak = explode( "\n" , $value3['choices'] );

					if( is_array( $arrayBreak ) && !empty( $arrayBreak ) ){

						foreach( $arrayBreak as $choice_key => $choice_value ){

							$array_key_value = explode(':', $choice_value);
							$array_key_value[0] = !empty( $array_key_value[0] ) ? preg_replace('/\s+/', '', $array_key_value[0] ) : '';
							$array_key_value[1] = !empty( $array_key_value[1] ) ? preg_replace('/\s+/', '', $array_key_value[1] ) : '';

							$selected_filter = ( !empty( $selected ) ? sanitize_text_field( $selected ) : 0  );

							echo '<option ';
							selected( $selected_filter, $array_key_value[0] );
							//echo ' se="' . $selected_filter . '" ';
							//echo ' se1="' . $array_key_value[0] . '" ';
							echo ' value="' . $array_key_value[0] . '">' . $array_key_value[1] . '</option>';
						}

					}

				}

			}
			
		}

	}

}

function get_conditions_label_options( $value2 , $condition_fields , $condition_logic_value ){

	if( !empty( $value2['sub_fields'] ) && is_array( $value2['sub_fields'] ) ){

		foreach( $value2['sub_fields'] as $key3 => $value3 ){

			if( in_array( $value3['type'] , $condition_fields ) ){
				echo '<option value="' . $value3['field_name'] . '" data-field-id="' . $key3 . '"';
				selected( $condition_logic_value['field'] , $value3['field_name'] );
				echo '>' . $value3['field_label'] . '</option>';
			}
			
 		}
 
 	}
 
}

function get_all_conditions_labels( $condition_logic_value ){
	//print_r( $condition_logic_value );
	$data = get_option( 'wpcb_options' , false );
	$condition_fields = array( 'palette' , 'select', 'checkbox', 'radio', 'toggle', 'switch' , 'radio-buttonset' , 'radio-image' );

	if( $data == false || !is_array( $data ) ){
		return;
	}

	foreach( $data as $key1 => $value1 ){

		foreach( $value1 as $key2 => $value2 ){

			/**
			* For parent section
			*/

			if( $value2['type'] == 'section' ){

				get_conditions_label_options( $value2 , $condition_fields , $condition_logic_value );

			} 

			/**
			* For parent panel
			*/

			else {

				if( !empty( $value2['sub_fields'] ) && is_array( $value2['sub_fields'] ) ){

					foreach( $value2['sub_fields'] as $key3 => $value3 ){

						// For sections
						get_conditions_label_options( $value3 , $condition_fields , $condition_logic_value );

					}

				}

			}			

		}

	}

}


function wpcb_option_name( $args , $values ){

	$db_field_option_name = ( !empty( $values['field_option_name'] ) ? esc_html( stripslashes( $values['field_option_name'] ) ) : '' ); ?>

	<tr>
		<td>
			<strong>
				<?php esc_html_e( 'Option Name' , 'wpcb' ); ?>									
			</strong> 
			<p class="description">
				<?php 
				printf(
					esc_html__( 'This option is set in your %s but can be overridden on a per-field basis. %s for details on this.' , 'wpcb' ),
					'<a target="_blank" href="'. site_url('wp-admin/admin.php?page=vcbs') .'">' . esc_html__( 'settings', 'wpcb' ) . '</a>',
					'<a href="https://aristath.github.io/kirki/docs/arguments/option_name.html" target="_blank">' . esc_html__( 'Click Here', 'wpcb' ) . '</a>'
				); 
				?>		
			</p>
		</td>
		<td>
			<input 
			type="text" 
			name="<?php echo $args['field_option_name']; ?>" 
			class="field_option_name" 
			value="<?php 
				if( get_option( 'wpcb_option_type' ) == 'option' && defined('DOING_AJAX') && DOING_AJAX ){
					echo sanitize_text_field( get_option( 'wpcb_option_name' ) );
				} else {
					echo sanitize_text_field( $db_field_option_name );
				}
			?>">
			<p class="description">
				<?php esc_html_e( 'Note : This will only work if above "Option Type" is selected to "Option".' , 'wpcb' ); ?>		
			</p>
		</td>
	</tr>

	<?php
}

function get_fields_headings( $values , $field ){ ?>

	<tr>
		<td><span class="circle ignore_custom_field_circle"></span></td>
		<td>
			<a href="javascript:void(0)" class="field_title">
				<?php 
				echo ( empty( $values['field_label'] ) ? esc_html__( 'New Field', 'wpcb' ) : esc_html( stripslashes( $values['field_label'] ) ) );
				?>
			</a>
			<div class="row_options">
				<span>
					<a 
					href="javascript:;" 
					title="<?php esc_html_e( 'Edit this Field', 'wpcb' ); ?>" 
					class="wpcb_edit_field">
						<?php esc_html_e( 'Edit' , 'wpcb' ); ?>
					</a> | 
				</span>
				<span>
					<a 
					href="javascript:;" 
					title="<?php esc_html_e( 'Delete this Field', 'wpcb' ); ?>" 
					class="wpcb_delete_field">
						<?php esc_html_e( 'Delete' , 'wpcb' ); ?>
					</a>
				</span>
			</div>
		</td>
	
		<td class="static_field_name">
			<?php 
			echo ( empty( $values['field_name'] ) ? '' : esc_html( stripslashes( $values['field_name'] ) ) );
			?>
		</td>
	
		<td><?php echo ucwords( str_replace( '-', ' ', $field ) ); ?></td>
	</tr>

	<?php
}

function wpcb_add_default_names( $output ){

	foreach( $output as $key => $value ){

		foreach( $value as $key_2 => $value_2 ){

			/**
			* For panel
			*/

			if( $output[$key][$key_2]['type'] == 'panel' ){

				// If no field label or field name is writted add random text

				$output[$key][$key_2]['field_label'] = !empty( $output[$key][$key_2]['field_label'] ) ? sanitize_text_field( $output[$key][$key_2]['field_label'] ) : wp_generate_password( 8, false , false );

				$output[$key][$key_2]['field_name'] = !empty( $output[$key][$key_2]['field_name'] ) ? preg_replace( '/[^a-z0-9-_]/' , "",  $output[$key][$key_2]['field_name'] ) : wp_generate_password( 8, false , false );

				if( !empty( $value_2['sub_fields'] ) && is_array( $value_2['sub_fields'] ) ){

					/**
					* For sections
					*/

					foreach( $value_2['sub_fields'] as $key_3 => $value_3 ){

						$output[$key][$key_2]['sub_fields'][$key_3]['field_label'] = !empty( $output[$key][$key_2]['sub_fields'][$key_3]['field_label'] ) ? sanitize_text_field( $output[$key][$key_2]['sub_fields'][$key_3]['field_label'] ) : wp_generate_password( 8, false , false );

						$output[$key][$key_2]['sub_fields'][$key_3]['field_name'] = !empty( $output[$key][$key_2]['sub_fields'][$key_3]['field_name'] ) ? preg_replace( '/[^a-z0-9-_]/' , "",  $output[$key][$key_2]['sub_fields'][$key_3]['field_name'] ) : wp_generate_password( 8, false , false );

						/**
						* For custom fields
						*/

						if( !empty( $value_3['sub_fields'] ) && is_array( $value_3['sub_fields'] ) ){

							foreach( $value_3['sub_fields'] as $key_4 => $value_4 ){

								$output[$key][$key_2]['sub_fields'][$key_3]['sub_fields'][$key_4]['field_label'] = !empty( $output[$key][$key_2]['sub_fields'][$key_3]['sub_fields'][$key_4]['field_label'] ) ? sanitize_text_field( $output[$key][$key_2]['sub_fields'][$key_3]['sub_fields'][$key_4]['field_label'] ) : wp_generate_password( 8, false , false );

								$output[$key][$key_2]['sub_fields'][$key_3]['sub_fields'][$key_4]['field_name'] = !empty( $output[$key][$key_2]['sub_fields'][$key_3]['sub_fields'][$key_4]['field_name'] ) ? preg_replace( '/[^a-z0-9-_]/' , "",  $output[$key][$key_2]['sub_fields'][$key_3]['sub_fields'][$key_4]['field_name'] ) : wp_generate_password( 8, false , false );

							}

						}

					}

				}

			} 
			
			/**
			* For setion
			*/

			else {

				$output[$key][$key_2]['field_label'] = !empty( $output[$key][$key_2]['field_label'] ) ? sanitize_text_field( $output[$key][$key_2]['field_label'] ) : wp_generate_password( 8, false , false );

				$output[$key][$key_2]['field_name'] = !empty( $output[$key][$key_2]['field_name'] ) ? preg_replace( '/[^a-z0-9-_]/' , "",  $output[$key][$key_2]['field_name'] ) : wp_generate_password( 8, false , false );

				if( !empty( $value_2['sub_fields'] ) && is_array( $value_2['sub_fields'] ) ){

					/**
					* For custom field
					*/

					foreach( $value_2['sub_fields'] as $key_3 => $value_3 ){

						$output[$key][$key_2]['sub_fields'][$key_3]['field_label'] = !empty( $output[$key][$key_2]['sub_fields'][$key_3]['field_label'] ) ? sanitize_text_field( $output[$key][$key_2]['sub_fields'][$key_3]['field_label'] ) : wp_generate_password( 8, false , false );

						$output[$key][$key_2]['sub_fields'][$key_3]['field_name'] = !empty( $output[$key][$key_2]['sub_fields'][$key_3]['field_name'] ) ? preg_replace( '/[^a-z0-9-_]/' , "",  $output[$key][$key_2]['sub_fields'][$key_3]['field_name'] ) : wp_generate_password( 8, false , false );

					}

				}

			}

		}

	}

	return $output;

}

add_action( 'admin_init' , 'wpcb_save_settings' );
function wpcb_save_settings(){

	if( empty( $_POST ) ){
		return;
	}

	// save settings to the database
	if( array_key_exists( 'general_settings' , $_POST ) && $_POST['general_settings'] == 'Submit' ){

		if( !empty( $_POST['option_type'] ) ){
			update_option( 'wpcb_option_type' , sanitize_text_field( $_POST['option_type'] ) );
		}

		update_option( 'wpcb_option_name' , sanitize_text_field( $_POST['wpcb_option_name'] ) );

	} 

}

function get_export_json(){

	$data = get_option( 'wpcb_options' );

	if( empty( $data ) ){
		echo '';
		return;
	}

	echo json_encode( $data );

}

/**
* Change order of the sections and panels
*/

add_action( "customize_register", "wpcb_theme_customize_register", 999, 1 );	
function wpcb_theme_customize_register($wp_customize){

	$title_tagline = $wp_customize->get_section( 'title_tagline' );

	if( $title_tagline ){
		$title_tagline->priority = 999;
	}

	$static_front_page = $wp_customize->get_section( 'static_front_page' );

	if( $static_front_page ){
		$static_front_page->priority = 1000;
	}

	$widgets = $wp_customize->get_panel( 'widgets' );

	if( $widgets ){
		$widgets->priority = 1001;
	}

	$nav_menus = $wp_customize->get_panel( 'nav_menus' );

	if( $nav_menus ){
		$nav_menus->priority = 1002;
	}

}

function wpcb_check_kirki() {

	if( get_option( 'wpcb_kirki_notice' ) == true ){
		return;
	} ?>

    <div class="notice notice-error kirki_notice">

        <p>Visual Customizer Builder needs Kirki to be installed on your wordpress site. Please download and install Kirki plugin first. <a href="javascript:void(0)" style="float:right" class="dismiss_kirki_notice">Dismiss this message</a></p>

         <script>
    
	    	jQuery(document).on( 'click' , '.dismiss_kirki_notice', function(){

	    		jQuery.ajax({
		    		url : "<?php echo admin_url( 'admin-ajax.php' ); ?>",
		    		type : 'post',
		    		dataType : 'json',
		    		data : {
		    			action : 'dismiss_kirki_notice'
		    		},
		    		success : function( data ){
		    			if( data.status == 'success' ){
		    				jQuery('.kirki_notice').remove();
		    			}
		    		}
		    	});

	    	});
	    	
	    </script>

    </div>

    <?php
}