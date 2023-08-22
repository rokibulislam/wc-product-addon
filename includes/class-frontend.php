<?php
namespace Contactum;

class Frontend {

    public function __construct() {

        add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_form_in_singl_product' ] );
    }

    public function add_form_in_singl_product() {
        global $post;
        $id = get_post_meta($post->ID, 'custom_form', true);
        $form = contactum()->forms->get( $id );
        
        if ( !$form->id ) {
            return $this->show_error( __( 'The form couldn\'t be found.', 'contactum' ) );
        }

        $this->render_form( $form );
    }


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
}
