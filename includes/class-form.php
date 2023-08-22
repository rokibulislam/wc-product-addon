<?php
namespace Contactum;

use Contactum\EntryManager;
use WP_Error;

class Form {

    public $id = 0;
    public $name;
    public $data;
    public $form_fields = [];

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

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getFields() {

        $form_fields = [];

        $fields = get_children( [
            'post_parent' => $this->id,
            'post_status' => 'publish',
            'post_type'   => 'chi_input',
            'numberposts' => '-1',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
        ] );

        foreach ( $fields as $key => $content ) {
            $field = maybe_unserialize( $content->post_content );

            if ( empty( $field['template']  ) ) {
                continue;
            }

            $field['id']   = $content->ID;
            $form_fields[] = $field;
        }

        return $form_fields;
    }

    public function hasField( $field_template ) {
        foreach ( $this->getFields() as $key => $field ) {
            if ( isset( $field['template'] ) && $field['template'] == $field_template ) {
                return true;
            }
        }
    }

    public function getFieldValues() {
        $values = [];
        $fields = $this->getFields();

        if ( !$fields ) {
            return $values;
        }

        foreach ( $fields as $field ) {
            if ( !isset( $field['name'] ) ) {
                continue;
            }

            $value = [
                'label' => isset( $field['label'] ) ? $field['label'] : '',
                'type'  => $field['template'],
            ];

            $values[ $field['name'] ] = array_merge( $field, $value );
        }

        return apply_filters( 'contactum_get_field_values', $values );
    }

    public function getSettings() {
        $settings = get_post_meta( $this->id, 'form_settings', true );
        $default  = contactum_get_default_form_settings();

        return array_merge( $default, $settings );
    }

    public function prepare_entries( $post_data = [] ) {
        $fields      = contactum()->fields->getFields();
        $form_fields = $this->getFields();
        $entry_fields = [];

        foreach ( $form_fields as $field ) {
            if ( !array_key_exists( $field['template'], $fields ) ) {
                continue;
            }

            $field_class = $fields[ $field['template'] ];
            $entry_fields[ $field['name'] ] = $field_class->prepare_entry( $field, $post_data );
        }

        return $entry_fields;
    }
}