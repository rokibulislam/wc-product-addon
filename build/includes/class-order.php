<?php
/**
 * Order
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

/**
 * Order class
 *
 * @package Product_Addon_Custom_Field
 */
class Order {

	/**
	 * Construct
	 */
	public function __construct() {

		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'checkout_create_order_line_item' ), 10, 4 );
	}

	/**
	 * Add order line item
	 *
	 * @param object $item item.
	 * @param string $cart_item_key cart_item_key.
	 * @param array  $values values.
	 * @param object $order order.
	 *
	 * @return void
	 */
	public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

		if ( isset( $values['post_data'] ) ) {
			foreach ( $values['post_data'] as $key => $field ) {
				$item->add_meta_data(
					$key,
					$field,
					true
				);
			}
		}
	}
}
