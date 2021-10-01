const { get } = require("jquery");

$(function () {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"').attr("content")
        }
    });

    var columnsAssignPermission = [
        { data: "no" },
        { data: "role" },
        { data: "permission" },
        { data: "created_at" },
        // { data: "action" },
    ];

    var tableAssignPermission = $("#table-assignPermission").DataTable({
        "searching": true,
        order: [
            [1, 'ASC']
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/assignpermission/datatable",
            dataType: "json",
            type: "POST",
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseJSON.data) {
                    var error = jqXHR.responseJSON.data.error;
                    Swal.fire({
                        icon: "error",
                        title: " <br>Copy error dan hubungi Programmer!",
                        html: '<div class="alert alert-danger text-left" role="alert">' +
                            '<p>Error Message: <strong>' + error + '</strong></p>' +
                            '</div>',
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
                        html: '<div class="alert alert-danger text-left" role="alert">' +
                            '<p>Error Message: <strong>' + message + '</strong></p>' +
                            '<p>File: ' + file + '</p>' +
                            '<p>Line: ' + errorLine + '</p>' +
                            '</div>',
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    }).then(function () {
                        refreshTable();
                    });
                }

            }
        },

        columns: columnsAssignPermission,
        columnDefs: [{
            orderable: false,
            targets: [0, -1]
        }]
    });
    $("#table-assignPermission_filter input").off();
    $("#table-assignPermission_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tableAssignPermission.search(this.value).draw();
        }
    });

    function refreshTableAssignPermission() {
        tableAssignPermission.search("").draw();
        tableAssignPermission.ajax.reload();
    }

    var btnAssignPermission = document.getElementById('btn-assignPermissionReload');
    if (btnAssignPermission) {
        btnAssignPermission.addEventListener('click', function () {
            refreshTableAssignPermission();
        });
    }

    /** ./end datatable */

    function formAttribute(dataPermissions) {
        var checkSatu = $('.checkSatu');
        $.each(checkSatu, (indexSatu, permitValSatu) => {
            if (dataPermissions.includes(permitValSatu.value)) {

                $('#switchSatu' + indexSatu).prop('checked', true);
            } else {
                $('#switchSatu' + indexSatu).prop('checked', false);


            }
        });
        var checkDua = $('.checkDua');

        $.each(checkDua, (indexDua, permitValDua) => {
            if (dataPermissions.includes(permitValDua.value)) {

                $('#switchDua' + indexDua).prop('checked', true);
            } else {
                $('#switchDua' + indexDua).prop('checked', false);


            }
        });


        Swal.close();
        $('#modalViewPermissions').modal({
            show: true,
            backdrop: 'static',
            keyboard: false // to prevent closing with Esc button (if you want this too)
        });
    }

    $('#table-assignPermission').on('click', '.btn-view', function () {
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false
        });

        var idRole = $(this).data("id");
        var urlViewPermissions = base_url + '/assignpermission/' + idRole + '/viewpermission';
        $.ajax({
            url: urlViewPermissions,
            type: "get",
            success: function (x) {
                var dataPermissions = x.data.dataPermissions;
                var dataRole = x.data.dataRole;
                $('[name="roleName"]').val(dataRole.name);
                $('[name="idRole"]').val(dataRole.id);

                if (dataPermissions == '') {
                    Swal.fire({
                        icon: "error",
                        title: x.meta.message,
                        html: '<div class="alert alert-danger text-left" role="alert">' +
                            '<p>' + x.data.message + '</p>' +
                            '</div>',
                        allowOutsideClick: false
                    }).then(() => {
                        formAttribute(dataPermissions);

                    });
                } else {
                    formAttribute(dataPermissions);

                }








            },
            error: function (jqXHR, textStatus, errorThrown) {
                var meta = jqXHR.responseJSON.meta;
                var data = jqXHR.responseJSON.data;

                Swal.fire({
                    icon: "error",
                    title: meta.message,
                    html: '<div class="alert alert-danger text-left" role="alert">' +
                        '<p>' + data.error + '</p>' +
                        '</div>',
                    allowOutsideClick: false
                });
            }
        });

    });




    $('.closeFormViewPermission').on('click', () => {
        $('#modalViewPermissions').modal('hide');
    });

    // // submit assign
    $("#formAssign").on("submit", function (e) {
        e.preventDefault();
        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false
        });
        var formData = new FormData($("#formAssign")[0]);
        var url = $("#formAssign").attr("action");
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
                    allowOutsideClick: false
                }).then(function () {

                    refreshTableAssignPermission();

                    $('#modalViewPermissions').modal('hide');

                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseJSON.data.errorValidator) {
                    var errors = jqXHR.responseJSON.data.errorValidator;
                    var message = jqXHR.responseJSON.message;
                    var li = '';
                    $.each(errors, function (key, value) {

                        li += "<li>" + value + "</li>"
                    });

                    Swal.fire({
                        icon: "error",
                        title: message,
                        html: '<div class="alert alert-danger text-left" role="alert">' +
                            '<ul>' + li + '</ul>' +
                            '</div>',
                        footer: "Pastikan data yang anda masukkan sudah benar!",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    });

                } else {
                    var message = jqXHR.responseJSON.meta.message;
                    var data = jqXHR.responseJSON.data;

                    Swal.fire({
                        icon: "error",
                        title: message + " <br>Copy error dan hubungi Programmer!",
                        html: '<div class="alert alert-danger text-left" role="alert">' +
                            '<p>Error Message: <strong>' + message + '</strong></p>' +
                            '<p>Error: ' + data.error + '</p>' +
                            '</div>',
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    });
                }

            }
        });
    });
    //./end submit assign

    $('#checkAll').on('click', function () {
        $('.checkSatu').prop('checked', true);
        $('.checkDua').prop('checked', true);
    });

    $('#unCheckAll').on('click', function () {
        $('.checkSatu').prop('checked', false);
        $('.checkDua').prop('checked', false);
    });


    /**
     * ==========  BATAS EDITIN =====================================================
     */
    //  $('#table-assignPermission').on('click', '.btn-edit', function () {
    //     var idRole = $(this).data("id");


    // });
    // $('#openFormRole').on('click', function () {
    //     openFormRole();
    // });

    // function openFormRole() {
    //     $('#modalFormInputRole').modal({
    //         show: true,
    //         backdrop: 'static',
    //         keyboard: false // to prevent closing with Esc button (if you want this too)
    //     });
    // }

    // $('.closeFormRole').on('click', function () {
    //     closeFormRole();
    // });



    // function formResetRole() {
    //     $('#formAssign')[0].reset();
    //     $("#formAssign").attr("action", base_url + "/role");
    //     $('[name="_method"]').remove();

    //     $("#modalFormInputRoleLabel").html('<i class="fas fa-user-shield"></i>&nbsp; Add Role');
    // }



    // // edit Role
    // /** Proses edit */
    // $('#table-assignPermission').on('click', '.btn-edit', function () {
    //     Swal.fire({
    //         imageUrl: base_url + "/images/loading.gif",
    //         imageHeight: 300,
    //         showConfirmButton: false,
    //         title: "Loading ...",
    //         allowOutsideClick: false
    //     });

    //     var idRole = $(this).data("id");
    //     var urlEdit = base_url + '/role/' + idRole + '/edit';

    //     $.ajax({
    //         url: urlEdit,
    //         type: "get",
    //         success: function (x) {
    //             var dataRole = x.data.role;

    //             $("#formAssign").attr("action", x.data.action);
    //             $('<input name="_method" value="patch">').attr("type", "hidden").appendTo("#formAssign");
    //             $("#modalFormInputRoleLabel").html('<i class="fas fa-user-shield"></i>&nbsp; Edit Role');



    //             $('[name="roleName"]').val(dataRole.name);

    //             openFormRole();
    //             Swal.close();


    //         },
    //         error: function (jqXHR, textStatus, errorThrown) {
    //             var meta = jqXHR.responseJSON.meta;
    //             var data = jqXHR.responseJSON.data;

    //             Swal.fire({
    //                 icon: "error",
    //                 title: meta.message,
    //                 html: '<div class="alert alert-danger text-left" role="alert">' +
    //                     '<p>' + data.error + '</p>' +
    //                     '</div>',
    //                 allowOutsideClick: false
    //             });
    //         }
    //     });
    // });
    // //./end edit Role


    // //delete Role
    // $('#table-assignPermission').on('click', '.btn-delete', function () {
    //     closeFormRole();
    //     Swal.fire({
    //         title: 'Anda yakin?',
    //         text: "Anda yakin ingin menghapus data?",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!',
    //         allowOutsideClick: false
    //     }).then((result) => {
    //         if (result.value) {
    //             Swal.fire({
    //                 imageUrl: base_url + "/images/loading.gif",
    //                 imageHeight: 300,
    //                 showConfirmButton: false,
    //                 title: "Loading ...",
    //                 allowOutsideClick: false
    //             });

    //             var idRole = $(this).data('id');
    //             var urlDelete = base_url + '/role/' + idRole + '/delete';
    //             $.ajax({
    //                 url: urlDelete,
    //                 type: "DELETE",
    //                 contentType: false,
    //                 processData: false,
    //                 success: function (data) {
    //                     Swal.fire({
    //                         icon: "success",
    //                         title: data.data.message,
    //                         showConfirmButton: false,
    //                         timer: 2000,
    //                         allowOutsideClick: false
    //                     }).then(function () {
    //                         refreshTableAssignPermission();
    //                     });

    //                 },
    //                 error: function (jqXHR, textStatus, errorThrown) {

    //                     var error = jqXHR.responseJSON;
    //                     Swal.fire({
    //                         icon: "error",
    //                         title: error.meta.message,
    //                         showConfirmButton: false,
    //                         timer: 2000,
    //                         allowOutsideClick: false
    //                     });

    //                 }
    //             });

    //         }
    //     });
    // });
    // //./end Delete Role

}); // ./end document
