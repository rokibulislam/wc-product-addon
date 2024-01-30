<?php
/**
 * Admin Template
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

/**
 * Admin Template class
 *
 * @package Product_Addon_Custom_Field
 */
class Admin_Template {

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'render_templates' ) );
		add_filter( 'admin_action_create_template', array( $this, 'create_template' ) );
	}

	/**
	 * Render footer template
	 *
	 * @return void | boolean
	 */
	public function render_templates() {
		$current_screen = get_current_screen();
		
		if ( ! in_array( $current_screen->id, array( 'toplevel_page_product-addon-custom-field' ) ) ) {
			return true;
		}

		$templates      = product_addon_extra_field()->templates->get_templates();
		$blank_form_url = admin_url( 'admin.php?page=product-addon-custom-field&action=add-new' );
		$action_name    = 'create_template';

		include __DIR__ . '/html/modal.php';
	}

	/**
	 * Create Template
	 *
	 * @return void | string
	 */
	public function create_template() {
		
		if ( !wp_verify_nonce( wp_unslash( sanitize_text_field( wp_unslash( $_REQUEST['prafe_nonce'] ) ) ), 'prafe_create_nonce' ) ) { 
			die('error');
		}

		$template = ( isset( $_GET['template'] ) && wp_unslash( $_GET['template'] ) !== null ) ? sanitize_text_field( wp_unslash( $_GET['template'] ) ) : '';

		if ( empty( $template ) ) {
			return;
		}

		$template_obj = product_addon_extra_field()->templates->get_template( $template );

		if ( false === $template_obj ) {
			return;
		}

		$form_id = product_addon_extra_field()->templates->create( $template );

		wp_redirect( admin_url( 'admin.php?page=product-addon-custom-field&action=edit&id='. $form_id . '&prafe_page_nonce=' . wp_create_nonce('prafe-page') ) );

		exit;
	}
}
