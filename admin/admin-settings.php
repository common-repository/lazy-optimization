<?php

//Notice to show if Autoptimze plugin not installed or is not activated
add_action( 'admin_notices', 'lazy_optimization_check_autoptimize_activation' );
function lazy_optimization_check_autoptimize_activation() {
	if( !class_exists( 'autoptimizeMain' ) ) { ?>
		<div class="notice update-nag">
			<p>
				<?php _e('To use "Lazy Optimization" plugin\'s background lazyload feature please install/activate the "Autoptimize" pulgin.' , 'lazy-optimization') ?>
			</p>
		</div>
<?php	}
}

// Setting up plugin's settings page 
add_action('admin_menu', 'wpdocs_register_my_custom_submenu_page');
function wpdocs_register_my_custom_submenu_page() {
    add_submenu_page(
        'options-general.php',
        'Lazy Optimization',
        'Lazy Optimization',
        'manage_options',
        'lazy-optimization-setting-page',
        'lazy_optimization_settig_page_callback' );
}
 
function lazy_optimization_settig_page_callback()
{
   ?>
      <div class="wrap">
 
         <form method="post" action="options.php" class="form-wrap">
            <?php
            	settings_fields("lazy_optimization_option_setting_section");
 
               	do_settings_sections("lazy-optimization-setting-page");
               
               	submit_button(); 
            ?>
         </form>
      </div>
   <?php
}

// Settings for Plugin
add_action("admin_init", "lazy_optimization_plugin_settings");
function lazy_optimization_plugin_settings()
{
	// Defining main Plugin setting area
	add_settings_section("lazy_optimization_option_setting_section", __('Plugin Settings', 'lazy-optimization'), 'lazy_optimization_setting_section_callback_function', "lazy-optimization-setting-page");

	add_settings_field(
		"lzyopti_global_images_to_exclude",
		__("Separate each image name by a comma.", 'lazy-optimization'),
		"lazy_optimization_global_imgs_to_exclude",
		"lazy-optimization-setting-page",
		"lazy_optimization_option_setting_section"
	);

	//Register settings for "Images to exclude"
	register_setting(
		"lazy_optimization_option_setting_section",
		"lzyopti_global_images_to_exclude",
		array('sanitize_callback' => 'sanitize_text_field')
	);

	add_settings_field(
		"lzyopti_replace_image",
		__("Path of replace image.", 'lazy-optimization'),
		"lazy_optimization_replace_image", "lazy-optimization-setting-page",
		"lazy_optimization_option_setting_section"
	);

	//Register settings for "Replace path image"
	register_setting(
		"lazy_optimization_option_setting_section",
		"lzyopti_replace_image",
		array('sanitize_callback' => 'esc_url_raw')
	);

	add_settings_field(
		"lzyopti_delete_all_data",
		__("Delete plugin's all data upon deleting the plugin.", 'lazy-optimization'),
		"lazy_optimization_delete_data",
		"lazy-optimization-setting-page",
		"lazy_optimization_option_setting_section"
	);

	//Register settings for "Delete all data upon installation"
	register_setting(
		"lazy_optimization_option_setting_section",
		"lzyopti_delete_all_data",
		array('type' => 'boolean')
	);
}

function lazy_optimization_global_imgs_to_exclude() { 
	$lzyopti_global_excluded_imgs = get_option('lzyopti_global_images_to_exclude');
	?>
	<input type="text" placeholder="<?php esc_attr_e('image1.png, image2.jpeg, image3.jpeg', 'lazy-optimization'); ?>" id="lzyopti_global_images_to_exclude" name="lzyopti_global_images_to_exclude" value="<?php  sanitize_text_field($lzyopti_global_excluded_imgs); ?>" style="width:80%" autocomplete="off" />
<?php }

function lazy_optimization_setting_section_callback_function( $args ) { ?>
	<h2 style="margin-bottom: 0px"><?php esc_attr_e('Images to exclude globally on website.', 'lazy-optimization'); ?></h2>
<?php }

add_action(  'update_option_lzyopti_global_images_to_exclude', 'lazy_optimization_save_global_images_to_exclude_option' , 10, 2 );

function lazy_optimization_save_global_images_to_exclude_option( $old_value, $new_value){
	lzyopti_del_plugin_static_files();
	if( class_exists( 'autoptimizeMain' ) ) {
		autoptimizeCache::clearall();
		autoptimize()->run();
	}
}

add_action( 'update_option_lzyopti_replace_image', 'lazy_optimization_save_replace_image' , 10, 2 );
function lazy_optimization_save_replace_image( $old_value, $new_value){
	lzyopti_del_plugin_static_files();
	if( class_exists( 'autoptimizeMain' ) ) {
		autoptimizeCache::clearall();
		autoptimize()->run();
	}
}

function lazy_optimization_replace_image() {
	$lzyopti_replace_image = get_option('lzyopti_replace_image');
	if( empty($lzyopti_replace_image) ) {
		$lzyopti_replace_image = Lazy_Optimization_IMG_URL.'/loading.gif';
	}
	?>
	<input type="text" placeholder="<?php esc_attr_e('Enter Path for replace image.', 'lazy-optimization'); ?>" id="lzyopti_replace_image" name="lzyopti_replace_image" value="<?php echo esc_url($lzyopti_replace_image); ?>" style="width:80%" autocomplete="off" />
	<div>
		<img src="<?php echo esc_url($lzyopti_replace_image); ?>" alt="BG lazyload image" style="max-width: 150px;margin-top: 15px;display: inline-block;">
	</div>
<?php }


function lazy_optimization_delete_data() { 
	$lzyopti_delete_all_data = get_option('lzyopti_delete_all_data');
?>
	<label for="lzyopti_delete_all_data">
		<input type="checkbox" value="1" name="lzyopti_delete_all_data" id="lzyopti_delete_all_data" <?php checked( $lzyopti_delete_all_data ); ?> />
		<span>Check this option if you want to delete all plugin's data upon deleting the plugin.</span>
	</label>

<?php }

?>