{{-- custom return value via attribute --}}
@php
    $value = $entry->{$column['function_name']}()->{$column['attribute']};
@endphp
<span>
	{{ (array_key_exists('prefix', $column) ? $column['prefix'] : '').str_limit($value, array_key_exists('limit', $column) ? $column['limit'] : 50, "[...]").(array_key_exists('suffix', $column) ? $column['suffix'] : '') }}
</span>