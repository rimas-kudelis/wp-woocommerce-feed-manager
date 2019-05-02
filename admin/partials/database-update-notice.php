<?php ?>


<div id="wpfm-message" class="updated notice">
    <p>
        <strong><?php esc_html_e( 'It is required to update database', 'rex-product-feed' ); ?></strong>
    </p>
    <p>
        <?php
            esc_html_e( 'WooCommerce Product Feed Manager has been updated! To keep things running smoothly, we have to update your database to the newest version.', 'rex-product-feed' );
            esc_html_e( 'The database update process runs in the background and may take a little while, so please be patient. It will not remove any of your data, just restructured your woocommerce feed manager related data', 'rex-product-feed' );
        ?>
    </p>
    <p class="submit">
        <a href="#" class="button-primary rex-wpfm-update-db" id="rex-wpfm-update-db">
            <?php esc_html_e( 'Update WPFM Database', 'rex-product-feed' ); ?>
        </a>

        <img src="<?php echo WPFM_PLUGIN_DIR_URL. '/admin/icon/loader.gif'?>" class="wpfm-db-update-loader">
    </p>
</div>