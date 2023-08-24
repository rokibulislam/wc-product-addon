<?php 

namespace Contactum;

/**
 * Order class
 * 
 * @package MultiStoreX
 */

class Order {

    /**
     * construct
     */ 
    public function __construct() {
        
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'checkout_create_order_line_item' ], 10, 4 );
        
        add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'checkout_order_processed' ], 1, 1 );
        
        add_filter('woocommerce_order_item_display_meta_value', [ $this, 'display_meta_value' ], 10, 3);
    
        add_action('woocommerce_after_order_itemmeta', [ $this, 'order_item_line_item_html' ], 10, 3);
        
        // add_action('woocommerce_order_item_get_formatted_meta_data', [ $this, 'order_item_get_formatted_meta_data' ], 10, 2);
    
        add_filter( 'woocommerce_display_item_meta', [ $this, 'display_item_meta' ], 10, 3);;
    }

    /**
     * add order line item
     * 
     * @param $item object
     * @param $cart_item_key string
     * @param $values array
     * @param $order object
     * 
     * @return void
     */ 
    public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
        
        $meta = new MetaDisplay();

        if( isset( $values['post_data'] )  ) {
            foreach ( $values['post_data'] as $key => $field ) {
                $item->add_meta_data(   
                    __( $field['name'], 'plugin-republic' ),
                    $meta->display($field),
                    true
                );
            }
        }
    }

    /**
     * add order line item
     * 
     * @param $order_id int
     * 
     * @return void
     */ 
    public function checkout_order_processed( $order_id ) {

    }
        
    /**
     * Display Meta value
     * 
     * @param $display_value string
     * @param $meta string
     * @param $item object
     * 
     * @return void
     */ 
    public function display_meta_value($display_value, $meta = null, $item = null) {

        return $display_value;
    }

    /**
     * add order line item html
     * 
     * @param $item_id int
     * @param $item object
     * @param $product object
     * 
     * @return void
     */ 
    public function order_item_line_item_html($item_id, $item, $product) {

    }

    /**
     * add order line item
     * 
     * @param $formatted_meta array
     * @param $item object
     * 
     * @return void
     */ 
    public function order_item_get_formatted_meta_data($formatted_meta, $item) {

    }

    /**
     * add order item meta
     * 
     * @param $html string
     * @param $item object
     * @param $args array
     * 
     * @return void
     */ 
    public function display_item_meta($html, $item, $args) {

        return $html;
    }
}