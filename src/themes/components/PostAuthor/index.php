<?php
function Component_Themes_PostAuthor( $props ) {
	$author = ct_get_value( $props, 'author', 'No author' );
	return React::createElement( 'span', array( 'className' => 'PostAuthor' ), array( "by {$author}" ) );
}

Component_Themes::register_component( 'PostAuthor', 'Component_Themes_PostAuthor' );
