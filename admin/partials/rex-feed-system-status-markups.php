<?php
$system_status = Rex_Feed_System_Status::get_all_system_status();
?>
<div id="tab5" class="tab-content block-wrapper">
    <div class="system-status">
        <h3 class="title"><?php echo esc_html__('System Status', 'rex-product-feed'); ?></h3>
        <button type="button" class="button" id="rex-feed-system-status-copy-btn"><?php esc_html_e( 'Copy Status', 'rex-product-feed' );?></button>
        <table class="wpfm_status_table widefat" id="status" cellspacing="0">
            <tbody>
            <?php
            foreach ( $system_status as $status ) {
                if ( isset( $status[ 'label' ] ) && $status[ 'label' ] !== '' && isset( $status[ 'message' ] ) && $status[ 'message' ] !== '' ) {
            ?>
            <tr>
                <td><?php echo esc_html__( $status[ 'label' ], 'rex-product-feed' ); ?></td>
                <td>
                    <?php
                    $message = $status[ 'message' ];  //phpcs:ignore
                    $classes = 'dashicons dashicons-yes';
                    if ( $status[ 'label' ] === 'Product Types' || $status[ 'label' ] === 'Total Products by Types' ) {
                        $classes = '';
                    }
                    if ( isset( $status[ 'status' ] ) && $status[ 'status' ] === 'error' || isset( $status[ 'is_writable' ] ) && $status[ 'is_writable' ] === 'False' ) {
                        echo '<mark class="error"><span class="dashicons dashicons-warning"></span>' . $message . '</mark> ';
                    }
                    else {
                        echo '<mark class="yes"><span class="' . $classes . '"></span>' . $message . '</mark> ';
                    }
                    ?>
                </td>
            </tr>
            <?php
                }
            }?>
            </tbody>
        </table>
    </div>
    <textarea name="" id="rex-feed-system-status-area" style="visibility: hidden; margin-top: 10px" cols="100" rows="30"><?php echo Rex_Feed_System_Status::get_system_status_text(); //phpcs:ignore?></textarea>
</div>