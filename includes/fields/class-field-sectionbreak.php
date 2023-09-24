<?php
/**
 * Field Section
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */

namespace WCPRAEF\Fields;

use WCPRAEF\Fields\Base_Field;

/**
 * Field SectionBreak class
 *
 * @package WC_Product_Addon_Extra_Field
 */
class Field_SectionBreak extends Base_Field {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Section Break', 'product-addon-custom-field' );
		$this->input_type = 'section_break';
		$this->icon       = 'text-width';
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
		$description = isset( $field_settings['description'] ) ? $field_settings['description'] : '';
		$name        = isset( $field_settings['name'] ) ? $field_settings['name'] : '';
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<div class="<?php echo 'section_' . esc_attr( $form_id ); ?> <?php echo esc_html( $name ) . '_' . esc_attr( $form_id ); ?>">
				<h2 class="section-title"><?php echo esc_attr( $field_settings['label'] ); ?></h2>
				<div class="section-details"><?php echo esc_attr( $description ); ?></div>
			</div>
		</li>
		<?php
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$settings = array(
			array(
				'name'      => 'label',
				'title'     => __( 'Title', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 10,
				'help_text' => __( 'Title of the section', 'product-addon-custom-field' ),
			),

			array(
				'name'      => 'name',
				'title'     => __( 'Meta Key', 'product-addon-custom-field' ),
				'type'      => 'text_meta',
				'section'   => 'basic',
				'priority'  => 11,
				'help_text' => __( 'Name of the meta key this field will save to', 'product-addon-custom-field' ),
			),

			array(
				'name'      => 'description',
				'title'     => __( 'Description', 'product-addon-custom-field' ),
				'type'      => 'textarea',
				'section'   => 'basic',
				'priority'  => 12,
				'help_text' => __( 'Some details text about the section', 'product-addon-custom-field' ),
			),
		);

		return $settings;
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$props = array(
			'template'       => $this->get_type(),
			'label'          => $this->get_name(),
			'description'    => __( 'Some description about this section', 'product-addon-custom-field' ),
			'id'             => 0,
			'is_new'         => true,
			'wcprafe_cond' => $this->default_conditional_prop(),
		);

		return $props;
	}

	/**
	 * Set full width
	 *
	 * @return boolean
	 */
	public function is_full_width() {
		return false;
	}
}
