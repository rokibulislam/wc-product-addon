<?php
/**
 * Order
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace Contactum;

/**
 * Order class
 *
 * @package MultiStoreX
 */
class Order {

	/**
	 * Construct
	 */
	public function __construct() {

		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'checkout_create_order_line_item' ), 10, 4 );

		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'checkout_order_processed' ), 1, 1 );

		add_filter( 'woocommerce_order_item_display_meta_value', array( $this, 'display_meta_value' ), 10, 3 );

		add_action( 'woocommerce_after_order_itemmeta', array( $this, 'order_item_line_item_html' ), 10, 3 );

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
	 * Add order line item
	 *
	 * @param int $order_id order_id.
	 *
	 * @return void
	 */
	public function checkout_order_processed( $order_id ) {

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
	 * Add order line item html
	 *
	 * @param int    $item_id item_id.
	 * @param object $item item.
	 * @param object $product product.
	 *
	 * @return void
	 */
	public function order_item_line_item_html( $item_id, $item, $product ) {

	}

	/**
	 * Add order line item
	 *
	 * @param array  $formatted_meta formatted_meta.
	 * @param object $item item.
	 *
	 * @return void
	 */
	public function order_item_get_formatted_meta_data( $formatted_meta, $item ) {

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
