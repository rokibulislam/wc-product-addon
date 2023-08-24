<?php

namespace Contactum\Fields;

/**
 * Contactum Field class
 * 
 * @package MultiStoreX
 */

abstract class Contactum_Field {

    /**
     * @var $name string
     */ 
    protected $name       = '';

    /**
     * @var $input_type string
     */ 
    protected $input_type = '';
        
    /**
     * @var $icon string
     */ 
    protected $icon       = 'header';

    /**
     * get name
     * 
     * @return string
     */ 
	public function get_name() {
		return $this->name;
	}

    /**
     * get type
     * 
     * @return string
     */ 
	public function get_type() {
		return $this->input_type;
	}

    /**
     * get icon
     * 
     * @return string
     */ 
	public function get_icon() {
		return $this->icon;
	}

    /**
     * render field
     * 
     * @param $field_settings array
     * @param $form_id int
     * 
     * @return void
     */ 
	abstract public function render( $field_settings, $form_id );
	   
    /**
     * get field option settings
     * 
     * @return array
     */ 
    abstract public function get_options_settings();
	
    /**
     * get field properties
     * 
     * @return array
     */ 
    abstract public function get_field_props();

    /**
     * set full width
     * 
     * @return boolean
     */ 
	public function is_full_width() {
		return false;
	}

    /**
     * get validator
     * 
     * @return boolean
     */ 
	public function get_validator() {
		return false;
	}

    /**
     * get settings
     * 
     * @return array
     */ 
	public function get_js_settings() {

		$settings = [
            'template'      => $this->get_type(),
            'title'         => $this->get_name(),
            'icon'          => $this->get_icon(),
            'settings'      => $this->get_options_settings(),
            'field_props'   => $this->get_field_props(),
            'is_full_width' => $this->is_full_width(),
            // $this->get_field_props()
        ];

       	if ( $validator = $this->get_validator() ) {
            $settings['validator'] = $validator;
        }

        // $settings['settings'][] = $this->get_conditional_field();

        return apply_filters( 'contactum_field_get_js_settings', $settings );
	}

    /**
     * get default conditional properties
     * 
     * @return array
     */ 
    public function default_conditional_prop() {
        return [
            'condition_status'  => 'no',
            'cond_field'        => [],
            'cond_operator'     => [ '=' ],
            'cond_option'       => [ __( '- select -', 'contactum' ) ],
            'cond_logic'        => 'all',
        ];
    }

    /**
     * get default attribute properties
     * 
     * @return array
     */ 
	public function default_attributes() {
        return [
            'template'    => $this->get_type(),
            'name'        => '',
            'label'       => $this->get_name(),
            'required'    => 'no',
            'message'     => 'this field is required',
            'id'          => 0,
            'width'       => 'large',
            'css'         => '',
            'placeholder' => '',
            'default'     => '',
            'size'        => 40,
            'help'        => '',
            'is_meta'     => 'yes',
            'is_new'      => true,
            // 'contactum_cond'   => $this->default_conditional_prop(),
        ];
	}

    /**
     * get default option settings
     * 
     * @return array
     */ 
	public static function get_default_option_settings( $is_meta = true, $exclude = [] ) {
		$common_properties = [
            [
                'name'      => 'label',
                'title'     => __( 'Field Label', 'contactum' ),
                'type'      => 'text',
                'section'   => 'basic',
                'priority'  => 10,
                'help_text' => __( 'Enter a title of this field', 'contactum' ),
            ],

            [
                'name'      => 'help',
                'title'     => __( 'Help text', 'contactum' ),
                'type'      => 'text',
                'section'   => 'basic',
                'priority'  => 20,
                'help_text' => __( 'Give the user some information about this field', 'contactum' ),
            ],

            [
                'name'      => 'required',
                'title'     => __( 'Required', 'contactum' ),
                // 'type'      => 'radio',
                'type'      => 'required',
                'options'   => [
                    'yes'   => __( 'Yes', 'contactum' ),
                    'no'    => __( 'No', 'contactum' ),
                ],
                'section'   => 'basic',
                'priority'  => 21,
                'default'   => 'no',
                'inline'    => true,
                'message'   => __('This Field is Required', 'contactum'),
                'help_text' => __( 'Check this option to mark the field required. A form will not submit unless all required fields are provided.', 'contactum' ),
            ],

            [
                'name'      => 'width',
                'title'     => __( 'Field Size', 'contactum' ),
                'type'      => 'radio',
                'options'   => [
                    'small'     => __( 'Small', 'contactum' ),
                    'medium'    => __( 'Medium', 'contactum' ),
                    'large'     => __( 'Large', 'contactum' ),
                ],
                'section'   => 'advanced',
                'priority'  => 21,
                'default'   => 'large',
                'inline'    => true,
            ],

            [
                'name'      => 'css',
                'title'     => __( 'CSS Class Name', 'contactum' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 22,
                'help_text' => __( 'Provide a container class name for this field. Available classes: contactum-col-half, contactum-col-half-last, contactum-col-one-third, contactum-col-one-third-last', 'contactum' ),
            ],

        ];


        if ( $is_meta ) {
            $common_properties[] = [
                'name'      => 'name',
                'title'     => __( 'Meta Key', 'contactum' ),
                // 'type'      => 'text_meta',
                'type'      => 'text',
                'section'   => 'basic',
                'priority'  => 11,
                'help_text' => __( 'Name of the meta key this field will save to', 'contactum' ),
            ];
        }

        if ( count( $exclude ) ) {
            foreach ( $common_properties as $key => &$option ) {
                if ( in_array( $option['name'], $exclude ) ) {
                    unset( $common_properties[$key] );
                }
            }
        }

        return $common_properties;
    }

    /**
     * get conditional field
     * 
     * @return array
     */
    public function get_conditional_field() {
        return array(
            'name'           => 'contactum_cond',
            'title'          => __( 'Conditional Logic', 'contactum' ),
            'type'           => 'conditional_logic',
            'section'        => 'advanced',
            'priority'       => 30,
            'help_text'      => '',
        );
    }

    /**
     * print label
     * 
     * @param $field object
     * @param $form_id int
     * 
     * @return void
     */
    public function print_label( $field, $form_id = 0 ) {
        ?>
        <div class="contactum-label"> <label for="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) . '_' . esc_attr( $form_id ) : 'cls'; ?>">
            <?php echo esc_html(  $field['label'] )  . wp_kses_post( $this->required( $field ) ) ; ?></label> </div>
        <?php
    }

    /**
     * print list attribute
     * 
     * @param $field object
     * 
     * @return void
     */
    public function print_list_attributes( $field ) {
        $label      = isset( $field['label'] ) ? $field['label'] : '';
        $el_name    = !empty( $field['name'] ) ? $field['name'] : '';
        $class_name = !empty( $field['css'] ) ? ' ' . $field['css'] : '';
        $field_size = !empty( $field['width'] ) ? ' field-size-' . $field['width'] : '';
        $message = !empty( $field['message'] ) ? $field['message'] : '';

        printf( 'class="contactum-el contactum-%s%s%s" data-label="%s" data-errormessage="%s"', esc_attr( $el_name ), esc_attr( $class_name ), esc_attr( $field_size ),
        esc_attr( $label ), esc_attr( $message )   );
    }

    /**
     * required
     * 
     * @param $field object
     * 
     * @return string
     */
    public function required( $field ) {
        if ( isset( $field['required'] ) && ( $field['required'] == 'yes' ||  $field['required'] === true ) ) {
            return '<span class="required">*</span>';
        }
    }

    /**
     * get help string
     * 
     * @param $field object
     * 
     * @return string
     */
    public function help_text( $field ) {
        if ( empty( $field['help'] ) ) {
            return;
        }
        ?>
        <span class="contactum-help"><?php echo esc_attr( $field['help'] ); ?></span>
        <?php
    }

    /**
     * prepare entry
     * 
     * @param $field array
     * @param $post_data array
     * 
     * @return string
     */ 
    public function prepare_entry( $field, $post_data = [] ) {
        $value = !empty( $post_data[$field['name']] ) ? $post_data[$field['name']] : '';

        if ( is_array( $value ) ) {
            $entry_value = implode(CONTACTUM_SEPARATOR, $value );
        } else {
            $entry_value = trim( $value  );
        }

        return $entry_value;
    }

    /**
     * conditional logic
     * 
     * @param $form_field array
     * @param $form_id int
     * 
     * @return string
     */ 
    public function conditional_logic( $form_field, $form_id ) {
        if ( !isset( $form_field['contactum_cond']['condition_status'] ) || $form_field['contactum_cond']['condition_status'] != 'yes' ) {
            return;
        }

        $cond_inputs                     = $form_field['contactum_cond'];
        $cond_inputs['condition_status'] = isset( $cond_inputs['condition_status'] ) ? $cond_inputs['condition_status'] : '';

        if ( $cond_inputs['condition_status'] == 'yes' ) {
            $cond_inputs['type']    = $form_field['template'];
            $cond_inputs['name']    = $form_field['name'];
            $cond_inputs['form_id'] = $form_id;
            $condition              = json_encode( $cond_inputs );
        } else {
            $condition = '';
        } ?>
        <?php
            $script = "contactum_conditional_items.push({$condition});";
            wp_add_inline_script( 'contactum-frontend', $script );
        ?>
        <?php
    }
}
