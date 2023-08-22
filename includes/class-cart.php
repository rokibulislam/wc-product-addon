<?php 

namespace Contactum;

class Cart {

    public function __construct () {
        add_filter('woocommerce_get_item_data', [ $this, 'get_item_data' ], 10, 2);
        add_filter('woocommerce_cart_item_class', [ $this, 'cart_item_class' ], 10, 3);
    }

    public function get_item_data( $item_data, $cart_item ) {

        if( isset( $cart_item['post_data'] ) ) {
            $entry_fields = $cart_item['post_data'];
            $form_id = $cart_item['form_id'];

            if( !empty( $entry_fields ) ) {
                
                $item_data[] = [
                    'key'   => 'form_id',
                    'value' => $form_id
                ];

                foreach ( $entry_fields as $key => $value ) {
                    $item_data[] = array(
                        'key' => __( $key, 'plugin-republic' ),
                        'value' => wc_clean( $value )
                    );
                }
            }
        }



        // if( isset( $cart_item['form_id'] ) ) {
            
        //     if( isset( $cart_item['text'] ) ) {
                
        //         $item_data[] = array(
        //             'key' => __( 'Text', 'plugin-republic' ),
        //             'value' => wc_clean( $cart_item['text'] )
        //         );
        //     }
        // }

        return $item_data;
    }

    public function cart_item_class($class, $cart_item) {
        
        return $class;
    }
}