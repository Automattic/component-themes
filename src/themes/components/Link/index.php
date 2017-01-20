<?php

function Component_Themes_Link ( $props, $children ) {
	$url = ct_get_value( $props, 'url' );
	$class_name = ct_get_value( $props, 'className', '' );
	return React::createElement(
		'a',
		array( 'className' => $class_name, 'href' => $url ),
		$children
	);
};

Component_Themes::register_component( 'Link', 'Component_Themes_Link' );
