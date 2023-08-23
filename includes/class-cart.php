<?php 

namespace Contactum;

class Cart {

    public function __construct () {
        add_filter('woocommerce_get_item_data', [ $this, 'get_item_data' ], 10, 2);
        add_filter('woocommerce_cart_item_class', [ $this, 'cart_item_class' ], 10, 3);

        add_filter( 'woocommerce_cart_item_name', [ $this, 'display_custom_image_in_cart' ], 10, 3 );
    }

    function display_custom_image_in_cart( $item_name, $cart_item, $cart_item_key ) {
        if ( isset( $cart_item['image'] ) ) {
            $image = $cart_item['image'];
            $image_attributes =  wp_get_attachment_image_src( $image, 'thumbnail' );
            if ( $image_attributes ) : 
        ?>
            <img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" />
        <?php endif; 
        }
        return $item_name;
    }

    public function get_item_data( $item_data, $cart_item ) {

        $meta = new MetaDisplay();
        // echo "<pre>";
        // print_r($cart_item['post_data']);
        // exit;
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
        // echo "<pre>";
        // print_r($item_data);

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