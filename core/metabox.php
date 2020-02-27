<?php

class PP_Maps_Metabox {

	function __construct() {

		add_action( 'load-post.php', array( &$this, 'add_metabox' ), 9 );
		add_action( 'load-post-new.php', array( &$this, 'add_metabox' ), 9 );

	}

	/***
	 ***    @Init the metaboxes
	 */
	function add_metabox() {
		global $current_screen;

		if ( $current_screen->id == 'um_directory' ) {
			add_action( 'add_meta_boxes', array( &$this, 'add_metabox_form' ), 1 );
			//add_action('save_post', array(&$this, 'save_metabox_form'), 10, 2);
		}

	}

	/***
	 ***    @add form metabox
	 */
	function add_metabox_form() {

		add_meta_box(
			'um-admin-directory-maps',
			__( 'Map Options', 'pp-maps' ),
			array( &$this, 'load_metabox_form' ),
			'um_directory',
			'normal',
			'default'
		);

	}

	/***
	 ***    @load a form metabox
	 */
	function load_metabox_form( $object, $box ) {
		$box['id'] = str_replace( 'um-admin-form-', '', $box['id'] );
		include_once UM_MAPS_PLUGIN_DIR . 'includes/admin/templates/directory.php';
		wp_nonce_field( basename( __FILE__ ), 'um_admin_metabox_maps_form_nonce' );
	}

	/***
	 ***    @save form metabox
	 */
	function save_metabox_form( $post_id, $post ) {
		global $wpdb;

		// validate nonce
		if ( ! isset( $_POST['um_admin_save_metabox_directory_nonce'] ) || ! wp_verify_nonce( $_POST['um_admin_save_metabox_directory_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		// validate post type
		if ( $post->post_type != 'um_directory' ) {
			return $post_id;
		}

		// validate user
		$post_type = get_post_type_object( $post->post_type );
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		foreach ( $_POST['um_metadata'] as $k => $v ) {
			if ( strstr( $k, '_um_' ) ) {
				update_post_meta( $post_id, $k, $v );
			}
		}
	}
}