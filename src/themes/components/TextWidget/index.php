<?php
function Component_Themes_TextWidget( $props ) {
	$text = ct_get_value( $props, 'text', 'This is a text widget with no data!' );
	$class_name = ct_get_value( $props, 'className', '' );
	return React::createElement( 'div', array( 'className' => $class_name ), $text );
}

Component_Themes::register_component( 'TextWidget', 'Component_Themes_TextWidget' );
