<?php

namespace Contactum;

class ProductMeta {

    public function __construct() {
        add_filter('woocommerce_product_data_tabs', [ $this, 'add_my_custom_product_data_tab' ], 101, 1);
        add_action('woocommerce_product_data_panels', [ $this, 'add_my_custom_product_data_fields' ] );
        add_action('woocommerce_process_product_meta', [ $this, 'woocommerce_process_product_meta_fields_save' ] );

        /** show forms assigned to a product in the product list (backend) */
        add_filter('manage_product_posts_columns', [ $this, 'manage_products_columns' ], 20, 1);
        add_action('manage_product_posts_custom_column', [ $this, 'manage_products_column' ], 10, 2);
    }

    public function add_my_custom_product_data_tab($tabs) {
        $tabs['wcpa_product-meta-tab'] = array(
            'label'    => __('Product Addons', 'my_text_domain'),
            'target'   => 'my_custom_product_data',
            'priority' => 90
        );

        return $tabs;
    }

    public function add_my_custom_product_data_fields() {
        global $woocommerce, $post;
        $entries_forms = contactum_entries_forms();
        $options = [];
        foreach ( $entries_forms as $id => $form ) {
            $options[$id] = $form->name;
        }
        
        $custom_form = get_post_meta($post->ID, 'custom_form', true);
        // echo $custom_form;
        echo '<div id="my_custom_product_data" class="panel">';
        woocommerce_wp_radio(
            array(
                'id' => 'custom_form',
                'label' => __('Custom Form', 'woocommerce'),
                'options' => $options
            )
        );
        echo '</div>';
    }

    public function woocommerce_process_product_meta_fields_save($post_id) { 
        $custom_form = isset($_POST['custom_form']) ? sanitize_text_field($_POST['custom_form']) : '';
        update_post_meta($post_id, 'custom_form', $custom_form);
    }

    public function manage_products_columns($columns) {
        return $columns;
    }

    public function manage_products_column($column_name, $post_id) {

    }

    public function get_forms($post_id) {

    }
}
