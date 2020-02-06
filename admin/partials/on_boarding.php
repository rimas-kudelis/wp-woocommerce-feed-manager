<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is display the on boarding page
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Rex_Product_Feed
 * @subpackage Rex_Product_Feed/admin/partials
 */


$is_premium = apply_filters('wpfm_is_premium', false);


$is_premium_activated = apply_filters('wpfm_is_premium_activate', false);
$custom_field = get_option('rex-wpfm-product-custom-field');
$pa_field = get_option('rex-wpfm-product-pa-field');
$structured_data = get_option('rex-wpfm-product-structured-data');
$exclude_tax = get_option('rex-wpfm-product-structured-data-exclude-tax');
$per_batch = get_option('rex-wpfm-product-per-batch', 50);

?>

<div class="columns">
    <div class="column">
        <div class="rex-onboarding">
            <div class="premium-merchant-alert">
                <div class="alert-box">
                    <a class="delete is-large close"></a>
                    <i class="fa fa-warning warning"></i>
                    <h2><?php echo __('Go Premium', 'rex-product-feed')?></h2>
                    <p>Purchase our <a href="">premium version</a> to unlock these pro components!</p>
                    <button type="button" class="close">ok</button>
                </div>
            </div>

            <div class="rex-settings-tab-wrapper">
                <ul class="rex-settings-tabs">
                    <li class="tab-link active" data-tab="tab1"><i class="fa fa-cog"></i>General</li>
                    <li class="tab-link" data-tab="tab2"><i class="fa fa-shopping-cart"></i>Merchants</li>
                    <li class="tab-link" data-tab="tab4"><i class="fa fa-cogs"></i>Controls</li>
                    <li class="tab-link" data-tab="tab3"><i class="fa fa-video-camera"></i>Video Tutorials</li>
                    <li class="tab-link" data-tab="tab5"><i class="fa fa-info-circle"></i>System Status</li>
                    <?php
                    if ( !$is_premium_activated ) {?>
                        <li class="tab-link" data-tab="tab6"><i class="fa fa-gift"></i>Go Premium</li>
                    <?php }
                    ?>
                    <li class="tab-link" data-tab="tab7"><i class="fa fa-question-circle"></i>Logs</li>
                </ul>

                <div class="rex-settings-tab-content">
                    <div id="tab1" class="tab-content active block-wrapper">
                        <div class="general">
                            <div class="left">
                                <div class="single-block-wrapper">
                                    <div class="single-block banner-block">
                                        <div class="onboarding-block">
                                            <img src="<?php echo WPFM_PLUGIN_DIR_URL . 'admin/icon/banner.png'?>" alt="rex-banner">
                                        </div>
                                    </div>

                                    <div class="single-block">
                                        <div class="onboarding-block">
                                            <div class="header">
                                                <img src="<?php echo WPFM_PLUGIN_DIR_URL . 'admin/icon/Document.png'?>" class="title-icon" alt="bwf-documentation">
                                                <h4><?php echo __('Documentation', 'rex-product-feed')?></h4>
                                            </div>

                                            <div class="body">
                                                <p>
                                                    <?php echo __('Before You start, you can check our Documentation to get familiar with WooCommerce Product Feed Manager.', 'rex-product-feed')?>
                                                </p>

                                                <a class="btn-default" href="https://www.youtube.com/channel/UCf-NabV2v7DGN8MxQNrxkmw" target="_blank"><?php echo __('View Documentation', 'rex-product-feed')?></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-block">
                                        <div class="onboarding-block">
                                            <div class="header">
                                                <img src="<?php echo WPFM_PLUGIN_DIR_URL . 'admin/icon/Support.png'?>" class="title-icon" alt="bwf-documentation">
                                                <h4>Support</h4>
                                            </div>

                                            <div class="body">
                                                <p>
                                                    <?php echo __('Can\'t find solution on with our documentation? Just Post a ticket on Support forum. We are to solve your issue.', 'rex-product-feed')?>
                                                </p>

                                                <a class="btn-default" href="<?php echo apply_filters('wpfm_support_link', 'https://wordpress.org/support/plugin/best-woocommerce-feed'); ?>" target="_blank">Post a Ticket</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-block">
                                        <div class="onboarding-block">
                                            <div class="header">
                                                <img src="<?php echo WPFM_PLUGIN_DIR_URL . 'admin/icon/Feedback.png'?>" class="title-icon" alt="bwf-documentation">
                                                <h4><?php echo __('Share Your Thoughts', 'rex-product-feed')?></h4>
                                            </div>

                                            <div class="body">
                                                <p>
                                                    <?php echo __('Your suggestions are valubale to us. It can help to make WPFM even better.', 'rex-product-feed')?>
                                                </p>

                                                <a class="btn-default" href="http://openvoyce.com/products/bwf" target="_blank"><?php echo __('Suggest', 'rex-product-feed')?></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-block">
                                        <div class="onboarding-block">
                                            <div class="header">
                                                <img src="<?php echo WPFM_PLUGIN_DIR_URL . 'admin/icon/Rating.png'?>" class="title-icon" alt="bwf-documentation">
                                                <h4><?php echo __('Make WPFM Popular', 'rex-product-feed')?></h4>
                                            </div>

                                            <div class="body">
                                                <p>
                                                    <?php echo __('Your rating and feedback matters to us. If you are happy with WooCommerce Product Feed Manager give us a rating.', 'rex-product-feed')?>

                                                </p>

                                                <a class="btn-default" href="<?php echo apply_filters('wpfm_review_link', 'https://wordpress.org/support/plugin/best-woocommerce-feed/reviews/#new-post') ?>" target="_blank"><?php echo __('Rate Us!', 'rex-product-feed')?> </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-block">
                                        <div class="onboarding-block">
                                            <div class="header">
                                                <img src="<?php echo WPFM_PLUGIN_DIR_URL . 'admin/icon/Heart.png'?>" class="title-icon" alt="bwf-documentation">
                                                <h4>Share On</h4>
                                            </div>

                                            <div class="body">
                                                <ul class="social">
                                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=https%3A//wordpress.org/plugins/best-woocommerce-feed/" target="_blank"><?php echo __('Share on Facebook', 'rex-product-feed')?></a></li>
                                                    <li><a href="https://twitter.com/home?status=https%3A//wordpress.org/plugins/best-woocommerce-feed/" target="_blank"><?php echo __('Share on Twitter', 'rex-product-feed')?></a></li>
                                                    <li><a href="https://plus.google.com/share?url=https%3A//wordpress.org/plugins/best-woocommerce-feed/" target="_blank"><?php echo __('Share on Google+', 'rex-product-feed')?></a></li>
                                                    <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=https%3A//wordpress.org/plugins/best-woocommerce-feed/&title=Best%20WooCommerce%20Product%20Feed%20Manager&summary=&source=" target="_blank"><?php echo __('Share on LinkedIn', 'rex-product-feed')?></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="right upgrade">
                                <?php
                                if ( !$is_premium_activated ) {?>
                                    <div class="wpfm-pro-features rex-upgrade">
                                        <h4 class="title">Why upgrade to Pro?</h4>
                                        <ul>
                                            <li class="items">Supports unlimited products.</li>
                                            <li class="items">Access to a elite support team.</li>
                                            <li class="items">Supports YITH brand attributes.</li>
                                            <li class="items">Dynamic Attribute.</li>
                                            <li class="items">Custom field support - Brand,GTIN,MPN,UPC,EAN,Size, Pattern, Material, Age Group, Gender </li>
                                            <li class="items">Fix WooCommerce's (JSON-LD) structure data bug </li>
                                        </ul>
                                    </div>
                                    <a href="https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro" class="update-btn btn-default" target="_blank">Upgrade to Pro</a>
                                <?php }
                                ?>
                            </div>
                        </div>
                        <!--/columns-->
                    </div>

                    <div id="tab2" class="tab-content block-wrapper">
                        <div class="rex-merchant">
                            <h3 class="merchant-title"><?php echo __('Available Merchants', 'rex-product-feed')?></h3>
                            <?php
                            // free vs pro merchants
                            $_merchants = array(
                                'custom'       => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Custom'
                                ),
                                'google'       => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Google Shopping'
                                ),
                                'google_Ad'    => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Google AdWords'
                                ),
                                'google_local_products'    => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Google Local Products'
                                ),
                                'google_local_products_inventory'    => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Google Local Products Inventory'
                                ),
                                'google_merchant_promotion'    => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Google Merchant Promotion Feed'
                                ),
                                'google_dsa'    => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Google Dynamic Search Ads'
                                ),
                                'facebook'     => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Facebook'
                                ),
                                'instagram'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Instagram (by Facebook)'
                                ),
                                'amazon'       => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Amazon'
                                ),
                                'adroll'       => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'AdRoll'
                                ),
                                'nextag'       => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Nextag'
                                ),
                                'pricegrabber' => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Pricegrabber'
                                ),
                                'bing'         => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Bing'
                                ),
                                'kelkoo'       => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Kelkoo'
                                ),
                                'become'       => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Become'
                                ),
                                'shopzilla'    => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'ShopZilla'
                                ),
                                'shopping'     => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Shopping'
                                ),
                                'pricerunner'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'PriceRunner'
                                ),
                                'billiger'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Billiger'
                                ),
                                'vergelijk'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Vergelijk'
                                ),
                                'marktplaats'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Marktplaats'
                                ),
                                'beslist'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Beslist'
                                ),
                                'daisycon'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Daisycon'
                                ),
                                'twenga'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Twenga'
                                ),
                                'kieskeurig'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Kieskeurig.nl'
                                ),
                                'yandex'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Yandex'
                                ),
                                'spartoo'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Spartoo.nl'
                                ),
                                'tweakers'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Tweakers.nl'
                                ),
                                'sooqr'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Sooqr'
                                ),
                                'heureka'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Heureka'
                                ),
                                'koopkeus'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Koopkeus'
                                ),
                                'scoupz'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Scoupz'
                                ),
                                'cdiscount'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Cdiscount'
                                ),
                                'kelkoonl'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Kelkoo.nl'
                                ),
                                'uvinum'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Uvinum / DrinsksAndCo'
                                ),
                                'idealo'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Idealo'
                                ),
                                'rakuten'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Rakuten'
                                ),
                                'pricesearcher'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Pricesearcher'
                                ),
                                'pricemasher'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Pricemasher'
                                ),
                                'pinterest'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Pinterest'
                                ),
                                'fashionchick'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Fashionchick'
                                ),
                                'ceneo'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Ceneo'
                                ),
                                'choozen'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Choozen'
                                ),
                                'rss'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'RSS'
                                ),
                                'ciao'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Ciao'
                                ),
                                'prisjkat'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Pricespy/Prisjkat'
                                ),
                                'crowdfox'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Crowdfox'
                                ),
                                'powerreviews'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'PowerReviews'
                                ),
                                'trovaprezzi'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Trovaprezzi'
                                ),
                                'zbozi'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Zbozi'
                                ),
                                'liveintent'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'LiveIntent'
                                ),
                                'skroutz'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Skroutz'
                                ),
                                'otto'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Otto'
                                ),
                                'sears'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Sears'
                                ),
                                'ammoseek'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'AmmoSeek'
                                ),
                                'fnac'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Fnac'
                                ),
                                'zalando'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Zalando'
                                ),
                                'pixmania'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Pixmania'
                                ),
                                'coolblue'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Coolblue'
                                ),
                                'shopmania'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'ShopMania'
                                ),
                                'kleding'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Kleding'
                                ),
                                'ladenzeile'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Ladenzeile'
                                ),
                                'preis'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Preis'
                                ),
                                'winesearcher'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Winesearcher'
                                ),
                                'walmart'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Walmart'
                                ),
                                'snapchat'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Snapchat'
                                ),
                                'verizon'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Yahoo/Verizon Dynamic Product Ads'
                                ),
                                'kelkoo_group'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Kelkoo Group'
                                ),
                                'target'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Target'
                                ),
                                'pepperjam'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Pepperjam'
                                ),
                                'cj_affiliate'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'CJ Affiliate'
                                ),
                                'guenstiger'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Guenstiger'
                                ),
                                'hood'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Hood'
                                ),
                                'livingo'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Livingo'
                                ),
                                'jet'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Jet'
                                ),
                                'bonanza'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Bonanza'
                                ),
                                'adcell'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Adcell'
                                ),
                                'adform'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Adform'
                                ),
                                'stylefruits'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Stylefruits'
                                ),
                                'medizinfuchs'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Medizinfuchs'
                                ),
                                'moebel'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Moebel'
                                ),
                                'restposten'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Restposten'
                                ),
                                'sparmedo'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Sparmedo'
                                ),
                                'whiskymarketplace'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Whiskymarketplace'
                                ),
                                'newegg'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'NewEgg'
                                ),
//                                '123i'     => array(
//                                    'free'  => true,
//                                    'status'    => 0,
//                                    'name'  => '123I'
//                                ),
//                                'adcrowd'     => array(
//                                    'free'  => true,
//                                    'status'    => 0,
//                                    'name'  => 'Adcrowd'
//                                ),
                                'bikeexchange'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Bike Exchange'
                                ),
                                'cenowarka'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Cenowarka'
                                ),
                                'cezigue'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Cezigue'
                                ),
                                'check24'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Check24'
                                ),
                                'clang'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Clang'
                                ),
                                'cherchons'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Cherchons'
                                ),
                                'boetiek'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Boetiek B.V'
                                ),
                                'comparer'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Comparer'
                                ),
                                'converto'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Converto'
                                ),
                                'coolshop'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Coolshop'
                                ),
                                'commerce_connector'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Commerce Connector'
                                ),
                                'everysize'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Everysize'
                                ),
                                'encuentraprecios'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Encuentraprecios'
                                ),
                                'geizhals'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Geizhals'
                                ),
                                'geizkragen'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Geizkragen'
                                ),
                                'giftboxx'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Giftboxx'
                                ),
                                'go_banana'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Go Banana'
                                ),
                                'goed_geplaatst'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Goed Geplaatst'
                                ),
                                'grosshandel'     => array(
                                    'free'  => true,
                                    'status'    => 0,
                                    'name'  => 'Grosshandel'
                                ),
                            );
                                
                            $_pro_merchants = array(
                                'ebay_mip'     => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'eBay (MIP)'
                                ),
                                'ebay_seller'     => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'eBay Seller Center'
                                ),
                                'bol'       => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'Bol.com'
                                ),
                                'wish'       => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'Wish.com'
                                ),
                                'fruugo'       => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'Fruugo'
                                ),
                                'leguide'       => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'Leguide'
                                ),
                                'connexity'       => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'Connexity'
                                ),
                                'drm'     => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'Google Remarketing (DRM)'
                                ),
                                'google_review'     => array(
                                    'free'  => false,
                                    'status'    => 0,
                                    'name'  => 'Google Review'
                                )

                            );
                            $_merchants = array_merge($_merchants, $_pro_merchants);

                            $merchants = get_option('rex_wpfm_merchant_status');
                            if($merchants) {
                                $_merchants = array_merge($_merchants, $merchants);
                            }

                            if(!$is_premium) {
                                $_merchants = array_merge($_merchants, $_pro_merchants);
                            }


                            /**
                             * result of bad planning
                             */
                            $_merchants['google']['name'] = 'Google Shopping';
                            $_merchants['google_Ad']['name'] = 'Google AdWords';

                            $_merchants['drm']['name'] = 'Google Remarketing (DRM)';


                            ?>
                            <?php foreach ($_merchants as $key => $merchant): ?>
                                <?php if($key && $key != 'undefined'): ?>
                                    <div class="single-merchant">
                                        <span class="title"><?php echo $merchant['name']; ?></span>
                                        <?php
                                        $checked = $merchant['status'] ? 'checked' : '';
                                        $is_free = $merchant['free'] ? true : false;
                                        $name = $merchant['name'] ;
                                        if($is_premium) {
                                            $disabled = '';
                                        }else {
                                            if( $merchant['free']) {
                                                $disabled = '';
                                            }else {
                                                $disabled = 'disabled';
                                            }

                                        }
                                        ?>
                                        <div class="switch <?php echo $disabled; ?>" >
                                            <div class="wpfm-switcher">
                                                <input class="switch-input" type="checkbox" <?php echo $checked; ?> <?php echo $disabled; ?> id="switcher-<?php echo strtolower($key); ?>" data-value="<?php echo $key; ?>" data-is-free="<?php echo $is_free; ?>" data-name="<?php echo ucfirst($name); ?>">
                                                <label class="lever" for="switcher-<?php echo strtolower($key); ?>"></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!--/merchant tab-->

                    <div id="tab3" class="tab-content block-wrapper">
                        <div class="video-container">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLelDqLncNWcVoPA7T4eyyfzTF0i_Scbnq" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                    </div>

                    <div id="tab4" class="tab-content block-wrapper">
                        <div class="rex-merchant feed-settings">
                            <h3 class="merchant-title"><?php echo __('Controls', 'rex-product-feed'); ?> </h3>

                            <div class="single-merchant">
                                <span class="title"><?php echo __('Product(s) per batch', 'rex-product-feed'); ?></span>
                                <div class="switch">
                                    <form id="wpfm-per-batch" class="wpfm-per-batch">
                                        <input id="wpfm_product_per_batch" type="number" name="wpfm_product_per_batch" value="<?php echo $per_batch; ?>" min="1" <?php echo !$is_premium ?  "max='50'" : ''?>>
                                        <button type="submit" class="save-batch"><span>save</span> <i class="fa fa-spinner fa-pulse fa-fw"></i></button>
                                    </form>
                                </div>
                            </div>

                            <div class="single-merchant">
                                <span class="title"><?php echo __('Clear batch', 'rex-product-feed'); ?></span>
                                <div class="switch">
                                    <button class="wpfm-clear-batch" id="wpfm-clear-batch"><span>Clear</span> <i class="fa fa-spinner fa-pulse fa-fw"></i></button>
                                </div>
                            </div>

                            <div class="single-merchant">
                                <span class="title">
                                    <?php echo __('Increase the number of products that will be approved in Google\'s Merchant Center:
                                           This option will fix WooCommerce\'s (JSON-LD) structured data bug and add extra structured data elements to your pages', 'rex-product-feed'); ?>
                                </span>
                                <div class="switch">
                                    <?php
                                    if(!$is_premium) {
                                        $disabled = 'disabled';
                                        $checked = '';
                                    }else {
                                        $disabled = '';
                                        $checked = $structured_data === 'yes' ? 'checked': '';
                                    }
                                    ?>
                                    <div class="wpfm-switcher <?php echo $disabled; ?>">
                                        <input class="switch-input" type="checkbox" id="rex-product-structured-data" <?php echo $checked; ?> <?php echo $disabled; ?>>
                                        <label class="lever" for="rex-product-structured-data"></label>
                                    </div>
                                </div>
                            </div>


                            <div class="single-merchant">
                                <span class="title">
                                    <?php echo __('Exclude TAX from structured data prices', 'rex-product-feed'); ?>
                                </span>
                                <div class="switch">
                                    <?php
                                    if(!$is_premium) {
                                        $disabled = 'disabled';
                                        $checked = '';
                                    }else {
                                        $disabled = '';
                                        $checked = $exclude_tax === 'yes' ? 'checked': '';
                                    }
                                    ?>
                                    <div class="wpfm-switcher <?php echo $disabled; ?>">
                                        <input class="switch-input" type="checkbox" id="rex-product-exclude-tax" <?php echo $checked; ?> <?php echo $disabled; ?>>
                                        <label class="lever" for="rex-product-exclude-tax"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="single-merchant">
                                <span class="title"><?php echo __('Add Unique Product Identifiers ( Brand, GTIN, MPN, UPC, EAN, JAN, ISBN, ITF14, Offer price, Offer effective date ) to product', 'rex-product-feed'); ?></span>
                                <div class="switch">
                                    <?php
                                    if(!$is_premium) {
                                        $disabled = 'disabled';
                                        $checked = '';
                                    }else {
                                        $disabled = '';
                                        $checked = $custom_field === 'yes' ? 'checked': '';
                                    }
                                    ?>
                                    <div class="wpfm-switcher <?php echo $disabled; ?>">
                                        <input class="switch-input" type="checkbox" id="rex-product-custom-field" <?php echo $checked; ?> <?php echo $disabled; ?>>
                                        <label class="lever" for="rex-product-custom-field"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="single-merchant">
                                <span class="title"><?php echo __('Add Detailed Product Attributes ( Size, Pattern, Material, Age group, Gender ) to product', 'rex-product-feed'); ?></span>
                                <div class="switch">
                                    <?php
                                    if(!$is_premium) {
                                        $disabled = 'disabled';
                                        $checked = '';
                                    }else {
                                        $disabled = '';
                                        $checked = $pa_field === 'yes' ? 'checked': '';
                                    }
                                    ?>
                                    <div class="wpfm-switcher <?php echo $disabled; ?>">
                                        <input class="switch-input" type="checkbox" id="rex-product-pa-field" <?php echo $checked; ?> <?php echo $disabled; ?>>
                                        <label class="lever" for="rex-product-pa-field"></label>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <!--/settings tab-->

                    <div id="tab5" class="tab-content block-wrapper">
                        <div class="system-status">
                            <h3 class="title"><?php echo __('System Status', 'rex-product-feed'); ?></h3>
                            <?php

                            $path  = wp_upload_dir();
                            $path  = $path['basedir'] . '/rex-feed';
                            if (is_writable($path)) {
                                $isWritable = "True";
                            } else {
                                $isWritable = "False";
                            }



                            $status = array(
                                'php_version'           =>  phpversion(),
                                'php_version_status'    =>  version_compare(phpversion(),  "5.6", ">="),
                                'wp_version'            =>  get_bloginfo('version'),
                                'wp_version_status'     =>  get_bloginfo('version') >= 4,
                                'wc_version'            =>  WC()->version,
                                'wc_version_status'     =>  WC()->version >= 3.4,
                                'memory'                =>  ini_get('memory_limit'),
                                'memory_status'         =>  preg_replace('/[^0-9]/', '', ini_get('memory_limit')) >= 64, // 64M
                                'upload_limit'          =>  ini_get('upload_max_filesize'),
                                'upload_limit_status'   =>  preg_replace('/[^0-9]/', '', ini_get('upload_max_filesize')) >= 64, // 64M,
                                'wp_cron'               =>  !( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ),
                                'feed_directory'        =>  $path,
                                'is_writable'           =>  $isWritable,
                            );
                            ?>
                            <table class="wpfm_status_table widefat" id="status" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td><?php echo __('PHP version:', 'rex-product-feed'); ?></td>
                                    <td>
                                        <?php
                                        if ( $status['php_version_status'] ) {
                                            echo '<mark class="yes"><span class="dashicons dashicons-yes"></span>'.$status['php_version'].'</mark> ';
                                        } else {
                                            echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend using PHP version 7.2 or above for greater performance and security.', 'rex-product-feed' ), esc_html( $status['php_version'] )) . '</mark>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('WordPress version:', 'rex-product-feed'); ?></td>
                                    <td>
                                        <?php
                                        if ( $status['wp_version_status'] ) {
                                            echo '<mark class="yes"><span class="dashicons dashicons-yes"></span>'.$status['wp_version'].'</mark> ';
                                        } else {
                                            echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - WP version above 4 is required. ', 'rex-product-feed' ), esc_html( $status['wp_version'] )) . '</mark>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('WooCommerce Version:', 'rex-product-feed'); ?></td>
                                    <td>
                                        <?php
                                        if ( $status['wc_version_status'] ) {
                                            echo '<mark class="yes"><span class="dashicons dashicons-yes"></span>'.$status['wc_version'].'</mark> ';
                                        } else {
                                            echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - WC version above 4 is required. ', 'rex-product-feed' ), esc_html( $status['wc_version'] )) . '</mark>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Memory:', 'rex-product-feed'); ?></td>
                                    <td>
                                        <?php
                                        if ( $status['memory_status'] ) {
                                            echo '<mark class="yes"><span class="dashicons dashicons-yes"></span>'.$status['memory'].'</mark> ';
                                        } else {
                                            echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend setting memory to at least 64MB. See: %2$s', 'rex-product-feed' ), esc_html( $status['memory'] ), '<a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">' . esc_html__( 'Increasing memory allocated to PHP', 'rex-product-feed' ) . '</a>' ) . '</mark>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Upload Limit:', 'rex-product-feed'); ?></td>
                                    <td>
                                        <?php
                                        if ( $status['upload_limit_status'] ) {
                                            echo '<mark class="yes"><span class="dashicons dashicons-yes"></span>'.$status['upload_limit'].'</mark> ';
                                        } else {
                                            echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend upload limit to at least 64MB.', 'rex-product-feed' ), esc_html( $status['upload_limit'] ) . '</a>' ) . '</mark>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('WP Cron', 'rex-product-feed'); ?></td>
                                    <td>
                                        <?php
                                        if ( $status['wp_cron'] ) {
                                            echo '<mark class="yes"><span class="dashicons dashicons-yes"></span>Enable</mark> ';
                                        } else {
                                            echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( 'Disable - Cron should be enabled') . '</a>' . '</mark>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('Product Feed Directory Writable', 'rex-product-feed'); ?></td>
                                    <td>
                                        <?php
                                        if ( $status['is_writable'] ) {
                                            echo '<mark class="yes"><span class="dashicons dashicons-yes"></span> <code class="private">' . esc_html( $status['feed_directory'] ) . '</code></mark> ';
                                        } else {
                                            echo '<mark class="error"><span class="dashicons dashicons-warning"></span>  ' . sprintf( esc_html__( 'It is required to make feed directory writable. %1$s', 'rex-product-feed' ), '<code>' . esc_html( $status['feed_directory'] ) . '</code>', '<code>Feed Directory</code>' ) . '</mark>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>


                        </div>
                    </div>

                    <?php
                    if ( !$is_premium_activated ) {?>
                        <div id="tab6" class="tab-content block-wrapper">
                            <div class="upgrade">
                                <div class="rex-upgrade wpfm-pro-features">
                                    <h4 class="title">Why upgrade to Pro?</h4>
                                    <ul>
                                        <li class="item">Supports unlimited products.</li>
                                        <li class="item">Access to a elite support team.</li>
                                        <li class="item">Supports YITH brand attributes.</li>
                                        <li class="item">Dynamic Attribute.</li>
                                        <li class="item">Custom field support - Brand,GTIN,MPN,UPC,EAN,Size, Pattern, Material, Age Group, Gender </li>
                                        <li class="item">Fix WooCommerce's (JSON-LD) structure data bug </li>
                                    </ul>
                                    <a href="https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro" target="_blank" class="btn-default">Get Premium Version</a>
                                </div>
                            </div>
                        </div>
                    <?php }
                    ?>
                    <div id="tab7" class="tab-content block-wrapper">
                        <?php
                            $logs = WC_Admin_Status::scan_log_files();
                            $wpfm_logs = array();
                            $pattern = '/^wpfm|fatal/';
                            foreach($logs as $key => $value) {
                                if (preg_match($pattern,$key)){
                                    $wpfm_logs[$key] = $value;
                                }
                            }
                            echo '<form id="wpfm-error-log-form" action="'.esc_url( admin_url( 'admin.php?page=wpfm_dashboard' ) ).'" method="post">';
                                echo '<select id="wpfm-error-log" name="wpfm-error-log">';
                                    echo '<option value="">Please Select</option>';
                                    foreach($wpfm_logs as $key => $value) {
                                        echo '<option value="'.$value.'">'.$value.'</option>';
                                    }
                                echo '<select>';
                                echo '<button type="submit" class="btn-default">'.__('View log', 'rex-product-feed').'</button>';
                            echo '</form>';

                            echo '<div id="log-viewer">';
                            echo '<button id="wpfm-log-copy" class="btn-default" style="display: none"> <i class="fa fa-files-o"></i>'.__('Copy log', 'rex-product-feed').'</button>';
                            echo '<pre id="wpfm-log-content"></pre>';
                            echo '</div>';

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>