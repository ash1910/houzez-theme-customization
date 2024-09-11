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
        /*  Property attachment1 Sort , delete
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

        //Js for property attachment1s upload
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


    }

});