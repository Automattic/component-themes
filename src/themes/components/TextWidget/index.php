<?php
function ComponentThemes_TextWidget( $props ) {
	$text = isset( $props->text ) ? $props->text : 'This is a text widget with no data!';
	$className = isset( $props->className ) ? $props->className : '';
	return React::createElement('div',['className' => $className],$text);
}

