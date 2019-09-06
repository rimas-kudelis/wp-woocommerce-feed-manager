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

?>

<div class="row">
    <div class="rex-onboarding">
        <div class="premium-merchant-alert">
            <div class="alert-box">
                <i class="material-icons close">close</i>
                <i class="material-icons warning">warning</i>
                <h3><?php echo __('Go Premium', 'rex-product-feed')?></h3>
                <p>Purchase our <a href="">premium version</a> to unlock these pro components!</p>
                <button type="button" class="close">ok</button>
            </div>
        </div>

        <div class="left">
            <div class="wrapper">
                <div class="col s12 no-pd">
                    <ul class="tabs tabs-icon rex-tabs">
                        <li class="tab"><a href="#tab1" class="active"><i class="material-icons">settings</i>General</a></li>
                        <li class="tab"><a href="#merchant"><i class="material-icons">shopping_cart</i>Merchants</a></li>
                        <li class="tab"><a href="#settings"><i class="material-icons">settings</i>Controls</a></li>
                        <li class="tab"><a href="#tab2"><i class="material-icons">perm_media</i>Video Tutorials</a></li>
                        <li class="tab"><a href="#system_status"><i class="material-icons">report</i>System Status</a></li>
                        <?php
                            if ( !$is_premium_activated ) {?>
                                <li class="tab"><a href="#tab3"><i class="material-icons">thumb_up_alt</i>Go Premium</a></li>
                            <?php }
                        ?>
                    </ul>
                </div>

                <div id="tab1" class="block-wrapper">
                    <div class="general-block-wrapper">
                        <div class="single-block-wrapper">
                            <div class="single-block">
                                <div class="onboarding-block banner-block">
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

                                        <a class="waves-effect waves-light btn bwf-btn" href="https://www.youtube.com/channel/UCf-NabV2v7DGN8MxQNrxkmw" target="_blank"><?php echo __('View Documentation', 'rex-product-feed')?></a>
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

                                        <a class="waves-effect waves-light btn bwf-btn" href="<?php echo apply_filters('wpfm_support_link', 'https://wordpress.org/support/plugin/best-woocommerce-feed'); ?>" target="_blank">Post a Ticket</a>
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

                                        <a class="waves-effect waves-light btn bwf-btn" href="http://openvoyce.com/products/bwf" target="_blank"><?php echo __('Suggest', 'rex-product-feed')?></a>
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

                                        <a class="waves-effect waves-light btn bwf-btn" href="<?php echo apply_filters('wpfm_review_link', 'https://wordpress.org/support/plugin/best-woocommerce-feed/reviews/#new-post') ?>" target="_blank"><?php echo __('Rate Us!', 'rex-product-feed')?> </a>
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
                        <div class="right upgrade">
                            <div class="rex-banner" style="text-align: left">
                                <?php
                                if ( !$is_premium_activated ) {?>
                                    <div class="wpfm-pro-features rex-upgrade">
                                        <p><strong>Why upgrade to Pro?</strong></p>
                                        <ol>
                                            <li class="items">Supports unlimited products.</li>
                                            <li class="items">Access to a elite support team.</li>
                                            <li class="items">Supports YITH brand attributes.</li>
                                            <li class="items">Dynamic Attribute.</li>
                                            <li class="items">Custom field support - Brand,GTIN,MPN,UPC,EAN,Size, Pattern, Material, Age Group, Gender </li>
                                            <li class="items">Fix WooCommerce's (JSON-LD) structure data bug </li>
                                        </ol>
                                    </div>
                                    <a href="https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro" class="update-btn" target="_blank">Upgrade to Pro</a>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="merchant" class="block-wrapper">
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
                                    'name'  => 'Google'
                                ),
                                'google_Ad'    => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Google AD'
                                ),
                                'facebook'     => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Facebook'
                                ),
                                'amazon'       => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'Amazon'
                                ),
                                'ebay'         => array(
                                    'free'  => true,
                                    'status'    => 1,
                                    'name'  => 'eBay'
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
                                )
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

                        ?>
                        <?php foreach ($_merchants as $key => $merchant): ?>
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
                                    <label>
                                        <input type="checkbox" <?php echo $checked; ?> <?php echo $disabled; ?> id="rex-product-feed-merchant" data-value="<?php echo $key; ?>" data-is-free="<?php echo $is_free; ?>" data-name="<?php echo $name; ?>">
                                        <span class="lever"></span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!--/merchant tab-->

                <div id="tab2" class="block-wrapper">
                    <div class="video-container">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLelDqLncNWcVoPA7T4eyyfzTF0i_Scbnq" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>


                <div id="settings" class="block-wrapper">
                    <div class="rex-merchant feed-settings">
                        <h3 class="merchant-title"><?php echo __('Controls', 'rex-product-feed'); ?> </h3>
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
                                <label>
                                    <input type="checkbox" id="rex-product-structured-data" <?php echo $checked; ?> <?php echo $disabled; ?>>
                                    <span class="lever"></span>
                                </label>
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
                                <label>
                                    <input type="checkbox" id="rex-product-exclude-tax" <?php echo $checked; ?> <?php echo $disabled; ?>>
                                    <span class="lever"></span>
                                </label>
                            </div>
                        </div>

                        <div class="single-merchant">
                            <span class="title"><?php echo __('Add Unique Product Identifiers (Brand,GTIN,MPN,UPC and EAN) to product', 'rex-product-feed'); ?></span>
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
                                <label>
                                    <input type="checkbox" id="rex-product-custom-field" <?php echo $checked; ?> <?php echo $disabled; ?>>
                                    <span class="lever"></span>
                                </label>
                            </div>
                        </div>

                        <div class="single-merchant">
                            <span class="title"><?php echo __('Add Detailed Product Attributes (size, pattern, material, age group, gender) to product', 'rex-product-feed'); ?></span>
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
                                <label>
                                    <input type="checkbox" id="rex-product-pa-field" <?php echo $checked; ?> <?php echo $disabled; ?>>
                                    <span class="lever"></span>
                                </label>
                            </div>
                        </div>


                    </div>
                </div>
                <!--/settings tab-->

                <div id="system_status" class="block-wrapper">
                    <div class="rex-merchant feed-settings">
                        <h3><?php echo __('System Status', 'rex-product-feed'); ?></h3>
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
                                'php_version_status'    =>  version_compare(phpversion(),  "5.2", ">="),
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
                        <div id="tab3" class="block-wrapper">
                            <div class="rex-upgrade wpfm-pro-features">
                                <p><strong>Why upgrade to Pro?</strong></p>
                                <ul>
                                    <li class="item">Supports unlimited products.</li>
                                    <li class="item">Access to a elite support team.</li>
                                    <li class="item">Supports YITH brand attributes.</li>
                                    <li class="item">Dynamic Attribute.</li>
                                    <li class="item">Custom field support - Brand,GTIN,MPN,UPC,EAN,Size, Pattern, Material, Age Group, Gender </li>
                                    <li class="item">Fix WooCommerce's (JSON-LD) structure data bug </li>
                                </ul>
                                <a href="https://rextheme.com/best-woocommerce-product-feed/#upgrade-pro" target="_blank" class="waves-effect waves-light btn bwf-btn">Get Premium Version</a>
                            </div>
                        </div>


                    <?php }
                ?>

            </div>
        </div>
    </div>
</div>