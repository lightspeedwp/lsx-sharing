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

		public function __construct() {
			if ( class_exists( 'Tour_Operator' ) ) {
				$this->options = get_option( '_lsx-to_settings', false );
			} else {
				$this->options = get_option( '_lsx_settings', false );

				if ( false === $this->options ) {
					$this->options = get_option( '_lsx_lsx-settings', false );
				}
			}

			add_action( 'init', array( $this, 'create_settings_page' ), 100 );
			add_filter( 'lsx_framework_settings_tabs', array( $this, 'register_tabs' ), 100, 1 );
		}

		/**
		 * Returns the array of settings to the UIX Class.
		 */
		public function create_settings_page() {
			if ( is_admin() ) {
				if ( ! class_exists( '\lsx\ui\uix' ) && ! class_exists( 'Tour_Operator' ) ) {
					include_once LSX_SHARING_PATH . 'vendor/uix/uix.php';
					$pages = $this->settings_page_array();
					$uix = \lsx\ui\uix::get_instance( 'lsx' );
					$uix->register_pages( $pages );
				}

				if ( class_exists( 'Tour_Operator' ) ) {
					add_action( 'lsx_framework_sharing_tab_content', array( $this, 'sharing_settings' ), 11 );
				} else {
					add_action( 'lsx_framework_sharing_tab_content', array( $this, 'sharing_settings' ), 11 );
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

			if ( ! array_key_exists( 'sharing', $tabs ) ) {
				$tabs['sharing'] = array(
					'page_title'        => '',
					'page_description'  => '',
					'menu_title'        => esc_html__( 'Sharing', 'lsx-sharing' ),
					'template'          => LSX_SHARING_PATH . 'includes/settings/sharing.php',
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

			return $tabs;
		}

		/**
		 * Outputs the display tabs settings.
		 */
		public function sharing_settings( $tab = 'general' ) {
			if ( 'general' === $tab ) {
				$this->general_tab();
			} elseif ( 'buttons' === $tab ) {
				$this->buttons_tab();
			}
		}

		/**
		 * Outputs the general tab options.
		 */
		public function general_tab() { ?>
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_label_text"><?php esc_html_e( 'Label text', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					{{#if sharing_label_text}}
						<input type="text" value="{{sharing_label_text}}" name="sharing_label_text" />
					{{else}}
						<input type="text" value="Share this:" name="sharing_label_text" />
					{{/if}}
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row">
					<label for="sharing_twitter_username"><?php esc_html_e( 'Twitter username', 'lsx-sharing' ); ?></label>
				</th>
				<td>
					<input type="text" {{#if sharing_twitter_username}} value="{{sharing_twitter_username}}" {{/if}} name="sharing_twitter_username" />
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" colspan="2">
					<h3><?php esc_html_e( 'Disable post types', 'lsx-sharing' ); ?></h3>
				</th>
			</tr>
			<?php
				$post_types = get_post_types( array(
					'public' => true,
				) );

				if ( false !== ( $key = array_search( 'attachment', $post_types, true ) ) ) {
					unset( $post_types[$key] );
				}
			?>
			<?php foreach ( $post_types as $key => $value ) : ?>
				<tr class="form-field">
					<th scope="row">
						<label for="sharing_disable_pt_<?php echo esc_attr( $key ); ?>"><?php printf( esc_html__( 'Disable %s', 'lsx-sharing' ), esc_html( $key ) ); ?></label>
					</th>
					<td>
						<input type="checkbox" {{#if sharing_disable_pt_<?php echo esc_attr( $key ); ?>}} checked="checked" {{/if}} name="sharing_disable_pt_<?php echo esc_attr( $key ); ?>" />
						<small><?php printf( esc_html__( 'Disable share buttons on post type: %s', 'lsx-sharing' ), esc_html( $key ) ); ?></small>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php }

		/**
		 * Outputs the buttons tab options.
		 */
		public function buttons_tab() { ?>
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
