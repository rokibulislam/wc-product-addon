<?php
/**
 * Abstract class template
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF\Templates;

/**
 * Base Template class
 *
 * @package Product_Addon_Custom_Field
 */
abstract class Base_Form_Template {

	/**
	 * Title
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Template fields
	 *
	 * @var array
	 */
	public $form_fields;

	/**
	 * Template settings
	 *
	 * @var array
	 */
	public $form_settings;

	/**
	 * Template category
	 *
	 * @var string
	 */
	public $category = 'default';

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get form fields
	 *
	 * @return array
	 */
	public function get_form_fields() {
		return $this->form_fields;
	}

	/**
	 * Get form settings
	 *
	 * @return array
	 */
	public function get_form_settings() {
		return prafe_get_default_form_settings();
	}

	/**
	 * Get register fields
	 *
	 * @return array
	 */
	public function get_register_fields() {
		return product_addon_extra_field()->fields->getFields();
	}

	/**
	 * Is enable
	 *
	 * @return boolean
	 */
	public function is_enabled() {
		return $this->enabled;
	}
}
