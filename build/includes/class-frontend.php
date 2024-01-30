<?php
/**
 * Frontend Manager
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

/**
 * Frontend class
 *
 * @package Product_Addon_Custom_Field
 */
class Frontend {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 10, 2 );
		add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_to_cart_url' ), 20, 2 );
		add_filter( 'woocommerce_loop_add_to_cart_args', array( $this, 'add_to_cart_args' ), 10, 2 );
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'add_form_in_single_product' ) );
	}

	/**
	 * Change add to cart text
	 *
	 * @param string $text text.
	 * @param object $product product.
	 *
	 * @return string
	 */
	public function add_to_cart_text( $text, $product ) {
		$product_id = $product->get_id();

		if ( $this->wcpa_product( $product_id ) ) {
			$text = 'Select options';
		}

		return $text;
	}

	/**
	 * Change add to cart text
	 *
	 * @param string $url url.
	 * @param object $product  product.
	 *
	 * @return string
	 */
	public function add_to_cart_url( $url, $product ) {
		$product_id = $product->get_id();

		if ( $this->wcpa_product( $product_id ) ) {
			return $product->get_permalink();
		}

		return $url;
	}

	/**
	 * Change add to cart argument
	 *
	 * @param array  $args args.
	 * @param object $product product.
	 *
	 * @return string
	 */
	public function add_to_cart_args( $args, $product ) {
		$product_id = $product->get_id();

		return $args;
	}

	/**
	 * Add form before single product
	 *
	 * @return string
	 */
	public function add_form_in_single_product() {
		global $post;

		$id   = get_post_meta( $post->ID, 'custom_form', true );
		$form = product_addon_extra_field()->forms->get( $id );

		if ( ! $form->id ) {
			return $this->show_error( __( 'The form couldn\'t be found.', 'product-addon-custom-field' ) );
		}

		$this->render_form( $form );
	}

	/**
	 * Render form
	 *
	 * @param object $form form.
	 * @param array  $atts atts.
	 *
	 * @return void
	 */
	private function render_form( $form, $atts = array() ) {
		product_addon_extra_field()->assets->register_frontend();
		product_addon_extra_field()->assets->enqueue_frontend();

		$form_fields   = $form->getFields();
		$form_settings = $form->getSettings();
		?>
		<ul class="prafe-form form-label-<?php echo esc_attr( $form_settings['label_position'] ); ?>">
			<?php product_addon_extra_field()->fields->render_fields( $form_fields, $form->id, $atts ); ?>
			<li>
				<input type="hidden" name="form_id" value="<?php echo esc_attr( $form->id ); ?>">
				<?php esc_attr( wp_nonce_field( 'prafe_frontend_action', 'prafe_frontend_nonce' ) ); ?>
			</li>
		</ul>
		<?php
	}

	/**
	 * Is form product
	 *
	 * @param int $product_id product_id.
	 *
	 * @return boolean
	 */
	public function wcpa_product( $product_id ) {
		$custom_form = get_post_meta( $product_id, 'custom_form', true );

		if ( ! empty( $custom_form ) ) {
			return true;
		}

		return false;
	}
}
