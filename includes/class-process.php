<?php
/**
 * Process
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */

namespace WCPRAEF;

/**
 * Process class
 *
 * @package WC_Product_Addon_Extra_Field
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
		$post_data = wp_unslash( $_POST );

		if ( isset( $post_data['form_id'] ) ) {
			$id = $post_data['form_id'];

			$form         = wc_product_addon_extra_field()->forms->get( $id );
			$fields       = $form->getFields();
			$entry_fields = $form->prepare_entries( $post_data );

			foreach ( $entry_fields as $key => $value ) {
				$cart_item_data[ $key ] = sanitize_text_field( $value );
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

		$post_data = wp_unslash( $_POST );

		if ( isset( $post_data['form_id'] ) ) {

			$id = $post_data['form_id'];

			$form = wc_product_addon_extra_field()->forms->get( $id );

			$fields = $form->getFields();

			foreach ( $fields as $field ) {
				if ( 'yes' === $field['required'] ) {
					if ( empty( $post_data[ $field['name'] ] ) ) {
						$passed = false;
						wc_add_notice( __( 'Please fill in the required ' . $field['name'] . ' field.', 'product-addon-custom-field' ), 'error' );
					}
				}
			}
		}

		return $passed;
	}
}
