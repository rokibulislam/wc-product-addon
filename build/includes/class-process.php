<?php
/**
 * Process
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

/**
 * Process class
 *
 * @package Product_Addon_Custom_Field
 */
class Process {
	/**
	 * Construct
	 */
	public function __construct() {
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 4 );
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 10, 4 );
	}

	/**
	 * Add order line item
	 *
	 * @param array   $cart_item_data  cart_item_data.
	 * @param int     $product_id      product_id.
	 * @param boolean $variation_id    variation_id.
	 * @param int     $quantity        quantity.
	 *
	 * @return void|array
	 */
	public function add_cart_item_data( $cart_item_data, $product_id, $variation_id = false, $quantity = 1 ) {
		
		if ( !wp_verify_nonce( wp_unslash( sanitize_text_field( wp_unslash( $_REQUEST['prafe_frontend_nonce'] ) ) ), 'prafe_frontend_action' ) ) { 
			die('error');
		}
		
		if ( isset( $_POST['form_id'] ) ) {
			$id = sanitize_text_field( wp_unslash( $_POST['form_id'] ) );

			$form         = product_addon_extra_field()->forms->get( $id );
			$fields       = $form->getFields();
			// $entry_fields = $form->prepare_entries( $_POST );
			$entry_fields = [];

			foreach ( $fields as $field ) {

				switch ( $field['template'] ) {
					case 'text':
					case 'email':
					case 'number':
					case 'date':
						$entry_fields[$field['name']] = sanitize_text_field( trim( $_POST[$field['name']] ) );
						break;
					case 'textarea':
						$entry_fields[$field['name']] = wp_kses_post( $_POST[ $field['name'] ] );
						break;

					default:
						$value = ! empty( $_POST[ $field['name'] ] ) ? $_POST[ $field['name'] ] : '';

						if ( is_array( $value ) ) {
							$entry_fields[$field['name']] = implode( PRAEF_SEPARATOR, $value );
						} else {
							$entry_fields[$field['name']] = trim( $value );
						}
				}
			}

			if( !empty( $entry_fields ) ) {
				foreach ( $entry_fields as $key => $value ) {
					$cart_item_data[ $key ] = sanitize_text_field( $value );
				}
			}

			$cart_item_data['form_id']   = $id;
			$cart_item_data['post_data'] = $entry_fields;
		}

		return $cart_item_data;
	}

	/**
	 * Add to cart validation
	 *
	 * @param boolean $passed       passed.
	 * @param int     $product_id   product_id.
	 * @param int     $qty          qty.
	 * @param boolean $variation_id variation_id.
	 *
	 * @return void|boolean
	 */
	public function add_to_cart_validation( $passed, $product_id, $qty = 1, $variation_id = false ) {

		if ( !isset( $_REQUEST['prafe_frontend_nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['prafe_frontend_nonce'] ) ), 'prafe_frontend_action' ) ) { 
			die('error');
		}

		if ( isset( $_POST['form_id'] ) ) {

			$id = sanitize_text_field( wp_unslash( $_POST['form_id'] ) );

			$form = product_addon_extra_field()->forms->get( $id );

			$fields = $form->getFields();

			foreach ( $fields as $field ) {
				if ( 'yes' === $field['required'] ) {
					if ( empty( $_POST[ $field['name'] ] ) ) {
						$passed = false;
						wc_add_notice( 
							sprintf(
								esc_html__(
									'Please fill in the required %s field.',
									'product-addon-custom-field'
								),
								$field['name']
							),
							'error'
						);
					}
				}
			}
		}

		return $passed;
	}
}
