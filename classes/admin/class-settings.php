<?php
namespace lsx\sharing\classes\admin;

/**
 * Houses the functions for the Settings page.
 *
 * @package lsx-sharing
 */
class Settings {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\sharing\classes\admin\Settings()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'cmb2_admin_init', array( $this, 'register_settings_page' ) );
		add_action( 'lsx_sharing_settings_page', array( $this, 'configure_general_fields' ), 15, 1 );
		add_action( 'lsx_sharing_settings_page', array( $this, 'configure_archive_fields' ), 15, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\sharing\classes\admin\Settings()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Configure fields for the Settings page.
	 *
	 * @return void
	 */
	public function register_settings_page() {
		$args = array(
			'id'           => 'lsx_sharing_settings',
			'title'        => '<h1>' . esc_html__( 'LSX Sharing Settings', 'lsx-search' ) . ' <span class="version">' . LSX_SHARING_VER . '</span></h1>',
			'menu_title'   => esc_html__( 'LSX Sharing', 'search' ), // Falls back to 'title' (above).
			'object_types' => array( 'options-page' ),
			'option_key'   => 'lsx-sharing-settings', // The option key and admin menu page slug.
			'parent_slug'  => 'options-general.php',
			'capability'   => 'manage_options', // Cap required to view options-page.
		);
		$cmb  = new_cmb2_box( $args );
		do_action( 'lsx_sharing_settings_page', $cmb );
	}

	/**
	 * Enqueue JS and CSS.
	 */
	public function assets( $hook ) {
		wp_enqueue_script( 'lsx-sharing-admin', LSX_SHARING_URL . 'assets/js/src/lsx-sharing-admin.js', array( 'jquery' ), LSX_SHARING_VER, true );
		wp_enqueue_style( 'lsx-sharing-admin', LSX_SHARING_URL . 'assets/css/lsx-sharing-admin.css', array(), LSX_SHARING_VER );
	}

	/**
	 * Enable Business Directory Search settings only if LSX Search plugin is enabled.
	 *
	 * @return  void
	 */
	public function configure_general_fields( $cmb ) {
		$global_args = array(
			'title' => __( 'Global', 'lsx-search' ),
			'desc'  => esc_html__( 'Control the sharing WordPress search results page.', 'lsx-search' ),
		);
		$this->get_fields( $cmb, 'global', $global_args );
	}

	/**
	 * Enable Sharing settings only if LSX Search plugin is enabled.
	 *
	 * @param object $cmb The CMB2() class.
	 * @param string $position either top of bottom.
	 * @return void
	 */
	public function configure_archive_fields( $cmb ) {
		$archives       = array();
		$post_type_args = array(
			'public' => true,
		);
		$post_types     = get_post_types( $post_type_args );
		if ( ! empty( $post_types ) ) {
			foreach ( $post_types as $post_type_key => $post_type_value ) {
				switch ( $post_type_key ) {
					case 'post':
						$page_url      = home_url();
						$page_title    = __( 'Home', 'lsx-search' );
						$show_on_front = get_option( 'show_on_front' );
						if ( 'page' === $show_on_front ) {
							$page_for_posts = get_option( 'page_for_posts' );
							if ( '' !== $page_for_posts ) {
								$page_title   = get_the_title( $page_for_posts );
								$page_url     = get_permalink( $page_for_posts );
							}
						}
						$description = sprintf(
							/* translators: %s: The subscription info */
							__( 'Control the filters which show on your <a target="_blank" href="%1$s">%2$s</a> page.', 'lsx-search' ),
							$page_url,
							$page_title
						);
						$archives[ $post_type_key ] = array(
							'title' => __( 'Blog', 'lsx-search' ),
							'desc'  => $description,
						);
						break;

					case 'product':
						$page_url = home_url();
						$page_title    = __( 'Shop', 'lsx-search' );
						if ( function_exists( 'wc_get_page_id' ) ) {
							$shop_page  = wc_get_page_id( 'shop' );
							$page_url   = get_permalink( $shop_page );
							$page_title = get_the_title( $shop_page );
						}
						$description = sprintf(
							/* translators: %s: The subscription info */
							__( 'Control the filters which show on your <a target="_blank" href="%1$s">%2$s</a> page.', 'lsx-search' ),
							$page_url,
							$page_title
						);
						$archives[ $post_type_key ] = array(
							'title' => __( 'Shop', 'lsx-search' ),
							'desc'  => $description,
						);
						break;

					case 'page':
					case 'media':
						break;

					default:
						$temp_post_type = get_post_type_object( $post_type_key );
						if ( ! is_wp_error( $temp_post_type ) ) {
							$page_url    = get_post_type_archive_link( $temp_post_type->name );
							$description = sprintf(
								/* translators: %s: The subscription info */
								__( 'Control the filters which show on your <a target="_blank" href="%1$s">%2$s</a> archive.', 'lsx-search' ),
								$page_url,
								$temp_post_type->label
							);

							$archives[ $post_type_key ] = array(
								'title' => $temp_post_type->label,
								'desc'  => $description,
							);
						}
						break;
				}
			}
		}
		if ( ! empty( $archives ) ) {
			foreach ( $archives as $archive_key => $archive_args ) {
				$this->get_fields( $cmb, $archive_key, $archive_args );
			}
		}
	}

	/**
	 * Gets the sharing fields and loops through them.
	 *
	 * @param object $cmb
	 * @param string $section
	 * @param array $args
	 * @return void
	 */
	public function get_fields( $cmb, $section, $args ) {
		$cmb->add_field(
			array(
				'id'          => $section . '_title',
				'type'        => 'title',
				'name'        => $args['title'],
				'default'     => $args['title'],
				'description' => $args['desc'],
			)
		);
		if ( 'sharing' === $section ) {
			$cmb->add_field(
				array(
					'name'        => esc_html__( 'Disable all', 'lsx-sharing' ),
					'id'          => $section . '_disable_all',
					'description' => esc_html__( 'Disable all share buttons on the site', 'lsx-sharing' ),
					'type'        => 'checkbox',
				)
			);
		}

		$cmb->add_field(
			array(
				'name'        => esc_html__( 'Label text', 'lsx-sharing' ),
				'id'          => $section . '_label_text',
				'description' => esc_html__( 'A default label for the sharing.', 'lsx-sharing' ),
				'type'        => 'text',
			)
		);

		do_action( 'lsx_sharing_settings_section', $cmb, $section );
		$cmb->add_field(
			array(
				'id'   => $section . '_title_closing',
				'type' => 'tab_closing',
			)
		);



		/*if ( 'sharing' === $section ) {
			$this->post_type_fields();
			$this->social_network_fields();
		}*/
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
	<?php
	}

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
	<?php
	}


}
