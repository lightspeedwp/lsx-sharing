<?php
/**
 * LSX_Sharing_Frontend
 *
 * @package lsx-sharing
 */

namespace lsx\sharing\classes;

/**
 * LSX Sharing front-end class.
 *
 * @package lsx-sharing
 */
class Frontend {


    /**
     * Holds class instance
     *
     * @since 1.0.0
     *
     * @var object \lsx\sharing\classes\Frontend()
     */
    protected static $instance = null;

    /**
     * Holds the output class.
     *
     * @var object \lsx\sharing\classes\frontend\Output()
     */
    public $output = null;

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
     * @return object \lsx\sharing\classes\Frontend()    A single instance of this class.
     */
    public static function get_instance() {
         // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Loads the plugin functions.
     */
    private function load_classes() {
         include_once LSX_SHARING_PATH . '/classes/frontend/class-button.php';
        include_once LSX_SHARING_PATH . '/classes/frontend/class-output.php';
        $this->output = \lsx\sharing\classes\frontend\Output::get_instance();
    }

    /**
     * Backwards compatabile function for the sharing buttons.
     */
    public function sharing_buttons( $buttons = array( 'facebook', 'twitter', 'pinterest' ), $echo = false, $post_id = false ) {
         wc_deprecated_function('LSX_Sharing_Frontend::sharing_buttons()', '1.2.0', 'lsx_sharing()->frontend->output->sharing_buttons()');
        $this->output->sharing_buttons($buttons, $echo, $post_id);
    }
}
