<?php
/**
 * Form Template
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

use WP_Error;

/**
 * Form class
 *
 * @package Product_Addon_Custom_Field
 */
class Form {

	/**
	 * Form id
	 *
	 * @var int
	 */
	public $id = 0;

	/**
	 * Form name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Form data
	 *
	 * @var array
	 */
	public $data;

	/**
	 * Form fields
	 *
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * Constructor
	 *
	 * @param string $form form.
	 *
	 */
	public function __construct( $form = null ) {
		if ( is_numeric( $form ) ) {
			$the_post = get_post( $form );

			if ( $the_post ) {
				$this->id   = $the_post->ID;
				$this->name = $the_post->post_title;
				$this->data = $the_post;
			}
		} elseif ( is_a( $form, 'WP_Post' ) ) {
			$this->id   = $form->ID;
			$this->name = $form->post_title;
			$this->data = $form;
		}
	}

	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get fields
	 *
	 * @return array
	 */
	public function getFields() {
		$form_fields = array();

		$fields = get_children(
			array(
				'post_parent' => $this->id,
				'post_status' => 'publish',
				'post_type'   => 'chi_input',
				'numberposts' => '-1',
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
			)
		);

		foreach ( $fields as $key => $content ) {
			$field = maybe_unserialize( $content->post_content );

			if ( empty( $field['template'] ) ) {
				continue;
			}

			$field['id']   = $content->ID;
			$form_fields[] = $field;
		}

		return $form_fields;
	}

	/**
	 * Get field values
	 *
	 * @param array $field_template field_template.
	 *
	 * @return boolean
	 */
	public function hasField( $field_template ) {
		foreach ( $this->getFields() as $key => $field ) {
			if ( isset( $field['template'] ) && $field['template'] === $field_template ) {
				return true;
			}
		}
	}

	/**
	 * Get field values
	 *
	 * @return array
	 */
	public function getFieldValues() {
		$values = array();
		$fields = $this->getFields();

		if ( ! $fields ) {
			return $values;
		}

		foreach ( $fields as $field ) {
			if ( ! isset( $field['name'] ) ) {
				continue;
			}

			$value = array(
				'label' => isset( $field['label'] ) ? $field['label'] : '',
				'type'  => $field['template'],
			);

			$values[ $field['name'] ] = array_merge( $field, $value );
		}

		return apply_filters( 'prafe_get_field_values', $values );
	}

	/**
	 * Get Settings
	 *
	 * @return array
	 */
	public function getSettings() {
		$settings = get_post_meta( $this->id, 'form_settings', false );
		$default  = prafe_get_default_form_settings();

		return array_merge( $default, $settings );
	}
}
