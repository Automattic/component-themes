<?php
function Component_Themes_PostAuthor( $props ) {
	$author = ct_get_value( $props, 'author', 'No author' );
	return '<span class="PostAuthor">by ' . $author . '</span>';
}

Component_Themes::register_component( 'PostAuthor', 'Component_Themes_PostAuthor' );
