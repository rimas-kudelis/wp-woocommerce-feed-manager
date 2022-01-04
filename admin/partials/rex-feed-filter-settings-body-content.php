<?php
$troubleshoot_url = 'https://rextheme.com/docs/wpfm-troubleshooting-for-common-issues/';
?>
<!--<h2> --><?php //echo __( "Add New Feed", "rex-product-feed" )?><!-- </h2>-->
<a id="rex-feed-instruction-btn" class="rex-fill-button">
    <?php echo __( 'Instructions', 'rex-product-feed' )?>
    <span class="rex_feed-tooltip">
        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/icon-question.php';?>
        <p>
            <?php _e( '1) Select your preferred merchant from the feed merchant dropdown list<br>', 'rex-product-feed' )?>
            <?php _e( '2) Configure the feed attributes<br>', 'rex-product-feed' )?>
            <?php _e( '3) Click on Publish/Update button to start generating the feed', 'rex-product-feed' )?>
        </p>
    </span>
</a>
<div class="rex-feed-cofig-settings">
    <a id="rex-feed-troubleshoot-btn" class="rex-fill-button" href="' . $troubleshoot_url . '" target="_blank">
        <i class="fa fa-exclamation-triangle"></i>
        <?php echo __( 'Troubleshoot', 'rex-product-feed' )?>
    </a>
    <a id="rex-pr-filter-btn" class="rex-fill-button">
        <i class="fa fa-filter"></i>
        <?php echo __( 'Product Filter', 'rex-product-feed' )?>
    </a>
    <a id="rex-feed-settings-btn" class="rex-fill-button">
        <i class="fa fa-cog"></i>
        <?php echo __( 'Settings', 'rex-product-feed')?>
    </a>
</div>
