<?php
/**
 * LSX_Sharing
 *
 * @package lsx-sharing
 */
namespace lsx\sharing;

/**
 * LSX Sharing class.
 *
 * @package lsx-sharing
 */
class Sharing {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\search\Sharing()
	 */
	protected static $instance = null;

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\search\classes\Admin()
	 */
	public $admin = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->load_vendors();
		$this->load_classes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\member_directory\search\Admin()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads the plugin functions.
	 */
	private function load_vendors() {
		// Configure custom fields.
		if ( ! class_exists( 'CMB2' ) ) {
			require_once LSX_SHARING_PATH . 'vendor/CMB2/init.php';
		}
	}
	/**
	 * Loads the plugin functions.
	 */
	private function load_classes() {
		require_once LSX_SHARING_PATH . '/classes/class-admin.php';
		require_once LSX_SHARING_PATH . '/classes/class-lsx-sharing-frontend.php';
		require_once LSX_SHARING_PATH . '/classes/class-lsx-sharing-button.php';

		global $lsx_sharing_admin;
		$this->admin = \lsx\sharing\classes\Admin::get_instance();
		$lsx_sharing_admin;

		global $lsx_sharing;
		$lsx_sharing = new \LSX_Sharing_Frontend();
	}
}
