<?php
// Events on the phoenix zoo
/**
* Plugin Name: Phoenix Events
* Description: Phoenix Events plugin is created to handle the content of site.
* Version: 1.0.0
* Text Domain: phoenix-events
* Domain Path: /languages
*
* @package Phoenix Events
* @category Core
* @author Laneterralever
*/


if ( ! class_exists( 'Phoenix_Events' ) ) :

  final class Phoenix_Events {

    /**
    * @var string
    */
    public $version = '1.0.0';

    /**
    * @var The single instance of the class
    * @since 1.0.0
    */
    protected static $_instance = null;

    /**
    * @var WP_Session session
    */
    public $session = null;

    /**
    * @var WP_Query $query
    */
    public $query = null;

    /**s
    * @var WP_Countries $countries
    */
    public $countries = null;

    /* * * * * * * * * *
    * Class constructor
    * * * * * * * * * */
    public function __construct() {
      $this->_hooks();
    }

    /**
    * Hook into actions and filters
    * @since  1.0.0
    */
    private function _hooks() {
      add_action( 'init',                     array( $this, 'phoenix_events_cpt' ) );
      add_action( 'init',                     array( $this, 'phoenix_events_cpt_tax' ) );
      add_action( 'plugins_loaded',           array( $this, 'phoenix_events_textdomain' ) );
    }

    /**
    * Registering CPT for phoenix-events section.
    *
    * @since 1.0.0
    */
    public function phoenix_events_cpt() {
      $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'edit_item'          => 'Edit Event',
        'new_item'           => 'New Event',
        'view_item'          => 'View Event',
        'search_items'       => 'Search Events',
        'not_found'          => 'Nothing found',
        'not_found_in_trash' => 'Nothing found in Trash',
        'parent_item_colon'  => ''
      );
      $args = array(
        'labels'             => $labels,
        'public'             => true,
        // 'publicly_queryable' => false,
        'show_ui'            => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'menu_position'      => 8,
        'rewrite' 			     => array( 'slug' => 'events', 'with_front' => false ),
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
        'has_archive'        => 'events',
      );
      register_post_type( 'phoenixzoo-events' , $args );
    }


    /**
    * Registering taxonomy for phoenix-events post type.
    *
    * @since 1.0.0
    */
    public function phoenix_events_cpt_tax() {
      $labels = array(
        'name'              => 'Categories',
        'singular_name'     => 'Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'parent_item'       => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Categories',
      );

      $args = array(
        'hierarchical'      => true, // Set this to 'false' for non-hierarchical taxonomy (like tags)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        // 'rewrite'           => array( 'slug' => 'phoenixzoo-events-categories' ),
      );

      register_taxonomy( 'phoenixzoo-events-categories', array( 'phoenixzoo-events' ), $args );
    }

    /**
    * Main Instance
    *
    * @since 1.0.0
    * @static
    * @see Phoenix_Events_loader()
    * @return Main instance
    */
    public static function instance() {
      if ( is_null( self::$_instance ) ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    /**
    * Load Languages
    * @since 1.0.0
    */
    public function phoenix_events_textdomain() {
      $plugin_dir =  dirname( plugin_basename( __FILE__ ) ) ;
      load_plugin_textdomain( 'phoenix-events', false, $plugin_dir . '/languages/' );
    }

    /**
    * Define constant if not already set
    * @param  string $name
    * @param  string|bool $value
    */
    private function define( $name, $value ) {
      if ( ! defined( $name ) ) {
        define( $name, $value );
      }
    }

  } // End Of Class.
endif;


/**
* Returns the main instance of WP to prevent the need to use globals.
*
* @since  1.0.0
* @return Phoenix_Events
*/
function Phoenix_Events_loader() {
  return Phoenix_Events::instance();
}

// Call the function
Phoenix_Events_loader();

// Camps and Programs on the phoenixzoo
/**
* Plugin Name: Phoenixzoo Camps and Programs
* Description: Phoenixzoo Camps and Programs plugin is created to handle the content of site.
* Version: 1.0.0
* Text Domain: phoenix-events
* Domain Path: /languages
*
* @package Phoenixzoo Camps and Programs
* @category Core
* @author Laneterralever
*/


if ( ! class_exists( 'Phoenixzoo_camps_and_programs' ) ) :

  final class Phoenixzoo_camps_and_programs {

    /**
    * @var string
    */
    public $version = '1.0.0';

    /**
    * @var The single instance of the class
    * @since 1.0.0
    */
    protected static $_instance = null;

    /**
    * @var WP_Session session
    */
    public $session = null;

    /**
    * @var WP_Query $query
    */
    public $query = null;

    /**s
    * @var WP_Countries $countries
    */
    public $countries = null;

    /* * * * * * * * * *
    * Class constructor
    * * * * * * * * * */
    public function __construct() {
      $this->_hooks();
    }

    /**
    * Hook into actions and filters
    * @since  1.0.0
    */
    private function _hooks() {
      add_action( 'init',                     array( $this, 'phoenixzoo_c_and_p_cpt' ) );
      // add_action( 'init',                     array( $this, 'phoenixzoo_c_and_p_cpt_tax' ) );
      add_action( 'plugins_loaded',           array( $this, 'phoenixzoo_camps_and_programs_textdomain' ) );
    }

    /**
    * Registering CPT for phoenixzoo-cp section.
    *
    * @since 1.0.0
    */
    public function phoenixzoo_c_and_p_cpt() {
      $labels = array(
        'name'               => 'Camps & Programs',
        'singular_name'      => 'Camp & Program',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Camp & Program',
        'edit_item'          => 'Edit Camp & Program',
        'new_item'           => 'New Camp & Program',
        'view_item'          => 'View Camp & Program',
        'search_items'       => 'Search Camp & Programs',
        'not_found'          => 'Nothing found',
        'not_found_in_trash' => 'Nothing found in Trash',
        'parent_item_colon'  => ''
      );
      $args = array(
        'labels'             => $labels,
        'public'             => true,
        // 'publicly_queryable' => false,
        'show_ui'            => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'menu_position'      => 8,
        'rewrite' 			     => array( 'slug' => 'camps-programs/kids-family-programs', 'with_front' => false ),
        'has_archive' => true,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
      );
      register_post_type( 'phoenixzoo-cp' , $args );
    }


    /**
    * Registering taxonomy for phoenixzoo-cp post type.
    *
    * @since 1.0.0
    */
    // public function phoenixzoo_c_and_p_cpt_tax() {
    //   $labels = array(
    //     'name'              => 'Categories',
    //     'singular_name'     => 'Category',
    //     'search_items'      => 'Search Categories',
    //     'all_items'         => 'All Categories',
    //     'parent_item'       => 'Parent Category',
    //     'parent_item_colon' => 'Parent Category:',
    //     'edit_item'         => 'Edit Category',
    //     'update_item'       => 'Update Category',
    //     'add_new_item'      => 'Add New Category',
    //     'new_item_name'     => 'New Category Name',
    //     'menu_name'         => 'Categories',
    //   );

    //   $args = array(
    //     'hierarchical'      => true, // Set this to 'false' for non-hierarchical taxonomy (like tags)
    //     'labels'            => $labels,
    //     'show_ui'           => true,
    //     'show_admin_column' => true,
    //     'query_var'         => true,
    //     'rewrite' => array( 'slug' => 'camps-programs', 'with_front' => false ),
    //   );

    //   register_taxonomy( 'phoenixzoo_cp_categories', 'phoenixzoo-cp', $args );
    // }

    /**
    * Main Instance
    *
    * @since 1.0.0
    * @static
    * @see Phoenixzoo_camps_and_programs_loader()
    * @return Main instance
    */
    public static function instance() {
      if ( is_null( self::$_instance ) ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    /**
    * Load Languages
    * @since 1.0.0
    */
    public function phoenixzoo_camps_and_programs_textdomain() {
      $plugin_dir =  dirname( plugin_basename( __FILE__ ) ) ;
      load_plugin_textdomain( 'phoenixzoo-cp', false, $plugin_dir . '/languages/' );
    }

    /**
    * Define constant if not already set
    * @param  string $name
    * @param  string|bool $value
    */
    private function define( $name, $value ) {
      if ( ! defined( $name ) ) {
        define( $name, $value );
      }
    }

  } // End Of Class.
endif;


/**
* Returns the main instance of WP to prevent the need to use globals.
*
* @since  1.0.0
* @return Phoenixzoo_camps_and_programs
*/
function Phoenixzoo_camps_and_programs_loader() {
  return Phoenixzoo_camps_and_programs::instance();
}

// Call the function
Phoenixzoo_camps_and_programs_loader();

// Use category and change permalinks
// function phoenix_zoo_show_permalinks( $post_link, $post ){
//   if ( is_object( $post ) && $post->post_type == 'phoenixzoo-cp' ){
//     $terms = wp_get_object_terms( $post->ID, 'phoenixzoo_cp_categories' );
//     // var_dump('aaaaaaaaaaa');
//     // var_dump($terms);
//     // die();
//       if( $terms ){
//           return str_replace( '%phoenixzoo_cp_categories%' , $terms[0]->slug , $post_link );
//       }
//   }
//   return $post_link;
// }
// add_filter( 'post_type_link', 'phoenix_zoo_show_permalinks', 1, 2 );