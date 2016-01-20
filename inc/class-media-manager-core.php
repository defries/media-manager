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
	 * @access  protected
	 * @return  array  The post-types which should have their attachments deleted.
	 */
	protected function get_post_types() {
		$settings = get_option( self::OPTION );
		if ( isset( $settings['post_types'] ) ) {
			foreach ( $settings['post_types'] as $post_type => $x ) {
				$post_types[] = $post_type;
			}
		} else {
			$post_types = array();
		}

		return $post_types;
	}

}
