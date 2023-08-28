<?php
/**
 * Field Image
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace Contactum\Fields;

use Contactum\Fields\Contactum_Field;

/**
 * Field Image class
 *
 * @package MultiStoreX
 */
class Field_Image extends Contactum_Field {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Image', 'contactum' );
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
			<div class="contactum-fields">
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

		wp_add_inline_script( 'contactum-upload', $script );
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options = $this->get_default_option_settings( true, arry( 'dynamic', 'width' ) );

		$settings = array(
			array(
				'name'      => 'max_size',
				'title'     => __( 'Max. file size', 'contactum' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 20,
				'help_text' => __( 'Enter maximum upload size limit in KB', 'contactum' ),
			),

			array(
				'name'      => 'count',
				'title'     => __( 'Max. files', 'contactum' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 21,
				'help_text' => __( 'Number of images can be uploaded', 'contactum' ),
			),
			array(
				'name'      => 'button_label',
				'title'     => __( 'Button Label', 'contactum' ),
				'type'      => 'text',
				'default'   => __( 'Select Image', 'contactum' ),
				'section'   => 'basic',
				'priority'  => 22,
				'help_text' => __( 'Enter a label for the Select button', 'contactum' ),
			),

			array(
				'name'        => 'extension',
				'title'       => __( 'Allowed Images', 'contactum' ),
				'title_class' => 'label-hr',
				'type'        => 'checkbox',
				'options'     => array(
					'jpg'  => __( 'JPG', 'contactum' ),
					'jpeg' => __( 'JPEG', 'contactum' ),
					'png'  => __( 'PNG', 'contactum' ),
					'gif'  => __( 'GIF', 'contactum' ),
					'bmp'  => __( 'BMP', 'contactum' ),
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
			'button_label' => __( 'Select Image', 'contactum' ),
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
