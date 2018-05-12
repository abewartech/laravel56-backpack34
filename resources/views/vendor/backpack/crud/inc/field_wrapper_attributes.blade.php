@if (isset($field['wrapperAttributes']))
    @foreach ($field['wrapperAttributes'] as $attribute => $value)
    	@if (is_string($attribute))
        {{ $attribute }}="{{ $value }}"
        @endif
    @endforeach

    @if (!isset($field['wrapperAttributes']['class']))
		class="form-group col-xs-12{{ isset($field['attributes']['required']) ? ' required' : '' }}"
    @endif
@else
	class="form-group col-xs-12{{ isset($field['attributes']['required']) ? ' required' : '' }}"
@endif
