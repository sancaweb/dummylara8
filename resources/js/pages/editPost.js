$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"').attr("content"),
        },
    });
    $.fn.modal.Constructor.prototype._enforceFocus = function () {};

    tinymce.init({
        height: "1000",
        selector: "textarea#content", // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins:
            "advlist code table lists autolink link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table directionality emoticons template",

        toolbar:
            "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | " +
            "bullist numlist outdent indent | link image | print preview media fullscreen | " +
            "forecolor backcolor emoticons | help",
        file_picker_callback: function (callback, value, meta) {
            var x =
                window.innerWidth ||
                document.documentElement.clientWidth ||
                document.getElementsByTagName("body")[0].clientWidth;
            var y =
                window.innerHeight ||
                document.documentElement.clientHeight ||
                document.getElementsByTagName("body")[0].clientHeight;

            var cmsURL = "/filemanager?editor=" + meta.fieldname;
            if (meta.filetype == "image") {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url: cmsURL,
                title: "Filemanager",
                width: x * 0.8,
                height: y * 0.8,
                resizable: "yes",
                close_previous: "no",
                onMessage: (api, message) => {
                    callback(message.content);
                },
            });
        },
    });

    $("#categories").select2({
        theme: "bootstrap4",
        placeholder: "Select Category",
        allowClear: true,
    });

    $("#inputFoto").filemanager("image");

    $("#tags")
        .select2({
            theme: "bootstrap4",
            // placeholder: "Tags",
            minimumInputLength: 3,
            multiple: true,
            allowClear: true,
            tags: true,
            ajax: {
                url: base_url + "/ajax/post/tags",
                dataType: "json",
                quietMillis: 100,
                data: function (params) {
                    return {
                        search: params.term,
                    };
                },
                processResults: function (data, params) {
                    return { results: data };
                },
            },
        })
        .on("select2:select", function (res) {});

    //setSelectedTags
    var idPost = $("#id_post").val();
    var tagsSelect = $("#tags");
    $.ajax({
        type: "GET",
        url: base_url + "/ajax/post/" + idPost + "/select2tagbypost",
    }).then(function (data) {
        // create the option and append to Select2
        var option;
        $.each(data, function (inTag, valTag) {
            option = new Option(valTag.text, valTag.id, true, true);
            tagsSelect.append(option).trigger("change");
        });

        // manually trigger the `select2:select` event
        tagsSelect.trigger({
            type: "select2:select",
            params: {
                data: data,
            },
        });
    });

    let publishedDate = moment($("#inputPublishedDate").val(), [
        "DD-MM-YYYY HH:mm:ss",
        "YYYY-MM-DD HH:mm:ss",
    ]);

    $("#published_date").datetimepicker({
        minDate: moment(publishedDate).format("YYYY-MM-DD HH:mm:ss"),
        sideBySide: true,
        icons: {
            time: "far fa-clock",
            date: "far fa-calendar-alt",
        },
        date: publishedDate,
        format: "DD-MM-YYYY HH:mm:ss",
        useCurrent: false,
    });

    $("#formPost").on("submit", function (e) {
        e.preventDefault();
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });
        var formData = new FormData($("#formPost")[0]);
        var url = $("#formPost").attr("action");
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (data) {
                Swal.fire({
                    icon: "success",
                    title: data.meta.message,
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false,
                }).then(function () {
                    window.location.replace("/post");
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseJSON.data.errorValidator) {
                    var errors = jqXHR.responseJSON.data.errorValidator;
                    var message = jqXHR.responseJSON.message;
                    var li = "";
                    $.each(errors, function (key, value) {
                        li += "<li>" + value + "</li>";
                    });

                    Swal.fire({
                        icon: "error",
                        title: message,
                        html:
                            '<div class="alert alert-danger text-left" role="alert">' +
                            "<ul>" +
                            li +
                            "</ul>" +
                            "</div>",
                        footer: "Pastikan data yang anda masukkan sudah benar!",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    });
                } else {
                    var message = jqXHR.responseJSON.meta.message;
                    var data = jqXHR.responseJSON.data;

                    Swal.fire({
                        icon: "error",
                        title:
                            message + " <br>Copy error dan hubungi Programmer!",
                        html:
                            '<div class="alert alert-danger text-left" role="alert">' +
                            "<p>Error Message: <strong>" +
                            message +
                            "</strong></p>" +
                            "<p>Error: " +
                            data.error +
                            "</p>" +
                            "</div>",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    });
                }
            },
        });
    });
}); // ./end document
