<?php
/**
 * Theme functions and definitions
 *
 * @package PhoenixZoo
 */
	define( 'PHOENXI_ZOO_VERSION', time() );

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function phoenix_enqueue_scripts() {
  //Styles
	wp_enqueue_style(
		'phoenix-zoo-style',
    get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		PHOENXI_ZOO_VERSION
  );
  wp_enqueue_style(
		'phoenix-zoo-main-css',
    get_stylesheet_directory_uri() . '/assets/css/main.css',
		time()
  );
  wp_enqueue_script(
    'phoenix-zoo-iscroll-js',
    get_stylesheet_directory_uri() . '/assets/js/iscroll.min.js',
    array( 'jquery' ) ,
    PHOENXI_ZOO_VERSION,
    true
  );

	//Scripts
	wp_enqueue_script(
    'phoenix-zoo-main-js',
    get_stylesheet_directory_uri() . '/assets/js/main.js',
    array( 'jquery' ) ,
    PHOENXI_ZOO_VERSION,
    true
	);
}

add_action( 'wp_enqueue_scripts', 'phoenix_enqueue_scripts' , 200 );

function phoenix_admin_enqueue_scripts( $sceern ) {
	// if ( 'post.php' != $sceern || 'post-new.php' != $sceern ) {
	// 	return;
	// }

	wp_enqueue_style(
    'phoenix-zoo-admin-styles',
    get_stylesheet_directory_uri() . '/assets/css/admin.css',
		PHOENXI_ZOO_VERSION
	);
}

add_action( 'admin_enqueue_scripts', 'phoenix_admin_enqueue_scripts' );

// require_once ('includes/events.php');
//register main menu for phoenix
function alternative_main_menu() {
  register_nav_menus(
    array(
      'phoenix-zoo-main-menu' => __( 'Phoenix Zoo Main Menu' ),
      'phoenix-zoo-utility-nav'=> __( 'Utility Nav Menu' )
    )
  );
}
add_action( 'init', 'alternative_main_menu' );

// Add required functionality files.
include get_stylesheet_directory() . '/includes/phoenix-zoo-cpt.php';
include get_stylesheet_directory() . '/includes/phoenix-zoo-events-metabox.php';
include get_stylesheet_directory() . '/includes/shortcodes.php';


//************************************************
/* Add Sortable Postion Column in Camps & Program 
**************************************************/

// Add the custom column to the post type
add_filter( 'manage_phoenixzoo-cp_posts_columns', 'phoenix_add_position_column' );
function phoenix_add_position_column( $columns ) {
  // $columns['position'] = 'position';
  $res = array_slice( $columns, 0, 2, true ) + array( "position" => "Position" ) + array_slice( $columns, 2, count($columns)-2, true );
    
  return $res;
}

// Add the data to the custom column
add_action( 'manage_phoenixzoo-cp_posts_custom_column' , 'phoenix_add_custom_position_data', 11, 99 );
function phoenix_add_custom_position_data( $column, $post_id ) {
  switch ( $column ) {
    case 'position' :
      echo get_post_meta( $post_id , 'phoenix_zoo_meta_cp_position' , true ); // the data that is displayed in the column
      break;
  }
}

// Make the custom column sortable
add_filter( 'manage_edit-phoenixzoo-cp_sortable_columns', 'phoenix_add_custom_position_make_sortable' );
function phoenix_add_custom_position_make_sortable( $columns ) {
	$columns['position'] = 'Position';

	return $columns;
}

// Add custom column sort request to post list page
add_action( 'load-edit.php', 'phoenix_add_custom_position_sort_request' );
function phoenix_add_custom_position_sort_request() {
	add_filter( 'request', 'phoenix_add_custom_position_do_sortable' );
}

// Handle the custom column sorting
function phoenix_add_custom_position_do_sortable( $vars ) {
	// check if post type is being viewed
	if ( isset( $vars['post_type'] ) && 'phoenixzoo-cp' == $vars['post_type'] ) {
		// check if sorting has been applied
		if ( isset( $vars['orderby'] ) && 'Position' == $vars['orderby'] ) {
			// apply the sorting to the post list
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'phoenix_zoo_meta_cp_position',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}


//*********************************************
/* Add Sortable Start Date in Camps & Program 
***********************************************/

// Add the custom column to the post type
add_filter( 'manage_phoenixzoo-cp_posts_columns', 'phoenix_add_cp_date_column' );
function phoenix_add_cp_date_column( $columns ) {
  // $columns['position'] = 'position';
  $res = array_slice( $columns, 0, 2, true ) + array( "start_date" => "Start Date" ) + array_slice( $columns, 2, count($columns)-2, true );
    
  return $res;
}

// Add the data to the custom column
add_action( 'manage_phoenixzoo-cp_posts_custom_column' , 'phoenix_add_custom_cp_date_data', 11, 99 );
function phoenix_add_custom_cp_date_data( $column, $post_id ) {
  switch ( $column ) {
    case 'start_date' :
      echo get_post_meta( $post_id , 'phoenix_zoo_meta_cp_date' , true ); // the data that is displayed in the column
      break;
  }
}

// // Make the custom column sortable
// add_filter( 'manage_edit-phoenixzoo-cp_sortable_columns', 'phoenix_add_custom_cp_date_make_sortable' );
// function phoenix_add_custom_cp_date_make_sortable( $columns ) {
// 	$columns['start_date'] = 'start_date';

// 	return $columns;
// }

// // Add custom column sort request to post list page
// add_action( 'load-edit.php', 'phoenix_add_custom_cp_date_sort_request' );
// function phoenix_add_custom_cp_date_sort_request() {
// 	add_filter( 'request', 'phoenix_add_custom_cp_date_do_sortable' );
// }

// // Handle the custom column sorting
// function phoenix_add_custom_cp_date_do_sortable( $vars ) {
// 	// check if post type is being viewed
// 	if ( isset( $vars['post_type'] ) && 'phoenixzoo-cp' == $vars['post_type'] ) {
// 		// check if sorting has been applied
// 		if ( isset( $vars['orderby'] ) && 'start_date' == $vars['orderby'] ) {
// 			// apply the sorting to the post list
// 			$vars = array_merge(
// 				$vars,
// 				array(
// 					'meta_key' => 'phoenix_zoo_meta_cp_date',
// 					'orderby' => 'meta_value'
// 				)
// 			);
// 		}
// 	}

// 	return $vars;
// }

//*********************************************
/* Add Sortable End Date in Events
***********************************************/

// Add the custom column to the post type
add_filter( 'manage_phoenixzoo-events_posts_columns', 'phoenix_add_event_end_column' );
function phoenix_add_event_end_column( $columns ) {
  // $columns['position'] = 'position';
  $res = array_slice( $columns, 0, 2, true ) + array( "end_date" => "End Date" ) + array_slice( $columns, 2, count($columns)-2, true );
    
  return $res;
}

// Add the data to the custom column
add_action( 'manage_phoenixzoo-events_posts_custom_column' , 'phoenix_add_custom_event_end_data', 11, 99 );
function phoenix_add_custom_event_end_data( $column, $post_id ) {
  switch ( $column ) {
    case 'end_date' :
      echo date( "m/d/Y", strtotime( get_post_meta( $post_id , 'phoenix_zoo_meta_enddate' , true ) ) );
      break;
  }
}

// Make the custom column sortable
add_filter( 'manage_edit-phoenixzoo-events_sortable_columns', 'phoenix_add_custom_event_end_make_sortable' );
function phoenix_add_custom_event_end_make_sortable( $columns ) {
	$columns['end_date'] = 'end_date';

	return $columns;
}

// Add custom column sort request to post list page
add_action( 'load-edit.php', 'phoenix_add_custom_event_end_sort_request' );
function phoenix_add_custom_event_end_sort_request() {
	add_filter( 'request', 'phoenix_add_custom_event_end_do_sortable' );
}

// Handle the custom column sorting
function phoenix_add_custom_event_end_do_sortable( $vars ) {
	// check if post type is being viewed
	if ( isset( $vars['post_type'] ) && 'phoenixzoo-events' == $vars['post_type'] ) {
		// check if sorting has been applied
		if ( isset( $vars['orderby'] ) && 'end_date' == $vars['orderby'] ) {
			// apply the sorting to the post list
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'phoenix_zoo_meta_enddate',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}

//*********************************************
/* Add Sortable Start Date in Events
***********************************************/

// Add the custom column to the post type
add_filter( 'manage_phoenixzoo-events_posts_columns', 'phoenix_add_event_start_column' );
function phoenix_add_event_start_column( $columns ) {
  // $columns['position'] = 'position';
  $res = array_slice( $columns, 0, 2, true ) + array( "start_date" => "Start Date" ) + array_slice( $columns, 2, count($columns)-2, true );
    
  return $res;
}

// Add the data to the custom column
add_action( 'manage_phoenixzoo-events_posts_custom_column' , 'phoenix_add_custom_event_start_data', 11, 99 );
function phoenix_add_custom_event_start_data( $column, $post_id ) {
  switch ( $column ) {
    case 'start_date' :
      echo date( "m/d/Y", strtotime( get_post_meta( $post_id , 'phoenix_zoo_meta_startdate' , true ) ) );
      break;
  }
}

// Make the custom column sortable
add_filter( 'manage_edit-phoenixzoo-events_sortable_columns', 'phoenix_add_custom_event_start_make_sortable' );
function phoenix_add_custom_event_start_make_sortable( $columns ) {
	$columns['start_date'] = 'start_date';

	return $columns;
}

// Add custom column sort request to post list page
add_action( 'load-edit.php', 'phoenix_add_custom_event_start_sort_request' );
function phoenix_add_custom_event_start_sort_request() {
	add_filter( 'request', 'phoenix_add_custom_event_start_do_sortable' );
}

// Handle the custom column sorting
function phoenix_add_custom_event_start_do_sortable( $vars ) {
	// check if post type is being viewed
	if ( isset( $vars['post_type'] ) && 'phoenixzoo-events' == $vars['post_type'] ) {
		// check if sorting has been applied
		if ( isset( $vars['orderby'] ) && 'start_date' == $vars['orderby'] ) {
			// apply the sorting to the post list
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'phoenix_zoo_meta_startdate',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}


// add broser class in body
function mv_browser_body_class($classes) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
 
    if($is_lynx) $classes[] = 'lynx';
    elseif($is_gecko) $classes[] = 'gecko';
    elseif($is_opera) $classes[] = 'opera';
    elseif($is_NS4) $classes[] = 'ns4';
    elseif($is_safari) $classes[] = 'safari';
    elseif($is_chrome) $classes[] = 'chrome';
    elseif($is_IE) {
        $classes[] = 'ie';
        if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
        $classes[] = 'ie'.$browser_version[1];
 
    } else $classes[] = 'unknown';
 
    if($is_iphone) $classes[] = 'iphone';
 
    if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
         $classes[] = 'osx';
       } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
         $classes[] = 'linux';
       } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
         $classes[] = 'windows';
       }
    return $classes;
}
add_filter('body_class','mv_browser_body_class');

// Events
	// add_action( 'init', 'risen_event_post_type' ); // register post type
	// add_action( 'load-post-new.php', 'risen_event_meta_boxes_setup' ); // setup meta boxes on add page
	// add_action( 'load-post.php', 'risen_event_meta_boxes_setup' ); // setup meta boxes on edit page
	// add_filter( 'admin_post_thumbnail_html', 'risen_staff_featured_image_note'); // add note below Featured Image
	// add_filter( 'manage_risen_event_posts_columns' , 'risen_event_columns' ); // add columns for meta values
	// add_action( 'manage_posts_custom_column' , 'risen_event_columns_content' ); // add content to the new columns
	// add_filter( 'manage_edit-risen_event_sortable_columns', 'risen_event_columns_sorting' ); // make columns sortable
  // add_filter( 'request', 'risen_event_columns_sorting_request' ); // set how to sort columns


/**
 * Filter yoast breadcrumbs for post types.
 * 
 */
function wpseo_breadcrumb_links_filter_cb( $ancestors ) {
  if ( is_category() ) {
    array_splice($ancestors, 1, 0, array( array(
      "text"=>  "Blog",
      "url"=> get_site_url().'/blog',
      "allow_html"=>true
      ) ));
    }
    
    if ( 'phoenixzoo-events' == get_post_type() ) {
      if ( is_single() ) {
        foreach ( $ancestors as $ancestor ) {
          if ( isset( $ancestor['ptarchive'] ) && 'phoenixzoo-events' == $ancestor['ptarchive'] ) {
            $ancestors[1] = array( 
              "text"=>  "Zoo Events",
              "url"=> get_site_url().'/events',
              "allow_html"=>true
            );
          }
        }
      } else if( is_archive() ) {
        foreach ( $ancestors as $ancestor ) {
          if ( isset( $ancestor['ptarchive'] ) && 'phoenixzoo-events' == $ancestor['ptarchive'] ) {
            $ancestors[1] = array( 
              "text"=>  "Zoo Events",
              "url"=> get_site_url().'/events',
              "allow_html"=>true
            );
          }
        }
      }
    }
    
    if ( 'phoenixzoo-cp' == get_post_type() ) {
      array_splice( $ancestors, 1, 0, array( array(
        "text"        =>  "Engage",
        "url"         => get_site_url().'/camps-programs',
        "allow_html"  => true
        ) ));
        
        if ( is_archive() ) {
          $ancestors[2] = array( 
            "text"=>  "Kids & Family Programs",
        "url"=> get_site_url().'/camps-programs/kids-family-programs',
        "allow_html"=>true
      );
    } else if( is_single() ) {
      $ancestors[2] = array( 
        "text"=>  "Kids & Family Programs",
        "url"=> get_site_url().'/camps-programs/kids-family-programs',
        "allow_html"=>true
      );
    }
  }
  
  return $ancestors;
}
add_filter( 'wpseo_breadcrumb_links', 'wpseo_breadcrumb_links_filter_cb' );

function phoenixzoo_set_timezone() {
	date_default_timezone_set( get_option('timezone_string') );
}

function phz_custom_pre_get_posts( $query ) {  
	if( $query->is_main_query() && !$query->is_feed() && !is_admin() && is_category()) {  
		$query->set( 'paged', str_replace( '/', '', get_query_var( 'page' ) ) );  
	} 
} 
		
add_action('pre_get_posts','phz_custom_pre_get_posts'); 

function phz_custom_request($query_string ) { 
	if( isset( $query_string['page'] ) ) { 
		if( ''!=$query_string['page'] ) { 
			if( isset( $query_string['name'] ) ) {
				unset( $query_string['name'] );
			}
		}
	}
	return $query_string;
}
add_filter('request', 'phz_custom_request');