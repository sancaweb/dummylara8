const { get } = require("jquery");

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"').attr("content"),
        },
    });

    /**
     * Role PROCES
     */

    var columnsTableRoles = [
        { data: "no" },
        { data: "roleName" },
        { data: "created_at" },
        { data: "action" },
    ];

    var tableRoles = $("#table-role").DataTable({
        searching: true,
        order: [[1, "ASC"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/role/datatable",
            dataType: "json",
            type: "POST",
        },

        columns: columnsTableRoles,
        columnDefs: [
            {
                orderable: false,
                targets: [0, -1],
            },
        ],
    });
    $("#table-role_filter input").unbind();
    $("#table-role_filter input").bind("keyup", function (e) {
        if (e.keyCode == 13) {
            tableRoles.search(this.value).draw();
        }
    });

    function refreshTableRole() {
        tableRoles.search("").draw();
        // tableRoles.ajax.reload();
    }

    var btnRoleReload = document.getElementById("btn-roleReload");
    if (btnRoleReload) {
        btnRoleReload.addEventListener("click", function () {
            refreshTableRole();
        });
    }

    /** ./end datatable */

    $("#openFormRole").on("click", function () {
        openFormRole();
    });

    function openFormRole() {
        $("#modalFormInputRole").modal({
            show: true,
            backdrop: "static",
            keyboard: false, // to prevent closing with Esc button (if you want this too)
        });
    }

    $(".closeFormRole").on("click", function () {
        closeFormRole();
    });

    function closeFormRole() {
        $("#modalFormInputRole").modal("hide");
        formResetRole();
    }

    function formResetRole() {
        $("#formRole")[0].reset();
        $("#formRole").attr("action", base_url + "/role");
        $('[name="_method"]').remove();

        $("#modalFormInputRoleLabel").html(
            '<i class="fas fa-user-shield"></i>&nbsp; Add Role'
        );
    }

    // submit role
    $("#formRole").on("submit", function (e) {
        e.preventDefault();
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });
        var formData = new FormData($("#formRole")[0]);
        var url = $("#formRole").attr("action");
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
                    refreshTableRole();
                    closeFormRole();
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
    //./end submit role

    // edit Role
    /** Proses edit */
    $("#table-role").on("click", ".btn-edit", function () {
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });

        var idRole = $(this).data("id");
        var urlEdit = base_url + "/role/" + idRole + "/edit";

        $.ajax({
            url: urlEdit,
            type: "get",
            success: function (x) {
                var dataRole = x.data.role;

                $("#formRole").attr("action", x.data.action);
                $('<input name="_method" value="patch">')
                    .attr("type", "hidden")
                    .appendTo("#formRole");
                $("#modalFormInputRoleLabel").html(
                    '<i class="fas fa-user-shield"></i>&nbsp; Edit Role'
                );

                $('[name="roleName"]').val(dataRole.name);

                openFormRole();
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
    //./end edit Role

    //delete Role
    $("#table-role").on("click", ".btn-delete", function () {
        closeFormRole();
        Swal.fire({
            title: "Anda yakin?",
            text: "Anda yakin ingin menghapus data?",
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

                var idRole = $(this).data("id");
                var urlDelete = base_url + "/role/" + idRole + "/delete";
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
                            refreshTableRole();
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
    //./end Delete Role

    /**
     * ./END Role PROCES
     */

    /**
     * PERMISSION PROCES
     */

    var columnsTablePermissions = [
        { data: "no" },
        { data: "permissionName" },
        { data: "created_at" },
        { data: "action" },
    ];

    var tablePermissions = $("#table-permissions").DataTable({
        searching: true,
        order: [[1, "ASC"]],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/permission/datatable",
            dataType: "json",
            type: "POST",
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

        columns: columnsTablePermissions,
        columnDefs: [
            {
                orderable: false,
                targets: [0, -1],
            },
        ],
    });
    $("#table-permissions_filter input").off();
    $("#table-permissions_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tablePermissions.search(this.value).draw();
        }
    });

    function refreshTablePermission() {
        tablePermissions.search("").draw();
        // tablePermissions.ajax.reload();
    }

    var btnReloadPermission = document.getElementById("btn-permissionReload");
    if (btnReloadPermission) {
        btnReloadPermission.addEventListener("click", function () {
            refreshTablePermission();
        });
    }

    /** ./end datatable */

    $("#openFormPermission").on("click", function () {
        openFormPermission();
    });

    function openFormPermission() {
        $("#modalFormInputPermission").modal({
            show: true,
            backdrop: "static",
            keyboard: false, // to prevent closing with Esc button (if you want this too)
        });
    }

    $(".closeFormPermission").on("click", function () {
        closeFormPermission();
    });

    function closeFormPermission() {
        $("#modalFormInputPermission").modal("hide");
        formResetPermission();
    }

    function formResetPermission() {
        $("#formPermission")[0].reset();
        $("#formPermission").attr("action", base_url + "/permission");
        $('[name="_method"]').remove();

        $("#modalFormInputPermissionLabel").html(
            '<i class="fas fa-user-shield"></i>&nbsp; Add Permission'
        );
    }

    // submit permission
    $("#formPermission").on("submit", function (e) {
        e.preventDefault();
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });
        var formData = new FormData($("#formPermission")[0]);
        var url = $("#formPermission").attr("action");
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
                    refreshTablePermission();
                    closeFormPermission();
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
    //./end submit Permission

    // edit Permission

    /** Proses edit */
    $("#table-permissions").on("click", ".btn-edit", function () {
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false,
        });

        var idPermission = $(this).data("id");
        var urlEdit = base_url + "/permission/" + idPermission + "/edit";

        $.ajax({
            url: urlEdit,
            type: "get",
            success: function (x) {
                var dataPermission = x.data.permission;

                $("#formPermission").attr("action", x.data.action);
                $('<input name="_method" value="patch">')
                    .attr("type", "hidden")
                    .appendTo("#formPermission");
                $("#modalFormInputPermissionLabel").html(
                    '<i class="fas fa-user-shield"></i>&nbsp; Edit Permission'
                );

                $('[name="permissionName"]').val(dataPermission.name);

                openFormPermission();
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
    //./end edit Permission

    //delete Permission
    $("#table-permissions").on("click", ".btn-delete", function () {
        closeFormPermission();
        Swal.fire({
            title: "Anda yakin?",
            text: "Anda yakin ingin menghapus data?",
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

                var idPermission = $(this).data("id");
                var urlDelete =
                    base_url + "/permission/" + idPermission + "/delete";
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
                            refreshTablePermission();
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
    //./end Delete Permission

    /**
     * ./END PERMISSION PROCES
     */
}); // ./end document
