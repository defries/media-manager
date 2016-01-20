<?php

/**
 * Media Manager admin page.
 */
class Media_Manager_Admin extends Media_Manager_Core {

	/**
	 * Fire the constructor up :)
	 */
	public function __construct() {

		// Add to hooks
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'create_admin_page' ) );

	}

	/**
	 * Init plugin options to white list our options.
	 */
	public function register_settings() {
		register_setting(
			self::GROUP,               // The settings group name
			self::OPTION,              // The option name
			array( $this, 'sanitize' ) // The sanitization callback
		);
	}

	/**
	 * Create the page and add it to the menu.
	 */
	public function create_admin_page() {
		add_options_page(
			__ ( 'Media Manager', 'media-manager' ), // Page title
			__ ( 'Media Manager', 'media-manager' ),       // Menu title
			'manage_options',                           // Capability required
			self::SLUG,                            // The URL slug
			array( $this, 'admin_page' )                // Displays the admin page
		);
	}

	/**
	 * Output the admin page.
	 */
	public function admin_page() {

		?>
		<div class="wrap">
			<h1><?php _e( 'Media Manager', 'media-manager' ); ?></h1>
			<p><?php _e( 'Place a description of what the admin page does here to help users make better use of the admin page.', 'media-manager' ); ?></p>
<!--
			<a href="<?php
			$url = admin_url( 'options-general.php?page=media-manager' );
			$url = wp_nonce_url( $url, self::SLUG, self::SLUG );
			echo esc_url( $url );
			?>" class="button"><?php _e( 'Delete images now', 'media-manager' ); ?></a>
-->
			<form method="post" action="options.php">

				<table class="form-table">

					<tr>
						<th>
							<label for="<?php echo esc_attr( self::OPTION ); ?>"><?php _e( 'Enter your input string.', 'media-manager' ); ?></label>
						</th>
						<td>
							<input type="text" id="<?php echo esc_attr( self::OPTION ); ?>" name="<?php echo esc_attr( self::OPTION ); ?>" value="<?php echo esc_attr( get_option( self::OPTION ) ); ?>" />
						</td>
					</tr>
				</table>

				<?php settings_fields( self::GROUP ); ?>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'media-manager' ); ?>" />
				</p>
			</form>

		</div><?php
	}

	/**
	 * Sanitize the page or product ID
	 *
	 * @param   string   $input   The input string
	 * @return  array             The sanitized string
	 */
	public function sanitize( $input ) {
		$output = wp_kses_post( $input );
		return $output;
	}

}
