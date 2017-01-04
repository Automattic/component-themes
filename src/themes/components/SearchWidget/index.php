<?php
function Component_Themes_Search_Widget ( $props ) {
	$placeholder = ct_get_value( $props, 'placeholder', 'search this site' );
	$class_name = ct_get_value( $props, 'className', '' );
	// TODO: import the rootUrl from the state
	$root_url = '/';
	$label = ct_get_value( $props, 'label' );
	$label_element = empty( $label ) ? null : React::createElement( 'span', array( 'className' => 'screen-reader-text' ), $label );
	$button_label = ct_get_value( $props, 'buttonLabel' );
	$button_element = empty( $button_label ) ? null : React::createElement( 'submit', array( 'className' => 'SearchWidget__button' ), $button_label );

	$input_element = React::createElement( 'input', array( 'className' => 'SearchWidget__input', 'placeholder' => $placeholder, 'name' => 's' ) );

	return React::createElement(
		'div',
		array( 'className' => $class_name ),
		array(
			React::createElement(
				'form',
				array( 'className' => 'SearchWidget__form', 'action' => $root_url, 'method' => 'get', 'role' => 'search' ),
				array(
					React::createElement( 'label', array(), array( $label_element, $input_element ) ),
					$button_element
				)
			)
		)
	);
};

Component_Themes::register_component( 'SearchWidget', 'Component_Themes_Search_Widget' );
