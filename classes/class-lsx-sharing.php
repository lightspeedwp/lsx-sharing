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
			add_action( 'init', array( $this, 'set_options' ), 50 );
			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
			add_shortcode( 'lsx_sharing_buttons', array( $this, 'sharing_buttons_shortcode' ) );
			add_action( 'wp_footer', array( $this, 'add_email_modal' ) );
			add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html' ), 10, 2 );
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
		public function sharing_buttons( $buttons = array( 'email', 'facebook', 'twitter', 'pinterest' ), $echo = false ) {
			$sharing_content = '';

			if ( ( is_preview() || is_admin() ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				return '';
			}

			global $post;

			$post_type = get_post_type();

			if ( isset( $this->options['display'] ) && ! empty( $this->options['display'][ 'sharing_disable_pt_' . $post_type ] ) ) {
				return '';
			}

			if ( is_array( $buttons ) && count( $buttons ) > 0 ) {
				$sharing_content .= '<div class="lsx-sharing-content"><p>';

				if ( isset( $this->options['display'] ) && ! empty( $this->options['display']['sharing_label_text'] ) ) {
					$sharing_content .= '<span class="lsx-sharing-label">' . $this->options['display']['sharing_label_text'] . '</span>';
				}

				foreach ( $buttons as $id => $button ) {
					$button_obj = new LSX_Sharing_Button( $button, $this->options );

					if ( ! empty( $button_obj ) ) {
						$url = $button_obj->get_link( $post );

						if ( ! empty( $url ) ) {
							if ( 'email' === $button ) {
								if ( ! isset( $this->options['display'] ) || empty( $this->options['display']['sharing_email_form_id'] ) ) {
									continue;
								}

								$sharing_content .= '<span class="lsx-sharing-button lsx-sharing-button-' . esc_attr( $button ) . '"><a href="#lsx-sharing-email" data-toggle="modal" data-link="' . esc_url( $url ) . '"><span class="fa" aria-hidden="true"></span></a></span>';
							} else {
								$sharing_content .= '<span class="lsx-sharing-button lsx-sharing-button-' . esc_attr( $button ) . '"><a href="' . esc_url( $url ) . '" target="_blank"><span class="fa" aria-hidden="true"></span></a></span>';
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
			$buttons = explode( ',', $no_whitespaces );

			if ( is_array( $buttons ) && count( $buttons ) > 0 ) {
				return $this->sharing_buttons( $buttons );
			}
		}

		/**
		 * Add email modal.
		 */
		public function add_email_modal() {
			if ( ! isset( $this->options['display'] ) || empty( $this->options['display']['sharing_email_form_id'] ) ) {
				return '';
			}

			$form_id = $this->options['display']['sharing_email_form_id'];
			?>
			<div id="lsx-sharing-email" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><?php esc_html_e( 'Share', 'lsx-sharing' ); ?></h4>
						</div>
						<div class="modal-body">
							<?php echo do_shortcode( '[caldera_form id="' . $form_id . '"]' ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Allow data params for Bootstrap modal.
		 */
		public function wp_kses_allowed_html( $allowedtags, $context ) {
			$allowedtags['a']['data-toggle'] = true;
			$allowedtags['a']['data-link'] = true;
			return $allowedtags;
		}

	}

}
