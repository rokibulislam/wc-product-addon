<?php
/**
 * Template Blank
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */

namespace WCPRAEF\Templates;

use WCPRAEF\Templates\Base_Form_Template;

/**
 * Template Blank class
 *
 * @package WC_Product_Addon_Extra_Field
 */
class Template_Blank extends Base_Form_Template {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->enabled     = true;
		$this->title       = __( 'Blank Form', 'product-addon-custom-field' );
		$this->description = __( 'Create a simple Blank form.', 'product-addon-custom-field' );
		$this->image       = '';
		$this->category    = 'default';
	}

	/**
	 *  Get form fields
	 *
	 * @return empty | array
	 */
	public function get_form_fields() {
		return __return_empty_array();
	}
}
