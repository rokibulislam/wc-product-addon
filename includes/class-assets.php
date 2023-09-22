<?php
/**
 * Assets
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace WCPRAEF;

/**
 * Assets class
 *
 * @package MultiStoreX
 */
class Assets {

	/**
	 * Settings
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Postdata
	 *
	 * @var string
	 */
	private $postdata;

	/**
	 * Constructor
	 */
	public function __construct() {
		$get_data = wp_unslash( $_GET );
		$id       = isset( $get_data['id'] ) ? intval( wp_unslash( $get_data['id'] ) ) : '';

		$this->postdata = get_post( $id );

		add_action( 'admin_enqueue_scripts', array( $this, 'register_builder_backend' ), 10 );

		if ( ! empty( $this->postdata->ID ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_builder_scripts' ) );
		}

		add_action( 'in_admin_header', array( $this, 'remove_admin_notices' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend' ) );
	}

	/**
	 * Remove admin notice
	 *
	 * @return void
	 */
	public function remove_admin_notices() {
		remove_all_actions( 'network_admin_notices' );
		remove_all_actions( 'user_admin_notices' );
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}

	/**
	 * Get frontend localize script
	 *
	 * @return array
	 */
	public function get_frontend_localized_scripts() {
		return apply_filters(
			'contactum_frontend_localize_script',
			array(
				'confirmMsg'    => __( 'Are you sure?', 'wc-product-addon-custom-field' ),
				'delete_it'  	=> __( 'Yes, delete it', 'wc-product-addon-custom-field' ),
				'cancel_it'  	=> __( 'No, cancel it', 'wc-product-addon-custom-field' ),
				'nonce'      	=> wp_create_nonce( 'contactum_nonce' ),
				'ajaxurl'    	=> admin_url( 'admin-ajax.php' ),
				'plupload'   	=> array(
					'url'           => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'contactum-upload-nonce' ),
					'flash_swf_url' => includes_url( 'js/plupload/plupload.flash.swf' ),
					'filters'       => array(
						array(
							'title'      => __( 'Allowed Files', 'wc-product-addon-custom-field' ),
							'extensions' => '*',
						),
					),
					'multipart'        => true,
					'urlstream_upload' => true,
					'warning'          => __( 'Maximum number of files reached!', 'wc-product-addon-custom-field' ),
					'size_error'       => __( 'The file you have uploaded exceeds the file size limit. Please try again.', 'wc-product-addon-custom-field' ),
					'type_error'       => __( 'You have uploaded an incorrect file type. Please try again.', 'wc-product-addon-custom-field' ),
				),
				'error_message' => __( 'Please fix the errors to proceed', 'wc-product-addon-custom-field' ),
			)
		);
	}

	/**
	 * Get admin localize script
	 *
	 * @return array
	 */
	public function get_admin_localized_scripts() {
		$form               = contactum()->forms->get( $this->postdata->ID );
		$contactum_settings = contactum_get_settings();

		return apply_filters(
			'contactum_admin_localize_script',
			array(
				'ajaxurl' 						  => admin_url( 'admin-ajax.php' ),
				'nonce'   						  => wp_create_nonce( 'contactum-form-builder-nonce' ),
				'rest'    						  => array(
					'root'    => esc_url_raw( get_rest_url() ),
					'nonce'   => wp_create_nonce( 'wp_rest' ),
					'version' => 'contactum/v1',
				),
				'field_settings' 				  => contactum()->fields->get_js_settings(),
				'panel_sections' 				  => contactum()->fields->get_field_groups(),
				'form_fields'    				  => $form->getFields(),
				'integration'    				  => array(),
				'settings'       				  => $form->getSettings(),
				'integrations'   				  => array(),
				'post'                            => $this->postdata,
				'contactum_cond_supported_fields' => array( 'radio_field', 'checkbox_field', 'dropdown_field' ),
				'contactum_settings'              => $contactum_settings,
				'countries'                       => contactum_get_countries(),
			)
		);
	}

	/**
	 * Register builder script and styles
	 *
	 * @return void
	 */
	public function register_builder_backend() {
		$screen = get_current_screen();

		if ( $screen->base !== 'toplevel_page_contactum' ) {
			return;
		}

		$this->register_styles( $this->get_admin_styles() );
		$this->register_scripts( $this->get_admin_scripts() );
	}

	/**
	 * Enqueue Builder Scripts
	 *
	 * @return void
	 */
	public function enqueue_builder_scripts() {
		$screen = get_current_screen();
		if ( $screen->base !== 'toplevel_page_contactum' ) {
			return;
		}

		$this->enqueue_styles( $this->get_admin_styles() );
		$this->enqueue_scripts( $this->get_admin_scripts() );

		$localize_script = $this->get_admin_localized_scripts();
		wp_localize_script( 'contactum-admin', 'contactum', $localize_script );
	}

	/**
	 * Register frontend script and styles
	 *
	 * @return void
	 */
	public function register_frontend() {
		$this->register_styles( $this->get_frontend_styles() );
		$this->register_scripts( $this->get_frontend_scripts() );
	}

	/**
	 * Enqueue Assets frontend
	 *
	 * @return void
	 */
	public function enqueue_frontend() {
		$this->enqueue_styles( $this->get_frontend_styles() );
		$this->enqueue_scripts( $this->get_frontend_scripts() );

		$localize_script = $this->get_frontend_localized_scripts();

		wp_localize_script( 'contactum-frontend', 'frontend', $localize_script );

		wp_localize_script(
			'contactum-frontend',
			'error_str_obj',
			array(
				'required'   => __( 'is required', 'wc-product-addon-custom-field' ),
				'mismatch'   => __( 'does not match', 'wc-product-addon-custom-field' ),
				'validation' => __( 'is not valid', 'wc-product-addon-custom-field' ),
				'duplicate'  => __( 'requires a unique entry and this value has already been used', 'wc-product-addon-custom-field' ),
			)
		);
	}

	/**
	 * Get admin scripts
	 *
	 * @return array
	 */
	public function get_admin_scripts() {

		$form_builder_js_deps = apply_filters(
			'contactum_builder_js_deps',
			array(
				'jquery',
				'jquery-ui-sortable',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
				'jquery-ui-resizable',
				'underscore',
				'contactum-clipboard',
				'contactum-sweetalert',
				'contactum-jquery-tooltip',
				'contactum-selectize',
			)
		);

		$scripts = array(
			'contactum-admin'           => array(
				'src'       => CONTACTUM_ASSETS . '/js/admin.js',
				'deps'      => $form_builder_js_deps,
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/admin.js' ),
				'in_footer' => true,
			),
			'contactum-jquery-scrollto' => array(
				'src'       => CONTACTUM_ASSETS . '/js/jquery.scrollTo.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/jquery.scrollTo.js' ),
				'in_footer' => true,
			),
			'contactum-selectize'       => array(
				'src'       => CONTACTUM_ASSETS . '/js/selectize.min.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/selectize.min.js' ),
				'in_footer' => true,
			),
			'contactum-jquery-tooltip'  => array(
				'src'       => CONTACTUM_ASSETS . '/js/tooltip.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/tooltip.js' ),
				'in_footer' => true,
			),
			'contactum-clipboard'       => array(
				'src'       => CONTACTUM_ASSETS . '/js/clipboard.min.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/clipboard.min.js' ),
				'in_footer' => true,
			),
			'contactum-sweetalert'      => array(
				'src'       => CONTACTUM_ASSETS . '/js/sweetalert2.min.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/sweetalert2.min.js' ),
				'in_footer' => true,
			),
		);

		return apply_filters( 'contactum_admin_scripts', $scripts );
	}

	/**
	 * Get admin styles
	 *
	 * @return array
	 */
	public function get_admin_styles() {
		$styles = array(
			'contactum-font-awesome'   => array(
				'src' => CONTACTUM_ASSETS . '/css/font-awesome/css/font-awesome.min.css',
			),
			'contactum-sweetalert2'    => array(
				'src' => CONTACTUM_ASSETS . '/css/sweetalert2.min.css',
			),
			'contactum-selectize'      => array(
				'src' => CONTACTUM_ASSETS . '/css/selectize.css',
			),
			'contactum-jquery-tooltip' => array(
				'src' => CONTACTUM_ASSETS . '/css/tooltip.css',
			),
			'contactum-admin'          => array(
				'src' => CONTACTUM_ASSETS . '/css/admin.css',
			),
			'contactum-star'           => array(
				'src' => CONTACTUM_ASSETS . '/css/star.css',
			),
		);

		return apply_filters( 'contactum_admin_styles', $styles );
	}

	/**
	 * Get frontend styles
	 *
	 * @return array
	 */
	public function get_frontend_styles() {

		$styles = array(
			'contactum-frontend'    => array(
				'src' => CONTACTUM_ASSETS . '/css/frontend.css',
			),
			'jquery-ui'             => array(
				'src' => CONTACTUM_ASSETS . '/css/jquery-ui-1.9.1.custom.css',
			),
			'contactum-sweetalert2' => array(
				'src' => CONTACTUM_ASSETS . '/css/sweetalert2.min.css',
			),
			'contactum-choices'     => array(
				'src' => CONTACTUM_ASSETS . '/css/choices.min.css',
			),
			'contactum-modal'       => array(
				'src' => CONTACTUM_ASSETS . '/css/jquery.modal.min.css',
			),
			'contactum-flatpickr'   => array(
				'src' => CONTACTUM_ASSETS . '/css/flatpickr.css',
			),
			'contactum-star'        => array(
				'src' => CONTACTUM_ASSETS . '/css/star.css',
			),
		);

		return apply_filters( 'contactum_frontend_styles', $styles );
	}

	/**
	 * Get Frontend Scripts
	 *
	 * @return array
	 */
	public function get_frontend_scripts() {
		$scripts = array(
			'contactum-frontend'             => array(
				'src'       => CONTACTUM_ASSETS . '/js/frontend.js',
				'deps'      => array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-slider', 'contactum-choices' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/frontend.js' ),
				'in_footer' => true,
			),
			'contactum-jquery-ui-timepicker' => array(
				'src'       => CONTACTUM_ASSETS . '/js/jquery-ui-timepicker-addon.js',
				'deps'      => array( 'jquery-ui-datepicker' ),
				'in_footer' => true,
			),
			'contactum-sweetalert'           => array(
				'src'       => CONTACTUM_ASSETS . '/js/sweetalert2.min.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/sweetalert2.min.js' ),
				'in_footer' => true,
			),
			'contactum-choices'              => array(
				'src'       => CONTACTUM_ASSETS . '/js/choices.min.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/choices.min.js' ),
				'in_footer' => true,
			),
			'contactum-upload'               => array(
				'src'       => CONTACTUM_ASSETS . '/js/upload.js',
				'deps'      => array( 'jquery', 'plupload-handlers', 'jquery-ui-sortable' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/upload.js' ),
				'in_footer' => true,
			),
			'contactum-conditional'          => array(
				'src'       => CONTACTUM_ASSETS . '/js/conditional.js',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
			),
			'contactum-modal'                => array(
				'src'       => CONTACTUM_ASSETS . '/js/jquery.modal.min.js',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
			),
			'contactum-flatpickr'            => array(
				'src'       => CONTACTUM_ASSETS . '/js/flatpickr.js',
				'deps'      => array( 'jquery', 'contactum-frontend' ),
				'in_footer' => true,
			),
			'contactum-mask'                 => array(
				'src'       => CONTACTUM_ASSETS . '/js/jquery.mask.min.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( CONTACTUM_PATH . '/assets/js/jquery.mask.min.js' ),
				'in_footer' => true,
			),
		);

		return apply_filters( 'contactum_frontend_scripts', $scripts );
	}


	/**
	 * Register scripts
	 *
	 * @param array $scripts scripts.
	 *
	 * @return void
	 */
	private function register_scripts( $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			$deps      = isset( $script['deps'] ) ? $script['deps'] : false;
			$in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
			$version   = isset( $script['version'] ) ? $script['version'] : CONTACTUM_VERSION;

			wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
		}
	}

	/**
	 * Register styles
	 *
	 * @param array $styles styles.
	 *
	 * @return void
	 */
	public function register_styles( $styles ) {
		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $deps, CONTACTUM_VERSION );
		}
	}

	/**
	 * Enqueue the scripts
	 *
	 * @param array $scripts scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts( $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Enqueue the styles
	 *
	 * @param array $styles styles.
	 *
	 * @return void
	 */
	public function enqueue_styles( $styles ) {
		foreach ( $styles as $handle => $script ) {
			wp_enqueue_style( $handle );
		}
	}
}
