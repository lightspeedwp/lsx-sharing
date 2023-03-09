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
    }

    /**
     * Loads the plugin functions.
     */
    private function load_includes() {
         include_once LSX_SHARING_PATH . '/includes/functions.php';
    }
}
