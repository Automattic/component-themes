<?php
$search_widget = function ( $props ) {
	$placeholder = ct_get_value( $props, 'placeholder', 'search this site' );
	$class_name = ct_get_value( $props, 'className', '' );
	// TODO: import the rootUrl from the state
	$root_url = '/';
	$label = ct_get_value( $props, 'label' );
	$label_element = empty( $label ) ? null : React::createElement( 'span', [ 'className' => 'screen-reader-text' ], $label );
	$button_label = ct_get_value( $props, 'buttonLabel' );
	$button_element = empty( $button_label ) ? null : React::createElement( 'submit', [ 'className' => 'SearchWidget__button' ], $button_label );
	return React::createElement( 'div', [ 'className' => $class_name ], [
		React::createElement( 'form', [ 'className' => 'SearchWidget__form', 'action' => $root_url, 'method' => 'get', 'role' => 'search' ], [
			React::createElement( 'label', [], [
				$label_element,
				React::createElement( 'input', [ 'className' => 'SearchWidget__input', 'placeholder' => $placeholder, 'name' => 's' ] ),
			] ),
			$button_element,
		] ),
	] );
};

Component_Themes::register_component( 'SearchWidget', $search_widget );
