/**
 * Created by waqasriaz on 06/10/15.
 */
jQuery(document).ready( function($) {
    "use strict";

    if ( typeof houzezProperty !== "undefined" ) {

        var ajax_url = houzezProperty.ajaxURL;
        var verify_nonce = houzezProperty.verify_nonce;
        var verify_file_type = houzezProperty.verify_file_type;
        var max_prop_attachments = houzezProperty.max_prop_attachments;
        var attachment_max_file_size = houzezProperty.attachment_max_file_size;

        /* ------------------------------------------------------------------------ */
        /*  Property attachment Sort , delete
         /* ------------------------------------------------------------------------ */
        var propertyAttachmentEvents = function() {

            $( ".houzez_attachments_form_container" ).sortable({
                revert: 100,
                placeholder: "attachments-placeholder",
                handle: ".sort-attachment",
                cursor: "move"
            });

            //Remove Image
            $('.attachment-form-delete').on('click', function(e){
                e.preventDefault();
                var $this = $(this);
                var thumbnail = $this.closest('.attach-thumb');
                var loader = $this.siblings('.icon-loader');
                var prop_id = $this.data('attach-id');
                var thumb_id = $this.data('attachment-id');
                var meta_name = $this.data('meta_name');

                loader.show();

                var ajax_request = $.ajax({
                    type: 'post',
                    url: ajax_url,
                    dataType: 'json',
                    data: {
                        'action': 'houzez_remove_property_documents_form',
                        'prop_id': prop_id,
                        'thumb_id': thumb_id,
                        'meta_name': meta_name,
                        'removeNonce': verify_nonce
                    }
                });

                ajax_request.done(function( response ) {
                    if ( response.remove_attachment ) {
                        thumbnail.remove();
                    } else {

                    }
                });

                ajax_request.fail(function( jqXHR, textStatus ) {
                    alert( "Request failed: " + textStatus );
                });

            });

        }
        propertyAttachmentEvents();

        //Js for property attachments upload
        var houzez_property_attachments = function() {

            var atch_uploader = new plupload.Uploader({
                browse_button: 'select_attachments_form_a',
                file_data_name: 'property_attachment_file',
                url: ajax_url + "?action=houzez_property_attachment_upload&verify_nonce=" + verify_nonce,
                filters: {
                    mime_types : [
                        { title : verify_file_type, extensions : "jpg,jpeg,png,pdf,zip" }
                    ],
                    max_file_size: attachment_max_file_size,
                    prevent_duplicates: true
                }
            });
            atch_uploader.init();

            atch_uploader.bind('FilesAdded', function(up, files) {
                var houzez_thumbs = "";
                var maxfiles = max_prop_attachments;
                if(up.files.length > maxfiles ) {
                    up.splice(maxfiles);
                    alert('no more than '+maxfiles + ' file(s)');
                    return;
                }
                plupload.each(files, function(file) {
                    houzez_thumbs += '<tr id="attachment-holder-' + file.id + '" class="attach-thumb">' + '' + '</tr>';
                });
                document.getElementById('houzez_attachments_form_a_container').innerHTML += houzez_thumbs;
                up.refresh();
                atch_uploader.start();
            });


            atch_uploader.bind('UploadProgress', function(up, file) {
                document.getElementById( "attachment-holder-" + file.id ).innerHTML = '<span>' + file.percent + "%</span>";
            });

            atch_uploader.bind('Error', function( up, err ) {
                document.getElementById('houzez_atach_errors').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
            });

            atch_uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
                var response = $.parseJSON( ajax_response.response );

                if ( response.success ) {

                    var attachment_file = ''+
                        '<td class="table-full-width table-cell-title">'+
                            '<span>'+ response.attach_title +'</span>'+
                        '</td>'+
                        '<td>'+
                            '<a href="'+ response.url +'" target="_blank" class="btn btn-light-grey-outlined"><i class="houzez-icon icon-download-bottom"></i></a>'+
                        '</td>'+
                        '<td>'+
                            '<button data-meta_name="fave_attachments_form_a" data-attach-id="' + 0 + '"  data-attachment-id="' + response.attachment_id + '" class="attachment-form-delete btn btn-light-grey-outlined"><i class="houzez-icon icon-close"></i></button>'+
                        '</td>'+
                        '<td class="sort-attachment">'+
                            '<a class="btn btn-light-grey-outlined"><i class="houzez-icon icon-navigation-menu"></i></a>'+
                        '</td>'+
                        '<input type="hidden" class="propperty-attach-id" name="propperty_attachment_form_a_ids[]" value="' + response.attachment_id + '"/>';

                    document.getElementById( "attachment-holder-" + file.id ).innerHTML = attachment_file;

                    propertyAttachmentEvents();

                } else {
                    console.log ( response );
                }
            });


            //Js for property attachments title_deed  upload

            var atch_uploader_title_deed = new plupload.Uploader({
                browse_button: 'select_attachments_title_deed',
                file_data_name: 'property_attachment_file',
                url: ajax_url + "?action=houzez_property_attachment_upload&verify_nonce=" + verify_nonce,
                filters: {
                    mime_types : [
                        { title : verify_file_type, extensions : "jpg,jpeg,png,pdf,zip" }
                    ],
                    max_file_size: attachment_max_file_size,
                    prevent_duplicates: true
                }
            });
            atch_uploader_title_deed.init();

            atch_uploader_title_deed.bind('FilesAdded', function(up, files) {
                var houzez_thumbs = "";
                var maxfiles = max_prop_attachments;
                if(up.files.length > maxfiles ) {
                    up.splice(maxfiles);
                    alert('no more than '+maxfiles + ' file(s)');
                    return;
                }
                plupload.each(files, function(file) {
                    houzez_thumbs += '<tr id="attachment-holder-' + file.id + '" class="attach-thumb">' + '' + '</tr>';
                });
                document.getElementById('houzez_attachments_title_deed_container').innerHTML += houzez_thumbs;
                up.refresh();
                atch_uploader_title_deed.start();
            });


            atch_uploader_title_deed.bind('UploadProgress', function(up, file) {
                document.getElementById( "attachment-holder-" + file.id ).innerHTML = '<span>' + file.percent + "%</span>";
            });

            atch_uploader_title_deed.bind('Error', function( up, err ) {
                document.getElementById('houzez_atach_errors').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
            });

            atch_uploader_title_deed.bind('FileUploaded', function ( up, file, ajax_response ) {
                var response = $.parseJSON( ajax_response.response );

                if ( response.success ) {

                    var attachment_file = ''+
                        '<td class="table-full-width table-cell-title">'+
                            '<span>'+ response.attach_title +'</span>'+
                        '</td>'+
                        '<td>'+
                            '<a href="'+ response.url +'" target="_blank" class="btn btn-light-grey-outlined"><i class="houzez-icon icon-download-bottom"></i></a>'+
                        '</td>'+
                        '<td>'+
                            '<button data-meta_name="fave_attachments_title_deed" data-attach-id="' + 0 + '"  data-attachment-id="' + response.attachment_id + '" class="attachment-form-delete btn btn-light-grey-outlined"><i class="houzez-icon icon-close"></i></button>'+
                        '</td>'+
                        '<td class="sort-attachment">'+
                            '<a class="btn btn-light-grey-outlined"><i class="houzez-icon icon-navigation-menu"></i></a>'+
                        '</td>'+
                        '<input type="hidden" class="propperty-attach-id" name="propperty_attachment_title_deed_ids[]" value="' + response.attachment_id + '"/>';

                    document.getElementById( "attachment-holder-" + file.id ).innerHTML = attachment_file;

                    propertyAttachmentEvents();

                } else {
                    console.log ( response );
                }
            });

            //Js for property attachments passport  upload

            var atch_uploader_passport = new plupload.Uploader({
                browse_button: 'select_attachments_passport',
                file_data_name: 'property_attachment_file',
                url: ajax_url + "?action=houzez_property_attachment_upload&verify_nonce=" + verify_nonce,
                filters: {
                    mime_types : [
                        { title : verify_file_type, extensions : "jpg,jpeg,png,pdf,zip" }
                    ],
                    max_file_size: attachment_max_file_size,
                    prevent_duplicates: true
                }
            });
            atch_uploader_passport.init();

            atch_uploader_passport.bind('FilesAdded', function(up, files) {
                var houzez_thumbs = "";
                var maxfiles = max_prop_attachments;
                if(up.files.length > maxfiles ) {
                    up.splice(maxfiles);
                    alert('no more than '+maxfiles + ' file(s)');
                    return;
                }
                plupload.each(files, function(file) {
                    houzez_thumbs += '<tr id="attachment-holder-' + file.id + '" class="attach-thumb">' + '' + '</tr>';
                });
                document.getElementById('houzez_attachments_passport_container').innerHTML += houzez_thumbs;
                up.refresh();
                atch_uploader_passport.start();
            });


            atch_uploader_passport.bind('UploadProgress', function(up, file) {
                document.getElementById( "attachment-holder-" + file.id ).innerHTML = '<span>' + file.percent + "%</span>";
            });

            atch_uploader_passport.bind('Error', function( up, err ) {
                document.getElementById('houzez_atach_errors').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
            });

            atch_uploader_passport.bind('FileUploaded', function ( up, file, ajax_response ) {
                var response = $.parseJSON( ajax_response.response );

                if ( response.success ) {

                    var attachment_file = ''+
                        '<td class="table-full-width table-cell-title">'+
                            '<span>'+ response.attach_title +'</span>'+
                        '</td>'+
                        '<td>'+
                            '<a href="'+ response.url +'" target="_blank" class="btn btn-light-grey-outlined"><i class="houzez-icon icon-download-bottom"></i></a>'+
                        '</td>'+
                        '<td>'+
                            '<button data-meta_name="fave_attachments_passport" data-attach-id="' + 0 + '"  data-attachment-id="' + response.attachment_id + '" class="attachment-form-delete btn btn-light-grey-outlined"><i class="houzez-icon icon-close"></i></button>'+
                        '</td>'+
                        '<td class="sort-attachment">'+
                            '<a class="btn btn-light-grey-outlined"><i class="houzez-icon icon-navigation-menu"></i></a>'+
                        '</td>'+
                        '<input type="hidden" class="propperty-attach-id" name="propperty_attachment_passport_ids[]" value="' + response.attachment_id + '"/>';

                    document.getElementById( "attachment-holder-" + file.id ).innerHTML = attachment_file;

                    propertyAttachmentEvents();

                } else {
                    console.log ( response );
                }
            });


        }
        houzez_property_attachments();

        /*-------------------------------------------------------------------
         *  Transfer Listing Credit [user_profile.php]
         * ------------------------------------------------------------------*/
        $(".houzez_transfer_listing").click( function(e) {
            e.preventDefault();

            var $this = $(this);
            var $form = $this.parents( 'form' );
            var $block = $this.parents( '.dashboard-content-block' );
            var $result = $block.find('.notify');

            $.ajax({
                url: ajax_url,
                data: $form.serialize(),
                method: $form.attr('method'),
                dataType: "JSON",

                beforeSend: function( ) {
                    $this.find('.houzez-loader-js').addClass('loader-show');
                },
                success: function(data) { 
                    if( data.success ) {
                        alert(data.msg);
                        location.reload();
                        //$result.empty().append('<div class="alert alert-success alert-dismissible fade show" role="alert">'+data.msg+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    } else {
                        $result.empty().append('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.msg+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                },
                error: function(errorThrown) {

                },
                complete: function(){
                    $this.find('.houzez-loader-js').removeClass('loader-show');
                }
            });

        });

        /*--------------------------------------------------------------------------
         *  Property actions
         * -------------------------------------------------------------------------*/
        $('.houzez-prop-action-js-child').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            var prop_id = $this.attr('data-propid');
            var type = $this.attr('data-type');

            bootbox.confirm({
                message: "<strong>"+houzezProperty.are_you_sure_text+"</strong>",
                buttons: {
                    confirm: {
                        label: houzezProperty.confirm_btn_text,
                        className: 'btn btn-primary'
                    },
                    cancel: {
                        label: houzezProperty.cancel_btn_text,
                        className: 'btn btn-grey-outlined'
                    }
                },
                callback: function (result) {
                    if(result==true) {
                        fave_processing_modal( houzezProperty.processing_text );
                        houzez_property_actions(prop_id, $this, type);
                        $this.unbind("click");
                    }
                }
            });
            
        });

        var houzez_property_actions = function( prop_id, currentDiv, type ) {

            var $messages = $('#dash-prop-msg');

            $.ajax({
                type: 'POST',
                url: ajax_url,
                dataType: 'JSON',
                data: {
                    'action' : 'houzez_property_actions_child',
                    'propid' : prop_id,
                    'type': type
                },
                success: function ( res ) {

                    if( res.success ) {
                        window.location.reload();
                    } else {
                        houzez_processing_modal_close();
                        $('html, body').animate({
                            scrollTop: $(".dashboard-content-inner-wrap").offset().top
                        }, 'slow');
                        $messages.empty().append('<div class="alert alert-danger alert-dismissible fade show" role="alert">Your Reload credit has expired. <a class="btn btn-primary" href="/membership-info/?packages=1">Add more Reload credits</a><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }

                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }

            });//end ajax
        }

        var houzez_processing_modal_close = function ( ) {
            jQuery('#fave_modal').modal('hide');
        };

    }

});

function functionShowMore(){
    var elList = document.querySelectorAll('.moreType');
    elList.forEach(el => el.style.display = "block");

    document.querySelector('.page-type-show-more.show-more').style.display = 'none';
    document.querySelector('.page-type-show-more.show-less').style.display = 'block';
}
function functionShowLess(){
    var elList = document.querySelectorAll('.moreType');
    elList.forEach(el => el.style.display = "none");

    document.querySelector('.page-type-show-more.show-more').style.display = 'block';
    document.querySelector('.page-type-show-more.show-less').style.display = 'none';
}

/* ------------------------------------------------------------------------ */
/*  Property phone, whatsapp view track
/* ------------------------------------------------------------------------ */

var propertyViewTrack = function($) {

    //view track
    $('.tracking_view').on('click', function(e){
        //e.preventDefault();
        //console.log("tracking_view");
        var $this = $(this);
        var ajaxurl = houzez_vars.admin_url+ 'admin-ajax.php';
        //var userID = houzez_vars.user_id;
        
        var type = $this.data('type');
        var prop_id = $this.data('prop_id');
        
        var ajax_request = $.ajax({
            type: 'post',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action': 'houzez_add_tracking_views',
                'type': type,
                'prop_id': prop_id
            }
        });

        ajax_request.done(function( response ) {
            if ( response.success ) {

            } else {

            }
        });

        ajax_request.fail(function( jqXHR, textStatus ) {
            //alert( "Request failed: " + textStatus );
        });

    });
}

var houzez_activities_update_url = function($) {

    $('#activities_by_month').on( 'change', function() {
        var activities_by_month  = $('#activities_by_month').val();

        //var queryStringParts = [];
        if(activities_by_month) {
            //queryStringParts.push('activities_by_month=' + encodeURIComponent(activities_by_month));
            newUrl = replaceUrlParam("activities_by_month", encodeURIComponent(activities_by_month));
            newUrl = replaceUrlParam("activities_by_day", '', newUrl);
            window.location.href = newUrl;
        }

        //houzez_activities_update_url_n(queryStringParts);
    });

    $('#activities_by_day').on( 'change', function() {
        var activities_by_day    = $('#activities_by_day').val();

        //var queryStringParts = [];
        if(activities_by_day) {
            //queryStringParts.push('activities_by_day=' + encodeURIComponent(activities_by_day));
            newUrl = replaceUrlParam("activities_by_day", encodeURIComponent(activities_by_day));
            newUrl = replaceUrlParam("activities_by_month", '', newUrl);
            window.location.href = newUrl;
        }
        //houzez_activities_update_url_n(queryStringParts);

    });
}

var houzez_activities_update_url_n = function(queryStringParts) {

    var queryString = queryStringParts.join('&');

    var newUrl = "/board/";
    if (queryString) {
        newUrl += '?' + queryString;
    }
    window.location.href = newUrl;
}

function replaceUrlParam(paramName, paramValue, url){
    if (url == null) {
        url = window.location.href;
    }

    if (paramValue == null) {
        paramValue = '';
    }

    var pattern = new RegExp('\\b('+paramName+'=).*?(&|#|$)');
    if (url.search(pattern)>=0) {
        return url.replace(pattern,'$1' + paramValue + '$2');
    }

    url = url.replace(/[?#]$/,'');
    return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue;
}

jQuery(document).ready( function($) {
    "use strict";

    /*--------------------------------------------------------------------------
    *  Property View Tracking
    * -------------------------------------------------------------------------*/
    
    propertyViewTrack($);

    /*--------------------------------------------------------------------------
    *  Activities Filter
    * -------------------------------------------------------------------------*/
    houzez_activities_update_url($);


    /* ------------------------------------------------------------------------ */
    /*  24 Hours Visits Chart
        /* ------------------------------------------------------------------------ */
    var visits_chart_24h = $('#visits-chart-24h-n');

    if( visits_chart_24h.length > 0 ) {

        var labels = visits_chart_24h.data('labels');
        var views = visits_chart_24h.data('views');
        var unique = visits_chart_24h.data('unique');
        var visit_label = visits_chart_24h.data('visit-label');
        var unique_label = visits_chart_24h.data('unique-label');

        var ctx = document.getElementById('visits-chart-24h-n').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: visit_label,
                    data: views,
                    backgroundColor: [
                    'rgba(54, 170, 113, 0.1)',
                    ],
                    borderColor: [
                    'rgba(54, 170, 113, 1)',
                    ],
                    borderWidth: 2
                },{
                    label: unique_label,
                    data: unique,
                    backgroundColor: [
                    'rgba(250, 131, 52, 0.3)',
                    ],
                    borderColor: [
                    //'rgba(54, 170, 113, 1)',
                    'rgba(250, 131, 52, 1)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12
                    },
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        labelColor: function(tooltipItem, chart) {
                            if (tooltipItem.datasetIndex === 0) { // For 'views_7d' dataset
                                return {
                                    borderColor: 'rgba(54, 170, 113, 1)',
                                    backgroundColor: 'rgba(54, 170, 113, 1)'
                                };
                            } else if (tooltipItem.datasetIndex === 1) { // For 'unique_7d' dataset
                                return {
                                    borderColor: 'rgba(250, 131, 52, 1)',
                                    backgroundColor: 'rgba(250, 131, 52, 1)'
                                };
                            }
                        },
                        labelTextColor: function(tooltipItem, chart) {
                            return '#fff';
                        }
                    }
                }
            }
        });
    }

    /* ------------------------------------------------------------------------ */
    /*  7 days Visits Chart
        /* ------------------------------------------------------------------------ */
    var visits_chart_7d = $('#visits-chart-7d-n');

    if( visits_chart_7d.length > 0 ) {

        var labels_7d = visits_chart_7d.data('labels');
        var views_7d = visits_chart_7d.data('views');
        var unique_7d = visits_chart_7d.data('unique');
        var visit_label_7d = visits_chart_7d.data('visit-label');
        var unique_label_7d = visits_chart_7d.data('unique-label');

        var ctx = document.getElementById('visits-chart-7d-n').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels_7d,
                datasets: [{
                    label: visit_label_7d,
                    data: views_7d,
                    backgroundColor: [
                    'rgba(54, 170, 113, 0.1)',
                    ],
                    borderColor: [
                    'rgba(54, 170, 113, 1)',
                    ],
                    borderWidth: 2
                },{
                    label: unique_label_7d,
                    data: unique_7d,
                    backgroundColor: [
                    'rgba(250, 131, 52, 0.3)',
                    ],
                    borderColor: [
                    //'rgba(54, 170, 113, 1)',
                    'rgba(250, 131, 52, 1)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12
                    },
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        labelColor: function(tooltipItem, chart) {
                            if (tooltipItem.datasetIndex === 0) { // For 'views_7d' dataset
                                return {
                                    borderColor: 'rgba(54, 170, 113, 1)',
                                    backgroundColor: 'rgba(54, 170, 113, 1)'
                                };
                            } else if (tooltipItem.datasetIndex === 1) { // For 'unique_7d' dataset
                                return {
                                    borderColor: 'rgba(250, 131, 52, 1)',
                                    backgroundColor: 'rgba(250, 131, 52, 1)'
                                };
                            }
                        },
                        labelTextColor: function(tooltipItem, chart) {
                            return '#fff';
                        }
                    }
                }

            }
        });
    }

    /* ------------------------------------------------------------------------ */
    /*  30 days Visits Chart
        /* ------------------------------------------------------------------------ */
    var visits_chart_30d = $('#visits-chart-30d-n');

    if( visits_chart_30d.length > 0 ) {

        var labels_30d = visits_chart_30d.data('labels');
        var views_30d = visits_chart_30d.data('views');
        var unique_30d = visits_chart_30d.data('unique');
        var visit_label_30d = visits_chart_30d.data('visit-label');
        var unique_label_30d = visits_chart_30d.data('unique-label');

        var ctx = document.getElementById('visits-chart-30d-n').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels_30d,
                datasets: [{
                    label: visit_label_30d,
                    data: views_30d,
                    backgroundColor: [
                    'rgba(54, 170, 113, 0.1)',
                    ],
                    borderColor: [
                    'rgba(54, 170, 113, 1)',
                    ],
                    borderWidth: 2
                },{
                    label: unique_label_30d,
                    data: unique_30d,
                    backgroundColor: [
                    'rgba(250, 131, 52, 0.3)',
                    ],
                    borderColor: [
                    //'rgba(54, 170, 113, 1)',
                    'rgba(250, 131, 52, 1)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12
                    },
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        labelColor: function(tooltipItem, chart) {
                            if (tooltipItem.datasetIndex === 0) { // For 'views_7d' dataset
                                return {
                                    borderColor: 'rgba(54, 170, 113, 1)',
                                    backgroundColor: 'rgba(54, 170, 113, 1)'
                                };
                            } else if (tooltipItem.datasetIndex === 1) { // For 'unique_7d' dataset
                                return {
                                    borderColor: 'rgba(250, 131, 52, 1)',
                                    backgroundColor: 'rgba(250, 131, 52, 1)'
                                };
                            }
                        },
                        labelTextColor: function(tooltipItem, chart) {
                            return '#fff';
                        }
                    }
                }
            }
        });
    }


    houzez_property_actions = function( prop_id, currentDiv, type ) {

        var $messages = $('#dash-prop-msg');

        $.ajax({
            type: 'POST',
            url: ajax_url,
            dataType: 'JSON',
            data: {
                'action' : 'houzez_property_actions',
                'propid' : prop_id,
                'type': type
            },
            success: function ( res ) {

                if( res.success ) {
                    window.location.reload();
                } else {
                    houzez_processing_modal_close();
                    $('html, body').animate({
                        scrollTop: $(".dashboard-content-inner-wrap").offset().top
                    }, 'slow');
                    $messages.empty().append('<div class="alert alert-danger alert-dismissible fade show" role="alert">3nd '+houzezProperty.featured_listings_none+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                }

            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.Message);
            }

        });//end ajax
    }

});
