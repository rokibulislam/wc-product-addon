<?php
/**
 * Form List Manager
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF;

use WP_Query;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Form List Table class
 *
 * @package Product_Addon_Custom_Field
 */
class Forms_List_Table extends \WP_List_Table {

	protected $counts;

	protected $count;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		global $status, $page, $page_status;

		parent::__construct( 
			array(
				'singular' => 'product-addon-form',
				'plural'   => 'product-addon-forms',
				'ajax'     => false,
		 	)
		);
	}

	/**
	 * Get fields
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'name'      => __( 'Form Name', 'product-addon-custom-field' ),
			'author'    => __( 'Author', 'product-addon-custom-field' ),
			'date'      => __( 'Date', 'product-addon-custom-field' ),
		);

		return $columns;
	}

	/**
	 * Prepare items
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$per_page     		   = $this->get_items_per_page( 'chi_forms_per_page' );
		$current_page 		   = $this->get_pagenum();
		$offset       		   = ( $current_page - 1 ) * $per_page;

		$args = array(
			'offset'         => $offset,
			'posts_per_page' => $per_page,
		);

		$items = $this->item_query( $args );

		$this->counts = $items['count'];
		$this->items  = $items['items'];

		$this->set_pagination_args(
			array(
				'total_items' => $items['count'],
				'per_page'    => $per_page,
				'total_pages' => ceil( $this->count / $per_page ),
			)
		);

		$this->process_bulk_action();
	}

	/**
	 * Get fields
	 *
	 * @param array $args args.
	 *
	 * @return array
	 */
	public function item_query( $args ) {
		$defauls = array(
			'post_status' => 'any',
			'orderby'     => 'DESC',
			'order'       => 'ID',
		);

		$args = wp_parse_args( $args, $defauls );

		$args['post_type'] = 'chi_forms';

		$query = new WP_Query( $args );

		$items = array();

		if ( $query->have_posts() ) {
			$i = 0;

			while ( $query->have_posts() ) {
				$query->the_post();

				$item = $query->posts[ $i ];

				$items[ $i ] = array(
					'ID'          => $item->ID,
					'name'        => $item->post_title,
					'post_status' => $item->post_status,
					'author'      => $item->post_author,
					'date'        => $item->post_date,
				);

				$i++;
			}
		}

		$count = $query->found_posts;

		wp_reset_postdata();

		return array(
			'items' => $items,
			'count' => $count,
		);
	}

	/**
	 * Get column cb
	 *
	 * @param array $item item.
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="id[]" value="%1$s" />', esc_attr( $item['ID'] ) );
	}

	/**
	 * Get column name
	 *
	 * @param array $item item.
	 *
	 * @return string
	 */
	public function column_name( $item ) {
		$title = '<strong>' . $item['name'] . '</strong>';
		
		// $actions['edit'] = sprintf( '<a href="?page=%s&action=%s&id=%s">Edit</a>', esc_attr( 'product-addon-custom-field' ), 'edit', absint( $item['ID'] ) );

		$actions['edit'] = sprintf( '<a href="%s">%s</a>',
			esc_url( 
				wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'edit',
							'id'     => $item['ID'],
						),
						admin_url( 'admin.php?page=product-addon-custom-field' )
					),
					'prafe-page',
					'prafe_page_nonce'
				) 
			),
			esc_html__( 'Edit', 'product-addon-custom-field' )
		);

		$actions['duplicate'] = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url(
				wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'duplicate',
							'id'     => $item['ID'],
							'prafe_nonce'  => wp_create_nonce('product_addon_form_list_nonce'),
						),
						admin_url( 'admin.php?page=product-addon-custom-field' )
					),
					'bulk-prafe-forms'
				)
			),
			esc_attr__( 'Duplicate this form', 'product-addon-custom-field' ),
			esc_html__( 'Duplicate', 'product-addon-custom-field' )
		);

		$actions['delete'] = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url(
				wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'delete',
							'id' 	 => $item['ID'],
							'prafe_nonce'  => wp_create_nonce('product_addon_form_list_nonce'),
						),
						admin_url( 'admin.php?page=product-addon-custom-field' )
					),
					'bulk-prafe-forms'
				)
			),
			esc_attr__( 'Delete this form', 'product-addon-custom-field' ),
			esc_html__( 'Delete', 'product-addon-custom-field' )
		);

		return $title . $this->row_actions( $actions );
	}

	/**
	 * Get column author
	 *
	 * @param array $item item.
	 *
	 * @return string
	 */
	public function column_author( $item ) {
		$user = get_user_by( 'id', $item['author'] );

		if ( ! $user ) {
			return '<span class="na">&ndash;</span>';
		}

		$user_name = ! empty( $user->data->display_name ) ? $user->data->display_name : $user->data->user_login;

		if ( current_user_can( 'edit_user' ) ) {
			return '<a href="' . esc_url(
				add_query_arg(
					array(
						'user_id' => $user->ID,
					),
					admin_url( 'user-edit.php' )
				)
			) . '">' . esc_html( $user_name ) . '</a>';
		}

		return esc_html( $user_name );
	}

	/**
	 * Get column date
	 *
	 * @param array $item item.
	 *
	 * @return string
	 */
	public function column_date( $item ) {
		$t_time = mysql2date(
			__( 'Y/m/d g:i:s A', 'product-addon-custom-field' ),
			$item['date'],
			true
		);
		$m_time = $item['date'];
		$time   = mysql2date( 'G', $item['date'] ) - get_option( 'gmt_offset' ) * 3600;

		$time_diff = time() - $time;

		if ( $time_diff > 0 && $time_diff < 24 * 60 * 60 ) {
			$h_time = sprintf(
				/* translators: %s: Time */
				__( '%s ago', 'product-addon-custom-field' ),
				human_time_diff( $time )
			);
		} else {
			$h_time = mysql2date( __( 'Y/m/d', 'product-addon-custom-field' ), $m_time );
		}

		return '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
	}

	/**
	 * Column default
	 *
	 * @param array  $item item.
	 * @param string $column_name column_name.
	 *
	 * @return array
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'ID':
			case 'name':
			case 'author':
			   return $item[ $column_name ];
			default:
				return $item;
		}
	}

	/**
	 * Process bulk action
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		$action = $this->current_action();

		$nonce = isset( $_REQUEST['product_addon_form_list_nonce'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['product_addon_form_list_nonce'] ) ) : '';

		if ( !isset( $nonce ) || !wp_verify_nonce( $nonce, 'product_addon_form_list_action' ) ) {
			return ;
		}

		$ids = isset( $_REQUEST['id'] ) ? wp_parse_id_list( wp_unslash( sanitize_text_field( $_REQUEST['id'] ) ) ) : array();

		if ( $action ) {
			$remove_query_args = array( '_wp_http_referer', '_wpnonce', 'product_addon_form_list_nonce', 'action', 'id', 'post', 'action2' );
			$add_query_args    = array();
			switch ( $action ) {
				case 'delete':
					foreach ( $ids as $id ) {
						wp_delete_post( $id );
					}
					$add_query_args['deleted'] = count( $ids );
					break;
				case 'duplicate':
					if ( ! empty( $_GET['id'] ) ) {
						$id                           = intval( sanitize_text_field( wp_unslash( $_GET['id'] ) ) );
						$add_query_args['duplicated'] = product_addon_extra_field()->forms->duplicate( $id );
					}
					break;
			}


			if ( ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'bulk-delete' ) || ( isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] === 'bulk-delete' ) ) {
				
				$ids = esc_sql( array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['id'] ) ) );

				foreach ( $ids as $id ) {
					wp_delete_post( $id );
				}
			}

			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$redirect    = remove_query_arg( $remove_query_args, $request_uri );
			$redirect    = add_query_arg( $add_query_args, $redirect );

			wp_redirect($redirect);
            exit;
		}
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'name'   => array( 'name', true ),
			'author' => array( 'author', false ),
			'date'   => array( 'date', false ),
		);

		return $sortable_columns;
	}

	/**
	 * Bulk actions
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions['bulk-delete'] = __( 'Delete Permanently', 'product-addon-custom-field' );

		return $actions;
	}

	/**
	 * Current action
	 *
	 * @return void|string
	 */
	public function current_action() {
		return parent::current_action();
	}
}