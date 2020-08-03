<?php
namespace lsx\sharing\classes\frontend;

/**
 * Houses the functions for the Settings page.
 *
 * @package lsx-sharing
 */
class Output {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\sharing\classes\frontend\Output()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 5 );
		add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html' ), 10, 2 );
		add_shortcode( 'lsx_sharing_buttons', array( $this, 'sharing_buttons_shortcode' ) );
		// Storefront (storefront_loop_post, storefront_single_post).
		add_action( 'storefront_post_content_before', array( $this, 'sharing_buttons_template' ), 20 );
		// WooCommerce.
		add_action( 'woocommerce_share', array( $this, 'sharing_buttons_template' ) );

		// General Post Types.
		add_action( 'lsx_entry_after', array( $this, 'output_sharing' ) );

		// Tribe Events.
		add_filter( 'tribe_events_ical_single_event_links', array( $this, 'output_event_sharing' ), 10, 1 );

		// Sensei Integration.
		add_action( 'sensei_pagination', array( $this, 'output_sharing' ), 20 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\sharing\classes\frontend\Output()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Enques the assets.
	 */
	public function assets() {
		if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
			$min = '';
		} else {
			$min = '.min';
		}
		/* Remove assets completely if all sharing options are off */

		if ( \lsx\sharing\includes\functions\is_disabled() ) {
			return '';
		}

		// Set our variables.
		$post_type = get_post_type();

		/* Only show the assets if the post type sharing option is on */
		if ( ! \lsx\sharing\includes\functions\is_pt_disabled( $post_type ) ) {

			wp_enqueue_script( 'lsx-sharing', LSX_SHARING_URL . 'assets/js/lsx-sharing' . $min . '.js', array( 'jquery' ), LSX_SHARING_VER, true );

			$params = apply_filters( 'lsx_sharing_js_params', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			));

			wp_localize_script( 'lsx-sharing', 'lsx_sharing_params', $params );

			wp_enqueue_style( 'lsx-sharing', LSX_SHARING_URL . 'assets/css/lsx-sharing.css', array(), LSX_SHARING_VER );
			wp_style_add_data( 'lsx-sharing', 'rtl', 'replace' );
		}
	}

	/**
	 * Display/return sharing buttons.
	 */
	public function sharing_buttons( $buttons = array( 'facebook', 'twitter', 'pinterest' ), $echo = false, $post_id = false ) {
		$sharing_content = '';

		if ( ( is_preview() || is_admin() ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return '';
		}

		if ( empty( $this->options ) ) {
			$this->options = array();
		}

		//Set our variables
		global $post;
		$share_post = $post;
		if ( false !== $post_id ) {
			$share_post = get_post( $post_id );
			$post_type = get_post_type( $post_id );
		} else {
			$post_type = get_post_type();
		}

		if ( \lsx\sharing\includes\functions\is_disabled() || \lsx\sharing\includes\functions\is_pt_disabled( $post_type ) ) {
			return '';
		}

		if ( ( is_array( $buttons ) && count( $buttons ) > 0 ) ) {
			$sharing_content .= '<div class="lsx-sharing-content"><p>';

			$sharing_text = \lsx\sharing\includes\functions\get_sharing_text( $post_type );
			if ( '' !== $sharing_text ) {
				$sharing_content .= '<span class="lsx-sharing-label">' . $sharing_text . '</span>';
			}

			foreach ( $buttons as $id => $button ) {
				$button_obj = new \lsx\sharing\classes\frontend\Button( $button, $this->options, $post_type );

				if ( ! empty( $button_obj ) ) {
					$url = $button_obj->get_link( $share_post );

					if ( ! empty( $url ) ) {
						if ( 'email' === $button ) {
							if ( ! isset( $this->options['display'] ) || empty( $this->options['display']['sharing_email_form_id'] ) ) {
								continue;
							}
							$sharing_content .= '<span class="lsx-sharing-button lsx-sharing-button-' . esc_attr( $button ) . '"><a href="#lsx-sharing-email" data-toggle="modal" data-link="' . esc_url( $url ) . '"><span class="fa" aria-hidden="true"></span></a></span>';
						} else {
							$sharing_content .= '<span class="lsx-sharing-button lsx-sharing-button-' . esc_attr( $button ) . '"><a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer"><span class="fa" aria-hidden="true"></span></a></span>';
						}
					}
				}
			}
			$sharing_content .= '</p></div>';
		}

		if ( $echo ) {
			echo wp_kses_post( $sharing_content );
		} else {
			return $sharing_content;
		}
	}

	/**
	 * Sharing buttons shortcode.
	 */
	public function sharing_buttons_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'buttons' => '',
		), $atts, 'lsx_sharing_buttons' );

		if ( empty( $atts['buttons'] ) ) {
			return '';
		}

		$no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var( $atts['buttons'], FILTER_SANITIZE_STRING ) );
		$buttons        = explode( ',', $no_whitespaces );

		if ( is_array( $buttons ) && count( $buttons ) > 0 ) {
			return $this->sharing_buttons( $buttons );
		}
	}

	/**
	 * Display buttons (template hook).
	 */
	public function sharing_buttons_template() {
		echo wp_kses_post( $this->sharing_buttons() );
	}

	/**
	 * Allow data params for Bootstrap modal.
	 */
	public function wp_kses_allowed_html( $allowedtags, $context ) {
		$allowedtags['a']['data-toggle'] = true;
		$allowedtags['a']['data-link']   = true;
		return $allowedtags;
	}

	/**
	 * Outputs the sharing to the templates.
	 *
	 * @return void
	 */
	public function output_sharing() {
		if ( is_main_query() && is_single() && ! is_singular( array( 'post', 'page', 'product' ) ) ) {

			if ( \lsx\sharing\includes\functions\is_disabled() || \lsx\sharing\includes\functions\is_pt_disabled( get_post_type() ) || in_array( get_post_type(), \lsx\sharing\includes\functions\get_restricted_post_types() ) || in_array( get_post_type(), \lsx\sharing\includes\functions\get_to_post_types() ) || in_array( get_post_type(), \lsx\sharing\includes\functions\get_hp_post_types() ) ) {
				return '';
			}
			?>
			<footer class="lsx-sharing-wrapper footer-meta clearfix">
				<div class="post-tags-wrapper">
					<?php $this->sharing_buttons_template(); ?>
				</div>
			</footer><!-- .footer-meta -->
			<?php
		}
	}

	/**
	 * Outputs the sharing below the events.
	 *
	 * @param string $ical_links
	 * @return string
	 */
	public function output_event_sharing( $ical_links = '' ) {
		if ( \lsx\sharing\includes\functions\is_disabled() || \lsx\sharing\includes\functions\is_pt_disabled( get_post_type() ) ) {
			return '';
		} else {
			$ical_links .= $this->sharing_buttons();
		}
		return $ical_links;
	}
}
