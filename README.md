# unish_cpt_builder
Simple method to load custom post type in wordpress

Add unish_cpt.php in your theme or plugin bundle.

Inside your functions.php file where you call "after_setup_theme" or "init" hook, require unish_cpt.php file and call "Unish_CPT_Builder" class as shown bellow.
    
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

Add as many custom posts you need.
