<?php
/**
 * Admin notice
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
    <div class="notice wcprafe-admin-notices-wrap">
        <div class="wcprafe-admin-notice wcprafe-alert">
            <div class="notice-content">
                <div class="wcprafe-message">
                    <h3><?php esc_html_e( 'WooCommerce Missing', 'product-addon-custom-field' ); ?></h3>
                    <?php
                    $install_url = wp_nonce_url(
                        add_query_arg(
                            [
                                'action' => 'install-plugin',
                                'plugin' => 'woocommerce',
                            ], admin_url( 'update.php' )
                        ), 'install-plugin_woocommerce'
                    );

                    $text = "test app";

                    // translators: 1$-2$: opening and closing <strong> tags, 3$-4$: link tags, takes to woocommerce plugin on wp.org, 5$-6$: opening and closing link tags, leads to plugins.php in admin
                    $text = sprintf( 
                    	esc_html__( 
                    		'%1$s Product Addon Custom Field  is inactive %2$s The %3$s WooCommerce plugin %s must be active to work. Please 
                    		%s install WooCommerce &raquo; %s', 
                    		'product-addon-custom-field' 
                    	), 
                    	'<strong>', 
                    	'</strong>', 
                    	'<a href="https://wordpress.org/plugins/woocommerce/">', 
                    	'</a>', 
                    	'<a href="' . esc_url( $install_url ) . '">', 
                    	'</a>' 
                    );
                    ?>
                    <div><?php echo wp_kses_post( $text ); ?></div>
                </div>
            </div>
        </div>
    </div>
