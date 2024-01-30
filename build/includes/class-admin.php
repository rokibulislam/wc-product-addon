<?php
/**
 * Admin Template
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

use PRAEF\Forms_List_Table;

/**
 * Admin class
 *
 * @package Product_Addon_Custom_Field
 */
class Admin {

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'parent_file', array( $this, 'fix_parent_menu' ) );
	}

	/**
	 * Register form post types
	 *
	 * @param string $parent_file parent_file.
	 *
	 * @return  string
	 */
	public function fix_parent_menu( $parent_file ) {
		$current_screen = get_current_screen();
		$post_types     = array( 'chi_forms' );

		if ( in_array( $current_screen->post_type, $post_types ) ) {
			$parent_file = 'product-addon-custom-field';
		}

		return $parent_file;
	}

	/**
	 * Register form post types
	 *
	 * @return void
	 */
	public function register_post_type() {
		$capability = 'manage_options';

		register_post_type(
			'chi_forms',
			array(
				'label'           => __( 'Forms', 'product-addon-custom-field' ),
				'public'          => false,
				'show_ui'         => false,
				'show_in_menu'    => false,
				'capability_type' => 'post',
				'hierarchical'    => false,
				'query_var'       => false,
				'supports'        => array( 'title' ),
				'capabilities'    => array(
					'publish_posts'       => $capability,
					'edit_posts'          => $capability,
					'edit_others_posts'   => $capability,
					'delete_posts'        => $capability,
					'delete_others_posts' => $capability,
					'read_private_posts'  => $capability,
					'edit_post'           => $capability,
					'delete_post'         => $capability,
					'read_post'           => $capability,
				),
				'labels'          => array(
					'name'               => __( 'Forms', 'product-addon-custom-field' ),
					'singular_name'      => __( 'Form', 'product-addon-custom-field' ),
					'menu_name'          => __( 'Forms', 'product-addon-custom-field' ),
					'add_new'            => __( 'Add Form', 'product-addon-custom-field' ),
					'add_new_item'       => __( 'Add New Form', 'product-addon-custom-field' ),
					'edit'               => __( 'Edit', 'product-addon-custom-field' ),
					'edit_item'          => __( 'Edit Form', 'product-addon-custom-field' ),
					'new_item'           => __( 'New Form', 'product-addon-custom-field' ),
					'view'               => __( 'View Form', 'product-addon-custom-field' ),
					'view_item'          => __( 'View Form', 'product-addon-custom-field' ),
					'search_items'       => __( 'Search Form', 'product-addon-custom-field' ),
					'not_found'          => __( 'No Form Found', 'product-addon-custom-field' ),
					'not_found_in_trash' => __( 'No Form Found in Trash', 'product-addon-custom-field' ),
					'parent'             => __( 'Parent Form', 'product-addon-custom-field' ),
				),
			)
		);

		register_post_type(
			'chi_input',
			array(
				'public'       => false,
				'show_ui'      => false,
				'show_in_menu' => false,
			)
		);
	}

	/**
	 * Add admin menu
	 *
	 * @return void
	 */
	public function admin_menu() {
		global $submenu;

		$capability = 'manage_options';
		$slug       = 'product-addon-custom-field';

		$nonce_url = wp_nonce_url(admin_url('admin.php?page=product-addon-custom-field'), 'product_addon_custom_field_nonce');

		$hook = add_menu_page( __( 'Product Addon', 'product-addon-custom-field' ), __( 'Product Addon', 'product-addon-custom-field' ), $capability, $slug, array( $this, 'forms_page' ), 'dashicons-text' );
		add_submenu_page( $slug, __( 'Forms', 'product-addon-custom-field' ), __( 'Forms', 'product-addon-custom-field' ), $capability, 'product-addon-custom-field', array( $this, 'forms_page' ) );
	}

	/**
	 * Load forms page
	 *
	 * @return void
	 */
	public function forms_page() {

		$add_new_page_url =	esc_url(
			wp_nonce_url(
				add_query_arg(
					array(
						'action' => 'add-new',
					),
					admin_url( 'admin.php?page=product-addon-custom-field' )
				),
				'prafe-page',
				'prafe_page_nonce'
			)
		);

		if ( isset( $_GET['prafe_page_nonce'] ) && 
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['prafe_page_nonce'] ) ) , 'prafe-page' ) 
		) {
			$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : null;
			
			switch ( $action ) {
				case 'edit':
					require_once PRAEF_INCLUDES . '/html/form.php';
					break;
				case 'add-new':
					require_once PRAEF_INCLUDES . '/html/form.php';
					break;
				default:
					require_once PRAEF_INCLUDES . '/html/form-list-view.php';
					break;
			}
		} else {
			require_once PRAEF_INCLUDES . '/html/form-list-view.php';
		}
	}
}
