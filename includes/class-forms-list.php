<?php
/**
 * Form List Manager
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace Contactum;

use WP_Query;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Form List Table class
 *
 * @package MultiStoreX
 */
class Forms_List_Table extends \WP_List_Table {
	/**
	 * Constructor
	 */
	public function __construct() {
		global $status, $page, $page_status;

		parent::__construct( 
			array(
				'singular' => 'contactum-form',
				'plural'   => 'contactum-forms',
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
			'name'      => __( 'Form Name', 'contactum' ),
			'shortcode' => __( 'Shortcode', 'contactum' ),
			'author'    => __( 'Author', 'contactum' ),
			'date'      => __( 'Date', 'contactum' ),
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

		$request_data = wp_unslash( $_REQUEST );
		$get_data     = wp_unslash( $_GET );

		if ( isset( $request_data['s'] ) && ! empty( $request_data['s'] ) ) {
			$args['s'] = sanitize_text_field( wp_unslash( $request_data['s'] ) );
		}

		if ( isset( $get_data['post_status'] ) && ! empty( $get_data['post_status'] ) ) {
			$args['post_status'] = sanitize_text_field( wp_unslash( $get_data['post_status'] ) );
		}

		if ( isset( $get_data['orderby'] ) && ! empty( $get_data['orderby'] ) ) {
			$args['orderby'] = sanitize_text_field( wp_unslash( $get_data['orderby'] ) );
		}

		if ( isset( $get_data['order'] ) && ! empty( $get_data['order'] ) ) {
			$args['order'] = sanitize_text_field( wp_unslash( $get_data['order'] ) );
		}

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
		$request_data = wp_unslash( $_REQUEST );

		$title = '<strong>' . $item['name'] . '</strong>';
		$actions['edit'] = sprintf( '<a href="?page=%s&action=%s&id=%s">Edit</a>', esc_attr( $request_data['page'] ), 'edit', absint( $item['ID'] ) );

		$actions['duplicate'] = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url(
				wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'duplicate',
							'id'     => $item['ID'],
						),
						admin_url( 'admin.php?page=contactum' )
					),
					'bulk-contactum-forms'
				)
			),
			esc_attr__( 'Duplicate this form', 'contactum' ),
			esc_html__( 'Duplicate', 'contactum' )
		);

		$actions['delete'] = sprintf(
			'<a href="%s" title="%s">%s</a>',
			esc_url(
				wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'delete',
							'id' 	 => $item['ID'],
						),
						admin_url( 'admin.php?page=contactum' )
					),
					'bulk-contactum-forms'
				)
			),
			esc_attr__( 'Delete this form', 'contactum' ),
			esc_html__( 'Delete', 'contactum' )
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
			__( 'Y/m/d g:i:s A', 'contactum' ),
			$item['date'],
			true
		);
		$m_time = $item['date'];
		$time   = mysql2date( 'G', $item['date'] ) - get_option( 'gmt_offset' ) * 3600;

		$time_diff = time() - $time;

		if ( $time_diff > 0 && $time_diff < 24 * 60 * 60 ) {
			$h_time = sprintf(
				/* translators: %s: Time */
				__( '%s ago', '' ),
				human_time_diff( $time )
			);
		} else {
			$h_time = mysql2date( __( 'Y/m/d', '' ), $m_time );
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
			case 'shortcode':
			   return '<code> [contactum id="' . $item['ID'] . '"] </code>';
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
		$request_data = wp_unslash( $_REQUEST );
		$get_data     = wp_unslash( $_GET );
		$action       = $this->current_action();

		$ids = isset( $_REQUEST['id'] ) ? wp_parse_id_list( wp_unslash( $_REQUEST['id'] ) ) : array();

		if ( $action ) {
			$remove_query_args = [ '_wp_http_referer', '_wpnonce', 'action', 'id', 'post', 'action2' ];
			$add_query_args    = [];
			switch ( $action ) {
				case 'delete':
					foreach ( $ids as $id ) {
						wp_delete_post( $id );
					}
					$add_query_args['deleted'] = count( $ids );
					break;
				case 'duplicate':
					if ( ! empty( $get_data['id'] ) ) {
						$id = intval( $get_data['id'] );
						$add_query_args['duplicated'] = contactum()->forms->duplicate( $id );
					}
					break;
			}

			if ( ( isset( $request_data['action'] ) && $request_data['action'] === 'bulk-delete' ) || ( isset( $request_data['action2'] ) && $request_data['action2'] === 'bulk-delete' ) ) {
				$ids = esc_sql( $request_data['id'] );

				foreach ( $ids as $id ) {
					wp_delete_post( $id );
				}
			}

			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$redirect    = remove_query_arg( $remove_query_args, $request_uri );
			$redirect    = add_query_arg( $add_query_args, $redirect );
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
		$actions['bulk-delete'] = __( 'Delete Permanently', 'contactum' );

		return $actions;
	}

	/**
	 * Current action
	 *
	 * @return void|string
	 */
	public function current_action() {
		$get_data = wp_unslash( $_GET );

		if ( isset( $get_data['contactum_form_search'] ) ) {
			return 'contactum_form_search';
		}

		return parent::current_action();
	}

	/**
	 * Search box
	 *
	 * @param string $text text.
	 * @param int    $input_id input_id.
	 *
	 * @return void
	 */
	public function search_box( $text, $input_id ) {
		$request_data = wp_unslash( $_REQUEST );

		if ( empty( $request_data['s'] ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

		if ( ! empty( $request_data['orderby'] ) ) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $request_data['orderby'] ) . '" />';
		}
		if ( ! empty( $request_data['order'] ) ) {
			echo '<input type="hidden" name="order" value="' . esc_attr( $request_data['order'] ) . '" />';
		}
		if ( ! empty( $request_data['post_mime_type'] ) ) {
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $request_data['post_mime_type'] ) . '" />';
		}
		if ( ! empty( $request_data['detached'] ) ) {
			echo '<input type="hidden" name="detached" value="' . esc_attr( $request_data['detached'] ) . '" />';
		}
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?> : </label>
			<input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
				<?php submit_button( $text, 'button', 'contactum_form_search', false, array( 'id' => 'search-submit' ) ); ?>
		</p>
		<?php
	}
}