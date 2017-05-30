<?php
/*
 * Plugin Name: LSX Sharing
 * Description: Sharing plugin for LSX Theme.
 * Version:     1.0.0
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-sharing
 * Domain Path: /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_SHARING_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_SHARING_CORE', __FILE__ );
define( 'LSX_SHARING_URL',  plugin_dir_url( __FILE__ ) );
define( 'LSX_SHARING_VER',  '1.0.0' );

require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing.php' );
require_once( LSX_SHARING_PATH . '/classes/class-lsx-sharing-button.php' );

global $lsx_sharing;
$lsx_sharing = new LSX_Sharing();