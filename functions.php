<?php

if(! function_exists('load_custom_post')) {
    function load_custom_post() {

        require_once( 'unish_cpt.php' );

        /*
         * custom post : album
         */
        $album_args = array(
            'custom_post_name'          => 'album',
        	'post_title'				=> 'Album',
            'taxonomy_name'             => 'album-category',
            'custom_post_fields'        => array(
                'supports'              	=> array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
                'menu_icon'          		=> 'dashicons-album'
            ),
            'custom_taxonomy_fields'    => array(),
        	'exiting_post'				=> '',
        	'existing_tax_slug'			=> '',
        );
        $album = new Unish_CPT_Builder( $album_args );
        $album->execute();

        /*
         * custom post : portfolio
         */
        $portfolio_args = array(
            'custom_post_name'          => 'portfolio',
            'post_title'                => 'Portfolio',
            'taxonomy_name'             => 'portfolio-category',
            'custom_post_fields'        => array(
                'supports'                  => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
                'menu_icon'                 => 'dashicons-portfolio'
            ),
            'custom_taxonomy_fields'    => array(),
            'exiting_post'              => '',
            'existing_tax_slug'         => '',
        );
        $portfolio = new Unish_CPT_Builder( $portfolio_args );
        $portfolio->execute();

        flush_rewrite_rules();
    }
}
add_action( 'after_setup_theme' , 'load_custom_post' );
