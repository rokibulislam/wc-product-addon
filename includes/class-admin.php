<?php
namespace Contactum;

use Contactum\Forms_List_Table;

/**
 * Admin class
 * 
 * @package MultiStoreX
 */ 

class Admin {

    /**
     * Construct
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_filter( 'parent_file', [ $this, 'fix_parent_menu' ] );
    }

    /**
     * Register form post types
     *
     * @param $parent_file string
     * 
     * @return void
     */
    public function fix_parent_menu( $parent_file ) {
        $current_screen = get_current_screen();
        $post_types     = [ 'chi_forms' ];

        if ( in_array( $current_screen->post_type, $post_types ) ) {
            $parent_file = 'contactum';
        }

        return $parent_file;
    }

    /**
     * Register form post types
     *
     * @return void
     */
    public function register_post_type() {
        $capability = 'manage_options';

        register_post_type( 'chi_forms', [
            'label'           => __( 'Forms', 'contactum' ),
            'public'          => false,
            'show_ui'         => false,
            'show_in_menu'    => false, //false,
            'capability_type' => 'post',
            'hierarchical'    => false,
            'query_var'       => false,
            'supports'        => ['title'],
            'capabilities'    => [
                'publish_posts'       => $capability,
                'edit_posts'          => $capability,
                'edit_others_posts'   => $capability,
                'delete_posts'        => $capability,
                'delete_others_posts' => $capability,
                'read_private_posts'  => $capability,
                'edit_post'           => $capability,
                'delete_post'         => $capability,
                'read_post'           => $capability,
            ],
            'labels' => [
                'name'               => __( 'Forms', 'contactum' ),
                'singular_name'      => __( 'Form', 'contactum' ),
                'menu_name'          => __( 'Forms', 'contactum' ),
                'add_new'            => __( 'Add Form', 'contactum' ),
                'add_new_item'       => __( 'Add New Form', 'contactum' ),
                'edit'               => __( 'Edit', 'contactum' ),
                'edit_item'          => __( 'Edit Form', 'contactum' ),
                'new_item'           => __( 'New Form', 'contactum' ),
                'view'               => __( 'View Form', 'contactum' ),
                'view_item'          => __( 'View Form', 'contactum' ),
                'search_items'       => __( 'Search Form', 'contactum' ),
                'not_found'          => __( 'No Form Found', 'contactum' ),
                'not_found_in_trash' => __( 'No Form Found in Trash', 'contactum' ),
                'parent'             => __( 'Parent Form', 'contactum' ),
            ],
        ] );

        register_post_type( 'chi_input', [
            'public'          => false,
            'show_ui'         => false,
            'show_in_menu'    => false,
        ] );
    }

    /**
     * add admin menu
     * 
     * @return void
     */ 
    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';
        $slug       = 'contactum';

        $hook = add_menu_page( __( 'Product Addon App', 'contactum' ), __( 'Product Addon App', 'contactum' ), $capability, $slug, [ $this, 'forms_page' ], 'dashicons-text' );
        add_submenu_page( $slug, __( 'Forms', 'contactum' ), __( 'Forms', 'contactum' ), $capability, 'contactum', [ $this, 'forms_page'] );
    }

    /**
     * load forms page
     * 
     * @return void
     */ 
    public function forms_page() {
        $action           = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : null;
        $add_new_page_url = admin_url( 'admin.php?page=contactum&action=add-new' );

        switch ( $action ) {
            case 'edit':
                require_once CONTACTUM_INCLUDES . '/html/form.php';
                break;
            case 'add-new':
                require_once CONTACTUM_INCLUDES . '/html/form.php';
                break;
            default:
                require_once CONTACTUM_INCLUDES . '/html/form-list-view.php';
                break;
        }
    }
}