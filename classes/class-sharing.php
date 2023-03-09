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
     * @var object \lsx\search\Sharing()
     */
    protected static $instance = null;

    /**
     * Holds class instance
     *
     * @since 1.0.0
     *
     * @var object \lsx\search\classes\Admin()
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
        $this->load_includes();
        $this->load_classes();
    }

    /**
     * Return an instance of this class.
     *
     * @since 1.0.0
     *
     * @return object \lsx\member_directory\search\Admin()    A single instance of this class.
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
    private function load_includes() {
         include_once LSX_SHARING_PATH . '/includes/functions.php';
    }
    /**
     * Loads the plugin functions.
     */
    private function load_classes() {
        include_once LSX_SHARING_PATH . '/classes/class-frontend.php';
        $this->frontend = \lsx\sharing\classes\Frontend::get_instance();
    }
}
