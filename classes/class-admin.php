<?php
/**
 * LSX_Sharing_Admin
 *
 * @package lsx-sharing
 */

namespace lsx\sharing\classes;

/**
 * LSX Sharing admin class.
 *
 * @package lsx-sharing
 */
class Admin {

	/**
	 * Holds class instance.
	 *
	 * @var      object \lsx\sharing\classes\Admin()
	 */
	protected static $instance = null;

	/**
	 * Holds Settings Theme Instance
	 *
	 * @var object \lsx\sharing\classes\admin\Settings_Theme()
	 */
	public $settings_theme = false;

	/**
	 * Holds Settings Instance
	 *
	 * @var      object \lsx\sharing\classes\admin\Settings()
	 */
	public $settings = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->load_classes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\sharing\Admin()    A single instance of this class.
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
	private function load_classes() {
		require_once LSX_SHARING_PATH . '/classes/admin/class-settings-theme.php';
		$this->settings_theme = \lsx\sharing\classes\admin\Settings_Theme::get_instance();
		require_once LSX_SHARING_PATH . '/classes/admin/class-settings.php';
		$this->settings = \lsx\sharing\classes\admin\Settings::get_instance();
	}
}
