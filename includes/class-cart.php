<?php
/**
 * Cart Template
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace WCPRAEF;

/**
 * Cart class
 *
 * @package MultiStoreX
 */
class Cart {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_class', array( $this, 'cart_item_class' ), 10, 1 );
	}

	/**
	 * Get Item Data
	 *
	 * @param array $item_data  item_data.
	 * @param array $cart_item  cart_item.
	 *
	 * @return $item_data array
	 */
	public function get_item_data( $item_data, $cart_item ) {
		$meta = new MetaDisplay();

		if ( isset( $cart_item['post_data'] ) ) {
			$entry_fields = $cart_item['post_data'];
			$form_id      = $cart_item['form_id'];
			if ( ! empty( $entry_fields ) ) {
				$item_data[] = array(
					'key'   => 'form_id',
					'value' => $form_id,
				);

				foreach ( $entry_fields as $key => $field ) {
					$item_data[] = array(
						'type'  => $field['template'],
						'name'  => $field['name'],
						'key'   => $field['name'],
						'value' => wc_clean( $meta->display( $field ) ),
					);
				}
			}
		}

		return $item_data;
	}

	/**
	 * Add cart item class
	 *
	 * @param string $item_class item_class.
	 *
	 * @return $class string
	 */
	public function cart_item_class( $item_class ) {
		return $item_class;
	}
}
