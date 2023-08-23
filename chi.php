<?php
/*
Plugin Name: WC Woocommerce Product Adddon Rokib
Description: WordPress Product Form Builder plugin. Use Drag & Drop form builder to create your WordPress forms.
Version:     1.0
Author:      Md Kamrul islam
Author URI:  https://profiles.wordpress.org/rajib00002/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: WCWCPAR
Domain Path: languages
*/

if ( !defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/vendor/autoload.php';

final class Chi {

    public $version    = '3.7';
    private $container = [];

    public function __construct() {
        $this->define_constants();
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Self();
        }

        return $instance;
    }

    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    public function define_constants() {
        define( 'CONTACTUM_VERSION', $this->version );
        define( 'CONTACTUM_SEPARATOR', ' | ');
        define( 'CONTACTUM_FILE', __FILE__ );
        define( 'CONTACTUM_ROOT', __DIR__ );
        define( 'CONTACTUM_PATH', dirname( CONTACTUM_FILE ) );
        define( 'CONTACTUM_INCLUDES', CONTACTUM_PATH . '/includes' );
        define( 'CONTACTUM_URL', plugins_url( '', CONTACTUM_FILE ) );
        define( 'CONTACTUM_ASSETS', CONTACTUM_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_classes();
        $this->init_hooks();
        do_action( 'contactum_loaded' );
    }

    public function includes() {
        require_once CONTACTUM_INCLUDES . '/class-product-meta.php';
        require_once CONTACTUM_INCLUDES . '/class-cart.php';
        require_once CONTACTUM_INCLUDES . '/class-order.php';
        require_once CONTACTUM_INCLUDES . '/class-process.php';
        require_once CONTACTUM_INCLUDES . '/class-frontend.php';
        require_once CONTACTUM_INCLUDES . '/class-meta-display.php';
    }

    public function activate() {

        if ( !array_key_exists( 'fields', $this->container ) ) {
            $this->container['fields'] = new Contactum\FieldManager();
        }

        if ( !array_key_exists( 'forms', $this->container ) ) {
            $this->container['forms'] = new Contactum\FormManager();
        }

        if ( !array_key_exists( 'templates', $this->container ) ) {
            $this->container['templates'] = new Contactum\TemplateManager();
        }

        $installed = get_option( 'contactum_installed' );

        if( !$installed ) {
            update_option( 'contactum_installed', time() );
        }

        update_option( 'contactum_version', CONTACTUM_VERSION );
    }

    public function deactivate() {

    }

    public function init_classes() {
        if ( is_admin() ) {
            $this->container['admin']              = new Contactum\Admin();
            $this->container['admin_template']     = new Contactum\Admin_Template();
            $this->container['admin_form_handler'] = new Contactum\Admin_Form_Handler();
            $this->container['product_meta']       = new Contactum\ProductMeta();
        }

        $this->container['assets']    = new Contactum\Assets();
        $this->container['ajax']      = new Contactum\Ajax();
        $this->container['fields']    = new Contactum\FieldManager();
        $this->container['templates'] = new Contactum\TemplateManager();
        $this->container['forms']     = new Contactum\FormManager();
        $this->container['frontend']  = new Contactum\Frontend();
        $this->container['order']     = new Contactum\Order();
        $this->container['cart']      = new Contactum\Cart();
        $this->container['process']   = new Contactum\Process();
    }

    public function init_hooks() {
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

    public function localization_setup() {
        load_plugin_textdomain( 'contactum', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
}

if( !function_exists('contactum') ) {
    function contactum() {
        return Chi::init();
    }
}

contactum();