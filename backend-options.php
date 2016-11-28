<?php

/**
* Add Backend Menus
*/

add_action( 'admin_menu', 'wpcb_admin_menu' );
function wpcb_admin_menu() {

	/**
	* Add admin page for visual customizer
	*/

    add_menu_page(
        esc_html__( 'Visual Customizer Builder', 'wpcb' ),
        esc_html__( 'Visual Customizer Builder' , 'wpcb' ),
        'manage_options',
        'wpcb_builder',
        'wpcb_builder_callback',
        'dashicons-hammer',
        90
    );

    /**
	* Add settings page for visual customizer
	*/

    add_submenu_page(
        'wpcb_builder',
        'Visual Customizer Bulider Settings',
        'Settings',
        'manage_options',
        'vcbs',
        'vcbs_callback' 
    );
}

/**
* Settings page
*/

function vcbs_callback(){ ?>

	<div class="wrap">
		<h1><?php esc_html_e( 'Visual Customizer Builder Settings' , 'wpcb' ); ?></h1>

		<ul class="wpcb_settings_tabs">
			<a href="javascript:void(0)" class="active" value="wpcb_settings"><?php esc_html_e( 'General' , 'wpcb' ); ?></a>
			<a href="javascript:void(0)" value="wpcb_import"><?php esc_html_e( 'Export/Import' , 'wpcb' ); ?></a>
		</ul>

		<form method="post" class="wpcb_settings_wrapper wpcb_settings_wrap" id="wpcb_settings">

		    <table class="form-table">

		        <tr valign="top">

		        	<th scope="row">
		        		<?php esc_html_e( 'Option Type' , 'wpcb' ); ?>		
		        	</th>

		        	<td>
		        		<?php 
		        		$option_type = esc_attr( get_option('wpcb_option_type') ); 
		        		?>

		        		<select name="option_type">
		        			<option value="theme_mod" <?php selected( $option_type, 'theme_mod' );?>>Theme Mod</option>
		        			<option value="option" <?php selected( $option_type, 'option' );?>>Option</option>
		        		</select>
		        		<p class="description">Default : <code>Theme Mod</code> For more info on this <a target="_blank" href="https://aristath.github.io/kirki/docs/arguments/option_type.html">click here</a></p>
		        	</td>
		        </tr>
		         
		        <tr valign="top">
		        	<th scope="row">Option Name</th>
		       		<td>
		       			<input type="text" name="wpcb_option_name" value="<?php echo esc_attr( get_option('wpcb_option_name') ); ?>" />
		       			<p class="description">If <code>Option Type : Option</code> is selected then only this field will be used. For more info on this <a href="https://aristath.github.io/kirki/docs/arguments/option_name.html" target="_blank">click here</a></p>		       			
		       		</td>
		        </tr>

		        <tr valign="top">
		        	<td>
		        		
		        	</td>
		        	<td>
		        		<input type="submit" value="Submit" class="button button-primary" name="general_settings">
		        	</td>
		        </tr>

		        <tr valign="top">
		        	<td colspan="2">
		        		
		        		<div class="alert alert-info store_data_info">
		        			<h4>How to store data ?</h4>
		        			<p>There are 3 ways you can choose to store your data:</p>
		        			<ol>
		        				<li>

		        					<strong>Using <code>theme_mods</code>:</strong>
		        					<p>- <code>theme_mods</code> are saved on a per-theme basis. So if for example you're building a theme and you want to allow each child theme to have its own set of options, then you might want to consider using theme_mods and not options.</p>
		        					<p>- On above <code>Option Type</code> choose <code>Theme Mod</code></p>
		        					<p>- When adding custom field leave <code>Option Type</code> and <code>Option Name</code> as it is.</p>
		        					<p>- If you have saved the custom field under <code>my_text_setting</code> then you can get the value like this <code>get_theme_mod( 'my_text_setting' );</code></p>
		        					<p><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/theme_mod.jpg';?>" /></p>
		        				</li>

		        				<li>
		        					<strong>Using a separate <code>option</code> for each field:</strong>
		        					<p>- <code>options</code> are not tied to specific theme, so if you want to make your settings persistent no matter which theme is activated then this is the way to go. When using options, you can also choose to save each option as a different row in the database, or save them all under a single option, in which case your options will be saved as a serialized array.</p>
		        					<p>- To do this you can choose any of the above <code>Option Type</code> but leave blank <code>Option Name</code></p>
		        					<p>- Inside on the custom field make settings like below and you can get the value like this <code>get_option( 'my_text_settings' );</code>.<br> <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/single_option.jpg';?>" /></p>
		        				</li>
		        				<li>
		        					<strong>Using a serialized array and saving all fields using a <code>single option</code>:</strong>
		        					<p>- To do this you can choose any of the above <code>Option Type</code> and give a name for the <code>Option Name</code> eg. <code>theme_options</code></p>
		        					<p>- Inside on the custom field make settings like below and you can get the values like this <code>get_option( 'theme_option' );</code> in an array.</p>
		        					<p><img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/option_serialize.jpg'; ?>" /></p>
		        				</li>
		        			</ol>		        			
		        		</div>		        		
		        	</td>
		        </tr>
		        
		    </table>

		</form>

	    <table class="form-table wpcb_settings_wrap" id="wpcb_import" style="display:none">
	    	<?php //print_r($_POST); ?>
	    	<tr>
	    		<td colspan="2" class="export_settings">
	    			<h4>Export Settings</h4>

	    			<div class="export_json_wrap">

	    				<h3>Exported To Json Data</h3>
		    			<textarea readonly><?php get_export_json(); ?></textarea>
		    			
	    			</div>

	    		</td>
	    	</tr>

	    	<tr>
	    		<td colspan="2">
	    			<h4>Import Settings</h4>
	    			<p>Paste the json data below and click Import.</p>
	    			<textarea class="import_from_json_data" autocomplete="off"></textarea>
	    			<label>
	    				<input type="radio" name="import_options" value="1" autocomplete="off" checked> Replace existing customizer options
	    			</label>
	    			<br>
	    			<label>
	    				<input type="radio" name="import_options" value="2" autocomplete="off"> Merge with existing customizer options
	    			</label>
	    			<br>
	    			<input type="button" value="Import from JSON" class="button button-primary btn_import_json" autocomplete="off">
	    			<div class="alert alert-success wpcb_import_success" style="display:none">
	    				You have successfully imported the settings.
	    			</div>
	    		</td>
	    	</tr>

	    </table>
		    
	</div>

	<?php
}

/**
* Customizer options
*/

function wpcb_builder_callback(){ ?>

	<div class="wrap">

		<h1><?php esc_html_e( 'Visual Customizer Builder' , 'wpcb' ); ?></h1>
		
	    <div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="postbox-container-1" class="postbox-container wpcb_sidebar">
					<div id="side-sortables wpcb_sidebar">
						<div id="submitdiv" class="postbox ">
							<h2 class="hndle">
								<span>Publish</span>
							</h2>
							<div class="inside">
								<div id="submitpost" class="submitbox">
									<div id="minor-publishing">

										<div id="misc-publishing-actions">

											<div class="misc-pub-section misc-pub-post-status">
												<label for="post_status">Status:</label>

												<div class="wpcb_status_wrapper">

													<?php 
													$wpcb_status = get_option( 'wpcb_customizer_status' , 1 );
													?>

													<label class="success">
														<input
														<?php checked( $wpcb_status , 1 ); ?>  
														type="radio" 
														name="wpcb_status" 
														value="1" 
														autocomplete="off"/>Published
													</label>
													<label class="danger">
														<input 
														<?php checked( $wpcb_status , 2 ); ?> 
														type="radio" 
														name="wpcb_status" 
														value="2" 
														autocomplete="off"/>Unpublished
													</label>
												</div>
											</div>

										</div>
										<div class="clear"></div>
									</div>

									<div id="major-publishing-actions">


										<div id="publishing-action">
											
											<input type="button" class="button button-primary wpcb_save" value="<?php esc_html_e( 'Update' , 'wpcb' ); ?>" autocomplete="off">
											<span class="spinner">
												<img src="<?php echo admin_url( 'images/spinner.gif' ); ?>" />
											</span>

										</div>
										<div class="clear"></div>
									</div>
								</div>

							</div>
							
						</div>
					</div>
				</div>

				<form class="kirki_builer_form" action="#" method="post">

					<div id="postbox-container-2" class="postbox-container wpcb_fields">
						<table class="fields_heading">
							<thead>
								<th><?php esc_html_e( 'Field Order', 'wpcb' ); ?></th>
								<th><?php esc_html_e( 'Field Label', 'wpcb' ); ?></th>
								<th><?php esc_html_e( 'Field Name', 'wpcb' ); ?></th>
								<th><?php esc_html_e( 'Field Type', 'wpcb' ); ?></th>
							</thead>
						</table>
						<div class="fields">

							<?php 
							$customizer_fields = get_option( 'wpcb_options' );

							if( !empty( $customizer_fields )  && is_array( $customizer_fields ) ){
								$no_fields_message = 'display:none';
							} else {
								$no_fields_message = 'display:block';
							}
							
							echo '<div class="no_fields_message" style="' . $no_fields_message . '">';
							printf(
								esc_html__( 'No fields. Click the %s or %s button to create your first field.' , 'wpcb' ),
								'<strong class="label label-primary">' . esc_html__( '+ Add Panel' , 'wpcb' ) . '</strong>',
								'<strong class="label label-primary">' . esc_html__( '+ Add Section' , 'wpcb' ) . '</strong>'
							);
							echo '</div>';
							?>						
									
							<div class="customizer_fields_wrapper">

								<?php 
								if( !empty( $customizer_fields )  && is_array( $customizer_fields ) ){
										wpcb_get_saved_custom_fields( $customizer_fields );
									}
								?>

							</div>

						</div>

						<div class="table_footer">
							<div class="order_message"><?php esc_html_e( 'Drag and drop to reorder', 'wpcb' ); ?></div>
							<div class="add_fields_btn">
								<input type="button" class="button button-primary" id="add_panel" href="javascript:void(0)" value="<?php esc_html_e( '+ Add Panel', 'wpcb' ); ?>" autocomplete="off">
								<input type="button" class="button button-primary" id="add_section" href="javascript:void(0)" value="<?php esc_html_e( '+ Add Section', 'wpcb' ); ?>" autocomplete="off">
							</div>
						</div>
					</div>

				</form>

			</div>

		</div>
		
	</div>

	<?php

}

/**
* Get all saved custom fields
*/

function wpcb_get_saved_custom_fields( $data ){

	foreach( $data as $key => $value ){

		foreach( $value as $key_2 => $value_2 ){

			$parents_keys = array();
			if( $value_2['type'] == 'panel' ){

				$parents_keys['grand_parent_key'] = $key_2;

				get_panel_section_html_field( 'panel' , $wrapper_class = null , $circle_class = null , $parent_unique_key = null , $value_2 , $key_2 , $parents_keys );
			
			} else {

				get_panel_section_html_field( 'section' , $wrapper_class = null , $circle_class = null , $parent_unique_key = null , $value_2 , $key_2 , $parents_keys );

			}

		}

	}

}