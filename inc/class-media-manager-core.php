<?php

/**
 * Main Media Manager class.
 */
abstract class Media_Manager_Core {

	/**
	 * Set some constants for setting options.
	 */
	const SLUG   = 'media-manager';
	const GROUP  = 'media-manager-group';
	const OPTION = 'media-manager';

	/**
	 * Get the post-types intended for deletion.
	 *
	 * @return  array  The post-types which should have their attachments deleted.
	 */
	public function get_post_types() {
		$settings = get_option( self::OPTION );
		if ( isset( $settings['post_types'] ) ) {
			$post_types = $settings['post_types'];
		} else {
			$post_types = array();
		}

		return $post_types;
	}

	/**
	 * Get the taxonomies intended for deletion.
	 *
	 * @return  array  The taxonomies which should have their attachments deleted.
	 */
	public function get_post_taxonomies() {
		$settings = get_option( self::OPTION );
		if ( isset( $settings['taxonomies'] ) ) {
			$taxonomies = $settings['taxonomies'];
		} else {
			$taxonomies = array();
		}

		return $taxonomies;
	}

}
