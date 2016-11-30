<?php
function Component_Themes_PostDate( $props, $component ) {
	$date = $component->get_prop_from_parent( 'date', 'No date' );
	return "<span class='PostDate'>$date</span>";
}

