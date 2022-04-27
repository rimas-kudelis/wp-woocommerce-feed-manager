<div class="rex-contnet-filter__header">
	<div class="rex-contnet-setting__header-text">
		<div class="rex-contnet-setting__icon">
			<?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/icon-filter.php';?>
			<?php echo '<h2>' . esc_html__( "Product Filter", "rex-product-feed" ) . '</h2>';?>
		</div>
	</div>

    <div class="rex-feed-buttons">
        <?php do_action( 'rex_feed_before_filter_modal_close_button' );?>
        <span class="rex-contnet-filter__close-icon" id="rex_feed_filter_modal_close_btn">Close</span>
        <?php do_action( 'rex_feed_after_filter_modal_close_button' );?>
    </div>
</div>