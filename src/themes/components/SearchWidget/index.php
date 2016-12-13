<?php
$search_widget = function ( $props ) {
	$placeholder = ct_get_value( $props, 'placeholder', 'search this site' );
	$class_name = ct_get_value( $props, 'className', '' );
	// TODO: import the rootUrl from the state
	$root_url = '/';
	return React::createElement( 'div', [ 'className' => $class_name ], [
		React::createElement( 'form', [ 'className' => 'SearchWidget__form', 'action' => $root_url, 'method' => 'get', 'role' => 'search' ], [
			React::createElement( 'input', [ 'className' => 'SearchWidget__input', 'placeholder' => $placeholder, 'name' => 's' ] ),
		] ),
	] );
};

Component_Themes::register_component( 'SearchWidget', $search_widget );
