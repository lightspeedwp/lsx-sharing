<?php
/*
 * Plugin Name: LSX Sharing
 * Plugin URI:  https://www.lsdev.biz/product/lsx-sharing/
 * Description: Sharing plugin for LSX Theme.
 * Version:     1.1
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-sharing
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_SHARING_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_SHARING_CORE', __FILE__ );
define( 'LSX_SHARING_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_SHARING_VER', '1.1' );

/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing.php' );
require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing-admin.php' );
require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing-frontend.php' );
require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing-button.php' );

global $lsx_sharing_admin;
$lsx_sharing_admin = new LSX_Sharing_Admin();

global $lsx_sharing;
$lsx_sharing = new LSX_Sharing_Frontend();
