<?php
/*
Plugin Name:  Product Addon Extra Field For Woocommerce
Description: Woocommerce Product Extra Field Plugin
Version:     1.0
Author:      Md Kamrul islam
Author URI:  https://profiles.wordpress.org/rajib00002/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wc-product-addon-custom-field
Domain Path: languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// require_once __DIR__ . '/vendor/autoload.php';

/**
 * MultiStoreX class
 *
 * @class MultiStoreX The class that holds the entire MultiStoreX plugin
 */
final class Main {
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
	 * @var MultiStoreX
	 */
	private static $instance = null;


	/**
	 * Constructor
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_classes();
		
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

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
		define( 'CONTACTUM_VERSION', $this->version );
		define( 'CONTACTUM_SEPARATOR', ' | ' );
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
		$this->init_hooks();
		do_action( 'contactum_loaded' );
	}

	/**
	 * Include all the required files
	 *
	 * @return void
	 */
	public function includes() {
        
        //fields.
		require_once CONTACTUM_INCLUDES . '/fields/field-trait.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-base-field.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-checkbox.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-date.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-dropdown.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-email.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-file.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-hidden.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-html.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-image.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-multidropdown.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-number.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-radio.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-sectionbreak.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-text.php';
		require_once CONTACTUM_INCLUDES . '/fields/class-field-textarea.php';
		
        //template.
        require_once CONTACTUM_INCLUDES . '/templates/class-base-template.php';
        require_once CONTACTUM_INCLUDES . '/templates/class-template-blank.php';
        
        require_once CONTACTUM_INCLUDES . '/class-admin-form-handler.php';
        require_once CONTACTUM_INCLUDES . '/class-admin-template.php';
        require_once CONTACTUM_INCLUDES . '/class-admin.php';
        require_once CONTACTUM_INCLUDES . '/class-ajax.php';
        require_once CONTACTUM_INCLUDES . '/class-assets.php';
        require_once CONTACTUM_INCLUDES . '/class-cart.php';

        require_once CONTACTUM_INCLUDES . '/class-field-manager.php';
        require_once CONTACTUM_INCLUDES . '/class-form-manager.php';
        require_once CONTACTUM_INCLUDES . '/class-form.php';
        require_once CONTACTUM_INCLUDES . '/class-frontend.php';
        require_once CONTACTUM_INCLUDES . '/class-forms-list.php';
		require_once CONTACTUM_INCLUDES . '/class-meta-display.php';
		require_once CONTACTUM_INCLUDES . '/class-order.php';
		require_once CONTACTUM_INCLUDES . '/class-process.php';
		require_once CONTACTUM_INCLUDES . '/class-product-meta.php';
		require_once CONTACTUM_INCLUDES . '/class-template-manager.php';

		require_once CONTACTUM_INCLUDES . '/functions.php';
	}

	/**
	 * Activation function
	 *
	 * @return void
	 */
	public function activate() {

		if ( ! array_key_exists( 'fields', $this->container ) ) {
			$this->container['fields'] = new WCPRAEF\FieldManager();
		}

		if ( ! array_key_exists( 'forms', $this->container ) ) {
			$this->container['forms'] = new WCPRAEF\FormManager();
		}

		if ( ! array_key_exists( 'templates', $this->container ) ) {
			$this->container['templates'] = new WCPRAEF\TemplateManager();
		}

		$installed = get_option( 'contactum_installed' );

		if ( ! $installed ) {
			update_option( 'contactum_installed', time() );
		}

		update_option( 'contactum_version', CONTACTUM_VERSION );
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
		load_plugin_textdomain( 'wc-product-addon-custom-field', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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

if ( ! function_exists( 'contactum' ) ) {
	/**
	 * Load Multistorx Plugin
	 *
	 * @return MultiStoreX
	 */
	function contactum() {
		return Main::init();
	}
}

contactum();
