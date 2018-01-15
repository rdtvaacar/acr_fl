<script>
    function fl_new() {
        $('#fl_form').show();
    }

    $(function () {
        // Variable to store your files
        var files;
        // Add events
        $('input[type=file]').on('change', prepareUpload);

        $('#fl_form_form').on('submit', uploadFiles);

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
                    console.log(data);

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
                            $('#fl_loading').hide();
                            $('#fl_complete').show();

                        },
                        processData: false, // Don't process the files
                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                        success: function (data, textStatus, jqXHR) {
                            if (typeof data.error === 'undefined') {
                                // Success so call function to process the form
                                // submitForm(event, data);

                                var items = [];
                                $.each($.parseJSON(data), function (key, val) {
                                    items.push("<li id='" + key + "'><div style='float: left'>" + val + "</div><div class='fl_complete'></div></li>");
                                });
                                $("<ul/>", {
                                    "class": "lf_list",
                                    html: items.join("")
                                }).appendTo("#fl_yuklenenler");
                                $('#fl_yuklenenler').show();
                            }
                            else {
                                var items = [];
                                $.each($.parseJSON(data), function (key, val) {
                                    items.push("<li id='" + key + "'><div style='float: left'>" + val + "</div><div class='fl_error'></div></li>");
                                });
                                $("<ul/>", {
                                    "class": "lf_list",
                                    html: items.join("")
                                }).appendTo("#fl_yuklenenler");
                                $('#fl_yuklenenler').show();
                                console.log('ERRORS: ' + data.error);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // Handle errors here
                            console.log('ERRORS: ' + textStatus);
                            // STOP LOADING SPINNER
                        }
                    });
                    data.delete(key);
                }
            );


        }

        function submitForm(event, data) {
            // Create a jQuery object from the form
            $form = $(event.target);

            // Serialize the form data
            var formData = $form.serialize();

            // You should sterilise the file names
            $.each(data.files, function (key, value) {
                formData = formData + '&filenames[]=' + value;
            });
            $.ajax({
                url: 'submit.php',
                type: 'POST',
                data: formData,
                cache: false,
                dataType: 'json',
                success: function (data, textStatus, jqXHR) {
                    if (typeof data.error === 'undefined') {
                        // Success so call function to process the form
                        console.log('SUCCESS: ' + data.success);
                    }
                    else {
                        // Handle errors here
                        console.log('ERRORS: ' + data.error);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Handle errors here
                    console.log('ERRORS: ' + textStatus);
                },
                complete: function () {
                    // STOP LOADING SPINNER
                }
            });
        }
    });

    function fl_file_delete(id) {
        if (confirm('Silmek istediğinizden eminmisiniz?')) {
            $.ajax({
                type: 'post',
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
            type: 'post',
            url: '/acr/fl/download',
            data: 'file_id=' + id,
            success: function (url) {
                window.open(url);
            }
        });
    }
</script>