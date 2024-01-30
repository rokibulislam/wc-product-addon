<?php
/**
 * Assets
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

/**
 * Assets class
 *
 * @package Product_Addon_Custom_Field
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

		add_action( 'admin_enqueue_scripts', array( $this, 'register_builder_backend' ), 10 );

		add_action( 'in_admin_header', array( $this, 'remove_admin_notices' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend' ) );

		if ( isset( $_GET['prafe_page_nonce'] ) && 
			wp_verify_nonce( sanitize_text_field( $_GET['prafe_page_nonce'] ) , 'prafe-page' ) 
		) {
			$id       = isset( $_GET['id'] ) ? intval( wp_unslash( $_GET['id'] ) ) : '';
			$this->postdata = get_post( $id );

			if ( ! empty( $this->postdata->ID ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_builder_scripts' ) );
			}
		}
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
			'prafe_frontend_localize_script',
			array(
				'confirmMsg'    => __( 'Are you sure?', 'product-addon-custom-field' ),
				'delete_it'  	=> __( 'Yes, delete it', 'product-addon-custom-field' ),
				'cancel_it'  	=> __( 'No, cancel it', 'product-addon-custom-field' ),
				'nonce'      	=> wp_create_nonce( 'prafe_nonce' ),
				'ajaxurl'    	=> admin_url( 'admin-ajax.php' ),
				'plupload'   	=> array(
					'url'           => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'prafe-upload-nonce' ),
					'flash_swf_url' => includes_url( 'js/plupload/plupload.flash.swf' ),
					'filters'       => array(
						array(
							'title'      => __( 'Allowed Files', 'product-addon-custom-field' ),
							'extensions' => '*',
						),
					),
					'multipart'        => true,
					'urlstream_upload' => true,
					'warning'          => __( 'Maximum number of files reached!', 'product-addon-custom-field' ),
					'size_error'       => __( 'The file you have uploaded exceeds the file size limit. Please try again.', 'product-addon-custom-field' ),
					'type_error'       => __( 'You have uploaded an incorrect file type. Please try again.', 'product-addon-custom-field' ),
				),
				'error_message' => __( 'Please fix the errors to proceed', 'product-addon-custom-field' ),
			)
		);
	}

	/**
	 * Get admin localize script
	 *
	 * @return array
	 */
	public function get_admin_localized_scripts() {
		$form = product_addon_extra_field()->forms->get( $this->postdata->ID );
		$settings = prafe_get_settings();

		return apply_filters(
			'prafe_admin_localize_script',
			array(
				'ajaxurl' 						  => admin_url( 'admin-ajax.php' ),
				'nonce'   						  => wp_create_nonce( 'prafe-form-builder-nonce' ),
				'rest'    						  => array(
					'root'    => esc_url_raw( get_rest_url() ),
					'nonce'   => wp_create_nonce( 'wp_rest' ),
					'version' => 'prafe/v1',
				),
				'field_settings' 				  => product_addon_extra_field()->fields->get_js_settings(),
				'panel_sections' 				  => product_addon_extra_field()->fields->get_field_groups(),
				'form_fields'    				  => $form->getFields(),
				'settings'       				  => $form->getSettings(),
				'post'                            => $this->postdata,
				'prafe_cond_supported_fields' => array( 'radio_field', 'checkbox_field', 'dropdown_field' ),
				'prafe_settings'              => $settings,
				'countries'                       => prafe_get_countries(),
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

		if ( $screen->base !== 'toplevel_page_product-addon-custom-field' ) {
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

		$this->enqueue_styles( $this->get_admin_styles() );
		$this->enqueue_scripts( $this->get_admin_scripts() );

		$localize_script = $this->get_admin_localized_scripts();
		
		wp_localize_script( 'prafe-admin', 'prafe', $localize_script );
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

		$scripts = $this->get_frontend_scripts();

		$localize_script = $this->get_frontend_localized_scripts();

		wp_localize_script( 'prafe-frontend', 'frontend', $localize_script );

		wp_localize_script(
			'prafe-frontend',
			'error_str_obj',
			array(
				'required'   => __( 'is required', 'product-addon-custom-field' ),
				'mismatch'   => __( 'does not match', 'product-addon-custom-field' ),
				'validation' => __( 'is not valid', 'product-addon-custom-field' ),
				'duplicate'  => __( 'requires a unique entry and this value has already been used', 'product-addon-custom-field' ),
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
			'prafe_builder_js_deps',
			array(
				'jquery',
				'jquery-ui-sortable',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
				'jquery-ui-resizable',
				'underscore',
				'prafe-sweetalert',
			)
		);

		$scripts = array(
			'prafe-admin'           => array(
				'src'       => PRAEF_ASSETS . '/js/admin.js',
				'deps'      => $form_builder_js_deps,
				'version'   => filemtime( PRAEF_PATH . '/assets/js/admin.js' ),
				'in_footer' => true,
			),
			'prafe-jquery-scrollto' => array(
				'src'       => PRAEF_ASSETS . '/js/jquery.scrollTo.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( PRAEF_PATH . '/assets/js/jquery.scrollTo.js' ),
				'in_footer' => true,
			),
			'prafe-sweetalert'      => array(
				'src'       => PRAEF_ASSETS . '/js/sweetalert2.min.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( PRAEF_PATH . '/assets/js/sweetalert2.min.js' ),
				'in_footer' => true,
			),
		);

		return apply_filters( 'prafe_admin_scripts', $scripts );
	}

	/**
	 * Get admin styles
	 *
	 * @return array
	 */
	public function get_admin_styles() {
		$styles = array(
			'prafe-font-awesome'   => array(
				'src' => PRAEF_ASSETS . '/css/font-awesome/css/font-awesome.min.css',
			),
			'prafe-sweetalert2'    => array(
				'src' => PRAEF_ASSETS . '/css/sweetalert2.min.css',
			),
			'prafe-admin'          => array(
				'src' => PRAEF_ASSETS . '/css/admin.css',
			)
		);

		return apply_filters( 'prafe_admin_styles', $styles );
	}

	/**
	 * Get frontend styles
	 *
	 * @return array
	 */
	public function get_frontend_styles() {

		$styles = array(
			'prafe-frontend'    => array(
				'src' => PRAEF_ASSETS . '/css/frontend.css',
			),
			'prafe-choices'     => array(
				'src' => PRAEF_ASSETS . '/css/choices.css',
			),
			'prafe-flatpickr'   => array(
				'src' => PRAEF_ASSETS . '/css/flatpickr.css',
			)
		);

		return apply_filters( 'prafe_frontend_styles', $styles );
	}

	/**
	 * Get Frontend Scripts
	 *
	 * @return array
	 */
	public function get_frontend_scripts() {
		$scripts = array(
			'prafe-frontend'             => array(
				'src'       => PRAEF_ASSETS . '/js/frontend.js',
				'deps'      => array( 
					'jquery',
					'prafe-choices' 
				),
				'version'   => filemtime( PRAEF_PATH . '/assets/js/frontend.js' ),
				'in_footer' => true,
			),
			'prafe-choices'              => array(
				'src'       => PRAEF_ASSETS . '/js/choices.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( PRAEF_PATH . '/assets/js/choices.js' ),
				'in_footer' => true,
			),
			'prafe-conditional'          => array(
				'src'       => PRAEF_ASSETS . '/js/conditional.js',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
			),
			'prafe-flatpickr'            => array(
				'src'       => PRAEF_ASSETS . '/js/flatpickr.js',
				'deps'      => array( 'jquery', 'prafe-frontend' ),
				'in_footer' => true,
			)
		);

		return apply_filters( 'prafe_frontend_scripts', $scripts );
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
			$version   = isset( $script['version'] ) ? $script['version'] : PRAEF_VERSION;

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

			wp_register_style( $handle, $style['src'], $deps, PRAEF_VERSION );
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
