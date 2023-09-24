<?php
/**
 * Order
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */

namespace WCPRAEF;

/**
 * Order class
 *
 * @package WC_Product_Addon_Extra_Field
 */
class Order {

	/**
	 * Construct
	 */
	public function __construct() {

		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'checkout_create_order_line_item' ), 10, 4 );

		add_filter( 'woocommerce_order_item_display_meta_value', array( $this, 'display_meta_value' ), 10, 3 );

		add_filter( 'woocommerce_display_item_meta', array( $this, 'display_item_meta' ), 10, 3 );
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

		$meta = new MetaDisplay();

		if ( isset( $values['post_data'] ) ) {
			foreach ( $values['post_data'] as $key => $field ) {
				$item->add_meta_data(
					$field['name'],
					$meta->display( $field ),
					true
				);
			}
		}
	}

	/**
	 * Display Meta value
	 *
	 * @param string $display_value display_value.
	 * @param string $meta meta.
	 * @param object $item item.
	 *
	 * @return void|string
	 */
	public function display_meta_value( $display_value, $meta = null, $item = null ) {

		return $display_value;
	}

	/**
	 * Add order item meta
	 *
	 * @param string $html html.
	 * @param object $item item.
	 * @param array  $args args.
	 *
	 * @return void
	 */
	public function display_item_meta( $html, $item, $args ) {

		return $html;
	}
}
