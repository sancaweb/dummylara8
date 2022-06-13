const { default: Swal } = require("sweetalert2");

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"').attr("content"),
        },
    });
    $.fn.modal.Constructor.prototype._enforceFocus = function () {};

    function formOpenTag() {
        $("#modalFormTag").modal({
            show: true,
            backdrop: "static",
            keyboard: false, // to prevent closing with Esc button (if you want this too)
        });
    }

    function cancelFormTag() {
        $("#formTag")[0].reset();
        $("#modalFormTag").modal("hide");
        $("#titleFormTag").empty().append("Input Tag");
        $("#formTag").attr("action", base_url + "/tag");
        $("#formTag").children("[name=_method]").remove();
    }

    function createSlugTag(string) {
        var urlSlug = base_url + "/ajax/post/" + string + "/createtagslug";

        $.ajax({
            url: urlSlug,
            type: "get",
            success: function (x) {
                var dataSlug = x.data.dataSlug;
                $("#tag_slug").val(dataSlug);
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
     * TAG
     */

    var columnsTable = [
        { data: "no" },
        { data: "name" },
        { data: "slug" },
        { data: "posts" },
        { data: "action" },
    ];

    var tableTags = $("#table-tags").DataTable({
        // "searching": false,
        order: [[1, "ASC"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/tag/datatable",
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
                    refreshTableTag();
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
                    refreshTableTag();
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
    $("#table-tags_filter input").off();
    $("#table-tags_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tableTags.search(this.value).draw();
        }
    });

    function refreshTableTag() {
        tableTags.search("").draw();
    }

    var btnTagsReload = document.getElementById("btn-tagsReload");

    if (btnTagsReload) {
        btnTagsReload.addEventListener("click", function () {
            refreshTableTag();
        });
    }

    /** ./end datatable */

    $("#btn-addTag").on("click", function () {
        $("#titleFormTag").empty().append("Input Tag");
        formOpenTag();
    });

    $(".cancelFormTag").on("click", function () {
        cancelFormTag();
    });

    $("#table-tags").on("click", ".btn-edit", function () {
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });

        var idTag = $(this).data("id");
        var urlEdit = base_url + "/tag/" + idTag + "/edit";

        $.ajax({
            url: urlEdit,
            type: "get",
            success: function (x) {
                var dataTag = x.data.dataTag;
                $("#formTag").attr("action", x.data.action);
                $('<input name="_method" value="patch">')
                    .attr("type", "hidden")
                    .appendTo("#formTag");

                $("[name=tag_name]").val(dataTag.name);
                $("[name=tag_slug]").val(dataTag.slug);
                $("#titleFormTag").empty().append("Edit Tag");
                formOpenTag();
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

    $("#tag_name").on("blur", function () {
        var string = $(this).val();
        createSlugTag(string);
    });

    $("#table-tags").on("click", ".btn-delete", function () {
        var name = $(this).data("name");
        Swal.fire({
            title: "Anda yakin?",
            text: "Anda yakin ingin menghapus Tag dengan nama: " + name + "?",
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
                var idTag = $(this).data("id");

                var urlDelete = base_url + "/tag/" + idTag + "/destroy";
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
                            refreshTableTag();
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
     * END Tag
     */

    $("#formTag").on("submit", function (e) {
        e.preventDefault();
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
                    refreshTableTag();
                    cancelFormTag();
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
