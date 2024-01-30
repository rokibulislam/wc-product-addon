<?php
/*
Plugin Name:  Product Addon Custom Field For Woocommerce
Description: Woocommerce Product Extra Field Plugin
Version:     1.0.0
Author:      Md Rokibul islam
Author URI:  https://profiles.wordpress.org/rajib00002/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: product-addon-custom-field
Domain Path: languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Product_Addon_Custom_Field class
 *
 * @class Product_Addon_Custom_Field The class that holds the entire Product_Addon_Custom_Field plugin
 */
final class Product_Addon_Custom_Field {
	/**
	 * Multistorex version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Holds various class instances
	 *
	 * @var array
	 */
	private $container = array();

	/**
	 * The single instance of the class.
	 *
	 * @var Product_Addon_Custom_Field
	 */
	private static $instance = null;


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->define_constants();
		
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_global_styles' ) );

		add_action( 'admin_notices', array( $this, 'render_missing_woocommerce_notice' ) );

		add_action( 'woocommerce_loaded', array( $this, 'init_plugin' ) );
	}

	/**
	 * Initializes the Product_Addon_Custom_Field class
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
	 * @param string $prop prop.
	 *
	 * @return void
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return $this->{$prop};
	}

	/**
	 * Check isset properties
	 *
	 * @param string $prop prop.
	 *
	 * @return boolean
	 **/
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}

	/**
	 *  Define Constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'PRAEF_VERSION', $this->version );
		define( 'PRAEF_SEPARATOR', ' | ' );
		define( 'PRAEF_FILE', __FILE__ );
		define( 'PRAEF_ROOT', __DIR__ );
		define( 'PRAEF_PATH', dirname( PRAEF_FILE ) );
		define( 'PRAEF_INCLUDES', PRAEF_PATH . '/includes' );
		define( 'PRAEF_URL', plugins_url( '', PRAEF_FILE ) );
		define( 'PRAEF_ASSETS', PRAEF_URL . '/assets' );
		define( 'PRAEF_TEMPLATES', PRAEF_PATH . '/templates' );
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

		do_action( 'prafe_loaded' );
	}

	/**
	 * Include all the required files
	 *
	 * @return void
	 */
	public function includes() {
        
        //fields.
		require_once PRAEF_INCLUDES . '/fields/field-trait.php';
		require_once PRAEF_INCLUDES . '/fields/class-base-field.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-checkbox.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-date.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-dropdown.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-email.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-hidden.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-html.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-multidropdown.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-number.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-radio.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-sectionbreak.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-text.php';
		require_once PRAEF_INCLUDES . '/fields/class-field-textarea.php';
		
        //template.
        require_once PRAEF_INCLUDES . '/templates/class-base-template.php';
        require_once PRAEF_INCLUDES . '/templates/class-template-blank.php';
        
        require_once PRAEF_INCLUDES . '/class-admin-form-handler.php';
        require_once PRAEF_INCLUDES . '/class-admin-template.php';
        require_once PRAEF_INCLUDES . '/class-admin.php';
        require_once PRAEF_INCLUDES . '/class-ajax.php';
        require_once PRAEF_INCLUDES . '/class-assets.php';
        require_once PRAEF_INCLUDES . '/class-cart.php';

        require_once PRAEF_INCLUDES . '/class-field-manager.php';
        require_once PRAEF_INCLUDES . '/class-form-manager.php';
        require_once PRAEF_INCLUDES . '/class-form.php';
        require_once PRAEF_INCLUDES . '/class-frontend.php';
        require_once PRAEF_INCLUDES . '/class-forms-list.php';
		require_once PRAEF_INCLUDES . '/class-order.php';
		require_once PRAEF_INCLUDES . '/class-process.php';
		require_once PRAEF_INCLUDES . '/class-product-meta.php';
		require_once PRAEF_INCLUDES . '/class-template-manager.php';

		require_once PRAEF_INCLUDES . '/functions.php';
	}

	/**
	 * Activation function
	 *
	 * @return void
	 */
	public function activate() {

		if ( ! $this->has_woocommerce() ) {
            set_transient( 'prafe_wc_missing_notice', true );
        }

		$installed = get_option( 'prafe_installed' );

		if ( ! $installed ) {
			update_option( 'prafe_installed', time() );
		}

		update_option( 'prafe_version', PRAEF_VERSION );
	}

	/**
	 * Init all the classes
	 *
	 * @return void
	 */
	public function init_classes() {
		if ( is_admin() ) {
			$this->container['admin']              = new PRAEF\Admin();
			$this->container['admin_template']     = new PRAEF\Admin_Template();
			$this->container['admin_form_handler'] = new PRAEF\Admin_Form_Handler();
			$this->container['product_meta']       = new PRAEF\ProductMeta();
		}

		$this->container['assets']    = new PRAEF\Assets();
		$this->container['ajax']      = new PRAEF\Ajax();
		$this->container['fields']    = new PRAEF\FieldManager();
		$this->container['templates'] = new PRAEF\TemplateManager();
		$this->container['forms']     = new PRAEF\FormManager();
		$this->container['frontend']  = new PRAEF\Frontend();
		$this->container['order']     = new PRAEF\Order();
		$this->container['cart']      = new PRAEF\Cart();
		$this->container['process']   = new PRAEF\Process();
	}

	/**
	 * Enqueue Global Scripts
	 *
	 * @return void
	 */
	public function enqueue_global_styles() {
		wp_register_style( 'prafe-notice', PRAEF_ASSETS . '/css/notice.css', array(), PRAEF_VERSION );
		wp_enqueue_style( 'prafe-notice' );
	}

	/**
     * Missing WooCommerce notice
     *
     * @return void
     */
    public function render_missing_woocommerce_notice() {
        
        if ( ! self::has_woocommerce() && current_user_can( 'activate_plugins' ) ) {
        	require_once PRAEF_TEMPLATES . '/admin-notice.php';
        }
    }

    /**
     * Check whether woocommerce is installed and active
     * 
     * @return bool
     */
    public function has_woocommerce() {
        return class_exists( 'WooCommerce' );
    }


	/**
     * Handles when WooCommerce is not active
     *
     * @return void
     */
    public function woocommerce_not_loaded() {
        if ( did_action( 'woocommerce_loaded' ) || ! is_admin() ) {
            return;
        }
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
		load_plugin_textdomain( 'product-addon-custom-field', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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

if ( ! function_exists( 'product_addon_extra_field' ) ) {
	/**
	 * Load Product_Addon_Custom_Field Plugin
	 *
	 * @return Product_Addon_Custom_Field
	 */
	function product_addon_extra_field() {
		return Product_Addon_Custom_Field::init();
	}
}

product_addon_extra_field();