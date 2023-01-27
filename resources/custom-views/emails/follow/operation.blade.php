@component('mail::message')
    @if(!empty($detail['msg']))
        <h2>{{$detail['msg']}}</h2>
    @endif
    @if(!empty($detail['custom_html']))
        {!! $detail['custom_html'] !!}
    @endif
    @if(!empty($detail['btn_text']))
        <p>
            @component('mail::button', ['url' => $detail['url']])
                {{$detail['btn_text']}}
            @endcomponent
        </p>
    @endif
    Thanks,<br>
    {{ config('app.name') }}<br>
@endcomponent