<?php
echo '<div id="'.esc_attr($this->prefix).'features_text">';
echo '<h2>'. esc_html__( 'Why upgrade to Premium Version?', 'rex-product-feed' ) .'</h2>';
echo '<ol class="parent">';
echo '<li class="item">' . esc_html__( 'Generate more than 200 products.', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Custom field support - Brand, GTIN, MPN, UPC, EAN, Size, Pattern, Material, etc.', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Manipulate product pricing.', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Fix WooCommerces (JSON-LD) structure data bug', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Access to an elite support team.', 'rex-product-feed' ) . '</li>';
echo '</ol>';

echo '<a class="btn" target="_blank" href="https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro">' . esc_html__( 'Upgrade to Pro', 'rex-product-feed' ) . '</a>';
echo '</div>';

