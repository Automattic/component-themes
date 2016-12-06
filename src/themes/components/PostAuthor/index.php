<?php
function Component_Themes_PostAuthor( $props, $children, $component ) {
	$author = $component->get_prop_from_parent( 'author', 'No author' );
	return "<span class='PostAuthor'>by $author</span>";
}

Component_Themes::register_component( 'PostAuthor', 'Component_Themes_PostAuthor' );
