<?php
namespace Contactum;

class Admin_Form_Handler {

    public function __construct() {
        add_action( 'load-toplevel_page_contactum', [ $this, 'chi_forms_actions' ] );
        add_action( 'admin_notices', [ $this, 'admin_notices' ] );
    }

    public function verify_current_page_screen( $page_id, $bulk_action ) {
        if ( !isset( $_REQUEST['_wpnonce'] ) || !isset( $_REQUEST['page'] ) ) {
            return false;
        }

        if ( $_REQUEST['page'] != $page_id ) {
            return false;
        }

        $nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_key( $_REQUEST['_wpnonce'] ) : '';

        if ( isset( $nonce ) && !wp_verify_nonce( $nonce, $bulk_action ) ) {
            return false;
        }


        
        return true;
    }

    public function chi_forms_actions() {
        // Nonce validation
        if ( !$this->verify_current_page_screen( 'contactum', 'bulk-contactum-forms' ) ) {
            return;
        }

        $request_data =  wp_unslash( $_REQUEST );
        $ids = isset( $_REQUEST['id'] ) ? wp_parse_id_list( wp_unslash( $_REQUEST['id'] ) ) : array();

        $chi_forms = new Forms_List_Table();
        $action        = $chi_forms->current_action();

        if ( $action ) {

            $remove_query_args = ['_wp_http_referer', '_wpnonce', 'action', 'id', 'post', 'action2','paged', 'doaction' ];
            $add_query_args    = [];

            switch ( $action ) {

                case 'contactum_form_search':
                    $redirect = remove_query_arg( [ 'contactum_form_search' ], $remove_query_args );
                    break;

                case 'delete':
                    foreach ( $ids as $id ) {
                        wp_delete_post( $id  );
                    }
                    $add_query_args['deleted'] = count( $ids );
                    break;

                case 'duplicate':
                    if ( !empty( $_GET['id'] ) ) {
                        $id = intval( $_GET['id'] );
                        $add_query_args['duplicated'] = contactum()->forms->duplicate( $id );
                    }
                    break;
            }

            if ( ( isset( $request_data['action'] ) && $request_data['action'] == 'bulk-delete' ) || ( isset( $request_data['action2'] ) && $request_data['action2'] == 'bulk-delete' ) ) {

                $ds = esc_sql( $request_data['id'] );

                foreach ( $ids as $id ) {
                    wp_delete_post( $id  );
                }
            }

            $request_uri = isset( $_SERVER['REQUEST_URI'] )  ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
            $redirect    = remove_query_arg( $remove_query_args, $request_uri );
            $redirect    = add_query_arg( $add_query_args, $redirect );

            // wp_redirect(esc_url( $redirect ) );
            wp_redirect( $redirect );
            exit;
        }
    }

    public function admin_notices() {
        if ( !empty( $_GET['duplicated'] ) ) {
            $duplicated = sanitize_text_field( wp_unslash( $_GET['duplicated'] ) );
            $notice   = sprintf( __( 'Form duplicated successfully', 'contactum') );
            $this->display_notice( $notice );
        }
    }

    public function display_notice( $text, $type = 'updated' ) {
        printf( '<div class="%s"><p>%s</p></div>', esc_attr( $type ), wp_kses_post( $text ) );
    }
}
