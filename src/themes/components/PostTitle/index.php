<?php
$post_title = function( $props ) {
	$link = ct_get_value( $props, 'link' );
	$link_text = ct_get_value( $props, 'title', 'No title' );
	$class_name = ct_get_value( $props, 'className', '' );
	return "<h1 class='$class_name'>
		<a class='PostTitle_link' href='$link'>$link_text</a>
		</h1>";
};

Component_Themes::register_component( 'PostTitle', $post_title );
