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
        $('#rex_feed_conf select, #rex_feed_products select').niceSelect();
        //$('.ui-timepicker-select').formSelect('destroy');

        if ( $('#rex_feed_xml_file').val() == '' ) {
            $('#rex_feed_file_link').slideUp('fast');
        }

        //$('.rex-tabs').tabs();

        //---------popup when click disabled input-------
        $( ".single-merchant > .disabled .lever, .single-merchant .wpfm-switcher.disabled .lever" ).on("click", function(){
            $(".premium-merchant-alert").addClass("show-alert");
        });

        $( ".premium-merchant-alert .close, .premium-merchant-alert button.close, .premium-merchant-alert" ).on("click", function(){
            $(".premium-merchant-alert").removeClass("show-alert");
        });

        $(".premium-merchant-alert .alert-box").on("click", function (e) {
            e.stopPropagation();
        });
        

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
        $row.find('select').niceSelect();


    });


    /**
     * Delete a table-row and update all row-id
     * beneath it and their input attributes names.
     */
    $(document).on('click', '#config-table .delete-row', function () {
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
    // $(document).on('change', '#rex_feed_merchant', function () {
    //     var selected = $(this).find('option:selected').val();
    //     var csv = '';
    //     if ( selected == 'google' ) {
    //         $('.cmb2-id-rex-feed-feed-format').hide();
    //     }else{
    //         $('.cmb2-id-rex-feed-feed-format').show();
    //     }
    // });


    /**
     * Event listener for Merchant change functionality.
     */
    $(document).on('change', '#rex_feed_merchant', function () {

        var $confBox = $('.rex-feed-config');

        $confBox.find('.rex-loading-spinner').css('display', 'flex');

        var $payload = {
            merchant: $('#rex_feed_merchant').find(':selected').val(),
            post_id: $('#post_ID').val()
            // feed_format: $('#rex_feed_feed_format').find(':selected').val()
        };


        wpAjaxHelperRequest( 'merchant-change', $payload )
            .success( function( response ) {
                $confBox.fadeOut();
                $confBox.find('#config-table').html( response );
                $('#rex_feed_conf select, #rex_feed_products select').niceSelect();
                $('.rex-loading-spinner').css('display', 'none');
                $confBox.fadeIn();
            })
            .error( function( response ) {
                $('.rex-loading-spinner').css('display', 'none');
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
        $('#wpfm-feed-clock').stopwatch().stopwatch('start');
        setTimeout(function() {
            var $payload = {};
            $('#publishing-action span.spinner').addClass('is-active');
            $(this).addClass('disabled');
            $('.bwfm-progressbar, .progress-msg').fadeIn();
            $('.progress-msg span').html('Calculating products.....');
            wpAjaxHelperRequest( 'my-handle', $payload )
                .success( function( response ) {
                    // console.log('Total Number of Products: ' + response.products);
                    var per_batch = response.perBatch ? parseInt(response.perBatch) : 50;
                    generate_feed(response.products, 0, 1, per_batch);
                })
                .error( function( response ) {
                    $('#publishing-action span.spinner').removeClass('is-active');
                    $('#publish').removeClass('disabled');
                    console.log( 'Uh, oh!' );
                    console.log( response.statusText );
                });
        }, 800);

    }
    $(document).on('click', '#publish', get_product_number);


    function generate_feed( product, offset, batch, per_batch ) {

        per_batch = typeof per_batch !== 'undefined' ? per_batch : 50;

        var $payload = {
            merchant: $('#rex_feed_merchant').find(':selected').val(),
            feed_format: $('#rex_feed_feed_format').find(':selected').val(),
            localization: $('#rex_feed_ebay_mip_localization').find(':selected').val(),
            ebay_cat_id: $('#rex_feed_ebay_seller_category').val(),
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

        var batches = Math.ceil( product/per_batch );
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
                    generate_feed(product, offset, batch, per_batch);
                }else if (response.msg == 'finish') {
                    feed_progressBar(progressWidth);
                    $('#wpfm-feed-clock').stopwatch().stopwatch('stop');
                    setTimeout(function() {
                        $('#publish').removeClass('disabled');
                        $(document).off( 'click', '#publish', get_product_number );
                        $('#publish').trigger( 'click' );
                    }, 1000);

                } else {
                    if ( batch < batches ) {
                        setTimeout(function(){
                            offset = offset + per_batch;
                            batch++;
                            feed_progressBar(progressWidth);
                            generate_feed(product, offset, batch, per_batch);
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
            width: Math.ceil(width) + '%'
        },1000);
        $('.progressbar-bar-percent').html(Math.ceil(width)+ '%');
    }


    /*
     * google merchant settings
     */
    function save_google_merchant_settings(event) {
        event.preventDefault();
        $('.rex-loading-spinner').css('display', 'flex');
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
                $('.rex-loading-spinner').css('display', 'none');
            })
            .error( function( response ) {
                console.log( 'Uh, oh!' );
                $('.rex-loading-spinner').css('display', 'none');
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
        $('.rex-loading-spinner').css('display', 'flex');
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
                $('.rex-loading-spinner').css('display', 'none');
                location.reload();
            })
            .error( function( response ) {
                $('.rex-loading-spinner').css('display', 'none');
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


    /**
     * Change merchant status
     */
    function product_feed_change_merchant_status() {
        var payload = {};
        var $this = $(this);
        var key = $this.attr('data-value');
        var name = $this.attr('data-name');
        var isfree = $this.attr('data-is-free');
        if($this.is(":checked")) {
            payload[key] = {
                status : 1,
                name : name,
                free: isfree,
            };
        }else {
            payload[key] = {
                status : 0,
                name : name,
                free: isfree,
            };
        }
        wpAjaxHelperRequest( 'rex-product-change-merchant-status', payload )
            .success( function( response ) {
                console.log('woohoo!');
            })
            .error( function( response ) {
                console.log( 'uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on('change', '.switch-input', product_feed_change_merchant_status);


    /**
     * Update product per batch
     * @param e
     */
    function update_per_batch(e) {
        e.preventDefault();
        var $form = $(this);
        $form.find("button.save-batch span").text("");
        $form.find("button.save-batch i").show();
        var per_batch = $form.find('#wpfm_product_per_batch').val();
        wpAjaxHelperRequest( 'rex-product-update-batch-size', per_batch )
            .success( function( response ) {
                $form.find("button.save-batch i").hide();
                $form.find("button.save-batch span").text("saved");
                setTimeout(function(){
                    $form.find("button.save-batch span").text("save");
                }, 1000);
                console.log('woohoo!');
            })
            .error( function( response ) {
                $form.find("button.save-batch i").hide();
                $form.find("button.save-batch span").text("failed");
                setTimeout(function(){
                    $form.find("button.save-batch span").text("save");
                }, 1000);
                console.log( 'uh, oh!' );
                console.log( response.statusText );
            });
    }
    $(document).on("submit", "#wpfm-per-batch", update_per_batch);
    
    
    //----------setting tab-------
    $(document).ready(function(){
        $('ul.rex-settings-tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.rex-settings-tabs li').removeClass('active');
            $('.rex-settings-tab-content .tab-content').removeClass('active');

            $(this).addClass('active');
            $("#"+tab_id).addClass('active');
        });

    });




})( jQuery );

