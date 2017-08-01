<?php
/**
 * LSX_Sharing_Admin
 *
 * @package lsx-sharing
 */

if ( ! class_exists( 'LSX_Sharing_Admin' ) ) {

	/**
	 * LSX Sharing admin class.
	 *
	 * @package lsx-sharing
	 */
	class LSX_Sharing_Admin {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'set_options' ), 50 );
			add_action( 'init', array( $this, 'create_settings_page' ), 100 );

			add_filter( 'lsx_to_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );
			add_filter( 'lsx_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );
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
		 * Returns the array of settings to the UIX Class.
		 */
		public function create_settings_page() {
			if ( is_admin() ) {
				if ( ! class_exists( '\lsx\ui\uix' ) && ! function_exists( 'tour_operator' ) ) {
					include_once LSX_SHARING_PATH . 'vendor/uix/uix.php';
					$pages = $this->settings_page_array();
					$uix = \lsx\ui\uix::get_instance( 'lsx' );
					$uix->register_pages( $pages );
				}

				if ( function_exists( 'tour_operator' ) ) {
					add_action( 'lsx_to_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
				} else {
					add_action( 'lsx_framework_display_tab_content', array( $this, 'display_settings' ), 11 );
				}
			}
		}

		/**
		 * Returns the array of settings to the UIX Class.
		 */
		public function settings_page_array() {
			$tabs = apply_filters( 'lsx_framework_settings_tabs', array() );

			return array(
				'settings'  => array(
					'page_title'  => esc_html__( 'Theme Options', 'lsx-sharing' ),
					'menu_title'  => esc_html__( 'Theme Options', 'lsx-sharing' ),
					'capability'  => 'manage_options',
					'icon'        => 'dashicons-book-alt',
					'parent'      => 'themes.php',
					'save_button' => esc_html__( 'Save Changes', 'lsx-sharing' ),
					'tabs'        => $tabs,
				),
			);
		}

		/**
		 * Register tabs.
		 */
		public function register_tabs( $tabs ) {
			$default = true;

			if ( false !== $tabs && is_array( $tabs ) && count( $tabs ) > 0 ) {
				$default = false;
			}

			if ( ! function_exists( 'tour_operator' ) ) {
				if ( ! array_key_exists( 'display', $tabs ) ) {
					$tabs['display'] = array(
						'page_title'        => '',
						'page_description'  => '',
						'menu_title'        => esc_html__( 'Display', 'lsx-sharing' ),
						'template'          => LSX_SHARING_PATH . 'includes/settings/display.php',
						'default'           => $default,
					);

					$default = false;
				}

				if ( ! array_key_exists( 'api', $tabs ) ) {
					$tabs['api'] = array(
						'page_title'        => '',
						'page_description'  => '',
						'menu_title'        => esc_html__( 'API', 'lsx-sharing' ),
						'template'          => LSX_SHARING_PATH . 'includes/settings/api.php',
						'default'           => $default,
					);

					$default = false;
				}
			}

			return $tabs;
		}

		/**
		 * Outputs the display tabs settings.
		 */
		public function display_settings( $tab = 'general' ) {
			if ( 'sharing' === $tab ) {
				$this->general_fields();
				$this->post_type_fields();
				$this->social_network_fields();
			}
		}

		/**
		 * Outputs the general fields.
		 */
		public function general_fields() {
			?>
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_label_text"><?php esc_html_e( 'Label text', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					<input type="text" {{#if sharing_label_text}} value="{{sharing_label_text}}" {{/if}} name="sharing_label_text" />
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_email_form_id"><?php esc_html_e( 'Caldera Form ID (e-mail sharing)', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					<input type="text" {{#if sharing_email_form_id}} value="{{sharing_email_form_id}}" {{/if}} name="sharing_email_form_id" />
				</td>
			</tr>
			<?php /*<!--
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_twitter_username"><?php esc_html_e( 'Twitter username', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					<input type="text" {{#if sharing_twitter_username}} value="{{sharing_twitter_username}}" {{/if}} name="sharing_twitter_username" />
				</td>
			</tr>
			-->*/ ?>
			<?php
		}

		/**
		 * Outputs the post type fields.
		 */
		public function post_type_fields() {
			?>
			<tr class="form-field">
				<th scope="row" colspan="2">
					<h3><?php esc_html_e( 'Disable share buttons by post type', 'lsx-sharing' ); ?></h3>
				</th>
			</tr>
			<?php
				$post_types = get_post_types( array(
					'public' => true,
				) );

				$key = array_search( 'attachment', $post_types, true );

				if ( false !== $key ) {
					unset( $post_types[ $key ] );
				}
			?>
			<?php foreach ( $post_types as $key => $value ) : ?>
				<tr class="form-field">
					<th scope="row">
						<label for="sharing_disable_pt_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( ucfirst( $key ) ); ?></label>
					</th>
					<td>
						<input type="checkbox" {{#if sharing_disable_pt_<?php echo esc_attr( $key ); ?>}} checked="checked" {{/if}} name="sharing_disable_pt_<?php echo esc_attr( $key ); ?>" />
						<small>
							<?php
								printf(
									/* Translators: 1: post type */
									esc_html__( 'Disable share buttons on post type: %s', 'lsx-sharing' ),
									esc_html( $key )
								);
							?>
						</small>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php }

		/**
		 * Outputs the social network fields.
		 */
		public function social_network_fields() {
			?>
			<tr class="form-field">
				<th scope="row" colspan="2">
					<h3><?php esc_html_e( 'Disable share buttons by social network', 'lsx-sharing' ); ?></h3>
				</th>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_disable_email"><?php esc_html_e( 'Disable E-mail', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					<input type="checkbox" {{#if sharing_disable_email}} checked="checked" {{/if}} name="sharing_disable_email" />
					<small><?php esc_html_e( 'Disable E-mail share button.', 'lsx-sharing' ); ?></small>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_disable_facebook"><?php esc_html_e( 'Disable Facebook', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					<input type="checkbox" {{#if sharing_disable_facebook}} checked="checked" {{/if}} name="sharing_disable_facebook" />
					<small><?php esc_html_e( 'Disable Facebook share button.', 'lsx-sharing' ); ?></small>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_disable_twitter"><?php esc_html_e( 'Disable Twitter', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					<input type="checkbox" {{#if sharing_disable_twitter}} checked="checked" {{/if}} name="sharing_disable_twitter" />
					<small><?php esc_html_e( 'Disable Twitter share button.', 'lsx-sharing' ); ?></small>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_disable_pinterest"><?php esc_html_e( 'Disable Pinterest', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					<input type="checkbox" {{#if sharing_disable_pinterest}} checked="checked" {{/if}} name="sharing_disable_pinterest" />
					<small><?php esc_html_e( 'Disable Pinterest share button.', 'lsx-sharing' ); ?></small>
				</td>
			</tr>
		<?php }

	}

}
