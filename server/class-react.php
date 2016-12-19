<?php
class React {
	protected static $props_for_cloning = null;

	// @codingStandardsIgnoreStart
	public static function createElement( $component, $props = array(), $children = array() ) {
		// @codingStandardsIgnoreEnd
		$builder = Component_Themes_Builder::get_builder();
		return $builder->create_element( $component, $props, $children );
	}

	public static function render( $component ) {
		if ( ! $component ) {
			return null;
		}
		if ( is_callable( $component ) ) {
			return React::render( React::createElement( $component ) );
		}
		if ( is_string( $component ) ) {
			return React::render( new Component_Themes_Text_Component( $component ) );
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
		$rendered_children = React::mapChildren( $children, 'React::render' );
		$rendered_children = array_filter( $rendered_children, 'ct_not_empty' );
		return implode( ' ', $rendered_children );
	}

	// @codingStandardsIgnoreStart
	public static function mapChildren( $children, $mapper ) {
		// @codingStandardsIgnoreEnd
		if ( ! $children ) {
			return array();
		}
		if ( ! is_array( $children ) ) {
			return array( call_user_func( $mapper, $children ) );
		}
		return array_map( $mapper, $children );
	}

	// @codingStandardsIgnoreStart
	public static function cloneElement( $component, $additional_props = array() ) {
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

	// @codingStandardsIgnoreStart
	public static function cloneChildren( $children, $props = array() ) {
		// @codingStandardsIgnoreEnd
		self::$props_for_cloning = $props;
		$new_children = React::mapChildren( $children, 'React::cloneChild' );
		self::$props_for_cloning = null;

		return $new_children;
	}

	// @codingStandardsIgnoreStart
	protected static function cloneChild( $child ) {
		// @codingStandardsIgnoreEnd
		return React::cloneElement( $child, self::$props_for_cloning );
	}
}
