(function( $ ) {
    'use strict';

    var progressWidth = 0;

    $(function() {
        $(".meter > span").each(function() {
            $(this)
                .data("origWidth", $(this).width())
                .width(0)
                .animate({
                    width: $(this).data("origWidth")
                }, 1200);
        });
    });



    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).ready(function() {
        $('select').formSelect();
        $('.ui-timepicker-select').formSelect('destroy');

        if ( $('#rex_feed_xml_file').val() == '' ) {
            $('#rex_feed_file_link').slideUp('fast');
        }

        $('.rex-tabs').tabs();



    });

    /**
     * Add a new table-row and update it's
     */
    $(document).on('click', '#rex-new-attr', function () {
        var rowId = $(this).siblings('#config-table').find('tbody tr').length;
        var lastrow = $(this).siblings('#config-table').find('tbody tr:last');
        var parent = $(this).siblings('#config-table').parent();

        if(parent.hasClass('rex-feed-config-filter')) {
            var filter = true;
        }else {
            filter = false;
        }


        $(this).siblings('#config-table').find('tbody tr:first')
            .clone()
            .insertAfter(lastrow)
            .attr('data-row-id', rowId)


        var $row = $(this).siblings('#config-table').find("[data-row-id='" + rowId + "']");
        $row.find('ul.dropdown-content.select-dropdown, .caret, .select-dropdown ').remove();

        $row.find('input, select').val('');

        updateFormNameAtts( $row, rowId, filter);
        $row.find('select').formSelect();


    });


    /**
     * Delete a table-row and update all row-id
     * beneath it and their input attributes names.
     */
    $(document).on('click', '#config-table .delete', function () {
        var $nextRows, rowId;

        var table = $(this).closest('table');
        var parent = table.parent();

        // delete row and get it's row-id
        rowId = $(this).closest('tr').remove().data('row-id');

        if(parent.hasClass('rex-feed-config-filter')) {
            var filter = true;
        }else {
            filter = false;
        }

        // Gell the next rows
        if ( rowId == 0) {
            $nextRows = $('#config-table tbody').children();
        }else{
            $nextRows = $('#config-table').find("[data-row-id='" + (rowId -1) + "']").nextAll('tr');
        }

        // Update their row-id and name attributes
        $nextRows.each( function (index, el) {
            $(el).attr( 'data-row-id', rowId);
            updateFormNameAtts( $(el), rowId, filter);
            rowId++;
        });
    });

    /**
     * Function for updating select and input box name
     * attribute under a table-row.
     */
    function updateFormNameAtts( $row, rowId, filter){
        var name, $el;
        $el = $row.find('input, select');
        $el.each(function(index, item) {
            name = $(item).attr('name');

            if ( name != undefined ) {
                // get new name via regex
                if (filter) {
                    name = name.replace(/^ff\[\d+\]/, 'ff[' + rowId + ']');
                    $(item).attr('name', name);
                }else {
                    name = name.replace(/^fc\[\d+\]/, 'fc[' + rowId + ']');
                    $(item).attr('name', name);
                }

            }

        });
    }


    /**
     * Event listener for Attribute type change functionality.
     */
    $(document).on('change', 'select.type-dropdown', function () {
        var selected = $(this).find('option:selected').val();
        if ( selected == 'static' ) {
            $(this).closest('td').next('td').find('.meta-dropdown').hide();
            $(this).closest('td').next('td').find('.static-input').show();
        }else{
            $(this).closest('td').next('td').find('.static-input').hide();
            $(this).closest('td').next('td').find('.meta-dropdown').show();
        }
    });

    /**
     * Event listener for Filter Product.
     */
    $(document).on('change', '#rex_feed_products', function () {
        var selected = $('#rex_feed_products').find(':selected').val();
        if ( selected == 'filter' ) {
            $('.cmb2-id-rex-feed-config-filter-title').show();
            $('#rex-feed-config-filter').show();
        }else{
            $('.cmb2-id-rex-feed-config-filter-title').hide();
            $('#rex-feed-config-filter').hide();
        }
    });



    /**
     * Event listener for feed type change.
     */
    $(document).on('change', '#rex_feed_merchant', function () {
        var selected = $(this).find('option:selected').val();
        var csv = '';
        if ( selected == 'google' ) {
            $('.cmb2-id-rex-feed-feed-format').hide();
        }else{
            $('.cmb2-id-rex-feed-feed-format').show();
        }
        // if ( selected == 'facebook' ) {
        //     $("#rex_feed_feed_format option[value='csv']").remove();
        //     csv = 'removed';
        // }
        // if(selected != 'facebook' || selected != 'google' ){
        //     if(csv == 'removed'){
        //         $("#rex_feed_feed_format").append("<option value='csv'>CSV</option>");
        //     }
        // }
    });


    /**
     * Event listener for Merchant change functionality.
     */
    $(document).on('change', '#rex_feed_merchant', function () {

        var $confBox = $('.rex-feed-config');

        $confBox.find('.rex-loading-spinner').slideDown('fast');

        var $payload = {
            merchant: $('#rex_feed_merchant').find(':selected').val(),
            post_id: $('#post_ID').val()
            // feed_format: $('#rex_feed_feed_format').find(':selected').val()
        };


        wpAjaxHelperRequest( 'merchant-change', $payload )
            .success( function( response ) {
                $confBox.fadeOut();
                $confBox.find('#config-table').html( response );
                $('select').formSelect();
                $('.rex-loading-spinner').fadeOut('fast');
                $confBox.fadeIn();
            })
            .error( function( response ) {
                console.log( 'Uh, oh! Merchant change returned error!' );
                console.log( response.statusText );
            });
    });

    function get_checkbox_val( name ){
        var items = 'input[name="rex_feed_' + name + '[]"]';
        var vals = [];

        $(items).each( function (){
            if( $(this).prop('checked') == true){
                vals.push( $(this).val() );
            }
        });

        return vals;
    }


    // $(document).on('click', '#publish', save_feed);



    function get_product_number(event) {
        event.preventDefault();
        var $payload = {};
        $('#publishing-action span.spinner').addClass('is-active');
        $(this).addClass('disabled');
        $('.bwfm-progressbar, .progress-msg').fadeIn();
        $('.progress-msg span').html('Calculating products.....')

        wpAjaxHelperRequest( 'my-handle', $payload )
            .success( function( response ) {
                // console.log('Total Number of Products: ' + response.products);
                generate_feed(response.products, 0, 1);
            })
            .error( function( response ) {
                $('#publishing-action span.spinner').removeClass('is-active');
                $('#publish').removeClass('disabled');
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on('click', '#publish', get_product_number);


    function generate_feed( product, offset, batch ) {

        var $payload = {
            merchant: $('#rex_feed_merchant').find(':selected').val(),
            feed_format: $('#rex_feed_feed_format').find(':selected').val(),
            info : {
                post_id     : $('#post_ID').val(),
                title       : $('#title').val(),
                desc        : $('#title').val(),
                offset      : offset,
                batch       : batch
            },

            products: {
                products_scope: $('#rex_feed_products').find(':selected').val(),
                tags: get_checkbox_val('tags'),
                cats: get_checkbox_val('cats'),
            },

            feed_config : $('form').serialize(),
        };

        var batches = Math.ceil( product/100 );
        console.log('Total Batch: '+ batches);
        console.log('Total Product(s): '+ product);
        console.log('Processing Batch Number: '+ batch);
        console.log('Offset Number: '+ offset);

        var progressbar = 100/batches;
        progressWidth = progressWidth + progressbar;
        // feed_progressBar(progressWidth);
        if (progressWidth >= 100) {
            $('.progress-msg span').html('Generating feed. Please wait.....');
        }else {
            $('.progress-msg span').html('Processing feed.....');
        }



        wpAjaxHelperRequest( 'generate-feed', $payload )
            .success( function( response ) {
                console.log( 'Woohoo!' );
                console.log(response);
                var msg = '<div id="message" class="error notice notice-error is-dismissible"><p>You feed exceed the limit.Please <a href="edit.php?post_type=product-feed&page=best-woocommerce-feed-pricing">Upgrade!!!</a> </p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

                if(response == 'false' || response == ''){
                    generate_feed(product, offset, batch);
                }else if (response.msg == 'finish') {
                    feed_progressBar(progressWidth);
                    $('#publish').removeClass('disabled');
                    $(document).off( 'click', '#publish', get_product_number );
                    $('#publish').trigger( 'click' );
                } else {
                    if ( batch < batches ) {
                        setTimeout(function(){
                            offset = offset + 100;
                            batch++;
                            feed_progressBar(progressWidth);
                            generate_feed(product, offset, batch);
                        }, 2000);
                    }
                }
            })
            .error( function( response ) {
                $('#publishing-action span.spinner').removeClass('is-active');
                $('#publish').removeClass('disabled');
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });

    }

    function feed_progressBar(width) {

        $('.progressbar-bar').animate({
            width:width + '%'
        },1000);
        $('.progressbar-bar-percent').html(width+ '%');

    }


    /*
     * google merchant settings
     */
    function save_google_merchant_settings(event) {
        event.preventDefault();
        $('.rex-loading-spinner').slideDown('fast');
        var payload = {
            client_id : $(this).find('#client_id').val(),
            client_secret : $(this).find('#client_secret').val(),
            merchant_id : $(this).find('#merchant_id').val(),
            merchant_settings: true
        };
        wpAjaxHelperRequest( 'google-merchant-settings', payload )
            .success( function( response ) {
                console.log('Woohoo!');
                $('.merchant-action').html(response.html);
                $('.rex-loading-spinner').fadeOut('fast');
            })
            .error( function( response ) {
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });


    }
    $(document).on('submit', '#rex-google-merchant', save_google_merchant_settings);




    /*
     * Send feed to Google
     * Merchant Center
     */
    function send_to_google(event) {
        event.preventDefault();
        $('.rex-loading-spinner').slideDown('fast');
        var payload = {
            feed_id     : $('#post_ID').val(),
            schedule    : $('#rex_feed_google_schedule option:selected').val(),
            hour        : $('#rex_feed_google_schedule_time option:selected').val(),
            country     : $('#rex_feed_google_target_country').val(),
            language    : $('#rex_feed_google_target_language').val()
        };

        if ($('#rex_feed_google_schedule option:selected').val() == 'monthly') {
            payload['month'] = $('#rex_feed_google_schedule_month option:selected').val();
            payload['day'] = '';
        }else if ($('#rex_feed_google_schedule option:selected').val() == 'weekly') {
            payload['day'] = $('#rex_feed_google_schedule_week_day option:selected').val();
            payload['month'] = '';
        }else {
            payload['month'] = '';
            payload['day'] = '';
        }

        console.log(payload);

        $('.rex-google-status').html('<p>Sending......</p>');
        wpAjaxHelperRequest( 'send-to-google', payload )
            .success( function( response ) {
                console.log('Woohoo!');
                console.log(response);
                $('.rex-loading-spinner').fadeOut('fast');
                location.reload();
            })
            .error( function( response ) {
                $('.rex-loading-spinner').fadeOut('fast');
                $('.rex-google-status').html('<div class="rex-error">Something is wrong! Please try again</div>');
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on('click', '#send-to-google', send_to_google);


    function reset_form(event) {
        event.preventDefault();
        $(this).closest('form').find("input[type=text]").not(':disabled').val("");
        $(this).closest('form').find("button[type=submit]").prop('disabled', false);
    }
    $(document).on('click', '.rex-reset-btn', reset_form);


    function product_custom_field_settings() {
        var payload = {};
        if($('#rex-product-custom-field').is(":checked")) {
            payload = {
                custom_field : 'yes',
            };
        }else {
            payload = {
                custom_field : 'no',
            };
        }
        wpAjaxHelperRequest( 'rex-product-custom-field', payload )
            .success( function( response ) {
                console.log('Woohoo!');
            })
            .error( function( response ) {
                console.log( 'Uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on('change', '#rex-product-custom-field', product_custom_field_settings);


})( jQuery );

