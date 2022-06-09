<?php
$active_plugins = get_option( 'active_plugins' );
if ( is_array( $active_plugins ) && !in_array('best-woocommerce-feed-pro/rex-product-feed-pro.php', $active_plugins ) ) {
    echo '<div id="' . esc_attr($this->prefix) . 'features_text">';
    echo '<h2>' . esc_html__('Premium Version Features', 'rex-product-feed') . '</h2>';
    echo '<ul class="parent">';

    echo '<li class="item">';
    include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/right-check.php';
    echo esc_html__('Generate more than 200 products.', 'rex-product-feed') .
        '</li>';

    echo '<li class="item">';
    include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/right-check.php';
    echo esc_html__('Custom field support - Brand, GTIN, MPN, UPC, EAN, Size, Pattern, Material, etc.', 'rex-product-feed') .
        '</li>';

    echo '<li class="item">';
    include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/right-check.php';
    echo esc_html__('Custom field support - Brand, GTIN, MPN, UPC, EAN, Size, Pattern, Material, etc.', 'rex-product-feed') .

        '</li>';

    echo '<li class="item">';
    include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/right-check.php';
    echo esc_html__('Manipulate product pricing.', 'rex-product-feed') .

        '</li>';

    echo '<li class="item">';
    include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/right-check.php';
    echo esc_html__('Fix WooCommerces (JSON-LD) structure data bug', 'rex-product-feed') .

        '</li>';

    echo '<li class="item">';
    include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/right-check.php';
    echo esc_html__('Access to an elite support team.', 'rex-product-feed') .
        '</li>';
    echo '</ul>';

    echo '<a class="btn" target="_blank" href="https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro">' . esc_html__('Upgrade to Pro', 'rex-product-feed') . '</a>';
    echo '</div>';
}
else {
    do_action( 'rex_feed_pro_features_overview' );
}