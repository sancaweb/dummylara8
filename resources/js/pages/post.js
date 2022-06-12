$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"').attr("content"),
        },
    });
    $.fn.modal.Constructor.prototype._enforceFocus = function () {};

    var columnsTable = [
        { data: "no" },
        { data: "title" },
        { data: "post_created_by" },
        { data: "category_name" },
        { data: "tags" },
        { data: "published_date" },
        { data: "status" },
        { data: "action" },
    ];

    var tablePosts = $("#table-posts").DataTable({
        // "searching": false,
        order: [[0, "DESC"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/post/datatable",
            dataType: "json",
            type: "POST",
            data: function (dataFilter) {
                var tglFilter = $("#tglFilterField").val();
                var titleContentFilter = $("#titleContentFilter").val();
                var catFilter = $("#catFilter").val();
                var tagFilter = $("#tagFilter").val();
                var userFilter = $("#userFilter").val();
                var statusFilter = $("#statusFilter").val();

                dataFilter.tglFilter = tglFilter;
                dataFilter.titleContentFilter = titleContentFilter;
                dataFilter.catFilter = catFilter;
                dataFilter.tagFilter = tagFilter;
                dataFilter.userFilter = userFilter;
                dataFilter.statusFilter = statusFilter;
            },
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.responseJSON.data) {
                var error = jqXHR.responseJSON.data.error;
                Swal.fire({
                    icon: "error",
                    title: " <br>Copy error dan hubungi Programmer!",
                    html:
                        '<div class="alert alert-danger text-left" role="alert">' +
                        "<p>Error Message: <strong>" +
                        error +
                        "</strong></p>" +
                        "</div>",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                }).then(function () {
                    refreshTable();
                });
            } else {
                var message = jqXHR.responseJSON.message;
                var errorLine = jqXHR.responseJSON.line;
                var file = jqXHR.responseJSON.file;
                Swal.fire({
                    icon: "error",
                    title: " <br>Copy error dan hubungi Programmer!",
                    html:
                        '<div class="alert alert-danger text-left" role="alert">' +
                        "<p>Error Message: <strong>" +
                        message +
                        "</strong></p>" +
                        "<p>File: " +
                        file +
                        "</p>" +
                        "<p>Line: " +
                        errorLine +
                        "</p>" +
                        "</div>",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                }).then(function () {
                    refreshTable();
                });
            }
        },

        columns: columnsTable,
        columnDefs: [
            {
                orderable: false,
                targets: [0, 1, -1, -4],
            },
            {
                targets: [-1],
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).addClass("text-center");
                },
            },
        ],
    });
    $("#table-posts_filter input").off();
    $("#table-posts_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tablePosts.search(this.value).draw();
        }
    });

    function refreshTable() {
        tablePosts.search("").draw();
    }

    var btnPostReload = document.getElementById("btn-postReload");

    if (btnPostReload) {
        btnPostReload.addEventListener("click", function () {
            refreshTable();
        });
    }

    /** ./end datatable */

    /**FILTER
     *
     */

    $("#btn-filter").on("click", function () {
        filterFunctions();
        $("#modalFilter").modal({
            show: true,
            backdrop: "static",
            keyboard: false, // to prevent closing with Esc button (if you want this too)
        });
    });

    $(".closeFilter").on("click", function () {
        closeFilter();
    });

    function closeFilter() {
        $("#modalFilter").modal("hide");
    }

    $("#tglFilter").daterangepicker(
        {
            maxDate: moment().format("DD/MM/YYYY"),
            autoUpdateInput: false,
            locale: {
                format: "DD/MM/YYYY",
            },
        },
        function (start, end, label) {
            var choosen_val =
                start.format("DD/MM/YYYY") + " - " + end.format("DD/MM/YYYY");
            $("#tglFilterField").val(choosen_val);
        }
    );

    function filterFunctions() {
        $("#tagFilter")
            .select2({
                theme: "bootstrap4",
                minimumInputLength: 3,
                placeholder: "Pilih Tag",
                allowClear: true,
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
    }

    //reset
    $("#btn-resetFilterReload").on("click", function () {
        resetFilter();
        refreshTable();
    });
    $("#resetFilter").on("click", function () {
        resetFilter();
    });

    $("#btn-proFilter").on("click", function () {
        proFilter();
        closeFilter();
    });

    function proFilter() {
        tablePosts.search("").draw();
    }

    function resetFilter() {
        $("#formFilter")[0].reset();
        $("#catFilter").val("").trigger("change");

        $("#tagFilter").empty();

        filterFunctions();

        $("#tglFilterField").val("");
        $("#tglFilter").data("daterangepicker").setStartDate(new Date());
        $("#tglFilter").data("daterangepicker").setEndDate(new Date());
    }
    /**
     * ./END FILTER
     */

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

    $("#categories,#catFilter").select2({
        theme: "bootstrap4",
        placeholder: "Select Category",
        allowClear: true,
    });

    $("#tags")
        .select2({
            theme: "bootstrap4",
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

    $("#inputFoto").filemanager("image");

    $("#published_date").datetimepicker({
        minDate: moment().format("YYYY-MM-DD HH:mm:ss"),
        sideBySide: true,
        icons: {
            time: "far fa-clock",
            date: "far fa-calendar-alt",
        },
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

    //rubah status
    $("#table-posts").on("click", ".btnStatus", function () {
        var title = $(this).data("title");

        Swal.fire({
            title: "Anda yakin?",
            text: "Anda yakin ingin merubah Post dengan judul: " + title + "?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, change!",
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    imageUrl: base_url + "/images/loading.gif",
                    imageHeight: 300,
                    showConfirmButton: false,
                    title: "Loading ...",
                    allowOutsideClick: false,
                });

                var idPost = $(this).data("id");
                var status = $(this).data("status");

                $.ajax({
                    url: base_url + "/ajax/post/status",
                    type: "POST",
                    data: {
                        idPost: idPost,
                        status: status,
                        _method: "patch",
                    },
                    // contentType: false,
                    // processData: false,
                    dataType: "JSON",
                    success: function (data) {
                        Swal.fire({
                            icon: "success",
                            title: data.meta.message,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false,
                        }).then(function () {
                            Swal.close();
                            refreshTable();
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseJSON.data) {
                            var message = jqXHR.responseJSON.meta.message;
                            var data = jqXHR.responseJSON.data;
                            Swal.fire({
                                icon: "error",
                                title:
                                    message +
                                    " <br>Copy error dan hubungi Programmer!",
                                html:
                                    '<div class="alert alert-danger text-left" role="alert">' +
                                    "<p>Error Message: <strong>" +
                                    data.message +
                                    "</strong></p>" +
                                    "<p>Error: " +
                                    data.error.errorInfo +
                                    "</p>" +
                                    "</div>",
                                allowOutsideClick: false,
                            });
                        } else {
                            var errors = jqXHR.responseJSON.errors;
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
                            });
                        }
                    },
                });
            }
        });
    });
    //./end rubah status

    /**
     * DELETE POST
     */
    $("#table-posts").on("click", ".btn-delete", function () {
        var title = $(this).data("title");
        Swal.fire({
            title: "Anda yakin?",
            text: "Anda yakin ingin merubah Post dengan judul: " + title + "?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    imageUrl: base_url + "/images/loading.gif",
                    imageHeight: 300,
                    showConfirmButton: false,
                    title: "Loading ...",
                    allowOutsideClick: false,
                });

                var idPost = $(this).data("id");
                var urlDelete = base_url + "/post/" + idPost + "/delete";
                $.ajax({
                    url: urlDelete,
                    type: "DELETE",
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        Swal.fire({
                            icon: "success",
                            title: data.data.message,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false,
                        }).then(function () {
                            refreshTable();
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        var error = jqXHR.responseJSON;

                        if (error.meta) {
                            var message = error.meta.message;
                        } else {
                            var message = error.message;
                        }
                        Swal.fire({
                            icon: "error",
                            title: message,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false,
                        });
                    },
                });
            }
        });
    });

    /**
     * BATAS EDITING ====
     */

    // $("#inputFoto").on("change", function () {
    //     var review = "imageReview";
    //     var linkFoto = "linkFoto";
    //     readURL(this, review, linkFoto);
    // });

    // function readURL(input, review, linkFoto) {
    //     if (input.files && input.files[0]) {
    //         var reader = new FileReader();

    //         reader.onload = function (e) {
    //             $("#" + review).attr("src", e.target.result);
    //             $("#" + linkFoto).attr("href", e.target.result);
    //         };

    //         reader.readAsDataURL(input.files[0]); // convert to base64 string
    //     }
    // }

    // function formReset() {
    //     $("#formPost")[0].reset();
    //     $("#formPost").attr("action", base_url + "/user");
    //     $("#role").val("").trigger("change");
    //     $('[name="_method"]').remove();
    //     $("#imageReview").attr("src", base_url + "/images/no-image.png");
    //     $("#linkFoto").attr("href", base_url + "/images/no-image.png");

    //     $("#modalFormInputLabel").html(
    //         '<i class="fas fa-user-plus"></i>&nbsp; Add User'
    //     );
    // }

    // $(".openForm").on("click", function () {
    //     openForm();
    // });

    // $(".closeForm").on("click", function () {
    //     closeForm();
    // });

    // function openForm() {
    //     $("#modalFormInput").modal({
    //         show: true,
    //         backdrop: "static",
    //         keyboard: false, // to prevent closing with Esc button (if you want this too)
    //     });
    // }

    // function closeForm() {
    //     $("#modalFormInput").modal("hide");
    //     formReset();
    // }

    // $("#role").select2({
    //     theme: "bootstrap4",
    //     placeholder: "Select Role",
    //     allowClear: true,
    // });

    // $("#formPost").on("submit", function (e) {
    //     e.preventDefault();
    //     Swal.fire({
    //         imageUrl: base_url + "/images/loading.gif",
    //         imageHeight: 300,
    //         showConfirmButton: false,
    //         title: "Loading ...",
    //         allowOutsideClick: false,
    //     });
    //     var formData = new FormData($("#formPost")[0]);
    //     var url = $("#formPost").attr("action");
    //     $.ajax({
    //         url: url,
    //         type: "POST",
    //         data: formData,
    //         contentType: false,
    //         processData: false,
    //         dataType: "JSON",
    //         success: function (data) {
    //             Swal.fire({
    //                 icon: "success",
    //                 title: data.meta.message,
    //                 showConfirmButton: false,
    //                 timer: 2000,
    //                 allowOutsideClick: false,
    //             }).then(function () {
    //                 var dataUser = data.data.user;

    //                 var self = data.data.self;

    //                 if (self == true) {
    //                     $("#userImageSide").attr(
    //                         "src",
    //                         base_url + "/storage/" + dataUser.foto
    //                     );
    //                     $("#userNameSide").html(dataUser.name);
    //                 }

    //                 refreshTable();

    //                 closeForm();
    //             });
    //         },
    //         error: function (jqXHR, textStatus, errorThrown) {
    //             if (jqXHR.responseJSON.data.errorValidator) {
    //                 var errors = jqXHR.responseJSON.data.errorValidator;
    //                 var message = jqXHR.responseJSON.message;
    //                 var li = "";
    //                 $.each(errors, function (key, value) {
    //                     li += "<li>" + value + "</li>";
    //                 });

    //                 Swal.fire({
    //                     icon: "error",
    //                     title: message,
    //                     html:
    //                         '<div class="alert alert-danger text-left" role="alert">' +
    //                         "<ul>" +
    //                         li +
    //                         "</ul>" +
    //                         "</div>",
    //                     footer: "Pastikan data yang anda masukkan sudah benar!",
    //                     allowOutsideClick: false,
    //                     showConfirmButton: true,
    //                 });
    //             } else {
    //                 var message = jqXHR.responseJSON.meta.message;
    //                 var data = jqXHR.responseJSON.data;

    //                 Swal.fire({
    //                     icon: "error",
    //                     title:
    //                         message + " <br>Copy error dan hubungi Programmer!",
    //                     html:
    //                         '<div class="alert alert-danger text-left" role="alert">' +
    //                         "<p>Error Message: <strong>" +
    //                         message +
    //                         "</strong></p>" +
    //                         "<p>Error: " +
    //                         data.error +
    //                         "</p>" +
    //                         "</div>",
    //                     allowOutsideClick: false,
    //                     showConfirmButton: true,
    //                 });
    //             }
    //         },
    //     });
    // });

    // /** Proses edit */
    // $("#table-posts").on("click", ".btn-edit", function () {
    //     Swal.fire({
    //         imageUrl: base_url + "/images/loading.gif",
    //         imageHeight: 300,
    //         showConfirmButton: false,
    //         title: "Loading ...",
    //         allowOutsideClick: false,
    //     });

    //     var idUser = $(this).data("id");
    //     var urlEdit = base_url + "/user/" + idUser + "/edit";

    //     $.ajax({
    //         url: urlEdit,
    //         type: "get",
    //         success: function (x) {
    //             var dataUser = x.data.user;

    //             $("#formPost").attr("action", x.data.action);
    //             $('<input name="_method" value="patch">')
    //                 .attr("type", "hidden")
    //                 .appendTo("#formPost");
    //             $("#modalFormInputLabel").html(
    //                 '<i class="fas fa-edit"></i>&nbsp; Edit User'
    //             );

    //             $("#imageReview").attr("src", dataUser.foto);
    //             $("#linkFoto").attr("href", dataUser.foto);

    //             $('[name="nama"]').val(dataUser.name);
    //             $('[name="username"]').val(dataUser.username);
    //             $('[name="email"]').val(dataUser.email);
    //             $("#role").val(dataUser.role).trigger("change");

    //             openForm();
    //             Swal.close();
    //         },
    //         error: function (jqXHR, textStatus, errorThrown) {
    //             var meta = jqXHR.responseJSON.meta;
    //             var data = jqXHR.responseJSON.data;

    //             Swal.fire({
    //                 icon: "error",
    //                 title: meta.message,
    //                 html:
    //                     '<div class="alert alert-danger text-left" role="alert">' +
    //                     "<p>" +
    //                     data.error +
    //                     "</p>" +
    //                     "</div>",
    //                 allowOutsideClick: false,
    //             });
    //         },
    //     });
    // });

    // /** delete user */
}); // ./end document
