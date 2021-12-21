<?php
/**
 * Calls the class on the post edit screen.
 */
function init_metabox() {
	new Phoenix_Zoo_Metabox();
}

if ( is_admin() ) {
	add_action( 'load-post.php',     'init_metabox' );
	add_action( 'load-post-new.php', 'init_metabox' );
}

/**
 * The Class.
 */
class Phoenix_Zoo_Metabox {

	private $post_types = array();
	private $map_types = array();

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		$this->post_types = array( 'phoenixzoo-events', 'phoenixzoo-cp' );
		$this->map_types = array(
			'ROADMAP'	=> _x( 'Road', 'map', 'phoenix-zoo' ),
			'SATELLITE'	=> _x( 'Satellite', 'map', 'phoenix-zoo' ),
			'HYBRID'	=> _x( 'Hybrid', 'map', 'phoenix-zoo' ),
			'TERRAIN'	=> _x( 'Terrain', 'map', 'phoenix-zoo' )
		);

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxs' ) );
		add_action( 'save_post',      array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_boxs( $post_type ) {
		if ( 'phoenixzoo-events' == $post_type ) {
			// Date & Time
			add_meta_box(
				'phoenix_events_date',
				__( 'Date & Time', 'phoenix-zoo' ),
				array( $this, 'render_date_metabox' ),
				$post_type,
				'advanced',
				'high'
			);

			// Location
			// add_meta_box(
			// 	'phoenix_events_location',
			// 	__( 'Location', 'phoenix-zoo' ),
			// 	array( $this, 'render_location_metabox' ),
			// 	$post_type,
			// 	'advanced',
			// 	'high'
			// );

		} elseif ( 'phoenixzoo-cp' == $post_type ) {
      add_meta_box(
				'phoenix_events_date',
				__( 'Custom Fileds', 'phoenix-zoo' ),
				array( $this, 'render_cp_field_metabox' ),
				$post_type,
				'advanced',
				'high'
			);
    }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {

		if ( ! isset( $_POST['post_type'] ) ) {
			return $post_id; 
		}

		// Check if our nonce is set.
		// if ( ! isset( $_POST['phoenixzoo_metabox_nonce'] ) ) {
		// 	return $post_id;
		// }
		
		// // $nonce = $_POST['phoenixzoo_metabox_nonce'];

		// // Verify that the nonce is valid.
		// if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) ) {
		// 	return $post_id;
		// }

		/*
			* If this is an autosave, our form has not been submitted,
			* so we don't want to do anything.
			*/
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( in_array( $_POST['post_type'], $this->post_types ) ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		}

		$meta_value = array(
      'phoenix_zoo_meta_cp_date',
      'phoenix_zoo_meta_cp_position',
			'phoenix_zoo_meta_startdate',
			'phoenix_zoo_meta_enddate',
			// 'phoenix_zoo_meta_time',
			// 'phoenix_zoo_meta_ticket_url',
			'phoenix_zoo_meta_venue',
			'phoenix_zoo_meta_address',
			'phoenix_zoo_meta_map_lat',
			'phoenix_zoo_meta_map_lng',
			'phoenix_zoo_meta_map_type',
			'phoenix_zoo_meta_map_zoom'
		);

		foreach ( $meta_value as $meta_value ) {
			if ( isset( $_POST[ $meta_value ] ) ) {
			  $sanitized_meta_value = esc_html( $_POST[ $meta_value ] );
			  update_post_meta( $post_id, $meta_value, $sanitized_meta_value );
      }
    }
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_date_metabox( $post ) {
		// var_dump(get_post_meta($post->ID));

		// Add an nonce field so we can check for it later.
		// wp_nonce_field( 'myplugin_inner_custom_box', 'phoenixzoo_metabox_nonce' );

		$phoenix_zoo_meta_startdate = get_post_meta( $post->ID, 'phoenix_zoo_meta_startdate', true );
		$phoenix_zoo_meta_enddate = get_post_meta( $post->ID, 'phoenix_zoo_meta_enddate', true );	
    // $phoenix_zoo_meta_time = get_post_meta( $post->ID, 'phoenix_zoo_meta_time', true );	
    // $phoenix_zoo_meta_ticket_url = get_post_meta( $post->ID, 'phoenix_zoo_meta_ticket_url', true );	
		?>

		<div class="phoenixzoo-meta-name">
			<label for="phoenix_zoo_meta_startdate"><?php _e( 'Start Date', 'phoenix-zoo' ); ?></label>
		</div>
		<div class="phoenixzoo-meta-value phoenixzoo-meta-small">
			<input type="date" name="phoenix_zoo_meta_startdate" id="phoenix_zoo_meta_startdate" value="<?php echo $phoenix_zoo_meta_startdate; ?>" size="30">
			<p class="description"> <?php _e( 'Date must be in MM-DD-YYYY format such as "10-04-2019" for Friday, October 04.', 'phoenix-zoo' ); ?></p>
		</div>

		<br>
    <br>
    
    <?php  if ( 'phoenixzoo-cp' != get_post_type( $post ) ) : ?>
    <div class="phoenixzoo-meta-name">
    <label for="phoenix_zoo_meta_enddate"><?php _e( 'End Date', 'phoenix-zoo' ); ?> </label>
    </div>
    <div class="phoenixzoo-meta-value phoenixzoo-meta-small">
      <input type="date" name="phoenix_zoo_meta_enddate" id="phoenix_zoo_meta_enddate" value="<?php echo $phoenix_zoo_meta_enddate; ?>" size="30">
      <p class="description"> <?php _e( 'Provide an end date if this is a multi-day event.', 'phoenix-zoo' ); ?> </p>
    </div>

    <br>
    <br>

    <!-- <div class="phoenixzoo-meta-name">
      <label for="phoenix_zoo_meta_time"><?php // _e( 'Time', 'phoenix-zoo' ); ?> <span> <?php // _e( '(Optional)', 'phoenix-zoo' ); ?> </span> </label>
    </div>
    <div class="phoenixzoo-meta-value phoenixzoo-meta-medium">
      <input type="text" name="phoenix_zoo_meta_time" id="phoenix_zoo_meta_time" value="<?php // echo $phoenix_zoo_meta_time; ?>" size="30">
      <p class="description"> <?php // _e( 'Optionally provide a time such as "8:00 am – 2:00 pm"', 'phoenix-zoo' ); ?> </p>
    </div> -->

    <!-- <div class="phoenixzoo-meta-name">
      <label for="phoenix_zoo_meta_ticket_url"><?php // _e( 'URL', 'phoenix-zoo' ); ?></label>
    </div>
    <div class="phoenixzoo-meta-value phoenixzoo-meta-medium">
      <input type="text" name="phoenix_zoo_meta_ticket_url" id="phoenix_zoo_meta_ticket_url" value="<?php // echo $phoenix_zoo_meta_ticket_url; ?>" size="30">
      <p class="description"> <?php // _e( 'Provide the link for info & tickets', 'phoenix-zoo' ); ?> </p>
    </div> -->
    <?php endif; ?>

	<?php
  }
  
  	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_cp_field_metabox( $post ) {
		$phoenix_zoo_meta_cp_date = get_post_meta( $post->ID, 'phoenix_zoo_meta_cp_date', true );
		$phoenix_zoo_meta_cp_position = get_post_meta( $post->ID, 'phoenix_zoo_meta_cp_position', true );
		?>

    <div class="phoenixzoo-meta-name">
			<label for="phoenix_zoo_meta_cp_position"><?php _e( 'Position', 'phoenix-zoo' ); ?></label>
		</div>
		<div class="phoenixzoo-meta-value phoenixzoo-meta-small">
			<input type="number" min="0" placeholder="000" name="phoenix_zoo_meta_cp_position" id="phoenix_zoo_meta_cp_position" value="<?php echo $phoenix_zoo_meta_cp_position; ?>" size="30">
		</div>

    <br>
    <br>

		<div class="phoenixzoo-meta-name">
			<label for="phoenix_zoo_meta_cp_date"><?php _e( 'Date', 'phoenix-zoo' ); ?></label>
		</div>
		<div class="phoenixzoo-meta-value phoenixzoo-meta-medium">
			<input type="text" name="phoenix_zoo_meta_cp_date" id="phoenix_zoo_meta_cp_date" value="<?php echo $phoenix_zoo_meta_cp_date; ?>" size="30">
			<p class="description"> <?php _e( 'Enter your date.', 'phoenix-zoo' ); ?></p>
		</div>

	<?php
	}

	function render_location_metabox( $object, $box ) {
		$screen = get_current_screen();
		// $nonce_params = risen_meta_box_nonce_params( $box['id'] );
		// wp_nonce_field( $nonce_params['action'], $nonce_params['key'] );
		?>
		
		<?php $meta_key = 'phoenix_zoo_meta_venue'; ?>
		<p>
			<div class="phoenixzoo-meta-name">
				<label for="<?php echo $meta_key; ?>"><?php _e( 'Venue <span>(Optional)</span>', 'phoenix-zoo' ); ?></label>
			</div>
			<div class="phoenixzoo-meta-value phoenixzoo-meta-medium">
				<input type="text" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo esc_attr( get_post_meta( $object->ID, $meta_key, true ) ); ?>" size="30" />
				<p class="description">
					<?php _e( 'You can provide a building name, room number or other location name to help people find the event.', 'phoenix-zoo' ); ?>
				</p>
			</div>
		</p>
		
		<?php
		$meta_key = 'phoenix_zoo_meta_address';
		$meta_value = get_post_meta( $object->ID, $meta_key, true );
		?>
		<p>
			<div class="phoenixzoo-meta-name">
				<label for="<?php echo $meta_key; ?>"><?php _e( 'Address <span>(Optional)</span>', 'phoenix-zoo' ); ?></label>
			</div>
			<div class="phoenixzoo-meta-value">
				<textarea name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>"><?php echo esc_textarea( $meta_value ); ?></textarea>
				<p class="description">
					<?php _e( 'You can enter an address if it is necessary for people to find this event.', 'phoenix-zoo' ); ?>
				</p>
			</div>
		</p>
		
		<p>
			<div class="phoenixzoo-meta-name">
				<label><?php _ex( 'Google Map <span>(Optional)</span>', 'events meta box', 'phoenix-zoo' ); ?></label>
			</div>
		</p>
		
		<p class="description">
			<?php _e( 'Provide the details below if you want to show a map for this event.', 'phoenix-zoo' ); ?>
		</p>
		
		<?php $meta_key = 'phoenix_zoo_meta_map_lat'; ?>
		<p>
			<div class="phoenixzoo-meta-name phoenixzoo-meta-name-secondary">
				<label for="<?php echo $meta_key; ?>"><?php _e( 'Latitude', 'phoenix-zoo' ); ?></label>
			</div>
			<div class="phoenixzoo-meta-value phoenixzoo-meta-medium">
				<input type="number" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo esc_attr( get_post_meta( $object->ID, $meta_key, true ) ); ?>" size="30" />
				<p class="description"></p>
			</div>
		</p>
		
		<?php $meta_key = 'phoenix_zoo_meta_map_lng'; ?>
		<p>
			<div class="phoenixzoo-meta-name phoenixzoo-meta-name-secondary">
				<label for="<?php echo $meta_key; ?>"><?php _e( 'Longitude', 'phoenix-zoo' ); ?></label>
			</div>
			<div class="phoenixzoo-meta-value phoenixzoo-meta-medium">
				<input type="number" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo esc_attr( get_post_meta( $object->ID, $meta_key, true ) ); ?>" size="30" />
			</div>
		</p>
		
		<?php
		$meta_key = 'phoenix_zoo_meta_map_type';
		$meta_value = get_post_meta( $object->ID, $meta_key, true );
		if ( 'post' == $screen->base && 'add' == $screen->action ) { // if this is first add, use a default value
			$meta_value = 'HYBRID';
		}
		?>
		<p>
			<div class="phoenixzoo-meta-name phoenixzoo-meta-name-secondary">
				<label for="<?php echo $meta_key; ?>"><?php _ex( 'Type', 'map', 'phoenix-zoo' ); ?></label>
			</div>
			<div class="phoenixzoo-meta-value">
				<select name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>">
					<?php 
					foreach ( $this->map_types as $map_type_key => $map_type ) {
						echo '<option value="' . $map_type_key . '"' . ( $map_type_key == $meta_value ? ' selected="selected"' : '' ) . '>' . $map_type . '</option>';
					}
					?>		
				</select>
				<p class="description">
					<?php _e( 'You can show a road map, satellite imagery, a combination of both or terrain.', 'phoenix-zoo' ); ?>
				</p>
			</div>
		</p>
		
		<?php
		$meta_key = 'phoenix_zoo_meta_map_zoom';
		$meta_value = get_post_meta( $object->ID, $meta_key, true );
		if ( 'post' == $screen->base && 'add' == $screen->action ) { // if this is first add, use a default value
			$meta_value = '14';
		}
		?>
		<p>
			<div class="phoenixzoo-meta-name phoenixzoo-meta-name-secondary">
				<label for="<?php echo $meta_key; ?>"><?php _e( 'Zoom Level', 'phoenix-zoo' ); ?></label>
			</div>
			<div class="phoenixzoo-meta-value">
			<input type="number" name="<?php echo $meta_key ?>" id="<?php echo $meta_key ?>" value="<?php echo $meta_value; ?>">
				<p class="description">
					<?php _e( 'A lower number is more zoomed out while a higher number is more zoomed in.', 'phoenix-zoo' ); ?>
				</p>
			</div>
		</p>

	<?php
	}

}
