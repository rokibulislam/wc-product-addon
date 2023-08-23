<?php 

namespace Contactum;

class Order {

    public function __construct() {
        
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'checkout_create_order_line_item' ], 10, 4 );
        
        add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'checkout_order_processed' ], 1, 1 );
        
        add_filter('woocommerce_order_item_display_meta_value', [ $this, 'display_meta_value' ], 10, 3);
    
        add_action('woocommerce_after_order_itemmeta', [ $this, 'order_item_line_item_html' ], 10, 3);
        
        // add_action('woocommerce_order_item_get_formatted_meta_data', [ $this, 'order_item_get_formatted_meta_data' ], 10, 2);
    
        add_filter( 'woocommerce_display_item_meta', [ $this, 'display_item_meta' ], 10, 3);


        // add the order email 

        add_filter( 'woocommerce_order_item_name', [ $this, 'order_item_name' ], 10, 2 );
    }

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

        // if( isset( $values['text'] ) ) {
        //     $item->add_meta_data(
        //         __( 'Text', 'plugin-republic' ),
        //         $values['text'],
        //         true
        //     );
        // }

        // if( isset( $values['email'] ) ) {
        //     $item->add_meta_data(
        //         __( 'form_id', 'plugin-republic' ),
        //         $values['form_id'],
        //         true
        //     );
        // }
    }

    public function checkout_order_processed( $order_id ) {

    }
    
    public function display_meta_value($display_value, $meta = null, $item = null) {

        return $display_value;
    }

    public function order_item_line_item_html($item_id, $item, $product) {

    }

    public function order_item_get_formatted_meta_data($formatted_meta, $item) {

    }

    public function display_item_meta($html, $item, $args) {

        return $html;
    }

    public function order_item_name( $product_name, $item  ) {
        
        if( isset( $item['text'] ) ) {
            $product_name .= sprintf(
            '<ul><li>%s: %s</li></ul>',
            __( 'Text', 'plugin_republic' ),
            esc_html( $item['text'] )
            );
        }

        return $product_name;
    }
}