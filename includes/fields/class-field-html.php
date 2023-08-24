<?php

namespace Contactum\Fields;
use Contactum\Fields\Contactum_Field;

/**
 * Field Html class
 * 
 * @package MultiStoreX
 */

class Field_Html extends Contactum_Field {

    /**
     * constructor
     */ 
	public function __construct() {
        $this->name       = __( 'Html', 'contactum' );
        $this->input_type = 'html_field';
        $this->icon       = 'code';
    }

    /**
     * render field
     * 
     * @param $field_settings array
     * @param $form_id int
     * 
     * @return void
     */ 
    public function render( $field_settings, $form_id ) {
        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php echo $field_settings['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </li>
    <?php }

    /**
     * get field option settings
     * 
     * @return array
     */ 
    public function get_options_settings() {
        $settings = [
            [
                'name'      => 'html',
                'title'     => __( 'Html Codes', 'contactum' ),
                'type'      => 'textarea',
                'section'   => 'basic',
                'priority'  => 11,
                'help_text' => __( 'Paste your HTML codes, WordPress shortcodes will also work here', 'contactum' ),
            ],
            [
                'name'          => 'name',
                'title'         => __( 'Meta Key', 'contactum' ),
                // 'type'          => 'text_meta',
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 12,
                'help_text'     => __( 'Name of the meta key this field will save to', 'contactum' ),
            ],
            [
                'name'           => 'contactum_cond',
                'title'          => __( 'Conditional Logic', 'contactum' ),
                'type'           => 'conditional-logic',
                'section'        => 'advanced',
                'priority'       => 30,
                'help_text'      => '',
            ]
        ];

        return $settings;
    }

    /**
     * get field properties
     * 
     * @return array
     */ 
    public function get_field_props() {
        $props = [
            'template'     => $this->get_type(),
            'label'        => $this->get_name(),
            'html'         => sprintf( '%s', __( 'HTML Section', 'contactum' ) ),
            'id'           => 0,
            'is_new'       => true,
            'contactum_cond' => $this->default_conditional_prop()
        ];

    	return $props;
    }
    
    /**
     * set full width
     * 
     * @return boolean
     */ 
    public function is_full_width() {
        return true;
    }
}
