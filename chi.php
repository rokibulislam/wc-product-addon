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

/**
 * MultiStoreX class
 *
 * @class MultiStoreX The class that holds the entire MultiStoreX plugin
 *
 */

final class Chi {

    /**
     * multistorex version.
     *
     * @var string
     */
    public $version    = '1.0';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = [];

    /**
     * The single instance of the class.
     *
     * @var MultiStoreX
     */
    private static $instance = null;


    /**
     * Constructor
     * 
     */ 
    public function __construct() {
        $this->define_constants();
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
     * Initializes the MultiStoreX class
     * 
     * @return object
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Self();
        }

        return $instance;
    }

    /**
     * Magic Method getter to bypass referencing objects
     * 
     * @return void
     */ 
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     *  Define Constants
     * 
     * @return void
     */ 
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

    /**
     * Include all the required files
     * 
     * @return void
     */
    public function includes() {
        require_once CONTACTUM_INCLUDES . '/class-product-meta.php';
        require_once CONTACTUM_INCLUDES . '/class-cart.php';
        require_once CONTACTUM_INCLUDES . '/class-order.php';
        require_once CONTACTUM_INCLUDES . '/class-process.php';
        require_once CONTACTUM_INCLUDES . '/class-frontend.php';
        require_once CONTACTUM_INCLUDES . '/class-meta-display.php';
    }

    /**
     * Activation function
     * 
     * @return @void
     */ 
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

    /**
     * Deactivation function
     * 
     * @return void
     */ 
    public function deactivate() {

    }

    /**
     * Init all the classes
     * 
     * @return void
     */ 
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

    /**
     * Hook into actions and filters.
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'contactum', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Get the plugin path.
     * 
     * @return string
     */ 
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }
}

/**
 * Load Multistorx Plugin
 *
 * @return MultiStoreX
 */
if( !function_exists('contactum') ) {
    function contactum() {
        return Chi::init();
    }
}

contactum();