<?php
/**
 * Admin Form Handler
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace Contactum;

/**
 * Admin class
 *
 * @package MultiStoreX
 */
class Admin_Form_Handler {

	/**
	 *  Constructor
	 */
	public function __construct() {
		add_action( 'load-toplevel_page_contactum', array( $this, 'chi_forms_actions' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 *  Verify current page
	 *
	 * @param string $page_id page_id.
	 * @param string $bulk_action bulk_action.
	 *
	 * @return boolean
	 */
	public function verify_current_page_screen( $page_id, $bulk_action ) {
		$request_data = wp_unslash( $_REQUEST );

		if ( ! isset( $request_data['_wpnonce'] ) || ! isset( $request_data['page'] ) ) {
			return false;
		}

		if ( $request_data['page'] !== $page_id ) {
			return false;
		}

		$nonce = isset( $request_data['_wpnonce'] ) ? sanitize_key( $request_data['_wpnonce'] ) : '';

		if ( isset( $nonce ) && ! wp_verify_nonce( $nonce, $bulk_action ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Forms action
	 *
	 * @return void
	 */
	public function chi_forms_actions() {
		// Nonce validation.
		if ( ! $this->verify_current_page_screen( 'contactum', 'bulk-contactum-forms' ) ) {
			return;
		}

		$request_data = wp_unslash( $_REQUEST );
		$get_data     = wp_unslash( $_GET );

		$ids = isset( $request_data['id'] ) ? wp_parse_id_list( wp_unslash( $request_data['id'] ) ) : array();

		$chi_forms = new Forms_List_Table();
		$action    = $chi_forms->current_action();

		if ( $action ) {
			$remove_query_args = [ '_wp_http_referer', '_wpnonce', 'action', 'id', 'post', 'action2', 'paged', 'doaction' ];
			$add_query_args    = array();

			switch ( $action ) {
				case 'contactum_form_search':
					$redirect = remove_query_arg( [ 'contactum_form_search' ], $remove_query_args );
					break;

				case 'delete':
					foreach ( $ids as $id ) {
						wp_delete_post( $id );
					}
					$add_query_args['deleted'] = count( $ids );
					break;

				case 'duplicate':
					if ( ! empty( $get_data['id'] ) ) {
						$id = intval( $get_data['id'] );
						$add_query_args['duplicated'] = contactum()->forms->duplicate( $id );
					}
					break;
			}

			if ( ( isset( $request_data['action'] ) && $request_data['action'] === 'bulk-delete' ) || ( isset( $request_data['action2'] ) && $request_data['action2'] === 'bulk-delete' ) ) {
				$ids = esc_sql( $request_data['id'] );

				foreach ( $ids as $id ) {
					wp_delete_post( $id );
				}
			}

			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$redirect    = remove_query_arg( $remove_query_args, $request_uri );
			$redirect    = add_query_arg( $add_query_args, $redirect );

			wp_redirect( esc_url( $redirect ) );
			exit;
		}
	}

	/**
	 *  Admin notice
	 *
	 * @return void
	 */
	public function admin_notices() {
		$get_data = wp_unslash( $_GET );

		if ( ! empty( $get_data['duplicated'] ) ) {
			$duplicated = sanitize_text_field( $get_data['duplicated'] );
			$notice = sprintf( __( 'Form duplicated successfully', 'contactum' ) );
			$this->display_notice( $notice );
		}
	}

	/**
	 *  Display notice
	 *
	 * @param string $text text.
	 * @param string $type type.
	 *
	 * @return void
	 */
	public function display_notice( $text, $type = 'updated' ) {
		printf( '<div class="%s"><p>%s</p></div>', esc_attr( $type ), wp_kses_post( $text ) );
	}
}
