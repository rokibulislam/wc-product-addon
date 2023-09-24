<?php
/**
 * Field Image
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */

namespace WCPRAEF\Fields;

use WCPRAEF\Fields\Base_Field;

/**
 * Field Image class
 *
 * @package WC_Product_Addon_Extra_Field
 */
class Field_Image extends Base_Field {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Image', 'product-addon-custom-field' );
		$this->input_type = 'image_field';
		$this->icon       = 'file-image-o';
	}

	/**
	 * Render field
	 *
	 * @param array $field_settings field_settings.
	 * @param int   $form_id form_id.
	 *
	 * @return void
	 */
	public function render( $field_settings, $form_id ) {
		$allowed_ext = '';
		$unique_id   = sprintf( '%s-%d', $field_settings['name'], $form_id );

		if ( is_array( $field_settings['extension'] ) ) {
			foreach ( $field_settings['extension'] as $ext ) {
				$allowed_ext .= $ext . ',';
			}
		} else {
			$allowed_ext = '*';
		}
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php $this->print_label( $field_settings, $form_id ); ?>
			<div class="wcprafe-fields">
				<div id="<?php echo esc_attr( $unique_id ); ?>-upload-container">
					<div class="attachment-upload-filelist" data-type="file" data-required="<?php echo esc_attr( $field_settings['required'] ); ?>">
						<a type="button" id="<?php echo esc_attr( $unique_id ); ?>-pickfiles" data-form_id="<?php echo esc_attr( $form_id ); ?>"
							class="button btn-image file-selector <?php echo esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ); ?>"
							href="#" data-field-name="<?php echo esc_attr( $field_settings['name'] ); ?>"><?php echo esc_attr( $field_settings['button_label'] ); ?>
						</a>
						<ul class="attachment-list thumbnails"></ul>
					</div>
				</div><!-- .container -->
				<?php $this->help_text( $field_settings ); ?>
			</div>
		</li>
		<?php
		$uid      = esc_attr( $unique_id );
		$count    = esc_attr( $field_settings['count'] );
		$name     = esc_attr( $field_settings['name'] );
		$max_size = esc_attr( $field_settings['max_size'] );

		$script = ";(function($) {
			$(document).ready( function() {
				var uploader = new Uploader(
					'{$uid}-pickfiles',
					'{$uid}-upload-container',
					{$count},
					'{$name}',
					'{$allowed_ext}',
					// 'jpg,jpeg,gif,png,bmp',
					{$max_size}
				);
			});
		})(jQuery);";

		wp_add_inline_script( 'wcprafe-upload', $script );
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options = $this->get_default_option_settings( true, array( 'dynamic', 'width' ) );

		$settings = array(
			array(
				'name'      => 'max_size',
				'title'     => __( 'Max. file size', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 20,
				'help_text' => __( 'Enter maximum upload size limit in KB', 'product-addon-custom-field' ),
			),

			array(
				'name'      => 'count',
				'title'     => __( 'Max. files', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 21,
				'help_text' => __( 'Number of images can be uploaded', 'product-addon-custom-field' ),
			),
			array(
				'name'      => 'button_label',
				'title'     => __( 'Button Label', 'product-addon-custom-field' ),
				'type'      => 'text',
				'default'   => __( 'Select Image', 'product-addon-custom-field' ),
				'section'   => 'basic',
				'priority'  => 22,
				'help_text' => __( 'Enter a label for the Select button', 'product-addon-custom-field' ),
			),

			array(
				'name'        => 'extension',
				'title'       => __( 'Allowed Images', 'product-addon-custom-field' ),
				'title_class' => 'label-hr',
				'type'        => 'checkbox',
				'options'     => array(
					'jpg'  => __( 'JPG', 'product-addon-custom-field' ),
					'jpeg' => __( 'JPEG', 'product-addon-custom-field' ),
					'png'  => __( 'PNG', 'product-addon-custom-field' ),
					'gif'  => __( 'GIF', 'product-addon-custom-field' ),
					'bmp'  => __( 'BMP', 'product-addon-custom-field' ),
				),
				'section'     => 'advanced',
				'priority'    => 22,
				'help_text'   => '',
			),
		);

		return array_merge( $default_options, $settings );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'max_size'     => '1024',
			'count'        => '1',
			'button_label' => __( 'Select Image', 'product-addon-custom-field' ),
			'extension'    => array( 'jpg', 'jpeg', 'png', 'gif', 'bmp' ),
		);

		return array_merge( $defaults, $props );
	}

	/**
	 * Prepare entry
	 *
	 * @param array $field field.
	 * @param array $post_data post_data.
	 *
	 * @return string
	 */
	public function prepare_entry( $field, $post_data = array() ) {
		return isset( $post_data[ $field['name'] ] ) ? $post_data[ $field['name'] ] : '';
	}
}
