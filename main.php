<?php
/*
Plugin Name:  Product Addon Custom Field For Woocommerce
Description: Woocommerce Product Extra Field Plugin
Version:     1.0
Author:      Md Rokibul islam
Author URI:  https://profiles.wordpress.org/rajib00002/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: product-addon-custom-field
Domain Path: languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// require_once __DIR__ . '/vendor/autoload.php';

/**
 * WC_Product_Addon_Extra_Field class
 *
 * @class WC_Product_Addon_Extra_Field The class that holds the entire WC_Product_Addon_Extra_Field plugin
 */
final class WC_Product_Addon_Extra_Field {
	/**
	 * Multistorex version.
	 *
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * Holds various class instances
	 *
	 * @var array
	 */
	private $container = array();

	/**
	 * The single instance of the class.
	 *
	 * @var WC_Product_Addon_Extra_Field
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
	 * Initializes the WC_Product_Addon_Extra_Field class
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
		define( 'WCPRAEF_VERSION', $this->version );
		define( 'WCPRAEF_SEPARATOR', ' | ' );
		define( 'WCPRAEF_FILE', __FILE__ );
		define( 'WCPRAEF_ROOT', __DIR__ );
		define( 'WCPRAEF_PATH', dirname( WCPRAEF_FILE ) );
		define( 'WCPRAEF_INCLUDES', WCPRAEF_PATH . '/includes' );
		define( 'WCPRAEF_URL', plugins_url( '', WCPRAEF_FILE ) );
		define( 'WCPRAEF_ASSETS', WCPRAEF_URL . '/assets' );
		define( 'WCPRAEF_TEMPLATES', WCPRAEF_PATH . '/templates' );
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

		do_action( 'wcprafe_loaded' );
	}

	/**
	 * Include all the required files
	 *
	 * @return void
	 */
	public function includes() {
        
        //fields.
		require_once WCPRAEF_INCLUDES . '/fields/field-trait.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-base-field.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-checkbox.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-date.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-dropdown.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-email.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-hidden.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-html.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-image.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-multidropdown.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-number.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-radio.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-sectionbreak.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-text.php';
		require_once WCPRAEF_INCLUDES . '/fields/class-field-textarea.php';
		
        //template.
        require_once WCPRAEF_INCLUDES . '/templates/class-base-template.php';
        require_once WCPRAEF_INCLUDES . '/templates/class-template-blank.php';
        
        require_once WCPRAEF_INCLUDES . '/class-admin-form-handler.php';
        require_once WCPRAEF_INCLUDES . '/class-admin-template.php';
        require_once WCPRAEF_INCLUDES . '/class-admin.php';
        require_once WCPRAEF_INCLUDES . '/class-ajax.php';
        require_once WCPRAEF_INCLUDES . '/class-assets.php';
        require_once WCPRAEF_INCLUDES . '/class-cart.php';

        require_once WCPRAEF_INCLUDES . '/class-field-manager.php';
        require_once WCPRAEF_INCLUDES . '/class-form-manager.php';
        require_once WCPRAEF_INCLUDES . '/class-form.php';
        require_once WCPRAEF_INCLUDES . '/class-frontend.php';
        require_once WCPRAEF_INCLUDES . '/class-forms-list.php';
		require_once WCPRAEF_INCLUDES . '/class-meta-display.php';
		require_once WCPRAEF_INCLUDES . '/class-order.php';
		require_once WCPRAEF_INCLUDES . '/class-process.php';
		require_once WCPRAEF_INCLUDES . '/class-product-meta.php';
		require_once WCPRAEF_INCLUDES . '/class-template-manager.php';

		require_once WCPRAEF_INCLUDES . '/functions.php';
	}

	/**
	 * Activation function
	 *
	 * @return void
	 */
	public function activate() {

		if ( ! $this->has_woocommerce() ) {
            set_transient( 'dokan_wc_missing_notice', true );
        }

		$installed = get_option( 'wcprafe_installed' );

		if ( ! $installed ) {
			update_option( 'wcprafe_installed', time() );
		}

		update_option( 'wcprafe_version', WCPRAEF_VERSION );
	}

	/**
	 * Init all the classes
	 *
	 * @return void
	 */
	public function init_classes() {
		if ( is_admin() ) {
			$this->container['admin']              = new WCPRAEF\Admin();
			$this->container['admin_template']     = new WCPRAEF\Admin_Template();
			$this->container['admin_form_handler'] = new WCPRAEF\Admin_Form_Handler();
			$this->container['product_meta']       = new WCPRAEF\ProductMeta();
		}

		$this->container['assets']    = new WCPRAEF\Assets();
		$this->container['ajax']      = new WCPRAEF\Ajax();
		$this->container['fields']    = new WCPRAEF\FieldManager();
		$this->container['templates'] = new WCPRAEF\TemplateManager();
		$this->container['forms']     = new WCPRAEF\FormManager();
		$this->container['frontend']  = new WCPRAEF\Frontend();
		$this->container['order']     = new WCPRAEF\Order();
		$this->container['cart']      = new WCPRAEF\Cart();
		$this->container['process']   = new WCPRAEF\Process();
	}

	/**
	 * Enqueue Global Scripts
	 *
	 * @return void
	 */
	public function enqueue_global_styles() {
		wp_register_style( 'wcprafe-notice', WCPRAEF_ASSETS . '/css/notice.css', array(), WCPRAEF_VERSION );
		wp_enqueue_style( 'wcprafe-notice' );
	}

	/**
     * Missing WooCommerce notice
     *
     * @return void
     */
    public function render_missing_woocommerce_notice() {
        
        if ( ! self::has_woocommerce() && current_user_can( 'activate_plugins' ) ) {
        	require_once WCPRAEF_TEMPLATES . '/admin-notice.php';
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

if ( ! function_exists( 'wc_product_addon_extra_field' ) ) {
	/**
	 * Load WC_Product_Addon_Extra_Field Plugin
	 *
	 * @return WC_Product_Addon_Extra_Field
	 */
	function wc_product_addon_extra_field() {
		return WC_Product_Addon_Extra_Field::init();
	}
}

wc_product_addon_extra_field();