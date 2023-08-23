<?php

namespace Contactum;

class MetaDisplay {

    public function __construct() {

    } 

    public function display( $field ) {
        $display = $field['value'];
        switch ($field['template']) {
            case 'image_field':
                $image_attributes =  wp_get_attachment_image_src( $field['value'], 'thumbnail' );
                if ( $image_attributes ) : 
                    $display = '<img src="'.  $image_attributes[0] .'" width="'. $image_attributes[1] .'" height="'. $image_attributes[2] .'" />';
                endif;
                break;
            case 'file_field':
                $image = wp_mime_type_icon( $field['value'] );
                $display = sprintf( '<div class="attachment-name"><img src="%s" /></div>', $image );
                break;
            default:
                $display = $field['value'];
                break;
        }

        return $display;
    }
}