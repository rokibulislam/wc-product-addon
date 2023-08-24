<?php
namespace Contactum\Templates;

use Contactum\Templates\Contactum_Form_Template;

/**
 * Template Blank class
 * 
 * @package MultiStoreX
 */ 

class Template_Blank extends Contactum_Form_Template {

        /**
         * constructor
         */ 
        public function __construct() {
                parent::__construct();
                $this->enabled     = true;
                $this->title       = __( 'Blank Form', 'contactum' );
                $this->description = __( 'Create a simple Blank form.', 'contactum' );
                $this->image       = '';
                $this->category    = 'default';
        }

        /**
         *  get form fields
         * 
         * @return empty | array
         */ 
        public function get_form_fields() {
        	return __return_empty_array();
        }
}