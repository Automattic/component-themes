<?php
class React {
	// @codingStandardsIgnoreStart
	public static function createElement( $component, $props = [], $children = [] ) {
		// @codingStandardsIgnoreEnd
		$builder = Component_Themes_Builder::get_builder();
		return $builder->create_element( $component, $props, $children );
	}

	public static function render( $component ) {
		if ( is_callable( $component ) ) {
			return React::render( React::createElement( $component ) );
		}
		if ( is_string( $component ) ) {
			return React::render( new Component_Themes_Text_Component( $component ) );
		}
		if ( is_array( $component ) ) {
			throw new Exception( 'Cannot call render on an array: ' . print_r( $component, true ) );
		}
		$out = $component->render();
		if ( is_string( $out ) ) {
			return $out;
		}
		return React::render( $out );
	}

	// @codingStandardsIgnoreStart
	public static function renderChildren( $children ) {
		// @codingStandardsIgnoreEnd
		$rendered_children = React::mapChildren( $children, function( $child ) {
			return React::render( $child );
		} );
		return implode( ' ', $rendered_children );
	}

	// @codingStandardsIgnoreStart
	public static function mapChildren( $children, $mapper ) {
		// @codingStandardsIgnoreEnd
		if ( ! $children ) {
			return [];
		}
		if ( ! is_array( $children ) ) {
			return [ call_user_func( $mapper, $children ) ];
		}
		return array_map( $mapper, $children );
	}

	// @codingStandardsIgnoreStart
	public static function cloneElement( $component, $additional_props = [] ) {
		// @codingStandardsIgnoreEnd
		$props = $component->props;
		$children = $component->children;
		$props = array_merge( $props, $additional_props );
		$component_type = get_class( $component );
		if ( 'Component_Themes_Html_Component' === $component_type ) {
			$tag = $component->tag;
			return React::createElement( $tag, $props, $children );
		}
		if ( 'Component_Themes_Stateless_Component' === $component_type ) {
			$render_function = $component->render_function;
			return React::createElement( $render_function, $props, $children );
		}
		return React::createElement( $component_type, $props, $children );
	}
}

