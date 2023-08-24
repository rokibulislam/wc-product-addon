<?php
namespace Contactum;

/**
 * Frontend class
 * 
 * @package MultiStoreX
 */ 

class Frontend {

    /**
     * constructor
     */ 
    public function __construct() {
        add_filter('woocommerce_product_add_to_cart_text', [ $this, 'add_to_cart_text' ], 10, 2);
        add_filter('woocommerce_product_add_to_cart_url', [ $this, 'add_to_cart_url' ], 20, 2);
        add_filter('woocommerce_loop_add_to_cart_args', [ $this, 'add_to_cart_args' ], 10, 2);
        add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_form_in_single_product' ] );
    }

    /**
     * change add to cart text
     * 
     * @param $text  string
     * @param $product object 
     * 
     * @return string
     */ 
    public function add_to_cart_text($text, $product) {

        $product_id = $product->get_id();

        if( $this->Wcpa_Product( $product_id ) ) {
            $text = 'Select options';
        }

        return $text;
    }

    /**
     * change add to cart text
     * 
     * @param $url string
     * @param $product object 
     * 
     * @return string
     */ 
    public function add_to_cart_url($url, $product) {

        $product_id = $product->get_id();

        if( $this->Wcpa_Product( $product_id ) ) {
            return $product->get_permalink();
        }

        return $url;
    }

    /**
     * change add to cart argument
     * 
     * @param $args array
     * @param $product object 
     * 
     * @return string
     */ 
    public function add_to_cart_args($args, $product)
    {
        $product_id = $product->get_id();

        if( $this->Wcpa_Product( $product_id ) ) {
            
        }

        return $args;
    }

    /**
     * add form before single product
     * 
     * 
     * @return string
     */ 
    public function add_form_in_single_product() {
        global $post;

        $id   = get_post_meta($post->ID, 'custom_form', true);
        $form = contactum()->forms->get( $id );
        
        if ( !$form->id ) {
            return $this->show_error( __( 'The form couldn\'t be found.', 'contactum' ) );
        }

        $this->render_form( $form );
    }

    /**
     * render form
     * 
     * @param $form object
     * @param $atts array
     * 
     * @return void
     */ 
    private function render_form( $form, $atts = []  ) {
        
        contactum()->assets->register_frontend();
        contactum()->assets->enqueue_frontend();

        $form_fields = $form->getFields();
        $form_settings = $form->getSettings();
    ?>
        <ul class="contactum-form form-label-<?php echo esc_attr( $form_settings['label_position'] ); ?>">
            <?php contactum()->fields->render_fields( $form_fields, $form->id, $atts ); ?>
            <li> 
                <input type="hidden" name="form_id" value="<?php echo esc_attr( $form->id ); ?>">
            </li>
        </ul>
        <?php
    }

    /**
     * is form product
     * 
     * @param $product_id int
     * 
     * @return boolean
     */ 
    public function Wcpa_Product($product_id) {
        $custom_form = get_post_meta($product_id, 'custom_form', true);

        if( !empty( $custom_form ) ) {
            return true;
        }

        return false;
    }
}
