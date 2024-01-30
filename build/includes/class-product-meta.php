<?php
/**
 * Product Meta
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

/**
 * ProductMeta class
 *
 * @package Product_Addon_Custom_Field
 */
class ProductMeta {
	/**
	 * Construct
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_my_custom_product_data_tab' ), 101, 1 );
		add_action( 'woocommerce_product_data_panels', array( $this, 'add_my_custom_product_data_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'process_product_meta_fields_save' ) );
	}

	/**
	 *  Add custom tabs
	 *
	 * @param array $tabs tabs.
	 *
	 * @return  array
	 */
	public function add_my_custom_product_data_tab( $tabs ) {
		$tabs['wcpa_product-meta-tab'] = array(
			'label'    => __( 'Product Addons', 'product-addon-custom-field' ),
			'target'   => 'my_custom_product_data',
			'priority' => 90,
		);

		return $tabs;
	}

	/**
	 * Add custom field on tabs
	 *
	 * @return void
	 */
	public function add_my_custom_product_data_fields() {
		global $woocommerce, $post;
        $entries_forms = prafe_entries_forms();
		$options = array();

		foreach ( $entries_forms as $id => $form ) {
			$options[ $id ] = $form->name;
		}

		$custom_form = get_post_meta( $post->ID, 'custom_form', true );

		echo wp_kses_post( '<div id="my_custom_product_data" class="panel">' ); 

		woocommerce_wp_radio(
			array(
				'id'      => 'custom_form',
				'label'   => __( 'Custom Form', 'product-addon-custom-field' ),
				'options' => $options,
			)
		);

		wp_nonce_field( 'prafe-meta-nonce', 'prafe_meta_nonce' );

		echo wp_kses_post( '</div>' ); 
	}

	/**
	 * Product meta fields save
	 *
	 * @param int $post_id post_id.
	 *
	 * @return void
	 */
	public function process_product_meta_fields_save( $post_id ) {
		if ( isset( $_POST['prafe_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prafe_meta_nonce'] ) ), 'prafe-meta-nonce') ) {
		
			$custom_form = isset( $_POST['custom_form'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_form'] ) ) : '';

			update_post_meta( $post_id, 'custom_form', $custom_form );
		}
	}
}
