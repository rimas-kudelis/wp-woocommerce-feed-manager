<?php
$troubleshoot_url = 'https://rextheme.com/docs/wpfm-troubleshooting-for-common-issues/';
?>
<!--<h2> --><?php //echo esc_html__( "Add New Feed", "rex-product-feed" )?><!-- </h2>-->
<a id="rex-feed-instruction-btn" class="rex-fill-button">
    <?php echo esc_html__( 'Instructions', 'rex-product-feed' )?>
    <span class="rex_feed-tooltip">
        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/icon-question.php';?>
        <p>
            <?php esc_html_e( '1) Select your preferred merchant from the feed merchant dropdown list<br>', 'rex-product-feed' )?>
            <?php esc_html_e( '2) Configure the feed attributes<br>', 'rex-product-feed' )?>
            <?php esc_html_e( '3) Click on Publish/Update button to start generating the feed', 'rex-product-feed' )?>
        </p>
    </span>
</a>
<div class="rex-feed-cofig-settings">
    <a id="rex-feed-troubleshoot-btn" class="rex-fill-button" href="<?php echo esc_url($troubleshoot_url);?>" target="_blank">
        <i class="fa fa-exclamation-triangle"></i>
        <?php echo esc_html__( 'Troubleshoot', 'rex-product-feed' )?>
    </a>
    <a id="rex-pr-filter-btn" class="rex-fill-button">
        <i class="fa fa-filter"></i>
        <?php echo esc_html__( 'Product Filter', 'rex-product-feed' )?>
    </a>
    <a id="rex-feed-settings-btn" class="rex-fill-button">
        <i class="fa fa-cog"></i>
        <?php echo esc_html__( 'Settings', 'rex-product-feed')?>
    </a>
</div>
