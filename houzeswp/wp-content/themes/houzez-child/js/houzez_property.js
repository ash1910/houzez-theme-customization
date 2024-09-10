/**
 * Created by waqasriaz on 06/10/15.
 */
jQuery(document).ready( function($) {
    "use strict";

    if ( typeof houzezProperty !== "undefined" ) {

        var ajax_url = houzezProperty.ajaxURL;
        var verify_nonce = houzezProperty.verify_nonce;
        var verify_file_type = houzezProperty.verify_file_type;
        var max_prop_attachment1s = houzezProperty.max_prop_attachments;
        var attachment1_max_file_size = houzezProperty.attachment_max_file_size;

        /* ------------------------------------------------------------------------ */
        /*  Property attachment1 delete
         /* ------------------------------------------------------------------------ */
        var propertyAttachment1Events = function() {

            $( "#houzez_attachment1s_container" ).sortable({
                revert: 100,
                placeholder: "attachment1s-placeholder",
                handle: ".sort-attachment1",
                cursor: "move"
            });

            //Remove Image
            $('.attachment1-delete').on('click', function(e){
                e.preventDefault();
                var $this = $(this);
                var thumbnail = $this.closest('.attach-thumb');
                var loader = $this.siblings('.icon-loader');
                var prop_id = $this.data('attach-id');
                var thumb_id = $this.data('attachment1-id');

                loader.show();

                var ajax_request = $.ajax({
                    type: 'post',
                    url: ajax_url,
                    dataType: 'json',
                    data: {
                        'action': 'houzez_remove_property_documents',
                        'prop_id': prop_id,
                        'thumb_id': thumb_id,
                        'removeNonce': verify_nonce
                    }
                });

                ajax_request.done(function( response ) {
                    if ( response.remove_attachment1 ) {
                        thumbnail.remove();
                    } else {

                    }
                });

                ajax_request.fail(function( jqXHR, textStatus ) {
                    alert( "Request failed: " + textStatus );
                });

            });

        }
        propertyAttachment1Events();

        //Js for property attachment1s upload
        var houzez_property_attachment1s = function() {

            var atch_uploader = new plupload.Uploader({
                browse_button: 'select_attachment1s',
                file_data_name: 'property_attachment1_file',
                url: ajax_url + "?action=houzez_property_attachment1_upload&verify_nonce=" + verify_nonce,
                filters: {
                    mime_types : [
                        { title : verify_file_type, extensions : "jpg,jpeg,png,pdf,zip" }
                    ],
                    max_file_size: attachment1_max_file_size,
                    prevent_duplicates: true
                }
            });
            atch_uploader.init();

            atch_uploader.bind('FilesAdded', function(up, files) {
                var houzez_thumbs = "";
                var maxfiles = max_prop_attachment1s;
                if(up.files.length > maxfiles ) {
                    up.splice(maxfiles);
                    alert('no more than '+maxfiles + ' file(s)');
                    return;
                }
                plupload.each(files, function(file) {
                    houzez_thumbs += '<tr id="attachment1-holder-' + file.id + '" class="attach-thumb">' + '' + '</tr>';
                });
                document.getElementById('houzez_attachment1s_container').innerHTML += houzez_thumbs;
                up.refresh();
                atch_uploader.start();
            });


            atch_uploader.bind('UploadProgress', function(up, file) {
                document.getElementById( "attachment1-holder-" + file.id ).innerHTML = '<span>' + file.percent + "%</span>";
            });

            atch_uploader.bind('Error', function( up, err ) {
                document.getElementById('houzez_atach_errors').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
            });

            atch_uploader.bind('FileUploaded', function ( up, file, ajax_response ) {
                var response = $.parseJSON( ajax_response.response );

                if ( response.success ) {

                    var attachment1_file = ''+
                        '<td class="table-full-width table-cell-title">'+
                            '<span>'+ response.attach_title +'</span>'+
                        '</td>'+
                        '<td>'+
                            '<a href="'+ response.url +'" target="_blank" class="btn btn-light-grey-outlined"><i class="houzez-icon icon-download-bottom"></i></a>'+
                        '</td>'+
                        '<td>'+
                            '<button data-attach-id="' + 0 + '"  data-attachment1-id="' + response.attachment1_id + '" class="attachment1-delete btn btn-light-grey-outlined"><i class="houzez-icon icon-close"></i></button>'+
                        '</td>'+
                        '<td class="sort-attachment1">'+
                            '<a class="btn btn-light-grey-outlined"><i class="houzez-icon icon-navigation-menu"></i></a>'+
                        '</td>'+
                        '<input type="hidden" class="propperty-attach-id" name="propperty_attachment1_ids[]" value="' + response.attachment1_id + '"/>';

                    document.getElementById( "attachment1-holder-" + file.id ).innerHTML = attachment1_file;

                    propertyAttachment1Events();

                } else {
                    console.log ( response );
                }
            });

        }
        houzez_property_attachment1s();


    }

});