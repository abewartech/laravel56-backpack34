{{-- localized datetime using jenssegers/date --}}
<span data-order="{{ $entry->{$column['name']} }}">
    @if (!empty($entry->{$column['name']}))
	{{ Date::parse($entry->{$column['name']})->format(config('backpack.base.default_datetime_format')) }}
    @endif
</span>