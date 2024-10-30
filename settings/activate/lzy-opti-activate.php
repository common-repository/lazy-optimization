<?php

// Run on plugin Activation
function lzyopti_activating_function() {
    if( class_exists( 'autoptimizeMain' ) ) {
		autoptimizeCache::clearall();
		autoptimize()->run();
	}

	$lzy_opti_css_dir = WP_CONTENT_DIR.'/lazy-optimization/css';
	$lzy_opti_js_dir = WP_CONTENT_DIR.'/lazy-optimization/js';

	//If CSS folder does not exist, create a new one in wp-content folder
    if ( ! file_exists( $lzy_opti_css_dir ) ) {
        @mkdir( $lzy_opti_css_dir, 0775, true ); // @codingStandardsIgnoreLine
        if ( ! file_exists( $lzy_opti_css_dir ) ) {
            return false;
        }
    }

    //If Js folder does not exist, create a new one in wp-content folder
    if ( ! file_exists( $lzy_opti_js_dir ) ) {
        @mkdir( $lzy_opti_js_dir, 0775, true ); // @codingStandardsIgnoreLine
        if ( ! file_exists( $lzy_opti_js_dir ) ) {
            return false;
        }
    }
}

?>