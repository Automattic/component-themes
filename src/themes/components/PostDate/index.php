<?php
function Component_Themes_PostDate( $props ) {
	$date_format = ct_get_value( $props, 'date_format', 'F j, Y' );
	$date = ct_get_value( $props, 'date', '' );
	$date_str = 'No date';

	if ( ! empty( $date ) ) {
		if ( ! is_int( $date ) ) {
			$date = strtotime( $date );
		}
		$date_str = date( $date_format, $date );
	}

	return React::createElement( 'span', array( 'className' => 'PostDate' ), array( $date_str ) );
}

function Component_Themes_PostDate_Api_Mapper( $get_api_endpoint ) {
	$settings = call_user_func( $get_api_endpoint, '/component-themes/v1/settings' );

	return array(
		'date_format' => $settings['date_format'],
	);
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_PostDate', 'Component_Themes_PostDate_Api_Mapper' );

Component_Themes::register_component( 'PostDate', $wrapped );
