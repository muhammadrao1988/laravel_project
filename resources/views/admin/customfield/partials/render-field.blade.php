@php
$customFields = \Common::getCustomFields($module);
if(!empty($customFields)){
@endphp
<div class="col-md-12"><hr><h3>Custom Attributes</h3><hr></div>
@php	
}
@endphp

@foreach($customFields as $key => $field)
	@if(!empty($field))
		@php
			$fieldName = $field->name;
			$name = "name=$fieldName";
			$hasError = $errors->has($fieldName) ? ' is-invalid' : '';
			$fieldValue = \Common::getCustomFieldValue($module, $field->name, @$model->id);
			$fieldValue = old($fieldName, (!empty($fieldValue) ? $fieldValue : @$field->defaultValue));
			$disabled = (((!empty(@$model->id) && (request()->route()->getActionMethod() == "show")) || $field->active == 0) ? "disabled" : "");
		@endphp

		<div class="form-group col-md-4">
		    <label for="{{ $fieldName }}">{{ $field->title }}</label><br>

			@if($field->type == "text")
			    <input {{ $disabled }} type="text" class="form-control{{ $hasError }}" {{ $name }} value="{{ $fieldValue }}">
			@endif


			@if($field->type == "number")
			    <input {{ $disabled }} type="number" class="form-control{{ $hasError }}" {{ $name }} value="{{ $fieldValue }}">
			@endif

			@if($field->type == "select")
		        <select {{ $disabled }} class="form-control{{ $hasError }} select2" {{ $name }} style="width: 100%;">
	              <option value="" disabled selected="">Please Select</option>
	              @foreach(explode(',', $field->acceptable) as $key => $value)
	              	<option value="{{ $value }}" {{ ($fieldValue == $value ? 'selected=selected' : '') }}>
	                	{{ $value }}
	              	</option>
	              @endforeach
	            </select>
			@endif

			@if($field->type == "textarea")
			    <textarea {{ $disabled }} class="form-control{{ $hasError }}" {{ $name }}>{{ $fieldValue }}</textarea>
			@endif

			@if($field->type == "checkbox")
				@foreach(explode(',', $field->acceptable) as $key => $value)
					<input {{ $disabled }} type="checkbox" class="{{ $hasError }}" id="{{ $value }}" {{ $name }}[] value="{{ $value }}" {{ ($fieldValue == $value ? 'checked' : '') }}>
					<label for="{{ $value }}">{{ $value }}</label>
				@endforeach
			@endif

			@if($field->type == "radio")
				@foreach(explode(',', $field->acceptable) as $key => $value)
					<input {{ $disabled }} type="radio" class="{{ $hasError }}" id="{{ $value }}" {{ $name }} value="{{ $value }}" {{ ($fieldValue == $value ? 'checked' : '') }}>
					<label for="{{ $value }}">{{ $value }}</label>
				@endforeach
			@endif

			@if ($errors->has($fieldName))
	        <span class="invalid-feedback" role="alert">
	          <strong>{{ $errors->first($fieldName) }}</strong>
	        </span>
	        @endif
		</div>
	@endif
@endforeach