<?php
$row_component = function( $props, $children ) {
	$class_name = ct_get_value( $props, 'className', '' );
	return React::createElement( 'div', [ 'className' => $class_name ], $children );
};

$styled = Component_Themes::style_component( $row_component, '
.RowComponent {
	display: flex;
	justify-content: space-between;
}' );

Component_Themes::register_component( 'RowComponent', $styled );
