$(document).ready(function() {
  $('body').attr('class', 'sidebar-mini sidebar-collapse');

  if($('.sticky-tr').length > 0) {
      var stickyNavTop = $('.sticky-tr').offset().top;
      var stickState = ($(window).scrollTop() > stickyNavTop);
      var stickyNav = function () {
          var scrollTop = $(window).scrollTop();
          if (stickState != (scrollTop > stickyNavTop)) {
              stickState = (scrollTop > stickyNavTop);
              if (stickState) {
                  $('.sticky-tr th').each(function () {
                      $(this).width($(this).width());
                  });
                  $('.sticky-tr').clone().appendTo($('thead')).addClass('sticky');
                  if ($('tr.sticky').length > 1) {
                      $('tr.sticky').eq(1).css('margin-top', '50px');
                  }
              } else {
                  $('tr.sticky').remove();
              }
          }
      };

      stickyNav();

      $(window).scroll(function () {
          stickyNav();
      });
  }

  if($('.auto-colspan').length > 0){
    $('.auto-colspan').each(function(){
      $(this).attr('colspan', parseInt($('tbody > tr > td').length) + parseInt(1));
    });
  }
});

function exportTableToExcel(filename = 'Excel', tableId = "to-print", className = ""){
  if(className != ""){
    var tableSelect = document.getElementsByClassName(className);
    $.each(tableSelect, function(){
      exportTable(filename, this, 1);
    });
  }else{
    var tableSelect = document.getElementById(tableId);
    exportTable(filename, tableSelect, 0);
  }
}

function exportTable(filename, table, isClass = 0){
  if(isClass == 0){
    var table = $('#'+table.id);
  }

  if(table){
    table.find('td:hidden').remove();
    
    $(table).table2excel({
        exclude: ".exclude",
        name: filename,
        filename: filename + ".xls",
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true,
        preserveColors: true
    });
  }
}