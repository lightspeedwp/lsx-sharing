<?php
/**
 * LSX Search functions.
 *
 * @package lsx-search
 */

namespace lsx\sharing\includes\functions;

/**
 * Gets a specific option from the array.
 *
 * @return boolean
 */
function is_button_disabled( $post_type = '', $service = '' ) {
	$sharing = lsx_sharing();
	$option  = false;
	if ( false === $sharing->is_new_options && isset( $sharing->options['display'] ) && ! empty( $sharing->options['display'][ 'sharing_disable_' . $service ] ) ) {
		$option = true;
	} elseif ( true === $sharing->is_new_options && ! empty( $sharing->options[ $post_type . '_disable_' . $service ] ) ) {
		$option = true;
	}
	return $option;
}

/**
 * Gets a specific option from the array.
 *
 * @return boolean
 */
function is_pt_disabled( $post_type = '' ) {
	$sharing = lsx_sharing();
	$option  = false;
	if ( false === $sharing->is_new_options && isset( $sharing->options['display'] ) && ! empty( $sharing->options['display'][ 'sharing_disable_pt_' . $post_type ] ) ) {
		$option = true;
	} elseif ( true === $sharing->is_new_options && isset( $sharing->options[ $post_type . '_disable_pt' ] ) ) {
		$option = true;
	}
	return $option;
}

/**
 * If the sharing has been disabled.
 *
 * @return boolean
 */
function is_disabled() {
	$sharing = lsx_sharing();
	$option  = false;
	if ( false === $sharing->is_new_options && isset( $sharing->options['display'] ) && ! empty( $sharing->options['display']['sharing_disable_all'] ) ) {
		$option = true;
	} elseif ( true === $sharing->is_new_options && isset( $sharing->options['global_disable_all'] ) ) {
		$option = true;
	}
	return $option;
}

/**
 * Gets the sharing text.
 *
 * @return string
 */
function get_sharing_text( $post_type = '' ) {
	$sharing = lsx_sharing();
	$text    = '';
	if ( false === $sharing->is_new_options && isset( $sharing->options['display'] ) && ! empty( $sharing->options['display']['sharing_label_text'] ) ) {
		$text = $sharing->options['display']['sharing_label_text'];
	} elseif ( true === $sharing->is_new_options ) {
		if ( isset( $sharing->options[ $post_type . '_label_text' ] ) ) {
			$text = $sharing->options[ $post_type . '_label_text' ];
		} elseif ( isset( $sharing->options['global_label_text'] ) ) {
			$text = $sharing->options['global_label_text'];
		}
	}
	return $text;
}
