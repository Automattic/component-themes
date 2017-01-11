<?php
function Component_Themes_Post_Body( $props, $children ) {
	$class_name = ct_get_value( $props, 'className', '' );
	$new_props = ct_omit( $props, array( 'className', 'children' ) );
	$new_children = React::cloneChildren( $children, $new_props );
	return React::createElement( 'div', array( 'className' => $class_name ), $new_children );
};

Component_Themes::register_component( 'PostBody', 'Component_Themes_Post_Body' );
