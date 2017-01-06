<?php
function Component_Themes_Post_Title ( $props ) {
	$link = ct_get_value( $props, 'link' );
	$link_text = ct_get_value( $props, 'title', 'No title' );
	$class_name = ct_get_value( $props, 'className', '' );
	return React::createElement(
		'h1', array( 'className' => $class_name ), array(
			React::createElement( 'a', array( 'className' => 'PostTitle_link', 'href' => $link ), array( $link_text ) )
		)
	);
};

Component_Themes::register_component( 'PostTitle', 'Component_Themes_Post_Title' );
