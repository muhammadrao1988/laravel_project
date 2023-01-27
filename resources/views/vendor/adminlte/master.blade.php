<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
    @yield('title', config('adminlte.title', 'AdminLTE 3'))
    @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    @if(! config('adminlte.enabled_laravel_mix'))
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    @include('adminlte::plugins', ['type' => 'css'])

    @yield('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/offline.css') }}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    @yield('adminlte_css')

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @else
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @endif

    @yield('meta_tags')

    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif
    @toastr_css
</head>
<body class="@yield('classes_body')" @yield('body_data')>

@yield('body')

@if(! config('adminlte.enabled_laravel_mix'))
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js">
</script>
<script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

@include('adminlte::plugins', ['type' => 'js'])

@yield('adminlte_js')
@else
<script src="{{ mix('js/app.js') }}"></script>
@endif
<script src="{{ asset('vendor/adminlte/dist/js/custom.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/offline.js') }}"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@if(isset($_SERVER['SERVER_ADDR']))
{{--<script src="http://{{ @$_SERVER['SERVER_ADDR'] }}:6001/socket.io/socket.io.js"></script>--}}
@endif
<!-- Custom JS With PHP Involevd -->
<script>
    //Jquery Validation
    $.validator.messages.required = function (param, input) {

        if (typeof $(input).attr('data-name') !== 'undefined'){
            return 'The ' + $(input).attr('data-name') + ' field is required';
        }else{
            return 'The ' + input.name + ' field is required';
        }

    }

    $(".validation_form").validate({
        errorClass: 'is-invalid text-danger',
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.insertAfter(element.closest('div'));
            error.addClass("error_text");
            //console.log(element);
        }
    });
</script>
<script type="text/javascript">
@auth
  @if (Common::checkPermission('System', "notification"))
  try {
    var socket = io("http://{{ @$_SERVER['SERVER_ADDR'] }}:6001");
    var notificationChannel = "{{ strtolower(env('APP_NAME')).'_database_BellNotification' }}";
    socket.emit('subscribe', {
        channel: notificationChannel
    }).on('App\\Events\\NotificationSent', function (channel, data) {
        let element = $('.div-bell-notif')
        $.ajax({
            type: 'GET',
            url: element.attr('data-route'),
            success: function (result) {
                element.html(result.html)
            }
        });
    });
  }
  catch(err) {
    //
  }
  @endif
@endauth

if($('.select2-ajax')[0]){
    select2Ajax(".select2-ajax")
}

if($('.select2-ajax-no-limit')[0]){
    select2AjaxNoLimit(".select2-ajax-no-limit")
}

if($('.select2-ajax-with-tags')[0]){
    select2AjaxWithTags(".select2-ajax-with-tags")
}

function select2Ajax(className){
  $(className).select2({
    allowClear: true,
    placeholder: 'Please Select',
    minimumInputLength: 2,
    selectOnClose: true,
    ajax: {
      url: "{{ route('select2Ajax') }}",
      dataType: "json",
      type: "POST",
      data: function (params) {
        var queryParameters = {
          _token: "{{ csrf_token() }}",
          term: params.term,
          source: $(this).attr('data-select2-source'),
          filter: $(this).attr('data-select2-filter')
        }
        return queryParameters;
      },
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.text,
              id: item.id
            }
          })
        };
      }
    }
  });
}

function select2AjaxNoLimit(className){
    $(className).select2({
        allowClear: true,
        placeholder: 'Please Select',
        minimumInputLength: 1,
        selectOnClose: true,
        ajax: {
            url: "{{ route('select2Ajax') }}",
            dataType: "json",
            type: "POST",
            data: function (params) {
                var queryParameters = {
                    _token: "{{ csrf_token() }}",
                    term: params.term,
                    source: $(this).attr('data-select2-source'),
                    filter: $(this).attr('data-select2-filter')
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            }
        }
    });
}

function select2AjaxWithTags(className){
    $(className).select2({
        allowClear: true,
        placeholder: 'Please Select',
        minimumInputLength: 1,
        selectOnClose: true,
        tags: true,
        ajax: {
            url: "{{ route('select2Ajax') }}",
            dataType: "json",
            type: "POST",
            data: function (params) {
                var queryParameters = {
                    _token: "{{ csrf_token() }}",
                    term: params.term,
                    source: $(this).attr('data-select2-source'),
                    filter: $(this).attr('data-select2-filter')
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            }
        }
    });
}

if($('.select2-ajax')[0]){
  $('.select2-ajax').each(function(){
    var select2 = $(this);
    var term = select2.attr('data-select2-value');
    var isMultiple = select2.attr('multiple');

    if(term){
      if(isMultiple){
        var term = JSON.parse(term);
        $.each(term, function(i, val){
          if(!isNull(val)){
            getValue(select2, val);
          }
        });
      }else{
        getValue(select2, term);
      }
    }
  });
}

if($('.select2-ajax-no-limit')[0]){
  $('.select2-ajax-no-limit').each(function(){
    var select2 = $(this);
    var term = select2.attr('data-select2-value');
    var isMultiple = select2.attr('multiple');

    if(term){
      if(isMultiple){
        var term = JSON.parse(term);
        $.each(term, function(i, val){
          if(!isNull(val)){
            getValue(select2, val);
          }
        });
      }else{
        getValue(select2, term);
      }
    }
  });
}

if($('.select2-ajax-with-tags')[0]){
  $('.select2-ajax-with-tags').each(function(){
    var select2 = $(this);
    var term = select2.attr('data-select2-value');
    var isMultiple = select2.attr('multiple');

    if(term){
      if(isMultiple){
        var term = JSON.parse(term);
        $.each(term, function(i, val){
          if(!isNull(val)){
            getValue(select2, val);
          }
        });
      }else{
        getValue(select2, term);
      }
    }
  });
}

function getSelect2Value(select2){
  var returnValue = 0;

  if($(select2).attr('data-select2-value') > 0){
    returnValue = $(select2).attr('data-select2-value');
  }

  if($(select2).val() > 0){
    returnValue = $(select2).val();
  }
  return  returnValue;
}

function updateSelect2(select2, value){
  var select2 = $(select2);
  select2.attr('data-select2-value', value);
  if(value){
    getValue(select2, value);
  }
}

function getValue(select2, value){
  $.ajax({
      url: "{{ route('select2Ajax') }}",
      dataType: "json",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        term : value,
        source: select2.attr('data-select2-source'),
        filter: select2.attr('data-select2-filter'),
        isDefault: 1
      }
  }).then(function (data) {
      var option = new Option(data[0].text, data[0].id, true, true);
      if(select2.attr('data-select2-trigger') == 0){
        select2.append(option);
      }else{
        select2.append(option).trigger('change');
      }
      select2.trigger({
          type: 'select2:select',
          params: {
              data: data[0]
          }
      });
  });
}
</script>
<!-- Custom JS With PHP Involevd -->
</body>
@toastr_js
@toastr_render
</html>
