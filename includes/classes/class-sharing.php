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

	public function init() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_block_variations' ) );
		add_action( 'init', array( $this, 'register_block_type' ), 20 );
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

	/**
	 * Registers the `core/social-link` blocks.
	 */
	public function register_block_type() {
		wp_register_style(
			'lsx-sharing-block-styles',
			LSX_SHARING_URL . 'includes/lsx-sharing.css',
			array( 'dashicons' )
		);

		wp_register_style(
			'lsx-sharing-block-editor-styles',
			LSX_SHARING_URL . 'includes/lsx-sharing-editor.css',
			array( 'dashicons' )
		);

		register_block_type_from_metadata(
			LSX_SHARING_PATH . 'src/sharing-link',
			array(
				'render_callback' => array( $this, 'render_sharing_link' ),
				'style_handles' => array( 'lsx-sharing-block-styles' ),
				'editor_style_handles' => array( 'lsx-sharing-block-editor-styles' ),
			)
		);

		register_block_type_from_metadata(
			LSX_SHARING_PATH . 'src/sharing-label',
		);
	}

	/**
	 * Renders the `lsx/sharing-link` block on server.
	 *
	 * @param Array    $attributes The block attributes.
	 * @param String   $content    InnerBlocks content of the Block.
	 * @param WP_Block $block      Block object.
	 *
	 * @return string Rendered HTML of the referenced block.
	 */
	public function render_sharing_link( $attributes, $content, $block ) {
		$open_in_new_tab = isset( $block->context['openInNewTab'] ) ? $block->context['openInNewTab'] : false;
		$service         = ( isset( $attributes['service'] ) ) ? $attributes['service'] : 'Icon';
		$url             = ( isset( $attributes['url'] ) ) ? $attributes['url'] : false;
		$label           = ( isset( $attributes['label'] ) ) ? $attributes['label'] : $this->get_name( $service );
		$rel             = ( isset( $attributes['rel'] ) ) ? $attributes['rel'] : '';
		$show_labels     = array_key_exists( 'showLabels', $block->context ) ? $block->context['showLabels'] : false;

		// Don't render a link if there is no URL set.
		if ( ! $url ) {
			return '';
		}

		/**
		 * Prepend emails with `mailto:` if not set.
		 * The `is_email` returns false for emails with schema.
		 */
		if ( is_email( $url ) ) {
			$url = 'mailto:' . $url;
		}

		/**
		 * Prepend URL with https:// if it doesn't appear to contain a scheme
		 * and it's not a relative link starting with //.
		 */
		if ( ! parse_url( $url, PHP_URL_SCHEME ) && ! str_starts_with( $url, '//' ) ) {
			$url = 'https://' . $url;
		}

		$icon               = $this->get_icon( $service );
		$wrapper_attributes = get_block_wrapper_attributes(
			array(
				'class' => 'wp-social-link wp-social-link-' . $service,
				'style' => $this->get_color_styles( $block->context ),
			)
		);

		//Lets replace our var
		$url = $this->replace_variables( $url );

		$link  = '<li ' . $wrapper_attributes . '>';
		$link .= '<a href="' . esc_url( $url ) . '" class="wp-block-social-link-anchor">';
		$link .= $icon;
		$link .= '<span class="wp-block-social-link-label' . ( $show_labels ? '' : ' screen-reader-text' ) . '">';
		$link .= esc_html( $label );
		$link .= '</span></a></li>';

		$w = new \WP_HTML_Tag_Processor( $link );
		$w->next_tag( 'a' );
		if ( $open_in_new_tab ) {
			$w->set_attribute( 'rel', esc_attr( $rel ) . ' noopener nofollow' );
			$w->set_attribute( 'target', '_blank' );
		} elseif ( '' !== $rel ) {
			$w->set_attribute( 'rel', esc_attr( $rel ) );
		}
		return $w;
	}

	/**
	 * Returns the SVG for social link.
	 *
	 * @param string $service The service icon.
	 *
	 * @return string SVG Element for service icon.
	 */
	public function get_icon( $service ) {
		$services = $this->get_services();
		if ( isset( $services[ $service ] ) && isset( $services[ $service ]['icon'] ) ) {
			return $services[ $service ]['icon'];
		}
		return $services['share']['icon'];
	}

	/**
	 * Returns the brand name for social link.
	 *
	 * @param string $service The service icon.
	 *
	 * @return string Brand label.
	 */
	public function get_name( $service ) {
		$services = $this->get_services();
		if ( isset( $services[ $service ] ) && isset( $services[ $service ]['name'] ) ) {
			return $services[ $service ]['name'];
		}
		return $services['share']['name'];
	}

	/**
	 * Returns the SVG for social link.
	 *
	 * @param string $service The service slug to extract data from.
	 * @param string $field The field ('name', 'icon', etc) to extract for a service.
	 *
	 * @return array|string
	 */
	public function get_services( $service = '', $field = '' ) {
		$services_data = array(
			'chain'         => array(
				'name' => 'Link',
				'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M19.647,16.706a1.134,1.134,0,0,0-.343-.833l-2.549-2.549a1.134,1.134,0,0,0-.833-.343,1.168,1.168,0,0,0-.883.392l.233.226q.2.189.264.264a2.922,2.922,0,0,1,.184.233.986.986,0,0,1,.159.312,1.242,1.242,0,0,1,.043.337,1.172,1.172,0,0,1-1.176,1.176,1.237,1.237,0,0,1-.337-.043,1,1,0,0,1-.312-.159,2.76,2.76,0,0,1-.233-.184q-.073-.068-.264-.264l-.226-.233a1.19,1.19,0,0,0-.4.895,1.134,1.134,0,0,0,.343.833L15.837,19.3a1.13,1.13,0,0,0,.833.331,1.18,1.18,0,0,0,.833-.318l1.8-1.789a1.12,1.12,0,0,0,.343-.821Zm-8.615-8.64a1.134,1.134,0,0,0-.343-.833L8.163,4.7a1.134,1.134,0,0,0-.833-.343,1.184,1.184,0,0,0-.833.331L4.7,6.473a1.12,1.12,0,0,0-.343.821,1.134,1.134,0,0,0,.343.833l2.549,2.549a1.13,1.13,0,0,0,.833.331,1.184,1.184,0,0,0,.883-.38L8.728,10.4q-.2-.189-.264-.264A2.922,2.922,0,0,1,8.28,9.9a.986.986,0,0,1-.159-.312,1.242,1.242,0,0,1-.043-.337A1.172,1.172,0,0,1,9.254,8.079a1.237,1.237,0,0,1,.337.043,1,1,0,0,1,.312.159,2.761,2.761,0,0,1,.233.184q.073.068.264.264l.226.233a1.19,1.19,0,0,0,.4-.895ZM22,16.706a3.343,3.343,0,0,1-1.042,2.488l-1.8,1.789a3.536,3.536,0,0,1-4.988-.025l-2.525-2.537a3.384,3.384,0,0,1-1.017-2.488,3.448,3.448,0,0,1,1.078-2.561l-1.078-1.078a3.434,3.434,0,0,1-2.549,1.078,3.4,3.4,0,0,1-2.5-1.029L3.029,9.794A3.4,3.4,0,0,1,2,7.294,3.343,3.343,0,0,1,3.042,4.806l1.8-1.789A3.384,3.384,0,0,1,7.331,2a3.357,3.357,0,0,1,2.5,1.042l2.525,2.537a3.384,3.384,0,0,1,1.017,2.488,3.448,3.448,0,0,1-1.078,2.561l1.078,1.078a3.551,3.551,0,0,1,5.049-.049l2.549,2.549A3.4,3.4,0,0,1,22,16.706Z"></path></svg>',
			),
			'facebook'      => array(
				'name' => 'Facebook',
				'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"></path></svg>',
			),
			'mail'          => array(
				'name' => 'Mail',
				'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M20,4H4C2.895,4,2,4.895,2,6v12c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2V6C22,4.895,21.105,4,20,4z M20,8.236l-8,4.882 L4,8.236V6h16V8.236z"></path></svg>',
			),
			'pinterest'     => array(
				'name' => 'Pinterest',
				'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12.289,2C6.617,2,3.606,5.648,3.606,9.622c0,1.846,1.025,4.146,2.666,4.878c0.25,0.111,0.381,0.063,0.439-0.169 c0.044-0.175,0.267-1.029,0.365-1.428c0.032-0.128,0.017-0.237-0.091-0.362C6.445,11.911,6.01,10.75,6.01,9.668 c0-2.777,2.194-5.464,5.933-5.464c3.23,0,5.49,2.108,5.49,5.122c0,3.407-1.794,5.768-4.13,5.768c-1.291,0-2.257-1.021-1.948-2.277 c0.372-1.495,1.089-3.112,1.089-4.191c0-0.967-0.542-1.775-1.663-1.775c-1.319,0-2.379,1.309-2.379,3.059 c0,1.115,0.394,1.869,0.394,1.869s-1.302,5.279-1.54,6.261c-0.405,1.666,0.053,4.368,0.094,4.604 c0.021,0.126,0.167,0.169,0.25,0.063c0.129-0.165,1.699-2.419,2.142-4.051c0.158-0.59,0.817-2.995,0.817-2.995 c0.43,0.784,1.681,1.446,3.013,1.446c3.963,0,6.822-3.494,6.822-7.833C20.394,5.112,16.849,2,12.289,2"></path></svg>',
			),
			'twitter'       => array(
				'name' => 'Twitter',
				'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M22.23,5.924c-0.736,0.326-1.527,0.547-2.357,0.646c0.847-0.508,1.498-1.312,1.804-2.27 c-0.793,0.47-1.671,0.812-2.606,0.996C18.324,4.498,17.257,4,16.077,4c-2.266,0-4.103,1.837-4.103,4.103 c0,0.322,0.036,0.635,0.106,0.935C8.67,8.867,5.647,7.234,3.623,4.751C3.27,5.357,3.067,6.062,3.067,6.814 c0,1.424,0.724,2.679,1.825,3.415c-0.673-0.021-1.305-0.206-1.859-0.513c0,0.017,0,0.034,0,0.052c0,1.988,1.414,3.647,3.292,4.023 c-0.344,0.094-0.707,0.144-1.081,0.144c-0.264,0-0.521-0.026-0.772-0.074c0.522,1.63,2.038,2.816,3.833,2.85 c-1.404,1.1-3.174,1.756-5.096,1.756c-0.331,0-0.658-0.019-0.979-0.057c1.816,1.164,3.973,1.843,6.29,1.843 c7.547,0,11.675-6.252,11.675-11.675c0-0.178-0.004-0.355-0.012-0.531C20.985,7.47,21.68,6.747,22.23,5.924z"></path></svg>',
			),
			'whatsapp'      => array(
				'name' => 'WhatsApp',
				'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M 12.011719 2 C 6.5057187 2 2.0234844 6.478375 2.0214844 11.984375 C 2.0204844 13.744375 2.4814687 15.462563 3.3554688 16.976562 L 2 22 L 7.2324219 20.763672 C 8.6914219 21.559672 10.333859 21.977516 12.005859 21.978516 L 12.009766 21.978516 C 17.514766 21.978516 21.995047 17.499141 21.998047 11.994141 C 22.000047 9.3251406 20.962172 6.8157344 19.076172 4.9277344 C 17.190172 3.0407344 14.683719 2.001 12.011719 2 z M 12.009766 4 C 14.145766 4.001 16.153109 4.8337969 17.662109 6.3417969 C 19.171109 7.8517969 20.000047 9.8581875 19.998047 11.992188 C 19.996047 16.396187 16.413812 19.978516 12.007812 19.978516 C 10.674812 19.977516 9.3544062 19.642812 8.1914062 19.007812 L 7.5175781 18.640625 L 6.7734375 18.816406 L 4.8046875 19.28125 L 5.2851562 17.496094 L 5.5019531 16.695312 L 5.0878906 15.976562 C 4.3898906 14.768562 4.0204844 13.387375 4.0214844 11.984375 C 4.0234844 7.582375 7.6067656 4 12.009766 4 z M 8.4765625 7.375 C 8.3095625 7.375 8.0395469 7.4375 7.8105469 7.6875 C 7.5815469 7.9365 6.9355469 8.5395781 6.9355469 9.7675781 C 6.9355469 10.995578 7.8300781 12.182609 7.9550781 12.349609 C 8.0790781 12.515609 9.68175 15.115234 12.21875 16.115234 C 14.32675 16.946234 14.754891 16.782234 15.212891 16.740234 C 15.670891 16.699234 16.690438 16.137687 16.898438 15.554688 C 17.106437 14.971687 17.106922 14.470187 17.044922 14.367188 C 16.982922 14.263188 16.816406 14.201172 16.566406 14.076172 C 16.317406 13.951172 15.090328 13.348625 14.861328 13.265625 C 14.632328 13.182625 14.464828 13.140625 14.298828 13.390625 C 14.132828 13.640625 13.655766 14.201187 13.509766 14.367188 C 13.363766 14.534188 13.21875 14.556641 12.96875 14.431641 C 12.71875 14.305641 11.914938 14.041406 10.960938 13.191406 C 10.218937 12.530406 9.7182656 11.714844 9.5722656 11.464844 C 9.4272656 11.215844 9.5585938 11.079078 9.6835938 10.955078 C 9.7955938 10.843078 9.9316406 10.663578 10.056641 10.517578 C 10.180641 10.371578 10.223641 10.267562 10.306641 10.101562 C 10.389641 9.9355625 10.347156 9.7890625 10.285156 9.6640625 C 10.223156 9.5390625 9.737625 8.3065 9.515625 7.8125 C 9.328625 7.3975 9.131125 7.3878594 8.953125 7.3808594 C 8.808125 7.3748594 8.6425625 7.375 8.4765625 7.375 z"></path></svg>',
			),
			'share'         => array(
				'name' => 'Share Icon',
				'icon' => '<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 11.8l6.1-4.5c.1.4.4.7.9.7h2c.6 0 1-.4 1-1V5c0-.6-.4-1-1-1h-2c-.6 0-1 .4-1 1v.4l-6.4 4.8c-.2-.1-.4-.2-.6-.2H6c-.6 0-1 .4-1 1v2c0 .6.4 1 1 1h2c.2 0 .4-.1.6-.2l6.4 4.8v.4c0 .6.4 1 1 1h2c.6 0 1-.4 1-1v-2c0-.6-.4-1-1-1h-2c-.5 0-.8.3-.9.7L9 12.2v-.4z"/></svg>',
			),
		);

		if ( ! empty( $service )
			&& ! empty( $field )
			&& isset( $services_data[ $service ] )
			&& ( 'icon' === $field || 'name' === $field )
		) {
			return $services_data[ $service ][ $field ];
		} elseif ( ! empty( $service ) && isset( $services_data[ $service ] ) ) {
			return $services_data[ $service ];
		}

		return $services_data;
	}

	/**
	 * Returns CSS styles for icon and icon background colors.
	 *
	 * @param array $context Block context passed to Social Link.
	 *
	 * @return string Inline CSS styles for link's icon and background colors.
	 */
	public function get_color_styles( $context ) {
		$styles = array();

		if ( array_key_exists( 'iconColorValue', $context ) ) {
			$styles[] = 'color: ' . $context['iconColorValue'] . '; ';
		}

		if ( array_key_exists( 'iconBackgroundColorValue', $context ) ) {
			$styles[] = 'background-color: ' . $context['iconBackgroundColorValue'] . '; ';
		}

		return implode( '', $styles );
	}

	/**
	 * Replace the variables in the url
	 *
	 * @param string $url
	 * @return string
	 */
	public function replace_variables( $url = '' ) {

		$current_vars = array();

		$tags = array(
			'lsx_sharing_url',
			'lsx_sharing_title',
			'lsx_sharing_image',
		);

		foreach ( $tags as $tag ) {
			$replace_value = '';

			if ( isset( $current_vars[ $tag ] ) && '' !== $current_vars[ $tag ] ) {
				$replace_value = $current_vars[ $tag ];
			} else {
				switch( $tag ) {
					case 'lsx_sharing_url':
						$replace_value = urlencode( get_permalink() );
					break;

					case 'lsx_sharing_title':
						$replace_value = get_the_title();
					break;

					case 'lsx_sharing_image':
						$replace_value = get_the_post_thumbnail_url();

						if ( false === $replace_value ) {
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							if ( false !== $custom_logo_id && '' !== $custom_logo_id ) {
								$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
								if ( ! empty( $image ) && isset( $image['src'] ) ) {
									$replace_value = $image['src'];
								}
							}
						}
					break;
						
					default:
						$replace_value = '';
					break;
				}
				$current_vars[ $tag ] = $replace_value;
			}

			if ( '' !== $replace_value ) {
				$url = str_replace( $tag, $replace_value, $url );
			}
		}
		return $url;
	}
}
