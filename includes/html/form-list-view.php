<?php
/**
 * Form List View
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */

	$url   = esc_url( add_query_arg( [ 'action'   => 'create_template' ], admin_url( 'admin.php' ) ) );
?>
<div class="wrap">
	<h2>
		<?php esc_html_e( 'Forms', 'product-addon-custom-field' );  ?>
		<a href="<?php echo esc_url( $add_new_page_url ); ?>" id="new-wcprafe-form" class="page-title-action add-form"><?php esc_html_e( 'Add Form', 'product-addon-custom-field' ); ?></a>
	</h2>
	<?php
		$form_list_table = new \WCPRAEF\Forms_List_Table();
	?>
	<form method="get">
		<input type="hidden" name="page" value="product_addon_custom_field">
		<?php
			$form_list_table->prepare_items();
			$form_list_table->search_box( __( 'Search Forms', 'product-addon-custom-field' ), 'wcprafe-form-search' );
			$form_list_table->views();
			$form_list_table->display();
		?>
	</form>
</div>