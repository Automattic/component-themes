<?php
function Component_Themes_Column_Component( $props, $children ) {
	$class_name = ct_get_value( $props, 'className', '' );
	$new_props = ct_omit( $props, [ 'className', 'children' ] );
	$new_children = React::cloneChildren( $chilren, $new_props );
	return React::createElement( 'div', [ 'className' => $class_name ], $new_children );
};

Component_Themes::register_component( 'ColumnComponent', 'Component_Themes_Column_Component' );
