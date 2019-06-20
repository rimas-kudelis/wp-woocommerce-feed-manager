(function( $ ) {
    'use strict';

    /**
     * all of the code for your admin-facing javascript source
     * should reside in this file.
     *
     * note: it has been assumed you will write jquery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * this enables you to define handlers, for when the dom is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * when the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * ideally, it is not considered best practise to attach more than a
     * single dom-ready or window-load handler for a particular page.
     * although scripts in the wordpress core, plugins and themes may be
     * practising this, we should strive to set a better example in our own work.
     */



    /*
     ** database update
     */

    function wpfm_update_database(event) {
        event.preventDefault();
        var payload = {};
        var $this = $(this);

        $('.wpfm-db-update-loader').fadeIn();


        $.ajax({
            type : "post",
            dataType : "json",
            url : rex_wpfm_ajax.ajax_url,
            data: {
                action   : 'rex_wpfm_database_update',
                security : rex_wpfm_ajax.ajax_nonce,
            },
            success: function(response) {
                console.log('woohoo!');
                setTimeout(function(){
                    location.reload();
                }, 1000);
            },
            error: function(){
                console.log( 'uh, oh!' );
            }
        });
    }
    $(document).on('click', '#rex-wpfm-update-db', wpfm_update_database);



})( jQuery );


