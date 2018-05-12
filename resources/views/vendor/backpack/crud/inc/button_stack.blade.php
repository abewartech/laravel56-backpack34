@if ($crud->buttons->where('stack', $stack)->count())
	@foreach ($crud->buttons->where('stack', $stack) as $button)
	  @if ($button->type == 'model_function')
		@if ($stack == 'line')
	  		  {!! $entry->{$button->content}($entry); !!}
		@else
			  {!! $crud->model->{$button->content}($crud); !!}
		@endif
	  @else
		@include($button->content)
	  @endif
	@endforeach
@endif
