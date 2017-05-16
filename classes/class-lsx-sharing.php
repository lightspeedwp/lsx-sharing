<?php
/**
 * LSX_Sharing
 *
 * @package lsx-sharing
 */

if ( ! class_exists( 'LSX_Sharing' ) ) {

	/**
	 * LSX Sharing plugin class.
	 *
	 * @package lsx-sharing
	 */
	class LSX_Sharing {

		/**
		 * Plugin slug.
		 *
		 * @var string
		 */
		public $plugin_slug = 'lsx-sharing';

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
			add_shortcode( 'lsx_sharing_buttons', array( $this, 'sharing_buttons_shortcode' ) );
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

			wp_enqueue_script( 'lsx-sharing', LSX_SHARING_URL . 'assets/js/lsx-sharing' . $min . '.js', array( 'jquery' ), LSX_SHARING_VER, true );

			$params = apply_filters( 'lsx_sharing_js_params', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			));

			wp_localize_script( 'lsx-sharing', 'lsx_sharing_params', $params );

			wp_enqueue_style( 'lsx-sharing', LSX_SHARING_URL . 'assets/css/lsx-sharing.css', array(), LSX_SHARING_VER );
			wp_style_add_data( 'lsx-sharing', 'rtl', 'replace' );
		}

		/**
		 * Display/return sharing buttons.
		 */
		public function sharing_buttons( $buttons = array(), $echo = false ) {
			$sharing_content = '';

			if ( ( is_preview() || is_admin() ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				return '';
			}

			global $post;

			if ( is_array( $buttons ) && count( $buttons ) > 0 ) {
				$sharing_content .= '<div class="lsx-sharing-content"><ul>';

				foreach ( $buttons as $id => $button ) {
					$button_obj = new LSX_Sharing_Button( $button );

					if ( ! empty( $button_obj ) ) {
						$url = $button_obj->get_link( $post );

						if ( ! empty( $url ) ) {
							$sharing_content .= '<li class="lsx-sharing-button-' . esc_attr( $button ) . '"><a href="' . esc_url( $url ) . '" target="_blank"><span class="fa" aria-hidden="true"></span></a></li>';
						}
					}
				}

				$sharing_content .= '</ul></div>';
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
			$buttons = explode( ',', $no_whitespaces );

			if ( is_array( $buttons ) && count( $buttons ) > 0 ) {
				return $this->sharing_buttons( $buttons );
			}
		}

	}

}
