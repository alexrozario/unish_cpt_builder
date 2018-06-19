<?php

/*
 * Package Name: Unish CPT Builder
 * Description: Dynamic custom post type builder
 * Version: 1.0.0
 * Author Name: Alex Benedict Rozario
 * Corporation: www.unish.io
 */

/*
 * require Unish_CPT_Builder class file
 * call Unish_CPT_Builder class : Unish_CPT_Builder
 * Param(array) required : associative array which could have five element array fields
 *   -- custom_post_name (string) : custom post name
 *   -- taxonomy_name (string) : custom taxonomy name
 *   -- custom_post_fields (array) : array for customizing custom post
 *   -- custom_taxonomy_fields (array) : array for customizing custom taxonomy
 *   -- exiting_post (string) : existing post name which will be replaced by this custom_post_name
 *   -- existing_post_slug (string) : existing taxonomy name which will be replaced by this taxonomy_name
 */
class Unish_CPT_Builder {

    /*
    * custom post title
    */
    private $post_title;
	
	 /*
    * custom post name
    */
    private $post_name;

    /*
    * custom taxonomy name
    */
    private $taxonomoy_name;

    /*
    * existing post which will be replaced
    */
    private $exiting_post;

    /*
    * array : fields for customizing custom post
    */
    private $custom_fields;

    /*
    * array : fields for customizing custom taxonomy
    */
    private $taxonomy_fields;

    /*
    * custom taonomy wich will be replaced
    */
    private $existing_tax_slug;

    /*
     * Param(string) required : $custom_post_type - custom post type name
     * Param(string) required : $taxonomy_name - custom taxonomy name. empty string/null must be given if no taxonomy
     * Param(multi-dimensional array): $fields - custom fields array
     * Param(string) : $exiting_post - existing custom post type(will be replaced by this custom post)
     * Param(string) : $existing_tax_slug - existing custom taxonomy type(will be replaced by this custom taxonomy)
    */
    public function __construct( $args ) {

        $this->post_title       		= $args['post_title'];
        $this->post_name        		= $args['custom_post_name'];
        $this->taxonomoy_name   		= $args['taxonomy_name'];

        if( array_key_exists( 'custom_post_fields' , $args ) ) {
            $this->custom_fields = $args['custom_post_fields'];
        } else {
            $this->custom_fields = array();
        }

        if( array_key_exists( 'custom_taxonomy_fields' , $args ) ) {
            $this->taxonomy_fields = $args['custom_taxonomy_fields'];
        } else {
            $this->taxonomy_fields = array();
        }

        if( array_key_exists( 'exiting_post' , $args ) ) {
            $this->exiting_post         = $args['exiting_post'];
        } else {
            $this->exiting_post         = '';
        }

        if( array_key_exists( 'existing_tax_slug' , $args ) ) {
            $this->existing_tax_slug   = $args['existing_tax_slug'];
        } else {
            $this->existing_tax_slug   = '';
        }
    }

    /*
     * Publicly accessible hook execution method
     * call this method after initialize Unish_CPT_Builder class
     */
    public function execute() {      

        if( $this->taxonomoy_name != '' || $this->taxonomoy_name != null ) {

            $this->taxonomoy_name = $this->taxonomoy_name;
        }
		
		if( $this->existing_tax_slug   == '' ) {
			$post_tax_slug = $this->taxonomoy_name;
		} else {
			$post_tax_slug = $this->existing_tax_slug;
		}
		
		if( $this->exiting_post == '' ) {

			$post_slug = $this->post_name;
		} else {
			$post_slug = $this->exiting_post;
		}
        if( $this->post_name != '' || $this->post_name != null ) {

            if( $this->taxonomoy_name != null || $this->taxonomoy_name != '' ) {

                $custom_taxonomy = $this->create_custom_taxonomy( $this->taxonomoy_name, $post_tax_slug );

                register_taxonomy( $this->taxonomoy_name, array( $this->post_name ), $custom_taxonomy );
            }

            $custom_post = $this->create_custom_post( $this->post_name, $this->post_title, $post_slug );

            register_post_type( $this->post_name , $custom_post );

            add_filter( 'post_updated_messages', array(&$this, 'updated_messages') );
        } else {

            return false;
        }

    }

    /*
     *
     * Function : create_custom_post - this function will generate custom post
     * Param(string) : $post_name - custom post type name
     *
     */
    public function create_custom_post( $post_name, $post_title, $post_slug ) {

        $customLabels = array(
            'name'               => sprintf( _x( '%s', 'post type general name', 'text-domain' ), $post_title ),
            'singular_name'      => sprintf( _x( '%s', 'post type singular name', 'text-domain' ) , $post_title ),
            'menu_name'          => sprintf( _x( '%s', 'admin menu', 'text-domain' ) , $post_title ),
            'name_admin_bar'     => sprintf( _x( '%s', 'add new on admin bar', 'text-domain' ) , $post_title ),
            'add_new'            => sprintf( __( 'Add New', 'text-domain' ) , $post_title ),
            'add_new_item'       => sprintf( __( 'Add New', 'text-domain' ) , $post_title ),
            'new_item'           => sprintf( __( 'New %s', 'text-domain' ) , $post_title ),
            'edit_item'          => sprintf( __( 'Edit %s', 'text-domain' ) , $post_title ),
            'view_item'          => sprintf( __( 'View %s', 'text-domain' ) , $post_title ),
            'all_items'          => sprintf( __( 'All %s', 'text-domain' ) , $post_title ),
            'search_items'       => sprintf( __( 'Search %s', 'text-domain' ) , $post_title ),
            'parent_item_colon'  => sprintf( __( 'Parent %s:', 'text-domain' ) , $post_title ),
            'not_found'          => sprintf( __( 'No %s found.', 'text-domain' ) , $post_title ),
            'not_found_in_trash' => sprintf( __( 'No %s found in Trash.', 'text-domain' ) , $post_title )
        );

        $customArgs  = array(
            'labels'                => $customLabels,
            'description'           => '',
            'public'                => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'show_in_menu'          => true,
            'show_in_admin_bar'     => true,
            'menu_position'         => null,
            'menu_icon'             => 'dashicons-portfolio',
            'capability_type'       => 'post',
            'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' , 'custom-fields', 'page-attributes' ),
            'taxonomies'            => array( $this->taxonomoy_name ),
            'has_archive'           => true,
            'rewrite'               => array( 'slug' => $post_slug ),
            'query_var'             => true,
            'can_export'            => true,
            'hierarchical'          => true
        );

        $customArgs = array_replace( $customArgs , $this->custom_fields );

        if( array_key_exists( 'description' , $this->custom_fields ) ) {

            $customArgs['description'] = sprintf( __( '%s', 'text-domain' ) , $this->custom_fields['description'] );
        }

        if( $this->taxonomoy_name == null || $this->taxonomoy_name == '' ) {

            $customArgs['taxonomies'] = array();
        }

        return $customArgs;
    }

    /*
     *
     * function : create_custom_taxonomy - this function will generate custom taxonomy
     * Param(string) : $tax_name - custom taxonomy name
     *
     */
    public function create_custom_taxonomy( $tax_name, $post_tax_slug ) {

        $post_name = ucfirst( $this->post_title );

        $labels = array(
            'name'                          => sprintf( _x( '%s Category' , 'taxonomy general name', 'text-domain' ) , $post_name ),
            'singular_name'                 => sprintf( _x( '%s Category', 'taxonomy singular name', 'text-domain' ) , $post_name ),
            'menu_name'                     => sprintf( __( '%s Category', 'text-domain' ) , $post_name ),
            'all_items'                     => sprintf( __( 'All %s' , 'text-domain' ) , $post_name ),
            'edit_item'                     => sprintf( __( 'Edit %s Category', 'text-domain' ) , $post_name ),
            'view_item'                     => sprintf( __( 'View %s Category' , 'text-domain') , $post_name ),
            'update_item'                   => sprintf( __( 'Update %s Category', 'text-domain' ) , $post_name ),
            'add_new_item'                  => sprintf( __( 'Add New %s Category', 'text-domain' ) , $post_name ),
            'new_item_name'                 => sprintf( __( 'New %s Category Name', 'text-domain' ) , $post_name ),
            'parent_item'                   => sprintf( __( 'Parent %s Category', 'text-domain' ) , $post_name ),
            'parent_item_colon'             => sprintf( __( 'Parent %s Category:', 'text-domain' ) , $post_name ),
            'search_items'                  => sprintf( __( 'Search %s' , 'text-domain' ) , $post_name ),
            'separate_items_with_commas'    => sprintf( __( 'Separate %s\'s With Commas', 'text-domain' ) , $post_name ),
            'add_or_remove_items'           => sprintf( __( 'Add or Remove %s\'s', 'text-domain' ) , $post_name ),
            'choose_from_most_used'         => sprintf( __( 'Choose From the Most %s\'s', 'text-domain' ) , $post_name ),
            'not_found'                     => sprintf( __( 'No %s\'s Found.', 'text-domain' ) , $post_name )

        );

        $args = array(
            'labels'            => $labels,
            'description'       => '',
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'show_in_quick_edit'=> true,
            'show_admin_column' => true,
            'hierarchical'      => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => $post_tax_slug ),
        );

        $args = array_replace( $args , $this->taxonomy_fields );

        if( array_key_exists( 'description' , $this->taxonomy_fields ) ) {

            $args['description'] = sprintf( __( '%s' , 'text-domain' ) , $this->taxonomy_fields['description'] );
        }

        return $args;

    }

    /**
     * Custom Post update messages.
     *
     * See /wp-admin/edit-form-advanced.php
     *
     * @param array $messages Existing post update messages.
     *
     * @return array Amended post update messages with new CPT update messages.
     */
    public function updated_messages( $messages ) {
        $post               = get_post();
        $post_type          = get_post_type( $post );
        $post_type_object   = get_post_type_object( $post_type );

        $post_name          = ucfirst( $post_type );

        $messages[$post_type] = array(
            0  => '',
            1  => sprintf( __( '%s updated.', 'text-domain' ) , $post_name ),
            2  => sprintf( __( '%s updated.', 'text-domain' ) , $post_name ),
            3  => sprintf( __( '%s updated.', 'text-domain' ) , $post_name ),
            4  => sprintf( __( '%s updated.', 'text-domain' ) , $post_name ),
            5  => isset( $_GET['revision'] ) ? $post_name . sprintf( __( ' restored to revision from %s', 'text-domain' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( __( '%s published.', 'text-domain' ) , $post_name ),
            7  => sprintf( __( '%s saved.', 'text-domain' ) , $post_name ),
            8  => sprintf( __( '%s submitted.', 'text-domain' ) , $post_name ),
            9  => sprintf(
                sprintf(__( '%s scheduled for: <strong>%1$s</strong>.', 'text-domain' ) , $post_name ),
                date_i18n( __( 'M j, Y @ G:i', 'text-domain' ), strtotime( $post->post_date ) )
            ),
            10 => sprintf( __( '%s draft updated.', 'text-domain' ) , $post_name )
        );

        if ( $post_type_object->publicly_queryable ) {
            $permalink = get_permalink( $post->ID );

            $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), sprintf( __( 'View %s', 'text-domain' )  , $post_name ) );
            $messages[ $post_type ][1] .= $view_link;
            $messages[ $post_type ][6] .= $view_link;
            $messages[ $post_type ][9] .= $view_link;

            $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
            $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), sprintf(__( 'Preview %s', 'text-domain' ) , $post_name ) );
            $messages[ $post_type ][8]  .= $preview_link;
            $messages[ $post_type ][10] .= $preview_link;
        }
        return $messages;
    }
}
