<?php
namespace Contactum\Templates;

/**
 * base Template class
 * 
 * @package MultiStoreX
 */ 

abstract class Contactum_Form_Template {

    /**
     * title
     * 
     * @var string
     */ 
	public $title;
	
    /**
     * description
     * 
     * @var string
     */ 
    public $description;
   
    /**
     * template fields
     * 
     * @var array
     */  
    public $form_fields;

    /**
     * template settings
     * 
     * @var array
     */      
    public $form_settings;

    /**
     * template category
     * 
     * @var string
     */  
    public $category = 'default';

    /**
     * constructor
     */ 
	public function __construct() {

	}

    /**
     * get title
     * 
     * @return string
     */ 
	public function get_title() {
		return $this->title;
	}

    /**
     * get description
     * 
     * @return string
     */ 
	public function get_description() {
		return $this->description;
	}

    /**
     * get form fields
     * 
     * @return array
     */ 
    public function get_form_fields() {
        return $this->form_fields;
    }

    /**
     * get form settings
     * 
     * @return array
     */ 
    public function get_form_settings() {
        return contactum_get_default_form_settings();
    }

    /**
     * get register fields
     * 
     * @return array
     */ 
    public function get_register_fields() {
        return contactum()->fields->getFields();
    }

    /**
     * is enable
     * 
     * @return boolean
     */ 
    public function is_enabled() {
        return $this->enabled;
    }
}