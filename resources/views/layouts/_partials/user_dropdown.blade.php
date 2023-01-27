@if(@$model->userId!="")
    <input type="hidden" name="oldUserId" value="{{@$model->userId}}">
@endif
<select class="form-control{{ $errors->has('userId') ? ' is-invalid' : '' }} select2"
        id="userId" name="userId">
    <option value="" selected="">Please Select</option>
    @if(@$model->userId!="")
    <option selected value="{{@$model->userId}}">{{$model->user->name}}</option>
    @endif
    @foreach(\App\Models\User::availableUser() as $user)
    <option value="{{$user->id}}" {{$model->userId == $user->id ? 'selected=selected' : (old('userId') == $user->id ? 'selected=selected' : '')}} >
        {{ $user->name }}
    </option>
    @endforeach
</select>
