<?php

/**
 * Media Manager image deletion class.
 */
class Media_Manager_Delete extends Media_Manager_Core {

	const TIME_LIMIT = 30; // Time limit at which a WP Cron task should give up

	/**
	 * Fire the constructor up :)
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );
		add_action( 'media_manager',  array( $this, 'task' ) );

		$file = dirname( dirname( __FILE__ ) ) . '/media-manager.php';
		register_activation_hook( $file, array( $this, 'activation' ) );
		register_deactivation_hook( $file, array( $this, 'deactivation' ) );
//if(isset($_GET['test'])){add_action( 'init', array( $this, 'task' ) );}
	}

	/**
	 * Run the attachment deletion task.
	 *
	 * Uses transients to ensure that only small batches of posts are done each time.
	 * Once a batch is complete, the post offset transient is iterated.
	 */
	public function task() {

		// Set initial offset
		if ( false == ( $offset = get_transient( 'media_manager_offset' ) ) ) {
			set_transient( 'media_manager_offset', $offset = 0, DAY_IN_SECONDS );
		}

		$time = time();
		while ( time() < ( $time + self::TIME_LIMIT ) ) {

			// Get the post IDs
			$query = new WP_Query( array(
				'post_type'              => $this->get_post_types(),
				'posts_per_page'         => 1,
				'post_status'            => 'publish',
				'offset'                 => $offset,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'fields'                 => 'ids',
			));
			$post_ids = $query->posts;

			// Completed all posts, so delete offset and bail out
			if ( empty( $post_ids ) ) {
				delete_transient( 'media_manager_offset' );
				return;
			}

			// Loop through the posts
			foreach ( $post_ids as $key => $post_id ) {
				$attached_media = get_attached_media( 'image', $post_id );
				$featured_id = get_post_thumbnail_id( $post_id );

				// Loop through media attached to each post
				foreach ( $attached_media as $x => $attachment ) {
					$attachment_id = $attachment->ID;

					// If not a featured image, then delete the attachment
					if ( $attachment_id != $featured_id ) {
						wp_delete_post( $attachment_id );
					}

				}

				set_transient( 'media_manager_offset', $offset++, DAY_IN_SECONDS );

			}

			usleep( 0.1 * 1000000 ); // Delaying the execution (reduces resource consumption)
		}

		return;
	}

	/**
	 * On activation, set a time, frequency and name of an action hook to be scheduled.
	 */
	public function activation() {

		// first run = Now + 15 minutes
		$first_run_time = current_time ( 'timestamp' ) + SELF::TIME_LIMIT;
		wp_schedule_event( $first_run_time, 'seconds' . SELF::TIME_LIMIT, 'media_manager' );
	}

	/**
	 * On deactivation, remove all functions from the scheduled action hook.
	 */
	public function deactivation() {
		wp_clear_scheduled_hook( 'media_manager' );
	}

	/**
	 * Add a new WP Cron schedule.
	 *
	 * @param array   $schedules Cron schedule array
	 * @return array $schedules Amended cron schedule array
	 */
	public function cron_schedules( $schedules ) {

		$schedules['seconds' . SELF::TIME_LIMIT] = array(
			'interval' => SELF::TIME_LIMIT,
			'display'  => sprintf( __( 'Every %s seconds', 'media-manager' ), SELF::TIME_LIMIT ),
		);

		return $schedules;
	}

}
