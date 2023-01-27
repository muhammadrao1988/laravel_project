
    $(document).ready(function(){
        console.log($('#date').val())
       if($('#date').val() != ''){
           var date = new Date($('#date').val()).getDate()
           $('#'+date).addClass('days')
       }
    });
    $(document).on('click','#backend',function(){
       // alert('ddd')
       var radio_id =  $(this).attr('for')
        console.log(radio_id)
        $('.slot').attr('checked',false)
       $('#'+radio_id).attr('checked',true)
    });
    const months = [
    { 'id': 1, 'name': 'Jan' },
    { 'id': 2, 'name': 'Feb' },
    { 'id': 3, 'name': 'Mar' },
    { 'id': 4, 'name': 'Apr' },
    { 'id': 5, 'name': 'May' },
    { 'id': 6, 'name': 'Jun' },
    { 'id': 7, 'name': 'Jul' },
    { 'id': 8, 'name': 'Aug' },
    { 'id': 9, 'name': 'Sep' },
    { 'id': 10, 'name': 'Oct' },
    { 'id': 11, 'name': 'Nov' },
    { 'id': 12, 'name': 'Dec' },
];
var currentYear = new Date().getFullYear();
var currentMonth = new Date().getMonth() + 1;
function letsCheck(year, month) {
    var daysInMonth = new Date(year, month, 0).getDate();
    var firstDay = new Date(year, month, 1).getUTCDay();
    var array = {
        daysInMonth: daysInMonth,
        firstDay: firstDay
    };
    return array;
}
function makeCalendar(year, month) {
    console.log('current month is: '+new Date().getMonth())
    var getChek = letsCheck(year, month);
    getChek.firstDay === 0 ? getChek.firstDay = 7 : getChek.firstDay;
    $('#calendarList').empty();
    for (let i = 1; i <= getChek.daysInMonth; i++) {
        if (i === 1) {
            if(month > new Date().getMonth()+1){
                var div = '<li id="' + i + '" style="grid-column-start: ' + getChek.firstDay + '; cursor: pointer; " onclick="getDate(event);">1</li>';
            }
            else if(month == new Date().getMonth()+1){
                if(i < new Date().getDate()){
                    var div = '<li id="' + i + '" style="grid-column-start: ' + getChek.firstDay + '; cursor: unset; opacity: 0.5;" onclick="getDate(event);">1</li>';
                }
                else{
                    var div = '<li id="' + i + '" style="grid-column-start: ' + getChek.firstDay + '; cursor: pointer; " onclick="getDate(event);">1</li>';
                }
            }
            else{
                var div = '<li id="' + i + '" style="grid-column-start: ' + getChek.firstDay + '; cursor: unset; opacity: 0.5;" onclick="getDate(event);">1</li>';
            }
        } else {
            if(month > new Date().getMonth()+1){
                var div = '<li id="' + i + '"  style="cursor: pointer; " onclick="getDate(event);">'+ i +'</li>';
            }
            else if(month == new Date().getMonth()+1){
                if(i < new Date().getDate()){
                    var div = '<li id="' + i + '"  style="cursor: unset; opacity: 0.5;" onclick="getDate(event);">'+ i +'</li>';
                }
                else{
                    var div = '<li id="' + i + '"  style="cursor: pointer; " onclick="getDate(event);">'+ i +'</li>';
                }
            }
            else{
                var div = '<li id="' + i + '"  style="cursor: unset; opacity: 0.5;" onclick="getDate(event);">'+ i +'</li>';
            }
        }
        $('#calendarList').append(div);
    }
    monthName = months.find(x => x.id === month).name;
    $('#yearMonth').text(monthName + ' ' + year );
}
makeCalendar(currentYear, currentMonth);
function nextMonth() {
    currentMonth = currentMonth + 1;
    if (currentMonth > 12) {
        currentYear = currentYear + 1;
        currentMonth = 1;
    }
    $('#calendarList').empty();
    $('#yearMonth').text(currentMonth + ' ' + currentYear);
    makeCalendar(currentYear, currentMonth);
}
function prevMonth() {
    console.log(new Date().getMonth())
    currentMonth = currentMonth - 1;
    if (currentMonth < 1) {
        currentYear = currentYear - 1;
        currentMonth = 12;
    }
    $('#calendarList').empty();
    $('#yearMonth').text(currentYear + ' ' + currentMonth);
    makeCalendar(currentYear, currentMonth);
}





/*CALENDAR 2*/

var currentYear2 = new Date().getFullYear();
var currentMonth2 = new Date().getMonth() + 1;
function letsCheck(year, month) {
    var daysInMonth = new Date(year, month, 0).getDate();
    var firstDay = new Date(year, month, 1).getUTCDay();
    var array = {
        daysInMonth: daysInMonth,
        firstDay: firstDay
    };
    return array;
}
function makeCalendar2(year, month) {
    console.log('current month is: '+new Date().getMonth())
    var getChek = letsCheck(year, month);
    getChek.firstDay === 0 ? getChek.firstDay = 7 : getChek.firstDay;
    $('#calendarList2').empty();
    for (let i = 1; i <= getChek.daysInMonth; i++) {
        if (i === 1) {
            if(month > new Date().getMonth()+1){
                var div = '<li id="' + i + '" style="grid-column-start: ' + getChek.firstDay + '; cursor: pointer; " onclick="getDate(event);">1</li>';
            }
            else if(month == new Date().getMonth()+1){
                if(i < new Date().getDate()){
                    var div = '<li id="' + i + '" style="grid-column-start: ' + getChek.firstDay + '; cursor: unset; opacity: 0.5;" onclick="getDate(event);">1</li>';
                }
                else{
                    var div = '<li id="' + i + '" style="grid-column-start: ' + getChek.firstDay + '; cursor: pointer; " onclick="getDate(event);">1</li>';
                }
            }
            else{
                var div = '<li id="' + i + '" style="grid-column-start: ' + getChek.firstDay + '; cursor: unset; opacity: 0.5;" onclick="getDate(event);">1</li>';
            }
        } else {
            if(month > new Date().getMonth()+1){
                var div = '<li id="' + i + '"  style="cursor: pointer; " onclick="getDate(event);">'+ i +'</li>';
            }
            else if(month == new Date().getMonth()+1){
                if(i < new Date().getDate()){
                    var div = '<li id="' + i + '"  style="cursor: unset; opacity: 0.5;" onclick="getDate(event);">'+ i +'</li>';
                }
                else{
                    var div = '<li id="' + i + '"  style="cursor: pointer; " onclick="getDate(event);">'+ i +'</li>';
                }
            }
            else{
                var div = '<li id="' + i + '"  style="cursor: unset; opacity: 0.5;" onclick="getDate(event);">'+ i +'</li>';
            }
        }
        $('#calendarList2').append(div);
    }
    monthName = months.find(x => x.id === month).name;
    $('#yearMonth').text(monthName + ' ' + year );
}
makeCalendar2(currentYear2, currentMonth2);
function nextMonth() {
    currentMonth2 = currentMonth2 + 1;
    if (currentMonth2 > 12) {
        currentYear2 = currentYear2 + 1;
        currentMonth2 = 1;
    }
    $('#calendarList2').empty();
    $('#yearMonth').text(currentMonth2 + ' ' + currentYear2);
    makeCalendar2(currentYear2, currentMonth2);
}
function prevMonth() {
    console.log(new Date().getMonth())
    currentMonth2 = currentMonth2 - 1;
    if (currentMonth2 < 1) {
        currentYear2 = currentYear2 - 1;
        currentMonth2 = 12;
    }
    $('#calendarList2').empty();
    $('#yearMonth').text(currentYear2 + ' ' + currentMonth2);
    makeCalendar2(currentYear2, currentMonth2);
}

/*CALENDAR 2*/

function getDate(event){
    var id = event.target.getAttribute('id');
   // var date = new Date(currentMonth+ ' '+ id + ' '+ currentYear).getDate();
    //alert(id)
    $('#exampleModal').modal('show')
    makeCalendar2(currentYear2,currentMonth2)
    $('.days').removeClass('days')
    if(id >= new Date().getDate() || currentMonth > new Date().getMonth()+1){
        $('#date').val(id+'-'+currentMonth+'-'+currentYear)
        $('#'+id).addClass('days')
    }
    //console.log(date);
}