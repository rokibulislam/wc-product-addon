<?php
/**
 * Form List View
 *
 * @author Kamrul
 * @package MultiStoreX
 */

	$url   = esc_url( add_query_arg( [ 'action'   => 'create_template' ], admin_url( 'admin.php' ) ) );
?>
<div class="wrap">
	<h2>
		<?php esc_html_e( 'Forms', 'wc-product-addon-custom-field' );  ?>
		<a href="<?php echo esc_url( $add_new_page_url ); ?>" id="new-contactum-form" class="page-title-action add-form"><?php esc_html_e( 'Add Form', 'wc-product-addon-custom-field' ); ?></a>
	</h2>
	<?php
		$form_list_table = new \WCPRAEF\Forms_List_Table();
	?>
	<form method="get">
		<input type="hidden" name="page" value="contactum">
		<?php
			$form_list_table->prepare_items();
			$form_list_table->search_box( __( 'Search Forms', 'wc-product-addon-custom-field' ), 'contactum-form-search' );
			$form_list_table->views();
			$form_list_table->display();
		?>
	</form>
</div>