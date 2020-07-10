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
	 * If we are using the new options or not.
	 *
	 * @var boolean
	 */
	public $is_new_options = false;

	/**
	 * The options for the plugin.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'set_options' ), 50 );
		$this->load_vendors();
		$this->load_includes();
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
	private function load_includes() {
		require_once LSX_SHARING_PATH . '/includes/functions.php';
	}
	/**
	 * Loads the plugin functions.
	 */
	private function load_classes() {
		require_once LSX_SHARING_PATH . '/classes/class-admin.php';
		global $lsx_sharing_admin;
		$this->admin = \lsx\sharing\classes\Admin::get_instance();
		$lsx_sharing_admin;

		require_once LSX_SHARING_PATH . '/classes/class-frontend.php';
		$this->frontend = \lsx\sharing\classes\Frontend::get_instance();
		global $lsx_sharing;
		$lsx_sharing = $this->frontend->output;
	}
	/**
	 * Set options.
	 */
	public function set_options() {
		if ( function_exists( 'tour_operator' ) ) {
			$this->options = get_option( '_lsx-to_settings', false );
		} else {
			$this->options = get_option( '_lsx_settings', false );

			if ( false === $this->options ) {
				$this->options = get_option( '_lsx_lsx-settings', false );
			}
		}

		$new_options = get_option( 'lsx-sharing-settings' );
		if ( ! empty( $new_options ) ) {
			if ( '' !== $new_options && false !== $new_options ) {
				$this->is_new_options = true;
				$this->options        = $new_options;
			}
		}
	}
}
