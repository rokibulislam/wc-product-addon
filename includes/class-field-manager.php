<?php
/**
 * Field Manager Template
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace WCPRAEF;

use WCPRAEF\Fields\Field_Email;
use WCPRAEF\Fields\Field_Text;
use WCPRAEF\Fields\Field_Textarea;
use WCPRAEF\Fields\Field_Radio;
use WCPRAEF\Fields\Field_Checkbox;
use WCPRAEF\Fields\Field_Date;
use WCPRAEF\Fields\Field_Dropdown;
use WCPRAEF\Fields\Field_MultiDropdown;
use WCPRAEF\Fields\Field_Number;
use WCPRAEF\Fields\Field_Image;
use WCPRAEF\Fields\Field_Html;
use WCPRAEF\Fields\Field_SectionBreak;
use WCPRAEF\Fields\Field_Hidden;
use WCPRAEF\Fields\Field_File;

/**
 * FieldManager class
 *
 * @package MultiStoreX
 */
class FieldManager {

	/**
	 *  Store fields
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * Get Fields
	 *
	 * @return array
	 */
	public function getFields() {
		if ( ! empty( $this->fields ) ) {
			return $this->fields;
		}

		$this->register_field_types();

		return $this->fields;
	}

	/**
	 * Get field
	 *
	 * @param string $field_type field_type.
	 *
	 * @return object|boolean
	 */
	public function getField( $field_type ) {
		$fields = $this->getFields();

		if ( array_key_exists( $field_type, $fields ) ) {
			return $fields[ $field_type ];
		}

		return false;
	}

	/**
	 * Register field types
	 *
	 * @return void
	 */
	private function register_field_types() {
		$fields = array(
			'email_field'     => new Field_Email(),
			'text_field'      => new Field_Text(),
			'textarea_field'  => new Field_Textarea(),
			'radio_field'     => new Field_Radio(),
			'checkbox_field'  => new Field_Checkbox(),
			'date_field'      => new Field_Date(),
			'dropdown_field'  => new Field_Dropdown(),
			'number_field'    => new Field_Number(),
			'image_field'     => new Field_Image(),
			'multiple_select' => new Field_MultiDropdown(),
			'html_field'      => new Field_Html(),
			'section_break'   => new Field_SectionBreak(),
			'hidden_field'    => new Field_Hidden(),
			'file_field'      => new Field_File(),
		);

		$this->fields = apply_filters( 'contactum_form_fields', $fields );
	}

	/**
	 * Get field groups
	 *
	 * @return array
	 */
	public function get_field_groups() {
		$before_custom_fields = apply_filters( 'contactum_form_fields_section_before', array() );
		$groups               = array_merge( $before_custom_fields, $this->get_custom_fields() );
		$after_custom_fields  = apply_filters( 'contactum_form_fields_section_after', array() );
		$groups               = array_merge( $groups, $after_custom_fields );

		return $groups;
	}

	/**
	 * Get custom fields
	 *
	 * @return array
	 */
	private function get_custom_fields() {
		$fields = apply_filters(
			'contactum_form_fields_custom_fields',
			array(
				'text_field',
				'textarea_field',
				'email_field',
				'checkbox_field',
				'radio_field',
				'date_field',
				'dropdown_field',
				'number_field',
				'image_field',
				'multiple_select',
				'hidden_field',
				'file_field',
				'section_break',
				'html_field',
			)
		);

		return array(
			array(
				'title'  => __( 'Custom Fields', 'wc-product-addon-custom-field' ),
				'id'     => 'custom-fields',
				'fields' => $fields,
				'show'   => true,
			),
		);
	}

	/**
	 * Get settings
	 *
	 * @return array
	 */
	public function get_js_settings() {
		$fields = $this->getFields();

		$js_array = array();

		if ( $fields ) {
			foreach ( $fields as $type => $object ) {
				if ( is_object( $object ) ) {
					$js_array[ $type ] = $object->get_js_settings();
				}
			}
		}

		return $js_array;
	}

	/**
	 * Render fields
	 *
	 * @param array $fields  fields.
	 * @param int   $form_id form_id.
	 * @param array $atts    atts.
	 *
	 * @return void
	 */
	public function render_fields( $fields, $form_id, $atts = array() ) {
		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {
			if ( ! $field_object = $this->getField( $field['template'] ) ) {
				continue;
			}

			$field_object->render( $field, $form_id );
		}
	}
}
