<?php
/**
 * Cart Template
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

/**
 * Cart class
 *
 * @package Product_Addon_Custom_Field
 */
class Cart {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );
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
						'key'   => $key,
						'value' => $field,
					);
				}
			}
		}

		return $item_data;
	}
}
