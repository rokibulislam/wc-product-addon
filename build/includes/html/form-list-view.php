<?php
/**
 * Form List View
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$url   = esc_url( add_query_arg( [ 'action'   => 'create_template' ], admin_url( 'admin.php' ) ) );
?>
<div class="wrap">
	<h2>
		<?php esc_html_e( 'Forms', 'product-addon-custom-field' );  ?>
		<a href="<?php echo esc_url( $add_new_page_url ); ?>" id="new-prafe-form" class="page-title-action add-form"><?php esc_html_e( 'Add Form', 'product-addon-custom-field' ); ?></a>
	</h2>
	<?php
		$form_list_table = new \PRAEF\Forms_List_Table();
	?>
	<form method="get">
		<input type="hidden" name="page" value="product-addon-custom-field">
		<?php wp_nonce_field('product_addon_form_list_action', 'product_addon_form_list_nonce'); ?>
		<?php
			$form_list_table->prepare_items();
			$form_list_table->views();
			$form_list_table->display();
		?>
	</form>
</div>