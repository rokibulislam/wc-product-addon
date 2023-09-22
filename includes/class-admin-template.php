<?php
/**
 * Admin Template
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace WCPRAEF;

/**
 * Admin Template class
 *
 * @package MultiStoreX
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

		if ( ! in_array( $current_screen->id, array( 'toplevel_page_contactum' ) ) ) {
			return true;
		}

		$templates      = contactum()->templates->get_templates();
		$blank_form_url = admin_url( 'admin.php?page=contactum&action=add-new' );
		$action_name    = 'create_template';

		include __DIR__ . '/html/modal.php';
	}

	/**
	 * Create Template
	 *
	 * @return void | string
	 */
	public function create_template() {
		$get_data = wp_unslash( $_GET );
		$template = isset( $get_data['template'] ) ? sanitize_text_field( $get_data['template'] ) : '';

		if ( empty( $template ) ) {
			return;
		}

		$template_obj = contactum()->templates->get_template( $template );

		if ( false === $template_obj ) {
			return;
		}

		$form_id = contactum()->templates->create( $template );

		wp_redirect( admin_url( 'admin.php?page=contactum&action=edit&id='. $form_id ) );
		exit;
	}
}
