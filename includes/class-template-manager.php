<?php
namespace Contactum;
use Contactum\Templates\Template_Blank;

class TemplateManager {

	private $templates = [];

	public function get_templates() {
        if ( !empty( $this->templates ) ) {
            return $this->templates;
        }

        $this->register_templates();

        return $this->templates;
    }

	public function get_template( $template_type ) {
		$templates = $this->get_templates();

		if ( isset( $template_type, $templates ) ) {
            return $templates[ $template_type ];
        }

        return false;
	}

	private function register_templates() {
        $templates = [
            'blank'   => new Template_Blank()
        ];

        $this->templates = apply_filters( 'contactum-form-templates', $templates );
	}

	public function get_field_groups() {
        $before_custom_templates = apply_filters( 'contactum-form-templates-section-before', [] );
        $groups                  = array_merge( $before_custom_templates, $this->get_custom_templates() );
        $groups                  = array_merge( $groups, $this->get_others_templates() );
        $after_custom_templates  = apply_filters( 'contactum-form-templates-section-after', [] );
        $groups                  = array_merge( $groups, $after_custom_templates );

        return $groups;
    }

    public function create( $name ) {
        if ( !$template = $this->exists( $name ) ) {
            return;
        }

        $form_id = contactum()->forms->create( $template->get_title(), $template->get_form_fields() );

        if ( is_wp_error( $form_id ) ) {
            return $form_id;
        }

        $meta_updates = [
            'form_settings' => $template->get_form_settings(),
        ];

        foreach ( $meta_updates as $meta_key => $meta_value ) {
            update_post_meta( $form_id, $meta_key, $meta_value );
        }

        return $form_id;
    }

    public function exists( $name ) {
        if ( array_key_exists( $name, $this->get_templates() ) ) {
            return $this->templates[ $name ];
        }

        return false;
    }
}