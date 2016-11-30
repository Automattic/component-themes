<?php
class React {
	public static function createElement( $component_type, $props = [], $children = [] ) {
		$builder = Component_Themes_Builder::get_builder();
		$out = $builder->create_element( $component_type, $props, $children );
		if ( is_string( $out ) ) {
			return $out;
		}
		return $out->render();
	}
}

