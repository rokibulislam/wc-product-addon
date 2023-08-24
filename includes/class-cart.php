<?php 

namespace Contactum;

/**
 * Cart class
 * 
 * @package MultiStoreX
 */ 

class Cart {

    /**
     * constructor
     */ 
    public function __construct () {
        add_filter('woocommerce_get_item_data', [ $this, 'get_item_data' ], 10, 2);
        add_filter('woocommerce_cart_item_class', [ $this, 'cart_item_class' ], 10, 3);
    }

    /**
     * 
     * 
     * @param $item_data array
     * @param $cart_item array
     * 
     * @return $item_data array
     */ 
    public function get_item_data( $item_data, $cart_item ) {

        $meta = new MetaDisplay();

        if( isset( $cart_item['post_data'] ) ) {
            $entry_fields = $cart_item['post_data'];
            $form_id = $cart_item['form_id'];

            if( !empty( $entry_fields ) ) {
                
                $item_data[] = [
                    'key'   => 'form_id',
                    'value' => $form_id
                ];

                foreach ( $entry_fields as $key => $field ) {
                    $item_data[] = array(
                        'type'  => $field['template'],
                        'name' =>  $field['name'],
                        'key'   => __( $field['name'], 'plugin-republic' ),
                        'value' => $meta->display($field)
                    );
                }
            }
        }

        return $item_data;
    }

    /**
     * add cart item class
     * 
     * @param $class
     * @param $cart_item
     * 
     * @return $class string
     */ 
    public function cart_item_class($class, $cart_item) {
        
        return $class;
    }
}