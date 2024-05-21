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
		 	&& !defined( 'REX_PRODUCT_FEED_PRO_VERSION' )
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

        $btn_link = 'https://rextheme.com/best-woocommerce-product-feed/pricing/?utm_source=Plugin-CTA&utm_medium=PFM-Plugin&utm_campaign=WP-anniversary-sale24';

		if ( in_array( $screen->base, $allowed_screens ) || in_array( $screen->parent_base, $allowed_screens ) || in_array( $screen->post_type, $allowed_screens ) || in_array( $screen->parent_file, $allowed_screens ) ) {
        echo '<input type="hidden" id="rexfeed_special_occasion" name="rexfeed_special_occasion" value="'.$this->occasion.'">';
        ?>

            <!-- Name: WordPress Anniversary Notification Banner -->

            <div class="rex-feed-tb__notification" id="rex_deal_notification">

                <div class="banner-overflow">
                    <div class="wpfm-wp-anniv__container-area">

                        <div class="wpfm-wp-anniv__image wpfm-wp-anniv__image--left">
                            <figure>
                                <img src="<?php echo plugin_dir_url( __FILE__ ) .'./assets/icon/wp-anniversary/wp-anniversary-left.webp' ; ?>" alt="Eid Mubark Rextheme" />
                            </figure>
                        </div>

                        <div class="wpfm-wp-anniv__content-area">


                            <div class="wpfm-wp-anniv__image--group">

                                <div class="wpfm-wp-anniv__text-divider">
                                    
                                    <div class="wpfm-wp-anniv__text-flex">

                                        <h2 class="wpfm-wp-anniv__title-wp">
                                          <?php echo __("WordPress ", 'rextheme')?>
                                        </h2>

                                    </div>
                                   

                                    <span class="wpfm-wp-anniv__subtitle-anniversary">
                                        <?php echo __("21st Anniversary Special", 'rextheme')?>
                                    </span>
                        
                                </div>

                                <div class="wpfm-wp-anniv__image wpfm-wp-anniv__image--four">
                                    <figure>
                                        <img src="<?php echo plugin_dir_url( __FILE__ ) .'./assets/icon/wp-anniversary/pfm.webp' ; ?>" alt="25% discount"  />
                                    </figure>
                                </div>

                            </div>

                            <!-- .wpfm-wp-anniv__image end -->
                            <div class="wpfm-wp-anniv__btn-area">
                               
                                <a href="<?php echo esc_url($btn_link); ?>" role="button" class="wpfm-wp-anniv__btn" target="_blank">
                                    <?php echo __('Claim Offer Now', 'rextheme')?>
                                </a>

                            </div>

                        </div>

                        <div class="wpfm-wp-anniv__image wpfm-wp-anniv__image--right">
                            <figure>
                                <img src="<?php echo plugin_dir_url( __FILE__ ) . './assets/icon/wp-anniversary/wp-anniversary-right.webp' ; ?>" alt="Masjid"  />
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
                            // rexfeed_hide_deal_notice();
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
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/wp-anniversary-campaign-font/LexendDeca-SemiBold.woff2";?>) format('woff2'),
                    url(<?php echo "{$plugin_dir_url}assets/fonts/wp-anniversary-campaign-font/LexendDeca-SemiBold.woff";?>) format('woff');
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/wp-anniversary-campaign-font/LexendDeca-Bold.woff2";?>) format('woff2'),
                    url(<?php echo "{$plugin_dir_url}assets/fonts/wp-anniversary-campaign-font/LexendDeca-Bold.woff";?>) format('woff');
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
        background-image: url(<?php echo "{$plugin_dir_url}assets/icon/wp-anniversary/notification-bg.webp"; ?>);
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
        border: none;
        box-shadow: none;
        display: block;
        max-height: 110px;
    }

    .rex-feed-tb__notification .banner-overflow {
        overflow: hidden;
        position: relative;
        width: 100%;
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

    .wpfm-wp-anniv__container {
        width: 100%;
        margin: 0 auto;
        max-width: 1640px;
        position: relative;
        padding-right: 15px;
        padding-left: 15px;
    }

    .wpfm-wp-anniv__container-area {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .wpfm-wp-anniv__content-area {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 90px;
        max-width: 1340px;
        position: relative;
        padding-right: 15px;
        padding-left: 15px;
        margin: 0 auto;
    }

    .wpfm-wp-anniv__image--group {
        display: flex;
        align-items: center;
        gap: 100px;
    }

    .wpfm-wp-anniv__text-flex {
        display: flex;
        align-items: center;
        gap:5px;
    }

    .wpfm-wp-anniv__text-flex svg {
        width: 108px;
        height: 27px
    }

    .wpfm-wp-anniv__title-wp {
        position: relative;
        font-family: 'Lexend Deca';
        font-size: 42px;
        font-style: normal;
        font-weight: 800;
        line-height: 1.4;
        margin: 0;
    }

    .wpfm-wp-anniv__title-wp::before {
        content: "WordPress";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: -moz-linear-gradient(359deg, #fff 33.26%, #fff 78.5%);
        background: -o-linear-gradient(359deg, #fff 33.26%, #fff 78.5%);
        background: linear-gradient(91deg, #fff 33.26%, #fff 78.5%);
        -webkit-background-clip: text !important;
        background-clip: text !important;
        -webkit-text-fill-color: transparent;
        z-index: 1;
    }

    .wpfm-wp-anniv__title-wp::after {
        content: "WordPress";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        -webkit-text-stroke: 8px #216DF0;
    }

    .wpfm-wp-anniv__subtitle-anniversary {
        position: relative;
        font-family: 'Lexend Deca';
        font-size: 36px;
        font-style: normal;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -.36px;
    }

    .wpfm-wp-anniv__subtitle-anniversary::before {
        content: "21st Anniversary Special";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: -moz-linear-gradient(181deg, #faff00 15.6%, #4fff33 96.87%);
        background: -o-linear-gradient(181deg, #faff00 15.6%, #4fff33 96.87%);
        background: linear-gradient(269deg, #faff00 15.6%, #4fff33 96.87%);
        -webkit-background-clip: text !important;
        background-clip: text !important;
        -webkit-text-fill-color: transparent;
        z-index: 1;
    }

    .wpfm-wp-anniv__subtitle-anniversary::after {
        content: "21st Anniversary Special";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        -webkit-text-stroke: 8px #216DF0;
    }

    .wpfm-wp-anniv__image--left img {
        width: 100%;
        max-width: 255px;
    }

    .wpfm-wp-anniv__image--four img {
        width: 100%;
        max-width: 410px;
    }

    .wpfm-wp-anniv__image--right img {
        width: 100%;
        max-width: 178px;
    }

    .wpfm-wp-anniv__image figure {
        margin: 0;
    }

    .wpfm-wp-anniv__text-container {
        position: relative;
        max-width: 330px;
    }

    .wpfm-wp-anniv__campaign-text-icon {
        position: absolute;
        top: -10px;
        right: -15px;
        max-width: 100%;
        max-height: 24px;
    }

    .wpfm-wp-anniv__btn-area {
        display: flex;
        align-items: flex-end;
        justify-content: flex-end;
        position: relative;
    }

    .wpfm-wp-anniv__btn {
        font-family: 'Lexend Deca';
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        border-radius: 30px;
        background: -webkit-gradient(linear, left bottom, left top, from(#ace7ff), to(#fff));
        background: -moz-linear-gradient(bottom, #ace7ff 0, #fff 100%);
        background: -o-linear-gradient(bottom, #ace7ff 0, #fff 100%);
        background: linear-gradient(0deg, #ace7ff 0, #fff 100%);
        box-shadow: 0px 11px 30px 0px rgba(19, 13, 57, 0.25);
        color: #216DF0;
        padding: 17px 26px;
        display: inline-block;
        text-decoration: none;
        cursor: pointer;
        text-transform: capitalize;
        -webkit-transition: all .5s linear;
        -o-transition: all .5s linear;
        -moz-transition: all .5s linear;
        transition: all .5s linear;
    }

    a.wpfm-wp-anniv__btn:hover {
        color: #216DF0;
        background: linear-gradient(0deg, #ace7ff 100%, #fff 0);
    }

    .wpfm-wp-anniv__btn-area a:focus {
        color: #fff;
        box-shadow: none;
        outline: 0px solid transparent;
    }

    .wpfm-wp-anniv__btn:hover {
        background-color: #201cfe;
        color: #fff;
    }

    @media only screen and (min-width: 1921px) {
        .wpfm-wp-anniv__image--left img {
            max-width: 215px;
        }
    }

 
    @media only screen and (max-width: 1710px) {

        .wpfm-wp-anniv__title {
            font-size: 30px;
            text-align: left;
        }

        .wpfm-wp-anniv__image--group {
            gap: 60px;
        }

        .wpfm-wp-anniv__image--four img {
            max-width: 320px;
        }

        .wpfm-wp-anniv__content-area {
            gap: 70px;
        }

        .wpfm-wp-anniv__title-wp {
            font-size: 32px;
        }

        .wpfm-wp-anniv__subtitle-anniversary {
            font-size: 26px;
        }

        .wpfm-wp-anniv__title-end {
            font-size: 26px;
        }

        .wpfm-wp-anniv__btn {
            font-size: 18px;
        }

        .wpfm-wp-anniv__text-inner svg {
            width: 120px;
        }

        .wpfm-wp-anniv__text {
            gap: 78px;
        }

        .wpfm-wp-anniv__content-inner {
            gap: 78px;
        }

        .wpfm-wp-anniv__img-area {
            gap: 78px;
        }

        .wpfm-wp-anniv__img img {
            max-width: 20px;
        }

    }


    @media only screen and (max-width: 1440px) {
        .wpfm-wp-anniv__image--group {
            gap: 20px;
        }

        .wpfm-wp-anniv__title-wp {
            font-size: 25px;
        }

        .wpfm-wp-anniv__subtitle-anniversary {
            font-size: 25px;
        }

        .wpfm-wp-anniv__image--left img {
            max-width: 185px;
        }

        .wpfm-wp-anniv__image--right img {
            max-width: 150px;
        }

        .wpfm-wp-anniv__content-area {
            max-width: 900px;
        }

        .wpfm-wp-anniv__image--four img {
            max-width: 280px;
        }

        .wpfm-wp-anniv__btn {
            font-size: 16px;
            font-weight: 600;
            line-height: 34px;
            border-radius: 30px;
            padding: 8px 27px;
        }
        
    }


    @media only screen and (max-width: 1399px) {

        .wpfm-wp-anniv__title-wp {
            font-size: 20px;
        }

        .wpfm-wp-anniv__image--four img {
            max-width: 275px;
        }

        .wpfm-wp-anniv__subtitle-anniversary {
            font-size: 20px;
        }

        .wpfm-wp-anniv__text-flex svg {
            width: 90px;
            height: 23px;
        }

    }

    @media only screen and (max-width: 1024px) {

        .wpfm-wp-anniv__content-area {
            gap: 40px;
        }

        .wpfm-wp-anniv__image--right img {
            max-width: 126px;
        }

        .wpfm-wp-anniv__image--group {
            gap: 20px;
        }

        .wpfm-wp-anniv__image--left img {
            max-width: 170px;
            margin-left: -30px;
        }

        .wpfm-wp-anniv__image--right img {
            max-width: 126px;
            margin-right: -30px;
        }

        .wpfm-wp-anniv__text-flex svg {
            width: 60px;
            height: 20px;
        }

        .wpfm-wp-anniv__title-wp {
            font-size: 16px;
        }

        .wpfm-wp-anniv__subtitle-anniversary {
            font-size: 16px;
        }

        .wpfm-wp-anniv__image--four img {
            max-width: 250px;
        }

        .wpfm-wp-anniv__image--four img {
            max-width: 200px;
        }

        .wpfm-wp-anniv__btn {
            font-size: 12px;
            line-height: 1.2;
            padding: 12px 21px;
            font-weight: 400;
        }

        .wpfm-wp-anniv__btn {
            box-shadow: none;
        }

    }

    @media only screen and (max-width: 768px) {

        .wpvr-promotional-banner {
            max-height: 62px;
        }

        .wpfm-wp-anniv__image--group {
            gap: 15px;
        }

        .wpfm-wp-anniv__image--four img {
            max-width: 200px;
        }

        .wpfm-wp-anniv__image--left,
        .wpfm-wp-anniv__image--right {
            display: none;
        }

        .wpfm-wp-anniv__btn {
            font-size: 12px;
            line-height: 1;
            font-weight: 400;
            padding: 13px 20px;
            margin-left: 0;
            box-shadow: none;
        }
    }

    @media only screen and (max-width: 767px) {
        .wpvr-promotional-banner {
            padding-top: 20px;
            padding-bottom: 30px;
            max-height: none;
        }

        .wpvr-promotional-banner {
            max-height: none;
        }

        .wpfm-wp-anniv__image--right,
        .wpfm-wp-anniv__image--left {
            display: none;
        }

        .wpfm-wp-anniv__stroke-font {
            font-size: 16px;
        }

        .wpfm-wp-anniv__content-area {
            flex-direction: column;
            gap: 25px;
            text-align: center;
            align-items: center;
        }
        .wpfm-wp-anniv__btn-area {
            justify-content: center;
            padding-top: 5px;
        }
        .wpfm-wp-anniv__btn {
            font-size: 12px;
            padding: 15px 24px;
        }
        .wpfm-wp-anniv__image--group {
            gap: 10px;
            padding: 0;
        }
    }

		</style>

		<?php
	}
}