<?php
$post_new_feed_url = 'post-new.php?post_type=product-feed&rex_feed_merchant=';
$generate_feed = 'Generate Feed';
$arrow_icon = 'icon/icon-svg/new-arrow.php';
$take_tour_icon = 'icon/icon-svg/take-tour.php';

$merchants = array(
    'google' => array(
        'name' => 'Google',
        'urls' => array(
            array(
                'text' => 'A Complete Guide To Google Shopping',
                'url' => 'https://rextheme.com/guide-to-woocommerce-product-feed/'
            ),
            array(
                'text' => 'How to generate WooCommerce product feed for Google',
                'url' => 'https://rextheme.com/docs/how-to-generate-woocommerce-product-feed-for-google/'
            ),
            array(
                'text' => 'How to Auto-sync product feed to Google Merchant shop',
                'url' => 'https://rextheme.com/docs/how-to-auto-sync-product-feed-to-google-merchant-shop/'
            ),
        ),
    ),
    'vivino' => array(
        'name' => 'Vivino',
        'urls' => array(
            array(
                'text' => 'How to generate WooCommerce product feed for Vivino',
                'url' => 'https://rextheme.com/guide-to-vivino-product-feed-woocommerce/'
            ),
        ),
    ),
    'glami' => array(
        'name' => 'Glami',
        'urls' => array(
            array(
                'text' => 'How to generate WooCommerce product feed for Glami',
                'url' => 'https://rextheme.com/glami-xml-feed-to-sell-fashion-products-woocommerce/'
            ),
        ),
    ),
    'facebook' => array(
        'name' => 'Facebook',
        'urls' => array(
            array(
                'text' => 'A Complete Guide To Facebook',
                'url' => 'https://rextheme.com/guide-to-woocommerce-product-feed/'
            ),
            array(
                'text' => 'How to generate WooCommerce product feed for Facebook',
                'url' => 'https://rextheme.com/docs/how-to-generate-woocommerce-product-feed-for-facebook/'
            ),
            array(
                'text' => 'How to upload your WooCommerce products on the Facebook store',
                'url' => 'https://rextheme.com/docs/how-to-upload-your-woocommerce-products-on-the-facebook-store/'
            ),
        ),
    ),
    'fruugo' => array(
        'name' => 'Fruugo',
        'urls' => array(
            array(
                'text' => 'How to generate WooCommerce product feed for Fruugo',
                'url' => 'https://rextheme.com/start-selling-on-fruugo-product-feed-for-woocommerce/'
            )
        ),
    ),
    'favi' => array(
        'name' => 'Favi',
        'urls' => array(
            array(
                'text' => 'How to generate WooCommerce product feed for Favi',
                'url' => 'https://rextheme.com/cz-generate-product-feed-for-favi-woocommerce/'
            ),
        ),
    ),
    'idealo' => array(
        'name' => 'Idealo',
        'urls' => array(
            array(
                'text' => 'How to List WooCommerce Store Products On Idealo',
                'url' => 'https://rextheme.com/list-woocommerce-store-products-on-idealo/'
            ),
        ),
    ),
    'ceneo' => array(
        'name' => 'Ceneo',
        'urls' => array(
            array(
                'text' => 'How to generate WooCommerce product feed for Ceneo',
                'url' => 'https://rextheme.com/sell-on-ceneo-pl-using-ceneo-xml-feed-woocommerce/'
            )
        ),
    ),
    'heureka' => array(
        'name' => 'Heureka',
        'urls' => array(
            array(
                'text' => 'How to generate WooCommerce product feed for Heureka',
                'url' => 'https://rextheme.com/generate-heureka-xml-feed-with-woocommerce-products/'
            ),
        ),
    ),
);
?>
<main class="rex-setup-wizard-area">
    <section class="rex-setup-wizard-hero-area">
        <div class="rex-setup-wizard__content">
            <button class="rex-setup-wizard__button" type="button">
                <a  href="<?php echo esc_url( admin_url( 'edit.php?post_type=product-feed' ) ); ?>" target="_self">
                    <?php esc_html_e('Back to Plugin Dashboard','rex-product-feed') ?>
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/Vector.php';?>
                </a>
            </button>

            <div class="rex-setup-wizard__content-layout">
                <div class="rex-setup-wizard__content-area">
                    <span><?php esc_html_e('Welcome to', 'rex-product-feed')?></span>
                    <h1><?php esc_html_e('Product Feed Manager for WooCommerce', 'rex-product-feed')?></h1>
                    <h6><?php esc_html_e("Select merchant and create feed", "rex-product-feed")?></h6>

                    <form class="rex-setup-wizard__search-from" role="search" method="GET" action="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>">
                        <input class="rex-setup-wizard__search-from__input" type="hidden" name="post_type" value="product-feed" placeholder="Select your merchant" aria-label="Search through site content">
                        <?php
                        $class = 'rex-setup-wizard-merchant-select2';
                        $id = 'rex_setup_wizard_merchant_select2';
                        $name = 'rex_feed_merchant';
                        Rex_Feed_Merchants::render_merchant_dropdown( $class, $id, $name, '-1' );
                        ?>
                        <button class="rex-setup-wizard__search-from__button" type="submit"><?php esc_html_e('Create Feed','rex-product-feed') ?></button>
                    </form>
                </div>
                <!-- rex-setup-wizard__content-area end -->
            <div class="box-video-area">
                <div class="box-video">
                    <div class="bg-video">
                        <div class="bt-play"></div>
                    </div>
                    <div class="video-container">
                        <iframe width="560" height="315"src="<?php echo esc_url( 'https://www.youtube.com/embed/videoseries?list=PLelDqLncNWcVoPA7T4eyyfzTF0i_Scbnq' ); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <div class="box-video__button">
                    
                    <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=product-feed&tour_guide=1' ) );?>" id="rex-feed-tour-start-btn" target="_self" role="button">
                        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $take_tour_icon;?>
                        <?php esc_html_e('Take Tour','rex-product-feed') ?>                   
                    </a>
                </div>
                <!-- rex-setup-wizard__video-area end -->
            </div>

        </div>
        <!-- rex-setup-wizard__content -->

    </section>
    <!-- .rex-setup-wizard-hero-area end -->

    <section class="rex-setup-wizard-feed-area">
        <div class="rex-setup-wizard__content">
            <div class="rex-setup-wizard-feed__header">
                <h3><?php esc_html_e("The best plugin to generate", "rex-product-feed")?></h3>
                <h3 class="header__text"><?php esc_html_e("WooCommerce Product Feed Manager", "rex-product-feed")?></h3>
            </div>

            <div class="rex-setup-wizard-feed__content-area rex-setup-wizard-feed__grid">

                <?php foreach ( $merchants as $key => $merchant ) { ?>
                <div class="rex-setup-wizard-feed__content rex-setup-wizard-feed__content_<?php echo $key;?>">

                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/'.$key.'.php';?>
                    <h6><?php esc_html_e( $merchant[ 'name' ], "rex-product-feed" )?></h6>

                    <ul class="rex-setup-wizard-feed__list-area">
                        <?php foreach ( $merchant[ 'urls' ] as $url ) { ?>
                        <li>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <a class="rex-setup-wizard-feed__list-link" href="<?php echo esc_url( $url[ 'url' ] ); ?>" target="_blank">
                                <?php echo esc_html__( $url[ 'text' ], 'rex-product-feed') ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                    <!-- .rex-setup-wizard-feed__list-area end -->
                    <?php $merchant_name = 'idealo' === $key ? 'idealo_de' : $key; ?>
                    <button class="rex-setup-wizard-feed__button" type="button">
                        <a  href="<?php echo esc_url( admin_url( $post_new_feed_url . $merchant_name ) ); ?>" target="_self">
                            <?php esc_html_e( $generate_feed,'rex-product-feed') ?>
                        </a>
                    </button>

                </div>
                <?php } ?>
                <!-- .rex-setup-wizard-feed__content end -->

            </div>

            <button class="rex-setup-wizard-feed-area__button" type="button">
                <a  href="<?php echo esc_url( admin_url( 'admin.php?page=wpfm_dashboard&tab=merchants' ) ); ?>" target="_blank">
                    <?php esc_html_e('View All Merchants','rex-product-feed') ?>
                </a>
            </button>
        </div>
        <!-- rex-setup-wizard__content end -->
    </section>
    <!-- .rex-setup-wizard-feed-area end -->

    <?php if ( !is_plugin_active( 'best-woocommerce-feed-pro/rex-product-feed-pro.php' ) ) { ?>
        <section class="rex-setup-wizard-price-area">
            <div class="rex-setup-wizard__content">
            <div class="rex-setup-wizard__contents-area">
                <div class="rex-setup-wizard-price__header">
                    <h3><?php esc_html_e('Upgrade to Pro to get access to our premium features', 'rex-product-feed')?></h3>
                </div>

                <div class="rex-setup-wizard-price__button-area">
                    <span><?php esc_html_e('Prices start at $79.99 ', 'rex-product-feed')?></span>
                    <button class="rex-setup-wizard-price__button wizard-btn" type="button">
                        <a  href="<?php echo esc_url( 'https://rextheme.com/best-woocommerce-product-feed/#pricing' ); ?>" target="_blank">
                            <?php esc_html_e('Get Pro Now','rex-product-feed') ?>
                        </a>
                    </button>
                </div>

                <ul class="rex-setup-wizard-price__list__layout">
                            
                    <li class="rex-setup-wizard-price__list__lists">
                        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $arrow_icon;?>
                        <?php esc_html_e('Use Product Filter feature to include/exclude specific products','rex-product-feed') ?>
                    </li>
                    <li class="rex-setup-wizard-price__list__lists">
                        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $arrow_icon;?>
                        <?php esc_html_e('Include all the required attributes in your feed (GTIN, MPN, EAN, UPC, etc)','rex-product-feed') ?>
                    </li>
                    <li class="rex-setup-wizard-price__list__lists">
                        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $arrow_icon;?>
                        <?php esc_html_e('Include detailed product attributes (Size, Pattern, Material, Gender, Color, etc)  ','rex-product-feed') ?>
                    </li>
                    <li class="rex-setup-wizard-price__list__lists">
                        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $arrow_icon;?>
                        <?php esc_html_e('Dynamic Pricing feature to manipulate your product pricing','rex-product-feed') ?>
                    </li>
                    <li class="rex-setup-wizard-price__list__lists">
                        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $arrow_icon;?>
                        <?php esc_html_e('Product data manipulation along with find & replace feature','rex-product-feed') ?>
                    </li>
                    <li class="rex-setup-wizard-price__list__lists">
                        <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . $arrow_icon;?>
                        <?php esc_html_e('Use eBay (MIP), Google Remarketing (DRM), Google Review, Leguide merchant templates','rex-product-feed') ?>
                    </li>
                            
                </ul>

            </div>
            <!-- .rex-setup-wizard__content end -->
            </div>

        </section>
    <?php } ?>
    <!-- .rex-setup-wizard-price-area end -->

    <section class="rex-setup-wizard-cta-area">
        <div class="rex-setup-wizard__content">
            <h2><?php esc_html_e('Boost your ROI with the largest marketplaces', 'rex-product-feed')?></h2>

            <div class="rex-setup-wizard-cta__button-area">
                <button class="rex-setup-wizard-cta__button wizard-btn rex-setup-wizard-cta__button--light-blue" type="button">
                    <a  href="<?php echo esc_url( 'https://rextheme.com/best-woocommerce-product-feed/?setup-wizard-support=1' ); ?>" target="_blank">
                        <?php esc_html_e('Our Support','rex-product-feed') ?>
                    </a>
                </button>
                
                <button class="rex-setup-wizard-cta__button wizard-btn" type="button">
                    <a  href="<?php echo esc_url( 'https://rextheme.com/docs-category/product-feed-manager/' ); ?>" target="_blank">
                        <?php esc_html_e('Documentation','rex-product-feed') ?>
                    </a>
                </button>
            </div>
            <!-- .rex-setup-wizard-cta__button-area end -->

        </div>

        <!-- .rex-setup-wizard__content end -->

    </section>
    <!-- .rex-setup-wizard-cta-area end -->

</main>
<!-- rex-setup-wizard-area -->