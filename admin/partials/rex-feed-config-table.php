<table id="config-table" class="responsive-table wpfm-field-mappings">
    <?php require_once plugin_dir_path( __FILE__ ) . '/loading-spinner.php';?>
</table>

<div id="rex-feed-footer-btn" class="rex-feed-footer-btn">
    <div class="rex-feed-attr-btn-area">
        <a id="rex-new-attr" class="rex-new-custom-btn">
            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/icon-plus.php';?>
            <?php echo esc_attr__( 'Add New Attribute', 'rex-product-feed' ) ?>
        </a>
        <a id="rex-new-custom-attr" class="rex-new-custom-btn">
            <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/icon-plus.php';?>
            <?php echo esc_attr__( 'Add New Custom Attribute', 'rex-product-feed' ) ?>
        </a>
    </div>

    <div class="rex-feed-publish-btn">
        <span class="spinner"></span>
        <a id="rex-bottom-preview-btn" class="rex-new-custom-btn bottom-preview-btn">
            <?php echo esc_attr__( 'Preview Feed', 'rex-product-feed' ) ?>
        </a>
        <a id="rex-bottom-publish-btn" class="rex-new-custom-btn bottom-publish-btn">
            <?php echo esc_attr__( 'Publish', 'rex-product-feed' ) ?>
        </a>
    </div>
</div>