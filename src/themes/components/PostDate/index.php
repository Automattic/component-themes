<?php
function Component_Themes_PostDate( $props, $children, $component ) {
	$date_format = ct_get_value( $props, 'date_format' );
	$date = $component->get_prop( 'date', 'No date' );

	if ( ! is_int( $date ) ) {
		$date = strtotime( $date );
	}
	$date_str = date( $date_format, $date );

	return "<span class='PostDate'>$date_str</span>";
}

function Component_Themes_PostDate_Api_Mapper( $get_api_endpoint ) {
	$settings = call_user_func( $get_api_endpoint, '/wp/v2/settings' );

	return array(
		'date_format' => $settings['date_format'],
	);
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_PostDate', 'Component_Themes_PostDate_Api_Mapper' );

Component_Themes::register_component( 'PostDate', $wrapped );
