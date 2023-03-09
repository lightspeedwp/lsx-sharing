<?php
/**
 * LSX_Sharing
 *
 * @package lsx-sharing
 */
namespace LSX\Sharing;

/**
 * LSX Sharing class.
 *
 * @package lsx-sharing
 */
class Sharing {

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
        $this->frontend = \LSX\Sharing\Classes\Frontend::get_instance();
    }
}
