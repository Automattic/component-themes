<?php
function Component_Themes_PostDate( $props, $children, $component ) {
	$date = $component->get_prop( 'date', 'No date' );
	return "<span class='PostDate'>$date</span>";
}

Component_Themes::register_component( 'PostDate', 'Component_Themes_PostDate' );
