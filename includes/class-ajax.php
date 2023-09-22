<?php
/**
 * Ajax Template
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace WCPRAEF;

/**
 * Ajax class
 *
 * @package MultiStoreX
 */
class Ajax {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_save_contactum_form', array( $this, 'save_contactum_form' ) );

		add_action( 'wp_ajax_upload_file', array( $this, 'upload_file' ) );
		add_action( 'wp_ajax_nopriv_upload_file', array( $this, 'upload_file' ) );

		add_action( 'wp_ajax_file_del', array( $this, 'delete_file' ) );
		add_action( 'wp_ajax_nopriv_file_del', array( $this, 'delete_file' ) );
	}

	/**
	 * Save Form
	 *
	 * @return void
	 */
	public function save_contactum_form() {
		$post_data = wp_unslash( $_POST );

		if ( ! wp_verify_nonce( $post_data['contactum_form_builder_nonce'], 'contactum-form-builder-nonce' ) ) {
			wp_send_json_error( __( 'Unauthorized operation', 'wc-product-addon-custom-field' ) );
		}

		if ( isset( $post_data['form_data'] ) ) {
			parse_str( $post_data['form_data'], $form_data );
		}

		if ( empty( $form_data['contactum_form_id'] ) ) {
			wp_send_json_error( __( 'Invalid form id', 'wc-product-addon-custom-field' ) );
		}

		$form_fields = isset( $post_data['form_fields'] ) ? $post_data['form_fields'] : '';
		$form_fields = json_decode( $form_fields, true );

		if ( isset( $post_data['settings'] ) ) {
			$form_settings = (array) json_decode( $post_data['settings'] );
		}

		$data = array(
			'form_id'       => absint( $form_data['contactum_form_id'] ),
			'post_title'    => $form_data['post_title'],
			'form_fields'   => $form_fields,
			'form_settings' => $form_settings,
		);

		$form_fields = contactum()->forms->save( $data );
        
        wp_send_json_success(
            array(
                'form_fields'   => $form_fields,
                'form_settings' => $form_settings,
            )
        );
    }

	/**
	 * Upload file
	 *
	 * @return void
	 */
	public function upload_file() {
		$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'contactum-upload-nonce' ) ) {
			die( 'error' );
		}

		$post_data  = wp_unslash( $_POST );
		$form_id    = isset( $post_data['form_id'] ) ? intval( sanitize_text_field( wp_unslash( $post_data['form_id'] ) ) ) : false;
		$field_name = isset( $post_data['field_name'] ) ? $post_data['field_name'] : '';

		if ( ! $form_id ) {
			die( 'error' );
		}

		$file = isset( $_FILES['file'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_FILES['file'] ) ) : array();

		$upload = array(
			'name'     => isset( $file['name'] ) ? $file['name'] : '',
			'type'     => isset( $file['type'] ) ? $file['type'] : '',
			'tmp_name' => isset( $file['tmp_name'] ) ? $file['tmp_name'] : '',
			'error'    => isset( $file['error'] ) ? $file['error'] : '',
			'size'     => isset( $file['size'] ) ? $file['size'] : '',
		);

		header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );

		$attach = $this->handle_upload( $upload );

		if ( $attach['success'] ) {
			$response         = array( 'success' => true );
			$response['html'] = $this->attach_html( $attach['attach_id'], $field_name );
			echo $response['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo 'error'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		exit;
	}

	/**
	 * Handle Upload and create attachment.
	 *
	 * @param array $upload_data upload_data.
	 *
	 * @return object
	 */
	public function handle_upload( $upload_data ) {
		$uploaded_file = wp_handle_upload( $upload_data, array( 'test_form' => false ) );

		// If the wp_handle_upload call returned a local path for the image.
		if ( isset( $uploaded_file['file'] ) ) {
			$file_loc  = $uploaded_file['file'];
			$file_name = basename( $upload_data['name'] );
			$file_type = wp_check_filetype( $file_name );

			$attachment = array(
				'post_mime_type' => $file_type['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			$attach_id   = wp_insert_attachment( $attachment, $file_loc );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file_loc );

			wp_update_attachment_metadata( $attach_id, $attach_data );
			return array(
				'success'   => true,
				'attach_id' => $attach_id,
			);
		}

		return array(
			'success' => false,
			'error'   => $uploaded_file['error'],
		);
	}


	/**
	 * Get html of attachment
	 *
	 * @param int    $attach_id  attach_id.
	 * @param string $field_name field_name.
	 * @param string $type type.
	 *
	 * @return string $html html.
	 */
	public static function attach_html( $attach_id, $field_name, $type = null ) {

		$get_data = wp_unslash( $_GET );

		if ( ! $type ) {
			$type = isset( $get_data['type'] ) ? sanitize_text_field( wp_unslash( $get_data['type'] ) ) : 'image';
		}

		$attachment = get_post( $attach_id );

		if ( ! $attachment ) {
			return;
		}

		if ( wp_attachment_is_image( $attach_id ) ) {
			$image = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
			$image = $image[0];
		} else {
			$image = wp_mime_type_icon( $attach_id );
		}

		$html  = '<li class="image-wrap thumbnail">';
		$html .= sprintf( '<div class="attachment-name"><img src="%s" alt="%s" /></div>', $image, esc_attr( $attachment->post_title ) );
		$html .= sprintf( '<input type="hidden" name="%s" value="%d">', $field_name, $attach_id );
		$html .= '<div class="caption">';
		$html .= sprintf( '<a href="#" class="attachment-delete" data-attach_id="%d"> <img src="%s" /> </a>', $attach_id, CONTACTUM_ASSETS . '/images/del-img.png' );
		$html .= '</div>';
		$html .= '</li>';

		return $html;
	}

	/**
	 * Delete file
	 *
	 * @return void
	 */
	public function delete_file() {
		check_ajax_referer( 'contactum_nonce', 'nonce' );

		$post_data  = wp_unslash( $_POST );
		$attach_id  = isset( $post_data['attach_id'] ) ? intval( $post_data['attach_id'] ) : 0;
		$attachment = get_post( $attach_id );

		if ( get_current_user_id() === $attachment->post_author || current_user_can( 'delete_private_pages' ) ) {
			wp_delete_attachment( $attach_id, true );
		}

		echo 'success';
		exit;
	}
}
