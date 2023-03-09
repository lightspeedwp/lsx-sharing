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

	public function init() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_block_variations' ) );
	}

    /**
     * Loads the plugin functions.
     */
    private function load_includes() {
         include_once LSX_SHARING_PATH . '/includes/functions.php';
    }

	/**
	 * Registers our block variations.
	 *
	 * @return void
	 */
	public function register_block_variations() {
		wp_enqueue_script(
			'lsx-sharing-block',
			LSX_SHARING_URL . '/build/blocks.js',
			array( 'wp-blocks','wp-element','wp-primitives' )
		);
	}
}
