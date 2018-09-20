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


?>

<div class="row">
    <div class="rex-onboarding">
        <div class="left">
            <div class="wrapper">
                <div class="col s12 no-pd">
                    <ul class="tabs tabs-icon rex-tabs">
                        <li class="tab col s3"><a href="#tab1"><i class="material-icons">settings</i>General</a></li>
                        <li class="tab col s3"><a href="#tab2"><i class="material-icons">perm_media</i>Video Tutorials</a></li>

                        <?php
                            if ( rex_product_feed()->is_free_plan() ) {?>
                                <li class="tab col s3"><a href="#tab3"><i class="material-icons">thumb_up_alt</i>Go Premium</a></li>
                            <?php }
                        ?>
                    </ul>
                </div>

                <div id="tab1" class="block-wrapper">
                    <div class="single-block">
                        <div class="onboarding-block banner-block">
                            <img src="<?php echo PLUGIN_DIR_URL . 'admin/icon/banner.png'?>" alt="rex-banner">
                        </div>
                    </div>
                    <div class="single-block">
                        <div class="onboarding-block">

                            <div class="header">
                                <img src="<?php echo PLUGIN_DIR_URL . 'admin/icon/Document.png'?>" class="title-icon" alt="bwf-documentation">
                                <h4>Documentation</h4>
                            </div>

                            <div class="body">
                                <p>
                                    Before You start, you can check our Documentation to get familiar with WooCommerce Product Feed Manager.
                                </p>

                                <a class="waves-effect waves-light btn bwf-btn" href="https://www.youtube.com/channel/UCf-NabV2v7DGN8MxQNrxkmw" target="_blank">View Documentation</a>
                            </div>
                        </div>
                    </div>

                    <div class="single-block">

                        <div class="onboarding-block">
                            <div class="header">
                                <img src="<?php echo PLUGIN_DIR_URL . 'admin/icon/Support.png'?>" class="title-icon" alt="bwf-documentation">
                                <h4>Support</h4>
                            </div>

                            <div class="body">
                                <p>
                                    Can't find solution on with our documentation? Just Post a ticket on Support forum. We are to solve your issue.
                                </p>

                                <a class="waves-effect waves-light btn bwf-btn" href="https://wordpress.org/support/plugin/best-woocommerce-feed" target="_blank">Post a Ticket</a>
                            </div>
                        </div>
                    </div>

                    <div class="single-block">
                        <div class="onboarding-block">
                            <div class="header">
                                <img src="<?php echo PLUGIN_DIR_URL . 'admin/icon/Feedback.png'?>" class="title-icon" alt="bwf-documentation">
                                <h4>Share Your Thoughts</h4>
                            </div>

                            <div class="body">
                                <p>
                                    Your suggestions are valubale to us. It can help to make BWFM even better.
                                </p>

                                <a class="waves-effect waves-light btn bwf-btn" href="http://openvoyce.com/products/bwf" target="_blank">Suggest</a>
                            </div>
                        </div>
                    </div>

                    <div class="single-block">
                        <div class="onboarding-block">
                            <div class="header">
                                <img src="<?php echo PLUGIN_DIR_URL . 'admin/icon/Rating.png'?>" class="title-icon" alt="bwf-documentation">
                                <h4>Make WPFM Popular</h4>
                            </div>

                            <div class="body">
                                <p>
                                    Your rating and feedback matters to us. If you are happy with WooCommerce Product Feed Manager give us a rating.
                                </p>

                                <a class="waves-effect waves-light btn bwf-btn" href="https://wordpress.org/support/plugin/best-woocommerce-feed/reviews/#new-post" target="_blank">Rate Us! </a>
                            </div>
                        </div>
                    </div>


                    <div class="single-block">
                        <div class="onboarding-block">
                            <div class="header">
                                <img src="<?php echo PLUGIN_DIR_URL . 'admin/icon/Heart.png'?>" class="title-icon" alt="bwf-documentation">
                                <h4>Share On</h4>
                            </div>

                            <div class="body">
                                <ul class="social">
                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=https%3A//wordpress.org/plugins/best-woocommerce-feed/" target="_blank">Share on Facebook</a></li>
                                    <li><a href="https://twitter.com/home?status=https%3A//wordpress.org/plugins/best-woocommerce-feed/" target="_blank">Share on Twitter</a></li>
                                    <li><a href="https://plus.google.com/share?url=https%3A//wordpress.org/plugins/best-woocommerce-feed/" target="_blank">Share on Google+</a></li>
                                    <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=https%3A//wordpress.org/plugins/best-woocommerce-feed/&title=Best%20WooCommerce%20Product%20Feed%20Manager&summary=&source=" target="_blank">Share on LinkedIn</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="block-wrapper">
                    <div class="video-container">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLelDqLncNWcVoPA7T4eyyfzTF0i_Scbnq" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>

                <?php
                if ( rex_product_feed()->is_free_plan() ) {?>
                    <div id="tab3" class="block-wrapper">
                        <div class="rex-upgrade">
                            <h4>Why upgrade to Premium Version?</h4>
                            <div class="parent">
                                <div class="item">Supports more than 50 products</div>
                                <div class="item">Access to a elite support team</div>
                                <div class="item">Supports YITH brand attributes</div>
                                <div class="item">Custom Filtering</div>
                                <div class="item">Dynamic Attribute</div>
                            </div>
                            <a href="https://checkout.freemius.com/mode/dialog/plugin/1327/plan/1878/" target="_blank" class="waves-effect waves-light btn bwf-btn">Get Premium Version</a>
                        </div>
                    </div>
                <?php }
                ?>

            </div>
        </div>

        <div class="right">
            <div class="rex-banner">
                <?php
                    if ( rex_product_feed()->is_free_plan() ) {?>
                        <img src="https://ps.w.org/best-woocommerce-feed/assets/icon-128x128.jpg?rev=1737647" class="banner-logo" alt="logo">
                        <a href="https://checkout.freemius.com/mode/dialog/plugin/1327/plan/1878/" class="update-btn" target="_blank">Upgrade to Pro</a>
                    <?php }
                ?>

                <a href="https://wordpress.org/plugins/social-booster/" target="_blank"><img src="<?php echo PLUGIN_DIR_URL . 'admin/icon/Social_Booster_Banner.png'?>" alt="rex-banner"></a>
            </div>
        </div>
    </div>


</div>
