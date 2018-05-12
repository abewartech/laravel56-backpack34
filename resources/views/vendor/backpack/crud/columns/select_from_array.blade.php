{{-- select_from_array column --}}
<span>
	<?php
		if ($entry->{$column['name']} !== null) {
	    	echo $column['options'][$entry->{$column['name']}];
	    } else {
	    	echo "-";
	    }
	?>
</span>
