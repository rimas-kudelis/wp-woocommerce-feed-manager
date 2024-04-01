<?php

/**
 * SpecialOccasionBanner Class
 *
 * This class is responsible for displaying a special occasion banner in the WordPress admin.
 *
 * @package YourVendor\SpecialOccasionPlugin
 *
 * @since 7.3.18
 */
class Rex_Feed_Special_Occasion_Banner {

	/**
	 * The occasion identifier.
	 *
	 * @var string
	 *
	 * @since 7.3.18
	 */
	private $occasion;

	/**
	 * The start date and time for displaying the banner.
	 *
	 * @var int
	 *
	 * @since 7.3.18
	 */
	private $start_date;

	/**
	 * The end date and time for displaying the banner.
	 *
	 * @var int
	 *
	 * @since 7.3.18
	 */
	private $end_date;

	/**
	 * Constructor method for SpecialOccasionBanner class.
	 *
	 * @param string $occasion The occasion identifier.
	 * @param string $start_date The start date and time for displaying the banner.
	 * @param string $end_date The end date and time for displaying the banner.
	 *
	 * @since 7.3.18
	 */
	public function __construct( $occasion, $start_date, $end_date ) {
		$this->occasion   = "rex_feed_{$occasion}";
		$this->start_date = strtotime( $start_date );
		$this->end_date   = strtotime( $end_date );
	}

	/**
	 * Controls the initialization of certain admin-related functionalities based on conditions.
	 * It checks the current screen, defined allowed screens, product feed version availability,
	 * and date conditions to determine whether to display a banner and enqueue styles.
	 *
	 * @since 7.3.18
	 */
	public function init() {
		$current_date_time = current_time( 'timestamp' );

		if (
			'hidden' !== get_option( $this->occasion, '' )
			//&& !defined( 'REX_PRODUCT_FEED_PRO_VERSION' )
			&& ( $current_date_time >= $this->start_date && $current_date_time <= $this->end_date )
		) {
			// Add styles
			add_action( 'admin_head', [ $this, 'enqueue_css' ] );
			// Hook into the admin_notices action to display the banner
			add_action( 'admin_notices', [ $this, 'display_banner' ] );
		}
	}
    

	/**
	 * Displays the special occasion banner if the current date and time are within the specified range.
	 *
	 * @since 7.3.18
	 */
	public function display_banner() {
		$screen          = get_current_screen();
		$allowed_screens = [ 'dashboard', 'plugins', 'product-feed' ];
        $time_remaining  = $this->end_date - current_time( 'timestamp' );

        $btn_link = 'https://rextheme.com/woocommerce-sell-kit/#pricing';

		if ( in_array( $screen->base, $allowed_screens ) || in_array( $screen->parent_base, $allowed_screens ) || in_array( $screen->post_type, $allowed_screens ) || in_array( $screen->parent_file, $allowed_screens ) ) {
        echo '<input type="hidden" id="rexfeed_special_occasion" name="rexfeed_special_occasion" value="'.$this->occasion.'">';
        ?>

            <!-- Name: Christmas Notification Banner -->

            <div class="rex-feed-tb__notification" id="rex_deal_notification">

                <div class="banner-overflow">
                    <div class="rextheme-td__content-area">
                        <div class="rextheme-td__image p-8 christmas">
                            <figure>
                                <img loading="lazy" src="<?php echo plugin_dir_url( __FILE__ ) . './assets/icon/launch-campaign-img/launch-campaign-woo-logo.webp' ; ?>"  alt="Launch Campaign Rextheme">
                            </figure>
                        </div>

                        <div class="rextheme-td__image">
                            <div class="rextheme-td__text-container">
                                <h4>WooCommerce <span class="rextheme-td__image addon-bundle">Addon Bundle</span><span> Pricing </span>Just Got Affordable</h4>
                                <figure>
                                    <img loading="lazy" class="rextheme-td__campaign-text-icon" src="<?php echo plugin_dir_url( __FILE__ ) . './assets/icon/launch-campaign-img/campaign-text-icon.webp' ; ?>" alt="campaing button icon">
                                </figure>
                            </div>
                        </div>

                        <div class="rextheme-td__image twenty-five-percent-logo">
                            <figure>
                                <img loading="lazy" src="<?php echo plugin_dir_url( __FILE__ ) . './assets/icon/launch-campaign-img/launch-campaign-discount.webp' ; ?>"  alt="60% discount">
                            </figure>
                        </div>

                        <div class="rextheme-td__btn-area">
                            <a href="<?php echo esc_url($btn_link); ?>" role="button" class="rextheme-td__btn" target="_blank"> Get Discount Now </a>
                            <figure>
                                <img loading="lazy" class="rextheme-td__btn-icon" src="<?php echo plugin_dir_url( __FILE__ ) . './assets/icon/launch-campaign-img/campaign-button-icon.webp'; ?>" alt="campaing button icon">
                            </figure>
                        </div>

                        <div class="rextheme-td__image campaign-note-image">
                            <figure>
                                <img loading="lazy" src="<?php echo plugin_dir_url( __FILE__ ) . './assets/icon/launch-campaign-img/launch-campaign-note.webp'; ?>" alt="launch campaign note image">
                            </figure>
                        </div>

                    </div>

                </div>

                <div class="rex-feed-tb__cross-top" id="rex_deal_close">
                    <?php include WPFM_PLUGIN_ASSETS_FOLDER_PATH . 'icon/icon-svg/cross-top.php'; ?>
                </div>

            </div>
            <!-- .rex-feed-tb-notification end -->

            <script>
                rexfeed_deal_countdown_handler();
                /**
                 * Handles count down on deal notice
                 *
                 * @since 7.3.18
                 */
                function rexfeed_deal_countdown_handler() {
                    // Pass the calculated time remaining to JavaScript
                    let timeRemaining = <?php echo $time_remaining;?>;

                    // Update the countdown every second
                    setInterval(function() {
                        const daysElement = document.getElementById('rex-feed-tb__days');
                        const hoursElement = document.getElementById('rex-feed-tb__hours');
                        const minutesElement = document.getElementById('rex-feed-tb__mins');
                        //const secondsElement = document.getElementById('seconds');

                        timeRemaining--;

                        if ( daysElement && hoursElement && minutesElement ) {
                            // Decrease the remaining time

                            // Calculate new days, hours, minutes, and seconds
                            let days = Math.floor(timeRemaining / (60 * 60 * 24));
                            let hours = Math.floor((timeRemaining % (60 * 60 * 24)) / (60 * 60));
                            let minutes = Math.floor((timeRemaining % (60 * 60)) / 60);
                            //let seconds = timeRemaining % 60;

                            // Format values with leading zeros
                            days = (days < 10) ? '0' + days : days;
                            hours = (hours < 10) ? '0' + hours : hours;
                            minutes = (minutes < 10) ? '0' + minutes : minutes;
                            //seconds = (seconds < 10) ? '0' + seconds : seconds;

                            // Update the HTML
                            daysElement.textContent = days;
                            hoursElement.textContent = hours;
                            minutesElement.textContent = minutes;
                        }
                        // Check if the countdown has ended
                        if (timeRemaining <= 0) {
                            rexfeed_hide_deal_notice();
                        }
                    }, 1000); // Update every second
                }

                document.getElementById( 'rex_deal_close' ).addEventListener( 'click', rexfeed_hide_deal_notice );

                /**
                 * Hide deal notice and save parameter to keep it hidden for future
                 *
                 * @since 7.3.2
                 */
                function rexfeed_hide_deal_notice() {
                    document.getElementById( 'rex_deal_notification' ).style.display = 'none';
                    const payload = { occasion: document.getElementById( 'rexfeed_special_occasion' )?.value }

                    wpAjaxHelperRequest( 'rex-feed-hide-deal-notice', payload );
                }
            </script>

            <?php
		}
	}

	/**
	 * Adds internal CSS styles for the special occasion banners.
	 *
	 * @since 7.3.18
	 */
	public function enqueue_css() {
        $plugin_dir_url = plugin_dir_url(__FILE__ );
		?>
		<style type="text/css">
            /* notification var css */

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/LexendDeca-SemiBold.woff2";?>) format('woff2'),
                    url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/LexendDeca-SemiBold.woff";?>) format('woff');
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/LexendDeca-Bold.woff2";?>) format('woff2'),
                    url(<?php echo "{$plugin_dir_url}assets/fonts/campaign-font/LexendDeca-Bold.woff";?>) format('woff');
                font-weight: bold;
                font-style: normal;
                font-display: swap;
            }
        

        .rex-feed-tb__notification, 
        .rex-feed-tb__notification * {
            box-sizing: border-box;
        }
                

        .rex-feed-tb__notification {
            background-color: #d6e4ff;
            width: calc(100% - 20px);
            margin: 50px 0 20px;
            background-image: url(<?php echo "{$plugin_dir_url}assets/icon/launch-campaign-img/launch-campaign-background-bar.webp"; ?>);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
            border: none;
            box-shadow: none;
            display: block;
        }

        .rex-feed-tb__notification .banner-overflow {
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .rextheme-td__content-area {
            width: 100%;
            max-height: 110px;
            margin: 0 auto;
            padding-left: 30px;
            position: relative;
        }

        .rex-feed-tb__notification figure {
            margin: 0;
        }

        .rextheme-td__content-area {
            max-width: 1920px;
        }

        .rextheme-td__content-area {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            align-items: center;
        }

        .rextheme-td__image h4 {
            font-family: 'Lexend Deca';
            text-transform: capitalize;
            font-size: 26px;
            font-style: normal;
            font-weight: 600;
            line-height: 34px;
            color: #0a0933;
            margin: 0;
        }

        @media only screen and (max-width: 1199px) {
            .rextheme-td__image h4 {
                font-size: 24px;
            }
        }
        @media only screen and (max-width: 991px) {
            .rextheme-td__image h4 {
                font-size: 20px;
                line-height: 28px;
            }
        }
        .rextheme-td__image h4 span {
            font-weight: 700;
            color: #211cfd;
            text-transform: none;
        }
        .rextheme-td__image figure {
            margin: 0;
        }
        .rextheme-td__image.addon-bundle {
            color: #211cfd;
            text-shadow: 2px 2px 0 #84dbff, -2px 2px 0 #84dbff, -2px -2px 0 #84dbff, 2px -2px 0 #84dbff;
        }
        .rextheme-td__image.p-8 {
            padding: 0;
        }
        .rextheme-td__image.christmas img {
            width: 100%;
            max-height: 114px;
            
        }
        .rextheme-td__image.twenty-five-percent-logo img {
            width: 100%;
            max-height: 110px;
        }
        .rextheme-td__image.twenty-five-pro-percent-logo img {
            width: 100%;
            max-height: 110px;
        }
        .rextheme-td__image.campaign-note-image img {
            width: 100%;
            max-height: 114px;
        }
        .rextheme-td__text-container {
            position: relative;
            max-width: 390px;
        }
        .rextheme-td__campaign-text-icon {
            position: absolute;
            top: -14px;
            right: -24px;
            max-width: 100%;
            max-height: 24px;
        }
        .rextheme-td__btn-area {
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            position: relative;
        }
        .rextheme-td__btn {
            font-family: 'Lexend Deca';
            font-size: 16px;
            font-style: normal;
            font-weight: 700;
            color: #fff;
            text-align: center;
            border-radius: 30px;
            background: linear-gradient(to bottom, #6460fe 0%, #211cfd 100%);
            padding: 14px 24px;
            display: inline-block;
            cursor: pointer;
            text-decoration: none;
            text-transform: capitalize;
            transition: all 0.3s ease;
        }
        .rextheme-td__btn:hover {
            background-color: #201cfe;
            color: #fff;
        }
        .rextheme-td__btn.pfm-claim {
            background-color: #216df0;
        }
        .rextheme-td__btn.pfm-claim:hover {
            background-color: #00b4ff;
            color: #fff;
            box-shadow: none;
        }
        .rextheme-td__btn.wpvr-claim {
            background-color: #3f04fe;
        }
        .rextheme-td__btn.wpvr-claim:hover {
            background-color: #211fa5;
            color: #fff;
            box-shadow: none;
        }
        .rextheme-td__btn-icon {
            position: absolute;
            top: -14px;
            right: -23px;
            width: 40px;
            height: 35px;
        }
        .rextheme-td__stroke-font {
            font-size: 26px;
            font-weight: 700;
        }

        .rex-feed-tb__notification .rex-feed-tb__cross-top {
            position: absolute;
            top: -10px;
            right: -9px;
            background: #fff;
            border: none;
            padding: 4px 4px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 9;
        }

        .rex-feed-tb__notification .rex-feed-tb__cross-top svg {
            display: block;
            width: 15px;
            height:15px;
        }

        @media only screen and (max-width: 1440px) {
            .rextheme-td__content h4 {
                font-size: 22px;
            }
            .rextheme-td__text-container {
                max-width: 570px;
            }
            .rextheme-td__image h4 {
                font-size: 22px;
                line-height: 1.2;
            }
            .rextheme-td__image.addon-bundle {
                color: #211cfd;
                text-shadow: 2px 2px 0 #84dbff, -2px 2px 0 #84dbff, -2px -2px 0 #84dbff, 2px -2px 0 #84dbff;
            }
            .rextheme-td__campaign-text-icon {
                top: -11px;
                right: 17px;
            }
            .rextheme-td__btn-area {
                min-width: 200px;
            }
            .rextheme-td__btn {
                font-size: 16px;
                font-weight: 600;
                line-height: 34px;
                border-radius: 30px;
                padding: 8px 27px;
            }
            .rextheme-td__btn-icon {
                position: absolute;
                top: -10px;
                right: -25px;
                max-height: 32px;
            }
            .rextheme-td__image.campaign-note-image img,
            .rextheme-td__image.christmas img {
                margin-bottom: -6px;
            }

            
        }

        @media only screen and (max-width: 1399px) {
            .rextheme-td__image h4 {
                font-size: 19px;
                line-height: 1.2;
            }

            .rextheme-td__campaign-text-icon {
                top: -11px;
                right: -9px;
            }

            .rextheme-td__btn {
                font-size: 14px;
                font-weight: 600;
                line-height: 1.2;
                border-radius: 30px;
                padding: 12px 28px;
            }

        }

        @media only screen and (max-width: 1024px) {
            .rextheme-td__content h4 {
                font-size: 16px;
            }
            .rextheme-td__notification .rextheme-td__text-container h4 {
                font-size: 16px;
            }
            .campaign-note-image img {
                max-width: 400px;
            }
            .rextheme-td__image h4 {
                font-size: 13px;
                line-height: 17px;
            }
            .rextheme-td__image.addon-bundle {
                color: #211cfd;
                text-shadow: 1px 1px 0 #84dbff, -1px 1px 0 #84dbff, -1px -1px 0 #84dbff, 1px -1px 0 #84dbff;
            }
            .rextheme-td__campaign-text-icon {
                display: none;
            }
            .rextheme-td__content-area {
                gap: 15px;
            }
            .rextheme-td__image.christmas img {
                max-width: 100%;
                margin-bottom: -6px;
            }
            .rextheme-td__image.twenty-five-pro-percent-logo {
                max-width: 100%;
            }
            .rextheme-td__image.twenty-five-percent-logo img {
                max-width: 80%;
            }
            .rextheme-td__btn-area {
                min-width: 150px;
            }

            .rextheme-td__btn-icon {
                display: none;
            }

            .rextheme-td__btn {
                font-size: 12px;
                line-height: 1.3;
                padding: 10px 21px;
                border-radius: 20px;
                font-weight: 400;
            }
            .rextheme-td__content h4 {
                font-size: 22px;
            }
    

            .rextheme-td__image.campaign-note-image img {
                margin-bottom: -9px;
            }
        }
        
        @media only screen and (max-width: 768px) {

            .rex-feed-tb__notification {
                margin: 64px 0 20px;
            }

            .rextheme-td__notification .rextheme-td__text-container h4 {
                font-size: 13px;
            }
            .rextheme-td__content-area {
                gap: 16px;
            }
            .rextheme-td__image h4 {
                font-size: 11px;
                line-height: 12px;
            }
            .rextheme-td__campaign-text-icon {
                display: none;
            }
            .rextheme-td__btn-area {
                min-width: 120px;
            }
            .rextheme-td__btn {
                font-size: 10px;
                line-height: 9px;
                font-weight: 400;
                padding: 10px 14px;
                border-radius: 15px;
                margin-left: 0;
            }
           
        }
        @media only screen and (max-width: 767px) {
            .rextheme-td__notification .rextheme-td__text-container h4 {
                font-size: 23px;
            }
            .rextheme-td__content-area {
                padding-left: 0;
            }
            .rextheme-td__image.p-8 {
                display: none;
            }
            .rextheme-td__image.twenty-five-percent-logo img {
                width: 70%;
                margin: 0 auto;
                max-height: 110px;
            }
            .wpvr-promotional-banner {
                padding-top: 20px;
                padding-bottom: 342px;
                background-position: 14%;
            }
            .rextheme-td__image h4 {
                font-size: 20px;
                line-height: 27px;
                padding: 0 20px 0 20px;
            }
            .rextheme-td__stroke-font {
                font-size: 22px;
            }
            .rextheme-td__content-area {
                flex-direction: column;
                gap: 12px;
                text-align: center;
                align-items: center;
            }
            .rextheme-td__content h4 {
                font-size: 24px;
                line-height: 1.2;
            }
            .rextheme-td__btn-area {
                justify-content: center;
                padding-top: 20px;
            }
            .rextheme-td__btn {
                font-size: 12px;
                padding: 18px 24px;
                border-radius: 30px;
            }
            .rextheme-td__btn-icon {
                position: absolute;
                top: 11px;
                right: -17px;
            }
        }
        @media only screen and (max-width: 320px) {
            .rextheme-td__notification {
                padding-top: 20px;
                padding-bottom: 361px;
                background-position: 14%;
            }
        }


		</style>

		<?php
	}
}