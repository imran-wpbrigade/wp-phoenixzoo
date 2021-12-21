<?php

add_shortcode( 'phoenixzoo_all_camps_programs', 'phoenixzoo_all_camps_programs_cb' );

function phoenixzoo_all_camps_programs_cb($attr) {
  ob_start();
  
  global $post;
  $old_post = $post;
  
  add_filter( 'excerpt_length', 'phoenix_zoo_excerpt_length' );

  // $data = shortcode_atts(
  //     array(
  //       'limit' => '12'
  //     ), $attr
  //   );
  // $limit = $data['limit'];
	?>

	<div class="camps_and_programs-wrapper">
    <ul class="camps_and_programs">

      <?php $posts_ids = array(); ?>
      <?php
			$args = array(
        'post_type' => 'phoenixzoo-cp',
        'posts_per_page' => -1,
        'meta_query' => array( array(
          'key'     => 'phoenix_zoo_meta_cp_position',
          'compare' => '!=',
          'value' => ''
          ) ),
        'meta_key' => 'phoenix_zoo_meta_cp_position',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
        );
      $the_query = new WP_Query( $args );
			?>

			<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php $posts_ids[] = get_the_id(); ?>
      <?php endwhile; endif; ?>
      
      <?php wp_reset_postdata(); ?>

      <?php
			$args = array(
        'post_type' => 'phoenixzoo-cp',
        'posts_per_page' => -1,
        'meta_query' => array( array(
          'key'     => 'phoenix_zoo_meta_cp_position',
          'compare' => '=',
          'value' => ''
          ) ),
        'orderby' => 'date',
        'order' => 'DESC'
        );
			$the_query = new WP_Query( $args );
      ?>

			<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
      <?php $posts_ids[] = get_the_id(); ?>
			<?php endwhile; endif; ?>
      <?php wp_reset_postdata(); ?>

      <?php
			if ( ! empty( $posts_ids ) ) {
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$event_args = array(
				'post_type' => 'phoenixzoo-cp',
				'posts_per_page' => get_option('posts_per_page'),
				'post__in' => $posts_ids,
        'orderby' => 'post__in',
        'paged' => $paged
			);
			$the_cp_query = new WP_Query( $event_args );
				
      if ( $the_cp_query->have_posts() ) : while ( $the_cp_query->have_posts() ) : $the_cp_query->the_post(); 

      $posts_id = get_the_ID();
      $alt = get_post_meta( get_post_thumbnail_id( $posts_id ), '_wp_attachment_image_alt', true);
      $post_meta = get_post_meta( get_the_id( $posts_id ) );
      $program_date = isset( $post_meta['phoenix_zoo_meta_cp_date'][0] ) ? $post_meta['phoenix_zoo_meta_cp_date'][0] : '';
      ?>

      <li>
        <div class="event-inner-container">
          <a href="<?php echo get_the_permalink( $posts_id ); ?>" class="event-img-wrapper">
            <img src="<?php echo esc_url( get_the_post_thumbnail_url( $posts_id ) ); ?>" alt="<?php echo $alt; ?>">
          </a>
          <div class="event-content-wrapper">
            <span class="category-name">Kids & Family</span>
            <h2 class="h4"><a href="<?php echo get_the_permalink( $posts_id ); ?>"> <?php echo get_the_title( $posts_id );?> </a></h2>
            <h6><?php echo $program_date; ?></h6>
            <p><?php echo str_replace( '[...]', '...', get_the_excerpt( $posts_id ) ); ?></p>
            <a href="<?php echo get_the_permalink( $posts_id ); ?>" class="event-link">Info & Registration</a>
          </div>
        </div>
      </li>

      <?php
      endwhile;	
      wp_reset_postdata();
      endif;
			}
      ?>

      </ul>
    </div>

    <?php
    $paginate = '<div class="elementor-pagination zoo-pagination">';
    $paginate .= zoo_paginate_links(
    array(
      'mid_size'  => 4,
      // 'format'    => '?paged=%#%',
      'current'   => max( 1, get_query_var( 'paged' ) ),
      'prev_text' => __( '&#xAB; Previous' ),
      'next_text' => __( 'Next »' ),
      'total'     => $the_cp_query->max_num_pages,
      'end_size'  => 2,
      )
    );
    $paginate .= '</div>';
    echo $paginate;
    $post = $old_post;
    return ob_get_clean();
}

/**
 * All events shortcode.
 */
function phoenixzoo_all_upcoming_events_cb($attr) {
  ob_start();
	phoenixzoo_set_timezone();

  global $post;
  $old_post = $post;

  add_filter( 'excerpt_length', 'phoenix_zoo_excerpt_length' );
	// $data = shortcode_atts(
	//   array(
	//     'limit' => '3'
	//   ), $attr
	// );
	?>

	<div class="upcoming-events-wrapper">
		<ul class="upcoming-events">

      <?php
      $i = 0;
			$event_ids = array();
			$event_date_view = array();
			$args = array(
        'post_type' => 'phoenixzoo-events',
        'posts_per_page' => -1,
        'meta_key' => 'phoenix_zoo_meta_startdate',
        'orderby' => 'meta_value',
        'order' => 'ASC'
			);
      $the_query = new WP_Query( $args );

      if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
        $post_meta = get_post_meta(get_the_ID());

        // If no start date, skip this event
        if ( empty( $post_meta['phoenix_zoo_meta_startdate'][0] ) ) {
          continue;
        }

        $current_date = date( "Y-m-d" );

        $event_start_date = isset( $post_meta['phoenix_zoo_meta_startdate'][0] ) && ! empty( $post_meta['phoenix_zoo_meta_startdate'][0] ) ? strtotime( $post_meta['phoenix_zoo_meta_startdate'][0] ) : '';
        $event_end_date = isset( $post_meta['phoenix_zoo_meta_enddate'][0] ) && ! empty( $post_meta['phoenix_zoo_meta_enddate'][0] ) ? strtotime( $post_meta['phoenix_zoo_meta_enddate'][0] ) : '';

        $date_view = '';
        $conditional_date = '';

        if ( ( ! empty( $event_start_date ) && ! empty( $event_end_date ) ) && ( $event_start_date < $event_end_date ) ) {
          // Multi day event
          $date_view = date( "l, F d", $event_start_date ) . ' - ' . date( "l, F d", $event_end_date );
          // Changing format to compare with current date
          $conditional_date = date( "Y-m-d", $event_end_date );
        } else {
          // Single day event.
          $date_view = date( "l, F d", $event_start_date );
          // Changing format to compare with current date
          $conditional_date = date( "Y-m-d", $event_start_date );
        }

        if ( $current_date <= $conditional_date  ) {
					$ids = get_the_id();
          $event_ids[] = $ids;
          $event_date_view[$ids]['string'] = $date_view;
        }

        $i++;

      endwhile;
      endif;

			wp_reset_query();

			// var_dump($event_ids);
			// var_dump($event_date_view);

			if ( ! empty( $event_ids ) ) {
			
        // $event_ids = array_values( $event_ids );
        // $event_date_view = array_values( $event_date_view );

        // if ( $data['limit'] == '-1' ) {
        //   $events_limit = count( $event_ids );
        // } else {
        //   $events_limit = ( $data['limit'] >= count( $event_ids ) ) ? count( $event_ids ) : $data['limit'];
				// }

			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$event_args = array(
				'post_type' => 'phoenixzoo-events',
				'posts_per_page' => get_option('posts_per_page'),
				'meta_key' => 'phoenix_zoo_meta_startdate',
				'orderby' => 'meta_value',
				'order' => 'ASC',
				'post__in' => $event_ids,
        'paged' => $paged
			);
			$the_event_query = new WP_Query( $event_args );
				
      if ( $the_event_query->have_posts() ) : while ( $the_event_query->have_posts() ) : $the_event_query->the_post();
        $post_meta = get_post_meta(get_the_ID());
				$the_id = get_the_ID();
				$alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
				?>
          <li>
            <div class="event-inner-container">
              <a href="<?php echo get_the_permalink(  ); ?>" class="event-img-wrapper">
                <img src="<?php echo esc_url( get_the_post_thumbnail_url(  ) ); ?>" alt="<?php echo $alt; ?>">
              </a>
              <div class="event-content-wrapper">
                <h2 class="h4"><a href="<?php echo get_the_permalink(  ); ?>"> <?php echo get_the_title(); ?> </a></h2>
                <h6><?php echo $event_date_view[$the_id]['string']; ?></h6>
                <p><?php echo get_the_excerpt(); ?></p>
                <a class="event-link" href="<?php echo get_the_permalink(  ); ?>" >Info &amp; Tickets</a>
              </div>
            </div>
          </li>

        <?php
				endwhile;	
				wp_reset_query();

				endif;
			}
      ?>

		</ul>
	</div>

	<?php

	$paginate = '<div class="elementor-pagination zoo-pagination">';
	$paginate .= zoo_paginate_links(
	array(
		'mid_size'  => 4,
		// 'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'prev_text' => __( '&#xAB; Previous' ),
		'next_text' => __( 'Next »' ),
		'total'     => $the_event_query->max_num_pages,
		'end_size'  => 2,
		)
	);
	$paginate .= '</div>';
	
	echo $paginate;

	wp_reset_query();

  $post = $old_post;

	return ob_get_clean();
}
add_shortcode( 'phoenixzoo_all_upcoming_events', 'phoenixzoo_all_upcoming_events_cb' );

/**
 * Events shortcode.
 */
function phoenixzoo_upcoming_events_cb($attr) {
	ob_start();
	phoenixzoo_set_timezone();

  global $post;
  $old_post = $post;

  add_filter( 'excerpt_length', 'phoenix_zoo_excerpt_length' );

    $data = shortcode_atts(
      array(
        'limit' => '3'
      ), $attr
    );
	?>

	<div class="upcoming-events-wrapper">
		<ul class="upcoming-events">

      <?php
      $i = 0;
      $event_ids = array();
			$args = array(
        'post_type' => 'phoenixzoo-events',
        'posts_per_page' => -1,
        'meta_key' => 'phoenix_zoo_meta_startdate',
        'orderby' => 'meta_value',
        'order' => 'ASC'
			);
      $the_query = new WP_Query( $args );

      if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
        $post_meta = get_post_meta(get_the_ID());

        // If no start date, skip this event
        if ( empty( $post_meta['phoenix_zoo_meta_startdate'][0] ) ) {
          continue;
        }

        $current_date = date( "Y-m-d" );

        $event_start_date = isset( $post_meta['phoenix_zoo_meta_startdate'][0] ) && ! empty( $post_meta['phoenix_zoo_meta_startdate'][0] ) ? strtotime( $post_meta['phoenix_zoo_meta_startdate'][0] ) : '';
        $event_end_date = isset( $post_meta['phoenix_zoo_meta_enddate'][0] ) && ! empty( $post_meta['phoenix_zoo_meta_enddate'][0] ) ? strtotime( $post_meta['phoenix_zoo_meta_enddate'][0] ) : '';

        $date_view = '';
        $conditional_date = '';

        if ( ( ! empty( $event_start_date ) && ! empty( $event_end_date ) ) && ( $event_start_date < $event_end_date ) ) {
          // Multi day event
          $date_view = date( "l, F d", $event_start_date ) . ' - ' . date( "l, F d", $event_end_date );
          // Changing format to compare with current date
          $conditional_date = date( "Y-m-d", $event_end_date );
        } else {
          // Single day event.
          $date_view = date( "l, F d", $event_start_date );
          // Changing format to compare with current date
          $conditional_date = date( "Y-m-d", $event_start_date );
        }

        if ( $current_date <= $conditional_date  ) {
          $event_ids[$i]['id'] = get_the_id();
          $event_ids[$i]['string'] = $date_view;
        }

        $i++;

      endwhile;
    
      wp_reset_postdata();
     
      endif;

      if ( ! empty( $event_ids ) ) {
        $event_ids = array_values( $event_ids );

        if ( $data['limit'] == '-1' ) {
          $events_limit = count( $event_ids );
        } else {
          $events_limit = ( $data['limit'] >= count( $event_ids ) ) ? count( $event_ids ) : $data['limit'];
        }

        for ( $j=0; $j < $events_limit; $j++ ) { 
					$post_meta = get_post_meta( $event_ids[$j]['id'] ); 
       		$alt = get_post_meta( get_post_thumbnail_id( $event_ids[$j]['id'] ), '_wp_attachment_image_alt', true);
					?>

          <li>
            <div class="event-inner-container">
              <a href="<?php echo get_the_permalink( $event_ids[$j]['id'] ); ?>" class="event-img-wrapper">
                <img src="<?php echo esc_url( get_the_post_thumbnail_url( $event_ids[$j]['id'] ) ); ?>" alt="<?php echo $alt; ?>">
              </a>
              <div class="event-content-wrapper">
                <h2 class="h4"><a href="<?php echo get_the_permalink( $event_ids[$j]['id'] ); ?>"> <?php echo get_the_title($event_ids[$j]['id']); ?> </a></h2>
                <h6><?php echo $event_ids[$j]['string']; ?></h6>
                <p><?php echo get_the_excerpt($event_ids[$j]['id']); ?></p>
                <a class="event-link" href="<?php echo get_the_permalink( $event_ids[$j]['id'] ); ?>" >Info &amp; Tickets</a>
              </div>
            </div>
          </li>

        <?php
        }
      }?>

		</ul>
	</div>

	<?php
  $post = $old_post;

	return ob_get_clean();
}
add_shortcode( 'phoenixzoo_upcoming_events', 'phoenixzoo_upcoming_events_cb' );

/**
 * Shortcode for upcoming events.
 */
function render_camps_programs($attr) {
  ob_start();
  
  global $post;
  $old_post = $post;
  
  add_filter( 'excerpt_length', 'phoenix_zoo_excerpt_length' );

  $data = shortcode_atts(
      array(
        'limit' => '3'
      ), $attr
    );

  $limit = $data['limit'];
	?>

	<div class="camps_and_programs-wrapper">
    <ul class="camps_and_programs">

      <?php $posts_ids = array(); ?>

      <?php
			$args = array(
        'post_type' => 'phoenixzoo-cp',
        'posts_per_page' => $limit,
        'meta_query' => array( array(
          'key'     => 'phoenix_zoo_meta_cp_position',
          'compare' => '!=',
          'value' => ''
          ) ),
        'meta_key' => 'phoenix_zoo_meta_cp_position',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
        );
      $the_query = new WP_Query( $args );
			?>

			<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<?php $posts_ids[] = get_the_id(); ?>
      <?php endwhile; endif; ?>

      <?php
			$args = array(
        'post_type' => 'phoenixzoo-cp',
        'posts_per_page' => $limit,
        'meta_query' => array( array(
          'key'     => 'phoenix_zoo_meta_cp_position',
          'compare' => '=',
          'value' => ''
          ) ),
        'orderby' => 'date',
        'order' => 'DESC'
        );
			$the_query = new WP_Query( $args );
      ?>

			<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
      <?php $posts_ids[] = get_the_id(); ?>
			<?php endwhile; endif; ?>
      <?php wp_reset_query(); ?>

			<?php
			if ( '-1' == $limit ) {
				$limit = count( $posts_ids );
			}

      for ( $i=0; $i < $limit; $i++ ) :
        $posts_id = $posts_ids[$i];
        $alt = get_post_meta( get_post_thumbnail_id( $posts_id ), '_wp_attachment_image_alt', true);
        $post_meta = get_post_meta( get_the_id( $posts_id ) );
        $program_date = isset( $post_meta['phoenix_zoo_meta_cp_date'][0] ) ? $post_meta['phoenix_zoo_meta_cp_date'][0] : '';
        ?>

        <li>
          <div class="event-inner-container">
            <a href="<?php echo get_the_permalink( $posts_id ); ?>" class="event-img-wrapper">
              <img src="<?php echo esc_url( get_the_post_thumbnail_url( $posts_id ) ); ?>" alt="<?php echo $alt; ?>">
            </a>
            <div class="event-content-wrapper">
              <span class="category-name">Kids & Family</span>
              <h2 class="h4"><a href="<?php echo get_the_permalink( $posts_id ); ?>"> <?php echo get_the_title( $posts_id );?> </a></h2>
              <h6><?php echo $program_date; ?></h6>
              <p><?php echo str_replace( '[...]', '...', get_the_excerpt( $posts_id ) ); ?></p>
              <a href="<?php echo get_the_permalink( $posts_id ); ?>" class="event-link">Info & Registration</a>
            </div>
          </div>
        </li>

      <?php endfor; ?>

		</ul>
	</div>

  <?php
  $post = $old_post;

	return ob_get_clean();
}
add_shortcode( 'phoenix_camps_and_programs', 'render_camps_programs' );

/**
 * Shortcode for the custom main menu.
 */
function main_menu_shortcode($atts) {
  ob_start(); ?>
  <span class="main-menu-btn"><span class="line"></span></span>
  <!-- This menu will only be used for Desktop -->
  <nav class="main-nav-container">
    <?php wp_nav_menu( array(
      'theme_location' => 'phoenix-zoo-main-menu',
      'menu_class'     => 'header-main-menu',
      'container'      => '',
      'fallback_cb'    => '',
    ) );?>
    <span class="search-btn">Search</span>
  </nav>
  <!-- This menu will only be used for Mobile -->
  <nav class="main-nav-container-mobile">
  <div class="menu-inner">
    <div class="menu-inner-wrapper">
      <?php wp_nav_menu( array(
        'theme_location' => 'phoenix-zoo-main-menu',
        'menu_class'     => 'header-main-menu',
        'container'      => '',
        'fallback_cb'    => '',
      ) );?>
      <!-- <span class="search-btn">Search</span> -->
      <form method="post" action="<?php echo get_home_url(); ?>" method="post" class="mobile-search-form">
        <input type="search" name="s" placeholder="SEARCH">
        <button class="seach" type="submit">Search</button>
      </form>
      <?php wp_nav_menu( array(
        'theme_location' => 'phoenix-zoo-utility-nav',
        'menu_class'     => 'utility-menu',
        'container'      => '',
        'fallback_cb'    => '',
      ) );?>
    </div>
  </div>
  </nav>
	<?php $output_string = ob_get_contents();
	ob_end_clean();
  return $output_string;
}
add_shortcode( 'phoenix_main_menu', 'main_menu_shortcode' );


/*******************
 *  Helper Methods
 ******************/

function phoenix_zoo_excerpt_length( $length ) {
  return 25;
}

add_filter('excerpt_more', 'phoenix_zoo_excerpt_more', 30);
function phoenix_zoo_excerpt_more( $more ) {
	return '...';
}

function zoo_paginate_links( $args = '' ) {
	global $wp_query, $wp_rewrite;

	// Setting up default values based on the current URL.
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$url_parts    = explode( '?', $pagenum_link );

	// Get max pages and current page out of the current query, if available.
	$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
	$current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

	// Append the format placeholder to the base URL.
	$pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

	// URL base depends on permalink settings.
	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	$defaults = array(
		'base'               => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
		'format'             => $format, // ?page=%#% : %#% is replaced by the page number
		'total'              => $total,
		'current'            => $current,
		'aria_current'       => 'page',
		'show_all'           => false,
		'prev_next'          => true,
		'prev_text'          => __( '&laquo; Previous' ),
		'next_text'          => __( 'Next &raquo;' ),
		'end_size'           => 1,
		'mid_size'           => 2,
		'type'               => 'plain',
		'add_args'           => array(), // array of query args to add
		'add_fragment'       => '',
		'before_page_number' => '',
		'after_page_number'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( ! is_array( $args['add_args'] ) ) {
		$args['add_args'] = array();
	}

	// Merge additional query vars found in the original URL into 'add_args' array.
	if ( isset( $url_parts[1] ) ) {
		// Find the format argument.
		$format       = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
		$format_query = isset( $format[1] ) ? $format[1] : '';
		wp_parse_str( $format_query, $format_args );

		// Find the query args of the requested URL.
		wp_parse_str( $url_parts[1], $url_query_args );

		// Remove the format argument from the array of query arguments, to avoid overwriting custom format.
		foreach ( $format_args as $format_arg => $format_arg_value ) {
			unset( $url_query_args[ $format_arg ] );
		}

		$args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
	}

	// Who knows what else people pass in $args
	$total = (int) $args['total'];
	if ( $total < 2 ) {
		return;
	}
	$current  = (int) $args['current'];
	$end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
	if ( $end_size < 1 ) {
		$end_size = 1;
	}
	$mid_size = (int) $args['mid_size'];
	if ( $mid_size < 0 ) {
		$mid_size = 2;
	}
	$add_args   = $args['add_args'];
	$r          = '';
	$page_links = array();
	$dots       = false;

	if ( $args['prev_next'] && $current ) :
		$link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
		$link = str_replace( '%#%', $current - 1, $link );
		if ( $add_args ) {
			$link = add_query_arg( $add_args, $link );
		}
		$link .= $args['add_fragment'];

		$disabled_class = '';
		if ( $current == 1 ) {
			$link           = '#/';
			$disabled_class = 'disabled';
		}
		/**
		 * Filters the paginated links for the given archive pages.
		 *
		 * @since 3.0.0
		 *
		 * @param string $link The paginated link URL.
		 */
		$page_links[] = '<a  data-page-no="' . number_format_i18n( $current - 1 ) . '" class="prev page-numbers prev-page ' . $disabled_class . '" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a>';
	endif;
	for ( $n = 1; $n <= $total; $n++ ) :
		if ( $n == $current ) :
			$page_links[] = "<span aria-current='" . esc_attr( $args['aria_current'] ) . "' class='page-numbers current'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . '</span>';
			$dots         = true;
		else :
			if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
				$link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
				$link = str_replace( '%#%', $n, $link );
				if ( $add_args ) {
					$link = add_query_arg( $add_args, $link );
				}
				$link .= $args['add_fragment'];

				/** This filter is documented in wp-includes/general-template.php */
				$page_links[] = "<a data-page-no='" . number_format_i18n( $n ) . "' class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . '</a>';
				$dots         = true;
			elseif ( $dots && ! $args['show_all'] ) :
				$page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';
				$dots         = false;
			endif;
		endif;
	endfor;
	if ( $args['prev_next'] && $current ) :
		$link = str_replace( '%_%', $args['format'], $args['base'] );
		$link = str_replace( '%#%', $current + 1, $link );
		if ( $add_args ) {
			$link = add_query_arg( $add_args, $link );
		}
		$link .= $args['add_fragment'];

		$disabled_class = '';
		if ( $current == $total ) {
			$link           = '#/';
			$disabled_class = 'disabled';
		}
		/** This filter is documented in wp-includes/general-template.php */
		$page_links[] = '<a data-page-no="' . number_format_i18n( $current + 1 ) . '" class="next page-numbers next-page ' . $disabled_class . '" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a>';
	endif;
	switch ( $args['type'] ) {
		case 'array':
			return $page_links;

		case 'list':
			$r .= "<ul class='page-numbers'>\n\t<li>";
			$r .= join( "</li>\n\t<li>", $page_links );
			$r .= "</li>\n</ul>\n";
			break;

		default:
			$r = join( "\n", $page_links );
			break;
	}
	return $r;
}