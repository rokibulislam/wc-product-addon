<?php
/**
 * Ajax Template
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

/**
 * Ajax class
 *
 * @package Product_Addon_Custom_Field
 */
class Ajax {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_save_prafe_form', array( $this, 'save_form' ) );
	}

	/**
	 * Save Form
	 *
	 * @return void
	 */
	public function save_form() {
		
		if ( ! isset( $_POST['radiantregistration_form_builder_nonce'] ) || ! wp_verify_nonce( $_POST['radiantregistration_form_builder_nonce'], 'prafe-form-builder-nonce' ) ) {
			wp_send_json_error( __( 'Unauthorized operation', 'product-addon-custom-field' ) );
		}

		if ( isset( $_POST['form_data'] ) ) {
			parse_str( sanitize_text_field( wp_unslash( $_POST['form_data'] ) ), $form_data );
		}

		if ( empty( $form_data['form_id'] ) ) {
			wp_send_json_error( __( 'Invalid form id', 'product-addon-custom-field' ) );
		}


		$form_fields = isset( $_POST['form_fields'] ) ? sanitize_text_field( wp_unslash( $_POST['form_fields'] ) ) : '';

		$form_fields = json_decode( $form_fields, true );

		if ( isset( $_POST['settings'] ) ) {
			$form_settings = (array) json_decode( sanitize_text_field( wp_unslash( $_POST['settings'] ) ) );
		}

		$data = array(
			'form_id'       => absint( $form_data['form_id'] ),
			'post_title'    => $form_data['post_title'],
			'form_fields'   => $form_fields,
			'form_settings' => $form_settings,
		);

		$form_fields = product_addon_extra_field()->forms->save( $data );
        
        wp_send_json_success(
            array(
                'form_fields'   => $form_fields,
                'form_settings' => $form_settings,
            )
        );
    }
}