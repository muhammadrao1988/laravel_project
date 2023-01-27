function cloneRow(tableId, rowId,allowremove=0){
    event.preventDefault();
    if ($('select.select2-hidden-accessible').not(".select2-ajax, .select2-ajax-no-limit, .select2-ajax-with-tags").data('select2')) {
        $('select.select2-hidden-accessible').not(".select2-ajax, .select2-ajax-no-limit, .select2-ajax-with-tags").select2('destroy');
    }

    if ($('.select2-ajax')[0]) {
        $(".select2-ajax").select2("destroy");
    }

    if ($('.select2-ajax-no-limit')[0]) {
        $(".select2-ajax-no-limit").select2("destroy");
    }

    if ($('.select2-ajax-with-tags')[0]) {
        $(".select2-ajax-with-tags").select2("destroy");
    }

    var cloneTr = $('#'+tableId).find("tr").last().clone();
    var i = (parseInt(cloneTr.attr('id').split('-')[1]) + parseInt(1));
    cloneTr.attr('id', rowId+'-' + i).removeAttr("style");

    if(cloneTr.hasClass('disabled')){
      cloneTr.removeClass('disabled');
      cloneTr.find("input, select").attr('disabled', false);
    }

    if(cloneTr.hasClass('highlighted')){
      cloneTr.removeClass('highlighted');
    }

    $(cloneTr).find('td').each(function () {
        var textarea = $(this).find('textarea');
        var input = $(this).find('input');
        var select = $(this).find('select');
        var remove = $(this).find('a.remove');
        var enable = $(this).find('a.enable');
        var sr = $(this).find('span.sr');
        var itemDetailSpan = $(this).find('span.itemDetail');
        var availQtySpan = $(this).find('span.availableQty');

        if (input.length == 1) {
            var element = input.attr('id').split('-');
        }

        if (textarea.length == 1) {
            var element = textarea.attr('id').split('-');
        }

        if (select.length == 1) {
            var element = select.attr('id').split('-');
        }

        if (remove.length == 1) {
            var element = remove.attr('id').split('-');
        }

        if (enable.length == 1) {
            var element = enable.attr('id').split('-');
        }
        var showSrText = true;
        if (sr.length == 1) {
            var element = sr.attr('id').split('-');
            if (sr.hasClass('noTxt')) {
                showSrText = false;
            }
        }

        if (itemDetailSpan.length == 1) {
            var element = itemDetailSpan.attr('id').split('-');
        }

        if (availQtySpan.length == 1) {
            var element = availQtySpan.attr('id').split('-');
        }

        if (element) {
            var newId = element[0] + '-' + i + '-' + element[2];
            var newName = element[0] + '[' + i + '][' + element[2] + ']';

            textarea.attr('name', newName); textarea.attr('id', newId); textarea.val("");
            input.attr('name', newName); input.attr('id', newId); input.val("");
            select.attr('name', newName); select.attr('id', newId); select.val("");
            remove.attr('name', newName); remove.attr('id', newId); if(allowremove===1){ remove.css('display','block');}
            enable.attr('name', newName); enable.attr('id', newId);
            sr.attr('name', newName); sr.attr('id', newId);
            if (showSrText) {
                sr.text(i);
            } else {
                sr.text("");
            }
            itemDetailSpan.attr('name', newName); itemDetailSpan.attr('id', newId); itemDetailSpan.text("");
            availQtySpan.attr('name', newName); availQtySpan.attr('id', newId); availQtySpan.text("0");
        }
    });

    $('#'+tableId).append(cloneTr);
    if ($('.select2').not(".select2-ajax, .select2-ajax-no-limit, .select2-ajax-with-tags").length > 0) {
        $('.select2').not(".select2-ajax, .select2-ajax-no-limit, .select2-ajax-with-tags").each(function () {
            $(this).select2({
                allowClear: true,
                placeholder: 'Please Select',
            });
        });
    }

    if ($('.select2-ajax')[0]) {
        select2Ajax(".select2-ajax");
    }

    if ($('.select2-ajax-no-limit')[0]) {
        select2AjaxNoLimit(".select2-ajax-no-limit");
    }

    if ($('.select2-ajax-with-tags')[0]) {
        select2AjaxWithTags(".select2-ajax-with-tags");
    }
}

function cloneDiv(maidDivId, divRowId, divText = "Row"){
    event.preventDefault();
    if ($('select.select2-hidden-accessible').not(".select2-ajax, .select2-ajax-no-limit, .select2-ajax-with-tags").data('select2')) {
        $('select.select2-hidden-accessible').not(".select2-ajax, .select2-ajax-no-limit, .select2-ajax-with-tags").select2('destroy');
    }

    if ($('.select2-ajax')[0]) {
        $(".select2-ajax").select2("destroy");
    }

    if ($('.select2-ajax-no-limit')[0]) {
        $(".select2-ajax-no-limit").select2("destroy");
    }

    if ($('.select2-ajax-with-tags')[0]) {
        $(".select2-ajax-with-tags").select2("destroy");
    }

    var cloneDiv = $('#'+maidDivId).find("div."+divRowId).last().clone();
    var i = (parseInt(cloneDiv.attr('id').split('-')[1]) + parseInt(1));

    cloneDiv.attr('id', divRowId+'-' + i).removeAttr("style");
    $(cloneDiv).find('div').each(function () {
        var textarea = $(this).find('textarea');
        var input = $(this).find('input');
        var select = $(this).find('select');
        var remove = $(this).find('button.remove');
        var sr = $(this).find('h3.sr');

        if (textarea.length == 1) {
            var element = textarea.attr('id').split('-');
        }

        if (input.length == 1) {
            var element = input.attr('id').split('-');
        }

        if (select.length == 1) {
            var element = select.attr('id').split('-');
        }

        if (remove.length == 1) {
            var element = remove.attr('id').split('-');
        }
        var showSrText = true;
        if (sr.length == 1) {
            var element = sr.attr('id').split('-');
            if (sr.hasClass('noTxt')) {
                showSrText = false;
            }
        }

        if (element) {
            var newId = element[0] + '-' + i + '-' + element[2];
            var newName = element[0] + '[' + i + '][' + element[2] + ']';

            input.attr('name', newName);
            input.attr('id', newId);
            if(!$(input).hasClass('keep-clone-value')){
                input.val("");
            }
            textarea.attr('name', newName);
            textarea.attr('id', newId);
            if(!$(textarea).hasClass('keep-clone-value')){
                textarea.val("");
            }
            select.attr('name', newName);
            select.attr('id', newId);
            select.val("");
            remove.attr('name', newName);
            remove.attr('id', newId);
            sr.attr('name', newName);
            sr.attr('id', newId);
            if (showSrText) {
                sr.text(divText+" # " + i);
            } else {
                sr.text("");
            }
        }
    });

    $('#'+maidDivId).append(cloneDiv);

    if ($('.select2').not(".select2-ajax, .select2-ajax-no-limit, .select2-ajax-with-tags").length > 0) {
        $('.select2').not(".select2-ajax, .select2-ajax-no-limit, .select2-ajax-with-tags").each(function () {
            $(this).select2({
                allowClear: true,
                placeholder: 'Please Select',
            });
        });
    }

    if ($('.select2-ajax')[0]) {
        select2Ajax(".select2-ajax");
    }

    if ($('.select2-ajax-no-limit')[0]) {
        select2AjaxNoLimit(".select2-ajax-no-limit");
    }

    if ($('.select2-ajax-with-tags')[0]) {
        select2AjaxWithTags(".select2-ajax-with-tags");
    }
}

function removeRow(id, tableId, rowId, forceRemove = 0) {
    var i = id.split('-')[1];
    if($('#'+rowId+'-' + i).hasClass("disabled")){
        toastr.info("Please enable this row to make any changes. ", "Info");
        return;
    }

    if (($('#'+tableId+' tbody tr:visible').length) == 1) {
        toastr.info("Atleast one row is required. ", "Info");
        return;
    }

    if(forceRemove == 0){
        if ($('#'+rowId+'-' + i + '-id').val()) {
            $('#'+rowId+'-' + i).hide();
            $('#'+rowId+'-' + i + '-delete').val(1);
        } else {
            $('#'+rowId+'-' + i).remove();
        }
    }else{
        $('#'+rowId+'-' + i).remove();
    }
}

function removeDiv(id, maidDivId, divRowId, divText = "row") {
    event.preventDefault();
    var i = id.split('-')[1];
    if (($('#'+maidDivId).find("div."+divRowId+":visible").length) == 1) {
        toastr.info("Atleast one "+divText+" is required. ", "Info");
        return;
    }
    if ($('#'+divRowId+'-' + i + '-id').val() == null || $('#'+divRowId+'-' + i + '-id').val() == "") {
        $('#'+divRowId+'-' + i).remove();
    } else {
        $('#'+divRowId+'-' + i).hide();
        $('#'+divRowId+'-' + i + '-delete').val(1);
    }
}

function enableRow(id) {
    var i = id.split('-')[1];
    var tr = $('#row-' + i);
    if(tr.hasClass('disabled')){
      tr.removeClass('disabled');
      tr.find("input, select").attr('disabled', false);
    }else{
      tr.addClass('disabled');
      tr.find("input, select").attr('disabled', true);
    }
}

$('#import-btn').click(function () {
    event.preventDefault();
    $('#import-file').trigger('click');
});

$('#import-file').change(function () {
    event.preventDefault();
    $('#import-form').submit();
});

$('#copy-btn').click(function (event) {
    event.preventDefault();
    $('#id').val('');
    $('input[name="id"]').val('');
    var attr = $(this).attr('data-copy-id');
    if (typeof attr !== typeof undefined && attr !== false) {
        $('input[name="copy_id"]').val(attr);
    }
    $('#save-btn').trigger('click');
});

$(function () {
    $("input.isDecimal[type=number]").prop('step', 0.01);
    $("input[type='number']:not(.isMinus)").prop('min', 0);
});

$('.disableOnSubmit').click(function () {
    event.preventDefault();
    $('<input>').attr({
        type: 'hidden',
        id: 'save_btn',
        name: 'save_btn',
        value: $(this).val()
    }).appendTo('form');
    $(this).attr('disabled', true);
    $('form').submit();
});

$('tbody tr').click(function (e) {
    $('tbody tr').removeClass('highlighted');
    $(this).addClass('highlighted');
});

if ($('.summernote').length > 0) {
    $('.summernote').summernote({height: 400});
}

if ($('.disabledSummerNote').length > 0) {
    $('.disabledSummerNote').summernote({height: 400});
    $('.disabledSummerNote').summernote("disable");
}

if ($('.table thead th').length > 0) {
    var toggleColumnsHtml = "";
    $(".table thead th").each(function (i) {
        if (!$(this).hasClass('exclude')) {
            toggleColumnsHtml += '<input type="checkbox" checked class="toggleColumn" data-column-index="' + i + '"> ' + $(this).text() + ' <br>';
        }
    });

    if ($('.toggleColumns').length > 0) {
        $(".toggleColumns").html(toggleColumnsHtml);
    }
}

$('body').on('change', '.toggleColumn', function (e) {
    e.preventDefault();
    var column = table.column($(this).attr('data-column-index'));
    column.visible(!column.visible());
});

$('#bulk-delete').click(function () {
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        buttons: true,
        dangerMode: true,
        icon: "warning",
    }).then(function (isConfirm) {
        if (isConfirm) {
            $('.check-single').each(function () {
                if ($(this).is(':checked')) {
                    var id = $(this).val();
                    var url = $(this).attr('data-destroy-route');
                    $.ajax({
                        type: 'POST',
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {id: id, _method: "DELETE"},
                        success: function (result) {
                            // table.draw(true);
                        }
                    });
                }
            });
            location.reload();
        }
    }).catch(swal.noop);
});

function showSuccess(msg){
    toastr.success(msg, 'Success');
}

function showError(msg){
    toastr.error(msg, "Error");
}

function showInfo(msg){
    toastr.info(msg, "Info");
}

function showWarning(msg){
    toastr.warning(msg, "Warning");
}

$(document).on('click','.fms-notif-icon',function(e){
    let element = $(this)
    $('.fms-notif-icon span ').hide()
    $.ajax({
        type: 'POST',
        url: element.attr('data-route'),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (result) {

        }
    });
})

$(document).ready(function ()
{
    var dateInput = "input[type=date]";
    if($(dateInput).length > 0) {
        if (document.querySelector(dateInput).type !== 'date')
        {
          var oCSS = document.createElement('link');
          oCSS.type='text/css'; oCSS.rel='stylesheet';
          oCSS.href='//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css';
          oCSS.onload=function()
          {
            var oJS = document.createElement('script');
            oJS.type='text/javascript';
            oJS.src='//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js';
            oJS.onload=function()
            {
                $(dateInput).attr("placeholder", 'yyyy-mm-dd');
                $(dateInput).datepicker({ dateFormat: 'yy-mm-dd' });
            }
            document.body.appendChild(oJS);
          }
          document.body.appendChild(oCSS);
        }
    }
});
