const { default: Swal } = require("sweetalert2");

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"').attr("content"),
        },
    });
    $.fn.modal.Constructor.prototype._enforceFocus = function () {};

    function formOpen() {
        $("#modalFormCat").modal({
            show: true,
            backdrop: "static",
            keyboard: false, // to prevent closing with Esc button (if you want this too)
        });
    }

    function cancelForm() {
        $("#formCat")[0].reset();
        $("#modalFormCat").modal("hide");
        $("#titleFormCat").empty().append("Input Category");
        $("#formCat").attr("action", base_url + "/category");
        $("#formCat").children("[name=_method]").remove();
    }

    function createSlug(string) {
        var urlSlug = base_url + "/ajax/post/" + string + "/createcatslug";

        $.ajax({
            url: urlSlug,
            type: "get",
            success: function (x) {
                var dataSlug = x.data.dataSlug;
                $("#cat_slug").val(dataSlug);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var meta = jqXHR.responseJSON.meta;
                var data = jqXHR.responseJSON.data;

                Swal.fire({
                    icon: "error",
                    title: meta.message,
                    html:
                        '<div class="alert alert-danger text-left" role="alert">' +
                        "<p>" +
                        data.error +
                        "</p>" +
                        "</div>",
                    allowOutsideClick: false,
                });
            },
        });
    }

    /**
     * CATEGORY
     */

    var columnsTable = [
        { data: "no" },
        { data: "name" },
        { data: "slug" },
        { data: "posts" },
        { data: "action" },
    ];

    var tableCats = $("#table-cats").DataTable({
        // "searching": false,
        order: [[1, "ASC"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/category/datatable",
            dataType: "json",
            type: "POST",
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
                targets: [-1],
            },
            {
                targets: [-1, -2],
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).addClass("text-center");
                },
            },
        ],
    });
    $("#table-cats_filter input").off();
    $("#table-cats_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tableCats.search(this.value).draw();
        }
    });

    function refreshTable() {
        tableCats.search("").draw();
    }

    var btnCatsReload = document.getElementById("btn-catsReload");

    if (btnCatsReload) {
        btnCatsReload.addEventListener("click", function () {
            refreshTable();
        });
    }

    /** ./end datatable */

    $("#btn-addCat").on("click", function () {
        $("#titleFormCat").empty().append("Input Category");
        formOpen();
    });

    $(".cancelFormCat").on("click", function () {
        cancelForm();
    });

    $("#table-cats").on("click", ".btn-edit", function () {
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });

        var idCat = $(this).data("id");
        var urlEdit = base_url + "/category/" + idCat + "/edit";

        $.ajax({
            url: urlEdit,
            type: "get",
            success: function (x) {
                var dataCat = x.data.dataCat;
                $("#formCat").attr("action", x.data.action);
                $('<input name="_method" value="patch">')
                    .attr("type", "hidden")
                    .appendTo("#formCat");

                $("[name=cat_name]").val(dataCat.name);
                $("[name=cat_slug]").val(dataCat.slug);
                $("#titleFormCat").empty().append("Edit Category");
                formOpen();
                Swal.close();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var meta = jqXHR.responseJSON.meta;
                var data = jqXHR.responseJSON.data;

                Swal.fire({
                    icon: "error",
                    title: meta.message,
                    html:
                        '<div class="alert alert-danger text-left" role="alert">' +
                        "<p>" +
                        data.error +
                        "</p>" +
                        "</div>",
                    allowOutsideClick: false,
                });
            },
        });
    });

    $("#cat_name").on("blur", function () {
        var string = $(this).val();
        createSlug(string, "cat");
    });

    $("#table-cats").on("click", ".btn-delete", function () {
        var name = $(this).data("name");
        Swal.fire({
            title: "Anda yakin?",
            text:
                "Anda yakin ingin menghapus Category dengan nama: " +
                name +
                "?",
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
                var idCat = $(this).data("id");

                //proses cek post
                var urlCek = base_url + "/ajax/post/" + idCat + "/cekcatpost";
                $.ajax({
                    url: urlCek,
                    type: "get",
                    success: function (x) {
                        //post tidak ditemukan, lanjutkan delete
                        proDelete(idCat);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status == 402) {
                            //data post masih ada, tentukan category
                            var alertMessage = jqXHR.responseJSON.data.error;
                            setCats(idCat, alertMessage);
                        } else {
                            var meta = jqXHR.responseJSON.meta;
                            var data = jqXHR.responseJSON.data;

                            Swal.fire({
                                icon: "error",
                                title: meta.message,
                                html:
                                    '<div class="alert alert-danger text-left" role="alert">' +
                                    "<p>" +
                                    data.error +
                                    "</p>" +
                                    "</div>",
                                allowOutsideClick: false,
                            });
                        }
                    },
                });
                //./end proses cek post
            }
        });
    });

    function proDelete(idCat) {
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });
        var urlDelete = base_url + "/category/" + idCat + "/destroy";
        $.ajax({
            url: urlDelete,
            type: "POST",
            data: {
                _method: "delete",
            },
            dataType: "JSON",
            success: function (data) {
                Swal.fire({
                    icon: "success",
                    title: data.data.message,
                    showConfirmButton: false,
                    timer: 2000,
                    allowOutsideClick: false,
                }).then(function () {
                    Swal.close();
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

    function setCats(idCat, messageAlertSetCat) {
        $.ajax({
            url: base_url + "/ajax/post/getcats",
            type: "get",
            success: function (x) {
                var dataCats = x.data.dataCats;
                $("#formSetCat").attr(
                    "action",
                    base_url + "/post/" + idCat + "/bulksetcat"
                );
                $("#messageAlertSetCat").empty().append(messageAlertSetCat);

                var catSet = '<option value=""></option>';
                $.each(dataCats, function (inCat, valCat) {
                    catSet +=
                        `<option value="` +
                        valCat.id +
                        `">` +
                        valCat.text +
                        `</option>`;
                });
                $("#catSet").empty().append(catSet);
                $("#catSet").select2({
                    theme: "bootstrap4",
                    placeholder: "Select Category",
                    allowClear: true,
                });

                $("#modalSetCat").modal({
                    show: true,
                    backdrop: "static",
                    keyboard: false, // to prevent closing with Esc button (if you want this too)
                });
                Swal.close();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var meta = jqXHR.responseJSON.meta;
                var data = jqXHR.responseJSON.data;

                Swal.fire({
                    icon: "error",
                    title: meta.message,
                    html:
                        '<div class="alert alert-danger text-left" role="alert">' +
                        "<p>" +
                        data.error +
                        "</p>" +
                        "</div>",
                    allowOutsideClick: false,
                });
            },
        });
    }

    $(".cancelSetCat").on("click", function () {
        cancelCatSet();
    });

    function cancelCatSet() {
        $("#modalSetCat").modal("hide");
        $("#catSet").empty();
    }

    /**
     * END CATEGORY
     */

    $("#formCat,#formSetCat").on("submit", function (e) {
        e.preventDefault();
        var jenisForm = $(this).data("jenis");

        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });
        var formData = new FormData($(this)[0]);
        var url = $(this).attr("action");
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
                    refreshTable();
                    if (jenisForm == "catSet") {
                        cancelCatSet();
                    } else {
                        cancelForm();
                    }
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
