<?php
function Component_Themes_PostAuthor( $props, $component ) {
	$author = $component->get_prop_from_parent( 'author', 'No author' );
	return "<span class='PostAuthor'>by $author</span>";
}
