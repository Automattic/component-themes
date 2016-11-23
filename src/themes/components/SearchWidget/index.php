<?php
function ComponentThemes_SearchWidget($props) {
$placeholder = isset( $props->placeholder ) ? $props->placeholder : '';
$className = isset( $props->className ) ? $props->className : '';
return React::createElement('div',['className' => $className],React::createElement('input',['className' => 'SearchWidget__input','placeholder' => ! empty( $placeholder ) ? $placeholder : 'search this site']));
}
