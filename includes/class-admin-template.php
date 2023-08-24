<?php
namespace Contactum;

/**
 * Admin Template class
 * 
 * @package MultiStoreX
 */

class Admin_Template {

    /**
     * construct
     */ 
	public function __construct() {
        add_action( 'admin_footer', [ $this, 'render_templates' ] );
        add_filter( 'admin_action_create_template', [ $this, 'create_template' ] );
	}

    /**
     * render footer template
     * 
     * @return void
     */ 
    public function render_templates() {
        $current_screen = get_current_screen();

        if ( !in_array( $current_screen->id, [ 'toplevel_page_contactum' ] ) ) {
            return true;
        }

        $templates      = contactum()->templates->get_templates();
        $blank_form_url = admin_url( 'admin.php?page=contactum&action=add-new' );
        $action_name    = 'create_template';

        include __DIR__ . '/html/modal.php';
    }

    /**
     * create_template
     * 
     * @return void
     */ 
	public function create_template() {
        $get_data = wp_unslash( $_GET );
        $template = isset( $get_data['template'] ) ? sanitize_text_field( $get_data['template'] ) : '';

        if( empty( $template ) ){
            return ;
        }

        $template_obj = contactum()->templates->get_template( $template );

        if( $template_obj == false ) {
            return ;
        }

        $form_id = contactum()->templates->create( $template );

        wp_redirect( admin_url( 'admin.php?page=contactum&action=edit&id='. $form_id ) );
        exit;
	}
}
