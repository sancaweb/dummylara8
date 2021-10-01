const { get } = require("jquery");

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"').attr("content")
        }
    });

    //Date range picker
    $('.rangeDate').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, function (start, end, label) {
        var choosen_val = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
        $('.rangeDate').val(choosen_val);

    });

    $('.rangeDate').val();

    $('#userAct').select2({
        theme: 'bootstrap4',
        placeholder: "Select User",
        allowClear: true

    });

    $('#logNameAct').select2({
        theme: 'bootstrap4',
        placeholder: "Select Log Name",
        allowClear: true

    });


    var columnsTable = [
        { data: "no" },
        { data: "user" },
        { data: "logName" },
        { data: "description" },
        { data: "created_at" },
        { data: "action" },
    ];

    var tableActivity = $("#table-activity").DataTable({
        "searching": false,
        order: [
            [0, 'DESC']
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/activity/datatable",
            dataType: "json",
            type: "POST",
            data: function (dataFilter) {
                var userAct = $('#userAct').val();
                var logNameAct = $('#logNameAct').val();
                var dateRangeFilter = $('#dateRangeFilter').val();
                var descAct = $('#descAct').val();

                dataFilter.userAct = userAct;
                dataFilter.logNameAct = logNameAct;
                dataFilter.dateRangeFilter = dateRangeFilter;
                dataFilter.descAct = descAct;
            },
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

        columns: columnsTable,
        columnDefs: [{
            orderable: false,
            targets: [0, -1]
        }]
    });
    $("#table-activity_filter input").off();
    $("#table-activity_filter input").on("keyup", function (e) {
        if (e.code == "Enter") {
            tableActivity.search(this.value).draw();
        }
    });

    function refreshTable() {
        tableActivity.search("").draw();
        // tableActivity.ajax.reload();
    }

    var btnReloadActivity = document.getElementById('btn-activityReload');
    if (btnReloadActivity) {
        btnReloadActivity.addEventListener('click', function () {
            refreshTable();
        });
    }

    /** ./end datatable */

    $('#openCard').on('click', function () {
        openCard();
    });

    $('#closeCard').on('click', function () {
        closeCard();
    });
    $('#formReset').on('click', function () {
        formReset();
    });

    function closeCard() {
        var elementLink = document.getElementById("cardFormUser");
        elementLink.classList.add("collapsed-card");

        $('.collapse').collapse('hide');

    }

    function openCard() {
        var elementLink = document.getElementById("cardFormUser");
        elementLink.classList.remove("collapsed-card");

        $('.collapse').collapse('show');

    }


    function formReset() {
        $('#formUser')[0].reset();
        $("#formUser").attr("action", base_url + "/user");
        $('#role').val('');
        $('[name="_method"]').remove();
        closeCard();
    }

    $('#btn-resetFilter').on('click', function () {
        $('#userAct').val("");
        $('#logNameAct').val("");
        $('#descAct').val("");
        $('#dateRangeFilter').val('');

    });

    $('#btn-filter').on('click', function () {
        tableActivity.draw();

    });





    $('#table-activity').on('click', '.btn-detail', function () {

        Swal.fire({
            imageUrl: base_url + "/images/loading.gif",
            imageHeight: 300,
            showConfirmButton: false,
            title: "Loading ...",
            allowOutsideClick: false
        });

        var idAct = $(this).data('id');
        var urlDetail = base_url + "/activity/" + idAct + "/show";

        $.ajax({
            url: urlDetail,
            type: "get",
            success: function (data) {
                var dataAct = data.data;
                $('#txt_user').val(dataAct.user);
                $('#txt_logName').val(dataAct.log_name);
                $('#txt_desc').val(dataAct.description);
                $('#txt_data').val(JSON.stringify(dataAct.properties));
                $('#txt_created').val(dataAct.created_at);

                Swal.close();
                $("#detailAct").modal({
                    show: true,
                    backdrop: "static",
                    keyboard: false // to prevent closing with Esc button (if you want this too)
                });
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

}); // ./end document
