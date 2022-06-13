const { get } = require("jquery");

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"').attr("content"),
        },
    });

    var columnsTable = [
        { data: "no" },
        { data: "foto" },
        { data: "name" },
        { data: "email" },
        { data: "username" },
        { data: "role" },
        { data: "action" },
    ];

    var tableUserTrash = $("#table-userTrash").DataTable({
        // "searching": false,
        order: [[0, "DESC"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/user/datatabletrash",
            dataType: "json",
            type: "POST",
            // data: function (dataFilter) {
            //     var columnsFilter = $('#columnsFilter').val();
            //     var filterVal = $('#filterVal').val();
            //     var jenis_data = $('#jenis_data').val();

            //     dataFilter.columnsFilter = columnsFilter;
            //     dataFilter.filterVal = filterVal;
            //     dataFilter.jenis_data = jenis_data;
            // },
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
        },

        columns: columnsTable,
        columnDefs: [
            {
                orderable: false,
                targets: [0, 1, -1],
            },
        ],
    });

    $("#table-userTrash_filter input").off();

    $("#table-userTrash_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tableUserTrash.search(this.value).draw();
        }
    });

    function refreshTable() {
        tableUserTrash.search("").draw();
        // tableUserTrash.ajax.reload();
    }

    var btnReloadUser = document.getElementById("btn-userReload");
    if (btnReloadUser) {
        btnReloadUser.addEventListener("click", function () {
            refreshTable();
        });
    }

    /** ./end datatable */

    $("#table-userTrash").on("click", ".btn-restore", function () {
        console.log("restoreaja");
        Swal.fire({
            title: "Anda yakin?",
            text: "Anda yakin ingin Me-Restore data User ?",
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

                var idUser = $(this).data("id");
                var urlDelete = base_url + "/user/" + idUser + "/restore";
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
                            title: data.meta.message,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false,
                        }).then(function () {
                            refreshTable();
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        var error = jqXHR.responseJSON;
                        Swal.fire({
                            icon: "error",
                            title: error.message,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false,
                        });
                    },
                });
            }
        });
    });
    //./end restore

    $("#table-userTrash").on("click", ".btn-destroy", function () {
        Swal.fire({
            title: "Anda yakin?",
            text: "Anda yakin ingin menghapus Permanent ?",
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

                var idUser = $(this).data("id");
                var urlDelete = base_url + "/user/" + idUser + "/destroy";
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
                            title: data.meta.message,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false,
                        }).then(function () {
                            refreshTable();
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        var error = jqXHR.responseJSON;
                        Swal.fire({
                            icon: "error",
                            title: error.meta.message,
                            showConfirmButton: false,
                            timer: 2000,
                            allowOutsideClick: false,
                        });
                    },
                });
            }
        });
    });
}); // ./end document
