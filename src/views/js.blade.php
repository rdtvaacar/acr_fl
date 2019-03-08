<script>
    function fl_new() {
        $('#fl_form').show();
        $('#fl_list').hide();
    }

    $(function () {
        // Variable to store your files
        var files;
        // Add events
        $('input[type=file]').on('change', prepareUpload);

        $('.fl_form').on('submit', uploadFiles);

        // Grab the files and set them to our variable
        function prepareUpload(event) {
            files = event.target.files;
        }

        // Catch the form submit and upload the files
        function uploadFiles(event) {
            event.stopPropagation(); // Stop stuff happening
            event.preventDefault(); // Totally stop stuff happening
            // START A LOADING SPINNER HERE
            // Create a formdata object and add the files
            var data = new FormData();
            @foreach($data as $key=> $datum)
            data.append("{{$key}}", "{{$datum}}");
            @endforeach
            $.each(files, function (key, value) {
                    data.append(key, value);
                    $.ajax({
                        url: '/acr/fl/upload',
                        type: 'POST',
                        data: data,
                        cache: false,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#fl_form').hide();
                            $('#fl_loading').show();
                            $('#fl_complete').hide();
                        },
                        complete: function () {
                            if (files.length == (key + 1)) {
                                $('#fl_loading').hide();
                                $('#fl_complete').show();
                            }
                        },
                        processData: false, // Don't process the files
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        success: function (data, textStatus, jqXHR) {
                            if (typeof data.error === 'undefined') {
                                // Success so call function to process the form
                                // submitForm(event, data);
                                var items = [];
                                var veri = $.parseJSON(JSON.stringify(data))
                                items.push("<li><div style='float: left'><a href='" + veri.data[0].zero + "'>" + veri.data[0].name_org + "</a></div><div class='fl_complete'></div></li><div style='clear:both;'></div>");
                                $("<ul/>", {
                                    "class": "lf_list",
                                    html: items.join("")
                                }).appendTo(".fl_yuklenenler");
                                $('.fl_yuklenenler').show();
                            }
                            else {
                                var items = [];
                                $.each($.parseJSON(JSON.stringify(data)), function (key, val) {
                                    items.push("<li id='" + key + "'><div style='float: left'>" + val.name + "</div><div class='fl_error'></div></li>");
                                });
                                $("<ul/>", {
                                    "class": "lf_list",
                                    html: items.join("")
                                }).appendTo(".fl_yuklenenler");
                                $('.fl_yuklenenler').show();
                                // console.log('ERRORS: ' + data.error);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Handle errors here
                            // console.log('ERRORS: ' + textStatus);
                            // STOP LOADING SPINNER
                        }
                    });
                    data.delete(key);
                }
            );

        }

        function fl_file_delete(id) {
            if (confirm('Silmek istediğinizden eminmisiniz?')) {
                $.ajax({
                    type: 'POST',
                    url: '/acr/fl/file/delete',
                    data: 'id=' + id,
                    success: function () {
                        $('#fl_file_div_' + id).hide(400);
                        $('#fl_file').show();
                    }
                });
            }
        }

        function fl_download(id) {
            $.ajax({
                type: 'POST',
                url: '/acr/fl/download',
                data: 'file_id=' + id,
                success: function (url) {
                    window.open(url);
                }
            });
        }
</script>