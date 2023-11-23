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

		if ( in_array( $screen->base, $allowed_screens ) || in_array( $screen->parent_base, $allowed_screens ) || in_array( $screen->post_type, $allowed_screens ) || in_array( $screen->parent_file, $allowed_screens ) ) {
			echo '<input type="hidden" id="rexfeed_special_occasion" name="rexfeed_special_occasion" value="'.$this->occasion.'">';
            ?>
            <div class="rex-feed-tb__notification" id="rex_deal_notification">
                <div class="rex-feed-tb__container">
                    <div class="rex-feed-tb__content-area">


                        <div class="rex-feed-tb__image p-8 black-friday">
                            <figure>
                                <img src="<?php echo plugin_dir_url( __FILE__ ) . './assets/icon/black-friday-min.svg' ; ?>" alt="black friday"  />
                            </figure>
                        </div>
                        <!-- .rex-feed-tb__image end -->

                        <div class="rex-feed-tb__content">
                            <h4><span>Super Sale </span> <?php esc_html_e('Is Live!','rextheme')?></h4>
                        </div>
                        <!-- .rex-feed-tb__content end -->


                        <div class="rex-feed-tb__image rex-feed-tb__image-three">
                            <figure>
                                <img src="<?php echo plugin_dir_url( __FILE__ ).'./assets/icon/wpfm-fourty-min.svg' ; ?>" alt="forty percent"  />
                            </figure>
                        </div>
                        <!-- .rex-feed-tb__image end -->

                        <div id="rex-feed-tb__countdown" class="rex-feed-tb__countdown">
                            <ul>
                                <li><span id="rex-feed-tb__days">30</span>days</li>
                                <li><span id="rex-feed-tb__hours">59</span>Hours</li>
                                <li><span id="rex-feed-tb__mins">30</span>Mins</li>
                                <!-- <li><span id="rextheme__secs"></span>secs</li> -->
                            </ul>
                        </div>

                        <div class="rex-feed-tb__btn-area">
                            <a href="<?php echo esc_url('https://rextheme.com/best-woocommerce-product-feed/pricing/?utm_source=plugin_dashboard&utm_medium=plugin&utm_campaign=bfcm_pfm&utm_id=bfcm_pfm'); ?>" role="button" class="rex-feed-tb__btn" target="_blank">
                                FLAT <span class="rex-feed-tb__stroke-font">40%</span> OFF
                            </a>
                        </div>
                        <!-- .rex-feed-tb__btn-area end -->

                    </div>
                    <!-- .rex-feed-tb__content-area end -->
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
                        const countdownElement = document.getElementById('countdown');
                        const daysElement = document.getElementById('rex-feed-tb__days');
                        const hoursElement = document.getElementById('rex-feed-tb__hours');
                        const minutesElement = document.getElementById('rex-feed-tb__mins');
                        //const secondsElement = document.getElementById('seconds');

                        // Decrease the remaining time
                        timeRemaining--;

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
                        //secondsElement.textContent = seconds;

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
                font-family: "Circular Std Bold";
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/circularstd-bold.woff2"; ?>) format("woff2"), url(<?php echo "{$plugin_dir_url}assets/fonts/circularstd-bold.woff"; ?>) format("woff");
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: "LexendDeca";
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/LexendDeca-Bold.woff2"; ?>) format("woff2"), url(<?php echo "{$plugin_dir_url}assets/fonts/LexendDeca-Bold.woff"; ?>) format("woff");
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: "Inter";
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/Inter-Black.woff2"; ?>) format("woff2"), url(<?php echo "{$plugin_dir_url}assets/fonts/Inter-Black.woff"; ?>) format("woff");
                font-weight: 900;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: "Inter";
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/Inter-Bold.woff2"; ?>) format("woff2"), url(<?php echo "{$plugin_dir_url}assets/fonts/Inter-Bold.woff"; ?>) format("woff");
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: "Inter";
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/Inter-Medium.woff2"; ?>) format("woff2"), url(<?php echo "{$plugin_dir_url}assets/fonts/Inter-Medium.woff"; ?>) format("woff");
                font-weight: 500;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: "Circular Std Book";
                src: url(<?php echo "{$plugin_dir_url}assets/fonts/CircularStd-Book.woff2"; ?>) format("woff2"), url(<?php echo "{$plugin_dir_url}assets/fonts/CircularStd-Book.woff"; ?>) format("woff");
                font-weight: 500;
                font-style: normal;
                font-display: swap;
            }
            .rex-feed-tb__notification {
                position: relative;
                width: calc(100% - 20px);
                background-image: url(<?php echo "{$plugin_dir_url}assets/icon/notification.webp"; ?>);
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                object-fit: cover;
                background-color: #1E1F2E;
                z-index: 1111;
                -webkit-animation-duration: 1s;
                animation-duration: 1s;
                -webkit-animation-fill-mode: both;
                animation-fill-mode: both;
                -webkit-animation-name: goDown;
                animation-name: goDown;
                margin-top: 50px;
                box-shadow: 0px 10px 40px rgba(8, 28, 61, 0.15);
            }
            .rex-feed-tb__container {
                /* width: 100%; */
                margin: 0 auto;
                max-width: 1400px;
                padding: 0 15px;
            }
            .rex-feed-tb__content-area {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .rex-feed-tb__btn-area {
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
            }
            .rex-feed-tb__image figure {
                margin: 0;
            }
            .rex-feed-tb__image figure img {
                max-width: 248px;
            }
            .rex-feed-tb__image.p-8 {
                padding: 4px 0;
            }
            .rex-feed-tb__image-three {
                max-width: 220px;
                margin-top: -10px;
            }
            .rex-feed-tb__content h4 {
                font-family: "LexendDeca";
                font-size: 24px;
                font-style: normal;
                font-weight: 600;
                line-height: 137.3%;
                letter-spacing: 1.68px;
                color: #E5F7FF;
                text-transform: uppercase;
                margin: 0;
            }
            @media only screen and (max-width: 1199px) {
                .rex-feed-tb__content h4 {
                    font-size: 18px;
                }
            }
            @media only screen and (max-width: 991px) {
                .rex-feed-tb__content h4 {
                    font-size: 12px;
                }
            }
            .rex-feed-tb__content h4 span {
                display: block;
                font-size: 30px;
                font-style: normal;
                font-weight: 700;
                line-height: 137.3%; /* 41.19px */
                letter-spacing: 0;
                color: #00B4FF;
            }
            @media only screen and (max-width: 1199px) {
                .rex-feed-tb__content h4 span {
                    font-size: 20px;
                }
            }
            @media only screen and (max-width: 991px) {
                .rex-feed-tb__content h4 span {
                    font-size: 13px;
                }
            }
            .rex-feed-tb__content figure {
                margin: 0;
            }
            .rex-feed-tb__content figure img {
                width: auto;
                max-width: 202px;
                height: auto;
                object-fit: cover;
            }
            .rex-feed-tb__countdown li {
                display: flex;
                flex-direction: column;
                width: 68.9px;
                font-family: "Circular Std Book";
                font-size: 16px;
                font-style: normal;
                font-weight: 500;
                line-height: normal;
                letter-spacing: 1.6px;
                text-transform: uppercase;
                text-align: center;
                color: #A89CC3;
            }
            @media only screen and (max-width: 1199px) {
                .rex-feed-tb__countdown li {
                    width: 45px;
                    font-size: 12px;
                }
            }
            @media only screen and (max-width: 991px) {
                .rex-feed-tb__countdown li {
                    font-size: 10px;
                }
            }
            @media only screen and (max-width: 767px) {
                .rex-feed-tb__countdown li {
                    padding: 0;
                    font-size: 12px;
                }
            }
            .rex-feed-tb__countdown ul {
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
                gap: 20px;
            }
            .rex-feed-tb__countdown li span {
                font-size: 44px;
                font-family: "Inter";
                font-style: normal;
                font-weight: 700;
                line-height: normal;
                color: #fff;
                text-align: center;
                border-radius: 10px;
                border: 1px solid #00B4FF;
                box-shadow: 0px 5px 0px 0px #018AC4;
                background: linear-gradient(148deg, #2A0856 21.92%, #140102 80.41%) padding-box, linear-gradient(#00B4FF, #1F22FB) border-box;
                border: 1px solid transparent;
                margin-bottom: 6px;
            }
            @media only screen and (max-width: 1199px) {
                .rex-feed-tb__countdown li span {
                    font-size: 24px;
                    border-radius: 6px;
                }
            }
            @media only screen and (max-width: 991px) {
                .rex-feed-tb__countdown li span {
                    font-size: 22px;
                }
            }
            @media only screen and (max-width: 767px) {
                .rex-feed-tb__countdown li span {
                    font-size: 30px;
                }
            }
            .rex-feed-tb__btn {
                position: relative;
                font-family: "LexendDeca";
                font-size: 20px;
                font-style: normal;
                font-weight: 700;
                line-height: normal;
                color: #FFF;
                text-align: center;
                border-radius: 15px;
                background: #216DF0;
                padding: 14px 24px;
                display: inline-block;
                cursor: pointer;
                text-transform: uppercase;
                text-decoration: none;
                transition: all 0.3s ease;
            }
            .rex-feed-tb__btn:hover {
                background-color: #216DF0;
                color: #ffffff;
            }
            .rex-feed-tb__cross-top {
                position: absolute;
                right: -12px;
                top: -12px;
                cursor: pointer;
            }
            .rex-feed-tb__stroke-font {
                font-size: 26px;
                font-weight: 700;
            }

            @media only screen and (max-width: 1470px) {
                .rex-feed-tb__notification {
                    background-position: right;
                }
                .rex-feed-tb__content-area {
                    padding: 0px 0;
                }
                .rex-feed-tb__image.black-friday img {
                    max-width: 180px;
                }
                .rex-feed-tb__image.rex-feed-tb__image-three {
                    margin-top: 0px;
                }
                .rex-feed-tb__image.rex-feed-tb__image-three img {
                    max-width: 190px;
                }
                .rex-feed-tb__countdown li span {
                    font-size: 35px;
                }
            }
            @media only screen and (max-width: 1399px) {
                .rex-feed-tb__container {
                    padding: 0 20px;
                }
            }
            @media only screen and (max-width: 1199px) {
                .rex-feed-tb__content figure img {
                    max-width: 150px;
                }
                .rex-feed-tb__countdown ul {
                    gap: 15px;
                }
                .rex-feed-tb__btn {
                    font-size: 14px;
                    line-height: 20px;
                    padding: 10px 22px;
                }
                .rex-feed-tb__image figure {
                    margin: 0;
                }
                .rex-feed-tb__image figure img {
                    max-width: 190px;
                }
                .rex-feed-tb__image.black-friday img {
                    max-width: 130px;
                }
                .rex-feed-tb__image.rex-feed-tb__image-three {
                    margin-top: 0px;
                }
                .rex-feed-tb__image.rex-feed-tb__image-three img {
                    max-width: 130px;
                }
                .rex-feed-tb__countdown li span {
                    font-size: 26px;
                }
                .rex-feed-tb__btn {
                    font-size: 15px;
                    line-height: 20px;
                    padding: 10px 16px;
                    border-radius: 10px;
                    font-weight: 400;
                }
                .rex-feed-tb__stroke-font {
                    font-size: 20px;
                }
            }
            @media only screen and (max-width: 991px) {
                .rex-feed-tb__content-area {
                    gap: 20px;
                }
                .rex-feed-tb__notification {
                    margin-top: 80px;
                }
                .rex-feed-tb__btn {
                    font-size: 12px;
                    line-height: 16px;
                    padding: 10px 15px;
                }
                .rex-feed-tb__stroke-font {
                    font-size: 18px;
                }
                .rex-feed-tb__image.black-friday img {
                    max-width: 115px;
                }
                .rex-feed-tb__image.rex-feed-tb__image-three img {
                    max-width: 120px;
                }
                .rex-feed-tb__countdown li span {
                    font-size: 24px;
                }
            }
            @media only screen and (max-width: 767px) {
                .rex-feed-tb__notification {
                    padding: 27px 0;
                }
                .rex-feed-tb__cross-top {
                    right: -4px;
                }
                .rex-feed-tb__notification {
                    margin-top: 60px;
                }
                .rex-feed-tb__content-area {
                    gap: 10px;
                    flex-direction: column;
                }
                .rex-feed-tb__btn {
                    font-size: 14px;
                    line-height: 18px;
                    padding: 11px 22px;
                }
                .rex-feed-tb__content h4 {
                    font-size: 26px;
                    text-align: center;
                }
                .rex-feed-tb__notification {
                    background-position: 13%;
                }
            }
            /* ANIMATION for go down */
            @-webkit-keyframes goDown {
                0% {
                    -webkit-transform: translate3d(0, -100%, 0);
                    transform: translate3d(0, -100%, 0);
                }
                100% {
                    -webkit-transform: none;
                    transform: none;
                }
            }
            @keyframes goDown {
                0% {
                    -webkit-transform: translate3d(0, -100%, 0);
                    transform: translate3d(0, -100%, 0);
                }
                100% {
                    -webkit-transform: none;
                    transform: none;
                }
            }
		</style>
		<?php
	}
}