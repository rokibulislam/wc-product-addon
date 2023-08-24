<?php 

namespace Contactum;

/**
 * Process class
 * 
 * @package MultiStoreX
 */

class Process {

    public function __construct() {
        add_filter('woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 10, 4);
        add_filter('woocommerce_add_to_cart_validation', [ $this, 'add_to_cart_validation' ], 10, 4);
    }

    /**
     * add order line item
     * 
     * @param $cart_item_data array
     * @param $product_id int
     * @param $variation_id boolean
     * @param $quantity int
     * 
     * @return void
     */ 
    public function add_cart_item_data( $cart_item_data, $product_id, $variation_id = false, $quantity = 1 ) {
        
        $post_data = wp_unslash( $_POST );

        if( isset( $post_data['form_id'] ) ) {  
            
            $id =  $post_data['form_id'];

            $form = contactum()->forms->get( $id );
            $fields = $form->getFields();
            $entry_fields  = $form->prepare_entries( $post_data );

            foreach ( $entry_fields as $key => $value ) {
                $cart_item_data[$key] = sanitize_text_field( $value );
            }

            $cart_item_data['form_id']   = $id;
            $cart_item_data['post_data'] = $entry_fields;
        }

        return $cart_item_data;
    }

    /**
     * add to cart validation
     * 
     * @param $passed boolean
     * @param $product_id int
     * @param $qty int
     * @param $variation_id boolean
     * 
     * @return void
     */ 
    public function add_to_cart_validation( $passed, $product_id, $qty = 1, $variation_id = false ) {

        $post_data = wp_unslash( $_POST );

        if( isset( $post_data['form_id'] ) ) {  
            
            $id =  $post_data['form_id'];

            $form = contactum()->forms->get( $id );

            $fields = $form->getFields();

            foreach( $fields as $field )  {
                if( $field['required'] == 'yes' ) {
                    if( empty( $post_data[$field['name']] ) ) {
                        $passed = false;
                        wc_add_notice( __( 'Please fill in the required '. $field['name'] .' field.', 'your-text-domain' ), 'error' );
                    }
                }
            }
        }
        
        return $passed;
    }
}