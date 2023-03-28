<?php 
/**
 * Plugin Name:  Assignment
 * Description: This is my assignment plugin.
 * Version: 1.0.0
 * Author: Zain Bin Tariq WP
 */

// Make sure the plugin is accessed through WordPress.
defined ('ABSPATH') || die("You Can't Access this File Directly");

class My_Featured_Post {
    public function __construct() {
        
        // Add a meta box for the "Featured Post" checkbox.
        add_action( 'add_meta_boxes', array( $this, 'add_featured_meta_box' ) );
        
        // Save the "Featured Post" meta box value when a post is saved.
        add_action( 'save_post', array( $this, 'save_featured_meta_box' ) );
        
        // Modify the main blog archive query to show featured posts first.
        add_action( 'pre_get_posts', array( $this, 'modify_blog_archive_query' ) );
        
        // Enqueue CSS and JavaScript files for the plugin.
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_style_script' ) ); 
    
    }

    public function wp_style_script() {

        // enqueue the stylesheet for the plugin or inject css file in plugin
        wp_enqueue_style('dev_plugin', plugin_dir_url(__FILE__)."assets/css/style.css"); 
        
        // enqueue the JavaScript file for the plugin or inject javascript file in plugin
        wp_enqueue_script('dev_script', plugin_dir_url(__FILE__)."assets/js/custom.js", array(), '1.0.0', true); 
    
    }

    public function add_featured_meta_box() {
        
        // Add a meta box for the "Featured Post" checkbox to the post edit screen.
        add_meta_box(
            'featured_meta_box', // Meta box ID.
            'Featured Post', // Meta box title.
            array( $this, 'featured_meta_box_callback' ), // Callback function to display the meta box content.
            'post', // Post type for which the meta box should be added.
            'side', // Meta box position.
            'high' // Meta box priority.
        );
    }

    public function featured_meta_box_callback( $post ) {

        // Get the value of the "Featured Post" meta field for the post.
        $featured = get_post_meta( $post->ID, '_featured_post', true );
        
        // Display the "Featured Post" checkbox.
        echo '<input type="checkbox" name="featured_post" id="featured_post" ' . checked( $featured, 1 ) . ' >';
        echo '<label for="featured_post">This post is a featured post</label>';
    
    }

    public function save_featured_meta_box( $post_id ) {
        
        // Update the value of the "Featured Post" meta field for the post based on the checkbox value.
        if ( isset( $_POST['featured_post'] ) ) {

            update_post_meta( $post_id, '_featured_post', 1 );
        
        } else {

            update_post_meta( $post_id, '_featured_post', 0 );
        
        }
    }

    public function modify_blog_archive_query( $query ) {
        
        // Check if the current page is the home page and if the query is the main query
        if ( is_home() && $query->is_main_query() ) {
            
            // Define a meta query to retrieve posts that are either featured (have the _featured_post meta key with a value of 1) or not featured (the _featured_post meta key does not exist)
            $meta_query = array(
                'relation' => 'OR',
                array(
                    'key'     => '_featured_post',
                    'value'   => '1',
                    'compare' => '='
                ),
                array(
                    'key'     => '_featured_post',
                    'compare' => 'NOT EXISTS'
                )
            );
            
            // Set the meta key to _featured_post, so only posts with this meta key will be retrieved
            $query->set( 'meta_key', '_featured_post' );
            
            // Order the posts by the value of the _featured_post meta key in descending order
            $query->set( 'orderby', 'meta_value');
            $query->set( 'order', 'DESC');
        }
    }
    
}


$my_featured_post = new My_Featured_Post();

function new_post_type_() {
  
    // Set UI labels for Case Study CPT
    
    $labels = array(
      'name' => _x( 'Case Studies', 'Post Type General Name', 'resources/case-studies' ),
      'singular_name' => _x( 'Case Study', 'Post Type Singular Name', 'resources/case-studies' ),
      'menu_name' => __( 'Case Studies', 'resources/case-studies' ),
      'parent_item_colon' => __( 'Parent Case Study', 'resources/case-studies' ),
      'all_items' => __( 'All Case Studies', 'resources/case-studies' ),
      'view_item' => __( 'View Case Study', 'resources/case-studies' ),
      'add_new_item' => __( 'Add New Case Study', 'resources/case-studies' ),
      'add_new' => __( 'Add New', 'resources/case-studies' ), 'edit_item' => __( 'Edit Case Study', 'resources/case-studies' ),
      'update_item' => __( 'Update Case Study', 'resources/case-studies' ),
      'search_items' => __( 'Search Case Study', 'resources/case-studies' ),
      'not_found' => __( 'Not Found', 'resources/case-studies' ),
      'not_found_in_trash' => __( 'Not found in Trash', 'resources/case-studies' ),
    );
    
    // Set other options for Case Study CPT
    
    $args = array(
      'label' => __( 'case-studies', 'resources/case-studies' ),
      //'description' => __( 'WordPress Developer.', 'resources/case-studies' ),
      'labels' => $labels,
      'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'categories', ),
      'taxonomies' => array( 'category' ),
      'hierarchical' => false, 'public' => true,
      'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => true,
      'show_in_admin_bar' => true, 'menu_position' => 6, 'menu_icon' => 'dashicons-analytics',
      'can_export' => true,
      'has_archive' => true,
      'exclude_from_search' => false,
      'publicly_queryable' => true,
      'capability_type' => 'post',
      'rewrite' => array('slug' => 'case-studies'),
    );
    function namespace_add_custom_types_CPT( $query ) { 
      if( (is_category() || is_tag()) && $query->is_archive() && empty( $query->query_vars['suppress_filters'] ) ) { 
        $query->set( 'post_type', array( 'post', 'case-studies' ))
      ; } return $query;
    }
    
    add_filter( 'pre_get_posts', 'namespace_add_custom_types_CPT' );
    
    // Registering my Case Study CPT
    
    register_post_type( 'case-studies', $args ); }
  
    /*
    * Hook into the 'init' action so that the function
    * Containing our post type registration is not
    * unnecessarily executed.
    */
  
    add_action( 'init', 'new_post_type_', 0 );

    function add_featured_meta_box() {
        add_meta_box(
            'featured_meta_box', // Unique ID
            'Feature Case Study', // Box title
            'featured_meta_box_callback', // Content callback
            'case-studies' // Post type
        );
    }
    
    function featured_meta_box_callback( $post ) {
        // Retrieve the current value of the featured checkbox
        $featured = get_post_meta( $post->ID, 'featured', true );
        ?>
        <label for="featured">
            <input type="checkbox" name="featured" id="featured" value="1" <?php checked( $featured, 1 ); ?>>
            Feature this case study
        </label>
        <?php
    }
    
    function save_featured_meta_box( $post_id ) {
        // Check if the featured checkbox was submitted and save its value
        if ( isset( $_POST['featured'] ) ) {
            update_post_meta( $post_id, 'featured', 1 );
        } else {
            delete_post_meta( $post_id, 'featured' );
        }
    }

    function namespace_add_custom_types( $query ) { 
        if ( $query->is_main_query() && !is_admin() && ( is_post_type_archive( 'case-studies' ) || is_tax( 'category' ) || is_tax( 'post_tag' ) ) ) {
            // Set the meta query to retrieve featured case studies first
            $meta_query = array(
                'relation' => 'OR',
                array(
                    'key' => 'featured',
                    'value' => '1',
                    'compare' => '=',
                ),
                array(
                    'key' => 'featured',
                    'compare' => 'NOT EXISTS',
                ),
            );
            $query->set( 'meta_query', $meta_query );
            $query->set( 'orderby', array( 'meta_value' => 'DESC', 'date' => 'DESC' ) );
        }
    }
    
    
    
    add_action( 'add_meta_boxes', 'add_featured_meta_box' );
    add_action( 'save_post', 'save_featured_meta_box' );
    add_action( 'pre_get_posts', 'namespace_add_custom_types' );
    
    
    
  ?>
