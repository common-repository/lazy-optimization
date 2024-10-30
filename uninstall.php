<?php
/**
 * Runs on uninstall of Lazy Optimization plugin
 */
// Check that we should be doing this
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit; // Exit if accessed directly
}

if( get_option('lzyopti_delete_all_data') == 1 ){

	// Deleting Lazy Optimization settings options

	$options = array(
		'lzyopti_global_images_to_exclude',
		'lzyopti_replace_image',
		'lzyopti_delete_all_data',
		'lzyopti_already_done_single_css'
	);

	foreach ( $options as $option ) {
			delete_option( $option );
	}

	if( class_exists( 'autoptimizeMain' ) ) {
		autoptimizeCache::clearall();
		autoptimize()->run();
	}
}

?>