<?php
/*
 * Plugin Name: LSX Sharing
 * Plugin URI:  https://www.lsdev.biz/product/lsx-sharing/
 * Description: The LSX Sharing extension add social share icons for LSX Theme.
 * Version:     2.0.1
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-sharing
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined('WPINC') ) {
    die;
}

define('LSX_SHARING_PATH', plugin_dir_path(__FILE__));
define('LSX_SHARING_CORE', __FILE__);
define('LSX_SHARING_URL', plugin_dir_url(__FILE__));
define('LSX_SHARING_VER', '2.0.1');

/* ======================= Below is the Plugin Class init ========================= */
require_once LSX_SHARING_PATH . 'includes/classes/class-sharing.php';

/**
 * Returns and instance of the LSX Sharing plugin.
 *
 * @return object \lsx\sharing\Sharing();
 */
function lsx_sharing() {
	global $lsx_sharing;
	if ( null === $lsx_sharing ) {
		$lsx_sharing = new \lsx\sharing\Sharing();
		$lsx_sharing->init();
	}
    return $lsx_sharing;
}
add_action( 'plugins_loaded', 'lsx_sharing' );
