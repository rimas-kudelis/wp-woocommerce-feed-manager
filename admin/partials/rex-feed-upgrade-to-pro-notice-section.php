<?php
echo '<div id="'.esc_attr($this->prefix).'features_text">';
echo '<h2>'. esc_html__( 'Why upgrade to Premium Version?', 'rex-product-feed' ) .'</h2>';
echo '<ol class="parent">';
echo '<li class="item">' . esc_html__( 'Supports more than 200 products.', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Access to a elite support team.', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Supports YITH brand attributes.', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Dynamic Attribute.', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Custom field support - Brand,GTIN,MPN,UPC,EAN,Size, Pattern, Material, Age Group, Gender.', 'rex-product-feed' ) . '</li>';
echo '<li class="item">' . esc_html__( 'Fix WooCommerce\'s (JSON-LD) structure data bug', 'rex-product-feed' ) . '</li>';
echo '</ol>';

echo '<a class="waves-effect waves-light btn" target="_blank" href="https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro">Upgrade to pro</a>';
echo '</div>';