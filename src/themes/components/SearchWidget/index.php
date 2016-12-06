<?php
function Component_Themes_SearchWidget( $props ) {
	$placeholder = ct_get_value( $props, 'placeholder', 'search this site' );
	$class_name = ct_get_value( $props, 'className', '' );
	return React::createElement( 'div', [ 'className' => $class_name ], React::createElement( 'input', [ 'className' => 'SearchWidget__input', 'placeholder' => $placeholder ] ) );
}

Component_Themes::register_component( 'SearchWidget', 'Component_Themes_SearchWidget' );
