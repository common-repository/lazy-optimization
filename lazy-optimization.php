<?php
/*
Plugin Name: Lazy Optimization
Description: Lazy load background images that are in external CSS files. 
Author: Muhammad Yasir Naseem
Author URI: https://wphowknow.com/
Version: 1.0.4
Text Domain: lazy-optimization
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
* Lazy Optimization main plugin file.
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define( 'Lazy_Optimization_Plugin_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'Lazy_Optimization_Plugin_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'Lazy_Optimization_CSS_DIR', WP_CONTENT_DIR."/lazy-optimization/css");
define( 'Lazy_Optimization_JS_DIR', WP_CONTENT_DIR."/lazy-optimization/js");
define( 'Lazy_Optimization_CSS_URL', WP_CONTENT_URL."/lazy-optimization/css");
define( 'Lazy_Optimization_JS_URL', WP_CONTENT_URL."/lazy-optimization/js");
define( 'Lazy_Optimization_IMG_URL', Lazy_Optimization_Plugin_URL."/img");

// Including Required Files
require_once 'settings/activate/lzy-opti-activate.php';
require_once 'admin/admin-settings.php';

	if( !class_exists( 'autoptimizeMain' ) ) {
		return; // If Autoptimize plugin not activate quit
	}
	
register_activation_hook(__FILE__, 'lzyopti_activating_function'); // Run on plugin Activation

add_filter( 'autoptimize_css_after_minify', 'lazyopti_bg_images_rules_extaction');
function lazyopti_bg_images_rules_extaction($code) {

	global $lzyopti_CSS_file_hash, $lzyopti_elements_array, $lzyopti_url_array, $lzyopti_is_running_first_time, $hash_array, $excluded_images_list_array, $final_style_CSS_file, $styles_to_include_in_extra_SS;

	$excluded_images_list = apply_filters( 'lzyopti_global_excluded_imgs', get_option('lzyopti_global_images_to_exclude', '' ));
	$excluded_images_list_array = explode(',', $excluded_images_list);
	$final_style_CSS_file = '';
	$styles_to_include_in_extra_SS = '';

	foreach ($hash_array as $hash_item => $hash_value) {

		if( strpos($hash_value, 'single_') !== false ) {

			$single_minified_CSS_file_path = WP_CONTENT_DIR.AUTOPTIMIZE_CACHE_CHILD_DIR.'css/autoptimize_'.$hash_value.'.css';
			$single_minified_CSS_file_exist = file_exists($single_minified_CSS_file_path);	//check if file exists as single (means already minified version in wp-content -> cache -> autoptimize -> css)

			if( $single_minified_CSS_file_exist ) {
				$lzyopti_is_running_first_time = true;

				$lzyopti_already_done_single_css = get_option('lzyopti_already_done_single_css', array(''));

				if( !in_array($hash_value, $lzyopti_already_done_single_css) ) {
					array_push($lzyopti_already_done_single_css, $hash_value);
					update_option('lzyopti_already_done_single_css', $lzyopti_already_done_single_css);
					$send_css_code = file_get_contents($single_minified_CSS_file_path);
					lzyopti_code_work($send_css_code, $hash_value, $single_minified_CSS_file_path, false);
				}
				else {
					$send_css_code = file_get_contents($single_minified_CSS_file_path);
					lzyopti_code_work($send_css_code, $hash_value, $single_minified_CSS_file_path, true);
				}
			}
		}
		else {
				$lzyopti_is_running_first_time = true;
				$lzyopti_CSS_file_hash = $hash_value;
				$code = lzyopti_code_work($code, $hash_value, '', false);
		}
	}
	
	if( !empty($final_style_CSS_file) ) {
		file_put_contents(Lazy_Optimization_CSS_DIR.'/css-'.$lzyopti_CSS_file_hash.'.css', $final_style_CSS_file);

		//JS code for replacing background image with original Image
		$js_code = 'document.addEventListener("DOMContentLoaded",function(){var e=document.createElement("style");e.appendChild(document.createTextNode("")),e.id="lazyopti_stylesheet",document.head.appendChild(e);var t,n=document.styleSheets,r="";for(i=0,len=n.length;i<len;i++)n[i].href||"lazyopti"!=n[i].ownerNode.id||(r=n[i].cssRules),n[i].href||"lazyopti_stylesheet"!=n[i].ownerNode.id||(t=n[i]);function o(e,t,n,r){"insertRule"in e?e.insertRule(n,r):"addRule"in e&&e.addRule(t,n,r)}function c(e){var t=[];return function(n){var r,o,i;for(r=0,o=n.length;r<o;r++)(i=n[r])instanceof CSSStyleRule&&e.forEach(function(e){e.matches(i.selectorText)&&t.push(i)})}(r),t}var l=[],s=[],f='.json_encode($lzyopti_elements_array).';'.json_encode($lzyopti_url_array).';f.forEach(function(e){l.push(document.querySelectorAll(e))}),l.forEach(function(e){e.forEach(function(n){if("IntersectionObserver"in window){let n=new IntersectionObserver(function(e,r){e.forEach(function(e){if(e.isIntersecting){var r=e.target,i=r.classList,l=r.id,u=r.tagName,d=!1,a=!1;if(void 0!==i&&0!=i.length&&i.forEach(function(e){var n=c(document.querySelectorAll("."+e));n=n.reverse();for(var r=0;r<n.length;r++)if(-1!=f.lastIndexOf(n[r].selectorText)){if(-1!=s.indexOf(n[r].selectorText))continue;o(t,n[r].selectorText,n[r].cssText),-1==s.indexOf(n[r].selectorText)&&s.push(n[r].selectorText),d=!0}}),l&&0==i.length){x=(x=c(document.querySelectorAll("#"+l))).reverse();for(var h=0;h<x.length;h++)if(-1!=f.lastIndexOf(x[h].selectorText)){if(-1!=s.indexOf(x[h].selectorText))continue;o(t,x[h].selectorText,x[h].cssText),-1==s.indexOf(x[h].selectorText)&&s.push(x[h].selectorText),a=!0}}if(!d&&!a){var x;x=(x=c(document.querySelectorAll(u))).reverse();for(h=0;h<x.length;h++)if(-1!=f.lastIndexOf(x[h].selectorText)){if(-1!=s.indexOf(x[h].selectorText))continue;o(t,u,x[h].cssText),-1==s.indexOf(x[h].selectorText)&&s.push(x[h].selectorText)}}n.unobserve(e.target)}})});e.forEach(function(e){n.observe(e)})}})})});';

	file_put_contents(Lazy_Optimization_JS_DIR.'/js-'.$lzyopti_CSS_file_hash.'.js', $js_code);
	}

	if( !empty($styles_to_include_in_extra_SS) ) {
		file_put_contents(Lazy_Optimization_CSS_DIR.'/extra-'.$lzyopti_CSS_file_hash.'.css', $styles_to_include_in_extra_SS);
	}

  	return $code;

}


function lzyopti_code_work($code, $hash_value, $single_CSS_file_path, $single_css_exists) {
	
	global $excluded_images_list_array, $lzyopti_elements_array, $lzyopti_url_array, $final_style_CSS_file, $styles_to_include_in_extra_SS;


    if( !empty($single_CSS_file_path) && !$single_css_exists ) { // If is single file, create its replica in plugin's css folder
		file_put_contents(Lazy_Optimization_CSS_DIR.'/'.$hash_value.'.css', $code);
	}
	elseif( !empty($single_CSS_file_path) && $single_css_exists ){ // If single css file already exists, get it's css data from plugin css folder
		$code = file_get_contents(Lazy_Optimization_CSS_DIR.'/'.$hash_value.'.css');
	}

	preg_match_all ("/[^}*\/]*{(?:[^}]*.?)(?:background-image|background)[\s]*:(?:[^}]*.?)url\(['\"]?(.*?)['\"]?\).*?}/i", $code, $extracted_bg_rules); // Extracting Rules From Autoptimize CSS That Contains background image property

	if( empty($extracted_bg_rules[0]) ) { // Check If there are extracted rules
		return;
	}

	foreach ($extracted_bg_rules[0] as $extracted_rule_key => $extracted_rule_value) {
		echo '<br>';
		preg_match_all ("/(?:.*?){(.*?)}/i", $extracted_rule_value, $get_rule_expression); // preg to get the rule value/expression.

		preg_match_all ("/url\(['\"]?(.*?)['\"]?\)/i", $extracted_rule_value, $get_url); // Extract URL of background Image

		preg_match_all ("/(.*?)(?:{)/i", $extracted_rule_value, $extract_elements_selector); // Extract rule selector

		// Is image to exclude?
		$exclude_from_lzy_load = false;
		if( !empty($excluded_images_list_array[0]) ) {
			foreach ($excluded_images_list_array as $i => $exclude_array_item) {
				foreach ($get_url[1] as $key => $value) {
					if( strpos($value, rtrim(ltrim($exclude_array_item))) !== false ) {
						$exclude_from_lzy_load = true;
						break;
					}
				}
			}
		}

		if( $exclude_from_lzy_load == false ) {

            if( strpos($get_url[1][0], 'data:') !== false ) {
				$temp_rule = $extracted_rule_value;
				$styles_to_include_in_extra_SS .= $temp_rule;
				continue;
			}

			$exploded_elements_array = explode(',', $extract_elements_selector[1][0]);

			foreach ($exploded_elements_array as $element_key => $element_value) {
				if( strpos($element_value, ':') !== false ) {

					// If rule has ":" in it ( which means ::after, :before, :hover etc), then prepare style to add in stylesheet with media = lazyextraCSS
					$temp_rule = $element_value.'{'.$get_rule_expression[1][0].'}';
					$styles_to_include_in_extra_SS .= $temp_rule;
				}
				else {
					// If rule is simple then prepare style to add in stylesheet whose hash = page stylesheet hash 
					$element_value = preg_replace("/\s*([>])\s*/", " $1 ", $element_value);
					array_push($lzyopti_elements_array, $element_value);
					array_push($lzyopti_url_array, $get_url[1][0]);
					$element_CSS = $element_value.'{'.$get_rule_expression[1][0].'}';
					$final_style_CSS_file .= $element_CSS;
				}	
			}

			$lzyopti_replace_image_url = get_option('lzyopti_replace_image');

			if( empty($lzyopti_replace_image_url) ) {
				$lzyopti_replace_image_url = Lazy_Optimization_IMG_URL.'/loading.gif';
			}

			foreach ($get_url[1] as $get_url_key => $get_url_value) {
				// Replace original background-images URL with a dummy background image
				if (strpos($code, $get_url_value) !== false) {
				    $code = str_replace($get_url[1][$get_url_key], $lzyopti_replace_image_url, $code);
				}
			}
		}
	}

    if( !empty($single_CSS_file_path) ){ // If is single file change its content
		file_put_contents($single_CSS_file_path, $code);
		return;
	}
	else{
		return $code;
	}
}

add_action( 'after_setup_theme', 'lzyopti_global_vars' );
function lzyopti_global_vars() {

	// Declaring Global Variables
    global $lzyopti_CSS_file_hash, $lzyopti_CSS_file_exist, $lzyopti_elements_array, $lzyopti_url_array, $lzyopti_is_running_first_time, $hash_array;
    $lzyopti_elements_array = array();
    $lzyopti_url_array = array();
    $lzyopti_CSS_file_exist = false;
	$lzyopti_CSS_file_hash = 'hash-issue';
	$hash_array = array();
}

add_action( 'autoptimize_action_css_hash', 'lzyopti_getting_hash_value' );
function lzyopti_getting_hash_value($hash) {
	
	global $lzyopti_CSS_file_hash, $lzyopti_CSS_file_exist, $hash_array;
	array_push($hash_array, $hash);
	$CSS_file_exist = file_exists( Lazy_Optimization_CSS_DIR.'/css-'.$hash.'.css');
	$extra_CSS_file_exist = file_exists( Lazy_Optimization_CSS_DIR.'/extra-'.$hash.'.css');

	// Defining Hash value
	if($lzyopti_CSS_file_exist) {
		return;
	}
	if(	$CSS_file_exist || $extra_CSS_file_exist ) {
		$lzyopti_CSS_file_hash = $hash;
		$lzyopti_CSS_file_exist = true;
		return;
	}
	else {
		$lzyopti_CSS_file_hash = $hash;
	}
}

add_filter( 'autoptimize_html_after_minify', 'lzyopti_injecting_css_and_js' );
function lzyopti_injecting_css_and_js($content) {

	global $lzyopti_CSS_file_hash, $lzyopti_CSS_file_exist, $lzyopti_is_running_first_time;

    if(get_option( 'autoptimize_css' ) != 'on') {
        return $content;
    }

	if( $lzyopti_is_running_first_time ) { //If running for first time get file hash from <head> section
		preg_match_all ("/<head>[\s\S]*<\/head>/i", $content, $extracted_content);
		preg_match_all ("/<link.*?autoptimize_(?!single)(.*?)\.css.*?\/>/i", $extracted_content[0][0], $new_extracted_content);
		$lzyopti_CSS_file_hash = $new_extracted_content[1][0];
		$lzyopti_CSS_file_exist = Lazy_Optimization_CSS_DIR.'/css-'.$lzyopti_CSS_file_hash.'.css';
	}

	if(	$lzyopti_CSS_file_exist ) { //If CSS file exists add(CSS/JS) just before </head> tag.

		$css_file_path = Lazy_Optimization_CSS_DIR.'/css-'.$lzyopti_CSS_file_hash.'.css';
		$css_file_content = file_get_contents($css_file_path);
		$content = str_replace('</head>', '<style id="lazyopti" media="lazyopti_CSS">'.$css_file_content.'</style></head>', $content);

		$js_file_path = Lazy_Optimization_JS_URL.'/js-'.$lzyopti_CSS_file_hash.'.js';
		$add_bg_images_js = '<script src="'.$js_file_path.'"></script>';
		$content = str_replace('</body>', $add_bg_images_js.'</body>', $content);
	}
	
	$extra_CSS_file_exist = file_exists( Lazy_Optimization_CSS_DIR.'/extra-'.$lzyopti_CSS_file_hash.'.css');

	if( $extra_CSS_file_exist ) { //If Extra CSS file exists add(CSS) just before </body> tag.
	
		$extra_CSS_file_path = Lazy_Optimization_CSS_URL.'/extra-'.$lzyopti_CSS_file_hash.'.css';

		$add_bg_images_extra_js = '<script>document.addEventListener("DOMContentLoaded",function(){var e=0;for(event of["scroll","mousemove"])window.addEventListener(event,function(){if(0==e&&window.pageYOffset>=0){console.log("Bacakground images loaded"),e++;var t=document.createElement("link");t.setAttribute("rel","stylesheet"),t.setAttribute("type","text/css"),t.setAttribute("href","'.$extra_CSS_file_path.'"),document.getElementsByTagName("head")[0].appendChild(t)}})});</script>';

		$content = str_replace('</body>', $add_bg_images_extra_js.'</body>', $content);

	}

	return $content;

}

add_action( 'wp_ajax_autoptimize_delete_cache', 'lzyopti_del_plugin_static_files', 5 ); // Clear lazy optimization static files on Ajax request made by Autoptimize
add_action( 'autoptimize_action_cachepurged', 'lzyopti_del_plugin_static_files', 12 ); // Clear lazy optimization static files on request via "Save Changes and Empty Cache" in Autoptimize Settings

function lzyopti_del_plugin_static_files() {

	// Delete Old CSS and JS files when clear autoptimize cache
	$css_files = glob(Lazy_Optimization_CSS_DIR.'/*'); // Get all CSS files from plugin folder
	foreach($css_files as $css_file){ // iterate css files
	  if(is_file($css_file))
	    unlink($css_file); // delete css file
	}

	$js_files = glob(Lazy_Optimization_JS_DIR.'/*'); // Get all js files from plugin folder
	foreach($js_files as $js_file){ // iterate js files
	  if(is_file($js_file))
	    unlink($js_file); // delete js file
	}
	
	delete_option( 'lzyopti_already_done_single_css' );
}

?>