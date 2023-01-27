// const loader = require("sass-loader");

// type date copy paste
$(() => {
    $(document).on("keydown", "input[type=date]", function (e) {
        if (e.ctrlKey === true) {
            if (e.keyCode === 67) {
                $(this).attr("type", "text").select();
                document.execCommand("copy");
                $(this).attr("type", "date");
            }
        }
    });
    $(document).bind("paste", function (e) {
        let $input = $(document.activeElement);
        if ($input.attr("type") === "date") {
            $input.val(e.originalEvent.clipboardData.getData('text'));
        }
    });
});

$(document).on("keyup", "#search-menu-term", function () {
    $('.clone-menu').remove();
    var cloneMenuTree = $('#menu-tree').clone();
    cloneMenuTree.attr('id', 'clone-menu-tree');
    cloneMenuTree.addClass('clone-menu');
    cloneMenuTree.removeClass('hidden');
    $('#menu-tree').addClass('hidden');
    $('#menu-tree').after(cloneMenuTree);

    var searchTerm = $(this).val().toLowerCase();
    if(searchTerm != ''){
        var cloneMenuTree = $('#clone-menu-tree .nav-item')
        $(cloneMenuTree).each(function(){
            var menuName = $(this).find('p').text().trim().toLowerCase();
            if(menuName.indexOf(searchTerm) >= 0){
                $(this).find('*').show();
            }else{
               $(this).remove();
            }
        });
    }else{
        $('.clone-menu').remove();
        $('#menu-tree').removeClass('hidden');
    }
});


// Confirm Button Action
$('.datatable').on('click','.delete-confirm-id', function(){
    var id = $(this).attr('data-id');
    if (id != undefined) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            buttons: true,
            dangerMode: true,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById('delete_form_' + id).submit();
            }
        }).catch(swal.noop);
    }
});

$('.datatable').on('click','.status-confirm-id', function(){
    var id = $(this).attr('data-id');
    if (id != undefined) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            buttons: true,
            dangerMode: true,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById('status_form_' + id).submit();
            }
        }).catch(swal.noop);
    }
});

$('.datatable').on('click','.dock-ge-confirm-id', function(){
    var id = $(this).attr('data-id');
    if (id != undefined) {
        swal({
            title: 'Are you sure?',
            type: 'warning',
            buttons: true,
            dangerMode: true,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById('dock_ge_form_' + id).submit();
            }
        }).catch(swal.noop);
    }
});

$('.datatable').on('click','.snapshot-confirm-id', function(){
    var id = $(this).attr('data-id');
    if (id != undefined) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            buttons: true,
            dangerMode: true,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById('snapshot_form_' + id).submit();
            }
        }).catch(swal.noop);
    }
});

// Confirm Button Action
$('.datatable').on('click','.change-status-confirm-id', function(){
    var id = $(this).attr('data-id');
    var message = $(this).attr('data-message');
    if (id != undefined) {
        swal({
            title: 'Confirm',
            text: "Do you really want to "+message,
            type: 'info',
            buttons: true,
            icon: "info",
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById('change_status_form_' + id).submit();
            }
        }).catch(swal.noop);
    }
});

$('.datatable').on('click','.close-ge-confirm-id', function(){
    var id = $(this).attr('data-id');
    if (id != undefined) {
        swal({
            title: 'Are you sure?',
            text: "This action will mark the token as Departed",
            type: 'warning',
            buttons: true,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById('close_ge_form_' + id).submit();
            }
        }).catch(swal.noop);
    }
});

// revivive Button Action
$('.datatable').on('click','.btn-revive', function(){
    event.preventDefault();
    var route = $(this).attr('data-route');
    if (route != undefined) {
        swal({
            title: 'Are you sure?',
            text: "This action will take this order to created status",
            type: 'warning',
            buttons: true,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.location.replace(route)
            }
        }).catch(swal.noop);
    }
});

$('.datatable').on('click','.freeze-confirm-id', function(){
    var id = $(this).attr('data-id');
    if (id != undefined) {
        swal({
            title: 'Are you sure?',
            text: "This action will freeze the shipment!",
            type: 'warning',
            buttons: true,
            dangerMode: true,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                document.getElementById('freeze_form_' + id).submit();
            }
        }).catch(swal.noop);
    }
});

$('.confirm-link-id').on('click', function () {
    var url = $(this).attr('data-href');
    var title = $(this).attr('data-title');
    var text = $(this).attr('data-text');

    if (url != undefined) {
        swal({
            title: (title != undefined) ? title : "none",
            text: (text != undefined) ? text : "none",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0CC27E',
            cancelButtonColor: '#FF586B',
            confirmButtonText: 'Yes!',
            cancelButtonText: "No, cancel"
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.location = url;
            }
        }).catch(swal.noop);
    }
});

//Ajax Base Modal Show ..
$(".ajax-base-modal").click(function () {
    var id = $(this).attr("data-modal-id");
    var url = $(this).attr("data-url");
    var object = $("#" + id);
    if (object.length) {
        object.modal("show");
        return;
    }
    $.ajax({
        url: url,
        type: "GET",
        dataType: "html",
        success: function (html) {
            $(html).appendTo('body');
        },
        error: function (request, status, error) {
            alert(request.responseText);
            return false; //this will prevent to go to next step
        }, complete: function () {
            $("#" + id).modal("show");
        }
    });
});

//select2
if ($('.select2').length > 0) {
    $('.select2').each(function () {
        $(this).select2({
            allowClear: true,
            placeholder: 'Please Select',
        });
    });
}

if ($('.select2-tab').length > 0) {
    $('.select2-tab').each(function () {
        $(this).select2({
            placeholder: 'Please Select',
            selectOnClose: true,
        });
    });
}
//

//daterange picker .....
if ($('.defaultrange').length > 0) {

    $('.defaultrange').each(function () {
        var obj = $(this);
        obj.daterangepicker({
                opens: 'left',
                format: 'MM/DD/YYYY',
                // separator: ' to ',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                minDate: '01/01/2016',
                // maxDate: moment().format('MM/DD/YYYY'),
                dateLimit: {days: 30},
            },
            function (start, end) {
                obj.find('input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );
    });
}


//daterange picker .....
if ($('.defaultrangeTime').length > 0) {

    $('.defaultrangeTime').each(function () {
        var obj = $(this);
        obj.daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                maxDate: moment().format('MM/DD/YYYY'),

            },
            function (start, end) {
                obj.find('input').val(start.format('MMMM D, YYYY hh:mm A') + ' - ' + end.format('MMMM D, YYYY hh:mm A'));
            }
        );

    });
}

$('#check-all').click(function(){
    var checkAll = $(this).attr('id');
    $('.check-single').each(function(){
        if ($('#'+checkAll).is(':checked')){
            $(this).prop('checked', true);
        }else{
            $(this).prop('checked', false);
        }
    });
});

//Report Scheduled Functions
$(document).on('click', "#schedule-report-btn", function (){
    $(".schedule-report-form").toggle();
});

$('input[name="scheduleType"]').click(function () {
    $(this).tab('show');
    $(this).removeClass('active');
});

$(".schedule_submit").click(function (){
     scheduleReport();
})

function scheduleReport(){
    var scheduleType  = "";
    var selectedScheduleType = $("input[type='radio'][name='scheduleType']:checked");
    var startDateTime = $("#reportScheduleStartDateTime").val();
    var endDateTime = $("#reportScheduleEndDateTime").val();
    if (selectedScheduleType.length > 0) {
        scheduleType  = selectedScheduleType.val();
    }
    var weekDay = "";
    var monthDay =$("#scheduleMonthDay").val();
    var scheduleTypeParams = "";
    if(scheduleType=="oneTime" || scheduleType=="daily" || scheduleType=="weekly" || scheduleType=="monthly") {

        if(startDateTime==""){
            showError("Please select start date/time");
            return false;
        }

        if(scheduleType=="weekly"){
            var selectedWeekDay = $("input[type='radio'][name='weekDay']:checked");
            if (selectedWeekDay.length > 0) {
                weekDay  = selectedWeekDay.val();
            }
            if(weekDay == ""){
                showError("Please select at least one week day");
                return false;
            }
            scheduleTypeParams = weekDay;
        }
        if(scheduleType=="monthly") {
            if (monthDay == "") {
                showError("Please select month day");
                return false;

            }
            scheduleTypeParams = monthDay;
        }
        var startDateTime = startDateTime;
        var endDateTime = endDateTime;

        if (new Date(startDateTime) < new Date()) {
            showError('Start Date/Time must be greater than Current Date/Time');
            return false;
        }

        if (new Date(endDateTime) <= new Date(startDateTime)) {
            showError('End Date/Time must be greater than Start Date/Time');
            return false;
        }

        var reportName = $("#reportName").val();
        var reportFunction = $("#reportFunction").val();
        var paperSize = $("#paperSize").val();
        var paperMode = $("#paperMode").val();

        if(reportName == "" || reportFunction == "" || paperSize == "" || paperMode == ""){
            showError('Something went wrong');
            return false;
        }

        var searchForm = $("#schedule-report-btn").parents("form").serializeArray();
        var dataObj = {};

        $(searchForm).each(function(i, field){
            if(field.name!="_token") {
                dataObj[field.name] = field.value;
            }
        });
        var payLoad = {
            _token: $("#schedule-report-form [name='_token']").val(),
            reportParams: dataObj,
            sendEmail: ($("#reportScheduleSendEmail").prop('checked') == true ? 1 : 0),
            scheduleType: scheduleType,
            scheduleTypeParams: scheduleTypeParams,
            startDateTime: startDateTime,
            endDateTime: endDateTime,
            schedule_report: 1,
            reportName: reportName,
            reportFunction: reportFunction,
            paperSize: paperSize,
            paperMode: paperMode,
        }

        $(".error_div_schedule").hide();
        $.ajax({
            url: $("#schedule-report-form").attr("action"),
            dataType: "json",
            type: "POST",
            data: payLoad
        }).then(function (data) {
            location.reload();
        }).fail(function (error) {
            if(error.responseJSON.hasOwnProperty('errors')){
                var error_msg = "";
                console.log(error.responseJSON.errors);
                for(var prop in error.responseJSON.errors){

                    $(error.responseJSON.errors[prop]).each(function (val,msg){

                        error_msg+=''+msg+"<br>";
                    });
                }
                $(".error_div_schedule").html(error_msg);
                $(".error_div_schedule").show();


            }else{
                showError("There is an error, please refresh the page and try again");
            }

        });
    }else{
        showError("Please select schedule type")
    }

}

$(window).on('load', function () {

    $('img').each(function () {
        var src = $(this).attr('data-src');
        if (src != undefined)
            $(this).attr('src', src);
    });
});

function returnZero(value){
    if(value == '' || value == 0 || value == '0' || value == null  || value == "null" || isNaN(value)){
        return 0;
    }else{
        return value;
    }
}

function isNull(variable){
    if(typeof(variable) != "undefined" && variable !== null) {
        return 0;
    }else{
        return 1;
    }
}

function successHighlight(element1, element2){
    $(element1).removeClass("highlighted");
    $(element1).removeClass("highlight-success");
    $(element2).addClass("highlight-success");
    setTimeout(function() {
      $(element2).removeClass("highlight-success");
    }, 5000);
    toastr.success('', 'Success');
}

function errorHighlight(element1, element2, errorMessage){
    $(element1).removeClass("highlighted");
    $(element1).removeClass("highlight-error");
    $(element2).addClass("highlight-error");
    setTimeout(function() {
      $(element2).removeClass("highlight-error");
    }, 5000);
    toastr.error(errorMessage, "Validation Error");
}

$(document).on('click','.delete-attachment', function(){
    event.preventDefault();
    var href=$(this).attr('href');
    if (href != "" && href != undefined) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            buttons: true,
            dangerMode: true,
            icon: "warning",
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.location.assign(href);
            }
        }).catch(swal.noop);
    }
});

function translator(source,destinationID)
{
    $.ajax({
        url: "/api/englishtourdu",
        dataType: "json",
        type: "POST",
        async:false,
        data: {text:$(source).val()}
    })
        .then(function (data) {
            $(destinationID).val(data.data.text);
        })
        .fail(function (error){
            showError(error.message);
            return false;
        });
    return true;
}



