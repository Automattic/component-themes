<?php
class Component_Themes_Not_Found_Component extends Component_Themes_Component {
	public function render() {
		$type = $this->get_prop( 'componentType', json_encode( $this->props ) );
		return "Could not find component '" . $type . "'";
	}
}

// @codingStandardsIgnoreStart
function Component_Themes_ErrorComponent( $props ) {
	// @codingStandardsIgnoreEnd
	return '<p>' . ct_get_value( $props, 'message' ) . '</p>';
}

class Component_Themes_Text_Component extends Component_Themes_Component {
	public $text;

	public function __construct( $text = '', $props = array(), $children = array() ) {
		parent::__construct( $props, $children );
		$this->text = $text;
	}

	public function render() {
		return $this->text;
	}
}

class Component_Themes_Html_Component extends Component_Themes_Component {
	public $tag;

	public function __construct( $tag = 'div', $props = array(), $children = array() ) {
		parent::__construct( $props, $children );
		$this->tag = $tag;
	}

	protected function render_props_as_html() {
		$attrs = array();
		foreach( $this->props as $name => $value ) {
			if ( ! is_string( $value ) ) {
				continue;
			}
			if ( 'className' === $name ) {
				$name = 'class';
			}

			$attrs[] = $name . '="' . htmlspecialchars( $value ) . '"';
		}

		return trim( implode( ' ', $attrs ) );
	}

	public function render() {
		$properties = $this->render_props_as_html();
		$properties = empty( $properties ) ? '' : ' ' . $properties;
		return '<' . $this->tag . $properties . '>' . $this->render_children() . '</' . $this->tag . '>';
	}
}

class Component_Themes_Stateless_Component extends Component_Themes_Component {
	public $render_function;

	public function __construct( $render_function, $props = array(), $children = array() ) {
		parent::__construct( $props, $children );
		$this->render_function = $render_function;
	}

	public function render() {
		return call_user_func( $this->render_function, $this->props, $this->children, $this );
	}
}

class Component_Themes_Builder {
	private static $registered_components = array();
	private static $registered_partials = array();

	public function __construct() {
	}

	public static function get_builder() {
		return new Component_Themes_Builder();
	}

	public function create_element( $component, $props = array(), $children = array() ) {
		$props = array_merge( array( 'context' => array() ), $props ? $props : array() );

		if ( $component instanceof Component_Themes_Component ) {
			return $component;
		}
		if ( $component instanceof Component_Themes_Component_Wrapper ) {
			return $component->create( $props, $children );
		}
		if ( is_callable( $component ) ) {
			return new Component_Themes_Stateless_Component( $component, $props, $children );
		}
		if ( is_string( $component ) && ! ctype_upper( $component[0] ) ) {
			return new Component_Themes_Html_Component( $component, $props, $children );
		}
		if ( 'Component_Themes_Text_Component' === $component ) {
			// This would be an error state, but to prevent errors, we will make an empty text node
			return new Component_Themes_Text_Component( '', $props, $children );
		}
		if ( 'Component_Themes_Html_Component' === $component ) {
			// This would be an error state, but to prevent errors, we will make a div
			return new Component_Themes_Html_Component( 'div', $props, $children );
		}
		if ( 'Component_Themes_Stateless_Component' === $component ) {
			// This would be an error state, but to prevent errors, we will make a noop
			$noop = create_function( '', '' );
			return new Component_Themes_Stateless_Component( $noop, $props, $children );
		}
		return new $component( $props, $children );
	}

	public static function register_component( $type, $component ) {
		self::$registered_components[ $type ] = $component;
	}

	private function get_component_by_type( $type ) {
		if ( isset( self::$registered_components[ $type ] ) ) {
			return self::$registered_components[ $type ];
		}

		return new Component_Themes_Not_Found_Component( array( 'componentType' => $type ) );
	}

	public function get_component_styles() {
		$styles = array();

		foreach ( self::$registered_components as $component ) {
			$style = null;
			if ( $component instanceof Component_Themes_Component_Wrapper ) {
				$style = $component->get_styles();
			} elseif ( is_string( $component ) && is_subclass_of( $component, 'Component_Themes_Component' ) ) {
				$style = Component_Themes_Component::get_styles( $component );
			}

			if ( ! empty( $style ) ) {
				$styles[] = $style;
			}
		}

		$styles = implode( '', $styles );

		return $styles;
	}

	private function get_partial_by_type( $type ) {
		if ( isset( self::$registered_partials[ $type ] ) ) {
			return self::$registered_partials[ $type ];
		}
		return array(
			'componentType' => 'ErrorComponent',
			'props' => array( 'message' => "I could not find the partial '$type'" )
		);
	}

	public static function register_partial( $type, $component_config ) {
		self::$registered_partials[ $type ] = $component_config;
	}

	private function register_partials( $partials ) {
		foreach ( $partials as $type => $config ) {
			self::register_partial( $type, $config );
		}
	}

	private function build_component_from_config( $component_config ) {
		if ( isset( $component_config['partial'] ) ) {
			return $this->build_component_from_config( $this->get_partial_by_type( $component_config['partial'] ) );
		}
		if ( ! isset( $component_config['componentType'] ) ) {
			$name = ct_get_value( $component_config, 'id', json_encode( $component_config ) );
			return $this->create_element( 'Component_Themes_Not_Found_Component', array( 'componentType' => $name ) );
		}
		$found_component = $this->get_component_by_type( $component_config['componentType'] );
		$child_components = isset( $component_config['children'] ) ? array_map( array( $this, 'build_component_from_config' ), $component_config['children'] ) : array();
		$props = ct_get_value( $component_config, 'props', array() );
		$component_config['id'] = ct_get_value( $component_config, 'id', $this->generate_id( $component_config ) );
		$component_props = array_merge( $props, array( 'componentId' => $component_config['id'], 'className' => $this->build_classname_for_component( $component_config ) ) );
		return $this->create_element( $found_component, $component_props, $child_components );
	}

	public function generate_id( $data ) {
		return 'ct-' . hash( 'md5', json_encode( $data ) );
	}

	private function build_classname_for_component( $component_config ) {
		$type = empty( $component_config['componentType'] ) ? '' : $component_config['componentType'];
		$id = empty( $component_config['id'] ) ? '' : $component_config['id'];
		return implode( ' ', array( $type, $id ) );
	}

	private function expand_config_partials( $component_config, $partials ) {
		if ( isset( $component_config['partial'] ) ) {
			$partial_key = $component_config['partial'];
			if ( isset( $partials[ $partial_key ] ) ) {
				return $partials[ $partial_key ];
			}
			throw new Exception( 'No partial found matching ' . $partial_key );
		}
		if ( isset( $component_config['children'] ) ) {
			$children = array();
			foreach( $component_config['children'] as $key => $child ) {
				$children[ $key ] = $this->expand_config_partials( $child, $partials );
			}
			$new_config = array_merge( $component_config, array( 'children' => $children ) );
			return $new_config;
		}
		return $component_config;
	}

	private function expand_config_templates( $component_config, $theme_config ) {
		if ( isset( $component_config['template'] ) ) {
			$key = $component_config['template'];
			return $this->get_template_for_slug( $theme_config, $key );
		}
		return $component_config;
	}

	public function get_template_for_slug( $theme_config, $slug ) {
		$original_slug = $slug;
		if ( ! isset( $theme_config['templates'] ) ) {
			throw new Exception( 'No template found matching ' . $slug . ' and no templates were defined in the theme' );
		}
		// Try a '404' template, then 'home'
		if ( ! isset( $theme_config['templates'][ $slug ] ) ) {
			$slug = '404';
		}
		if ( ! isset( $theme_config['templates'][ $slug ] ) ) {
			$slug = 'home';
		}
		if ( ! isset( $theme_config['templates'][ $slug ] ) ) {
			throw new Exception( 'No template found matching ' . $original_slug . ' and no 404 or home templates were defined in the theme' );
		}
		$template = $theme_config['templates'][ $slug ];
		if ( isset( $template['template'] ) ) {
			return $this->get_template_for_slug( $theme_config, $template['template'] );
		}
		return $template;
	}

	private function array_has_string_key( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}

		$string_keys = array_filter( array_keys( $array ), 'is_string' );
		return ! empty( $string_keys );
	}

	public function merge_themes( $theme1, $theme2 ) {
		$array_in_theme1 = array_filter( $theme1, array( $this, 'array_has_string_key' ) );
		$array_in_theme2 = array_filter( $theme2, array( $this, 'array_has_string_key' ) );

		foreach ( $array_in_theme2 as $key => $value )  {
			$source = isset( $array_in_theme1[ $key ] ) ? $array_in_theme1[ $key ] : array();
			$array_in_theme1[ $key ] = array_merge( $source, $array_in_theme2[ $key ] );
		}

		$theme = array_merge( $theme1, $theme2, $array_in_theme1 );

		return $theme;
	}

	private function build_components_from_theme( $theme_config, $page_config ) {
		$this->register_partials( ct_get_value( $theme_config, 'partials', array() ) );
		return $this->build_component_from_config( $this->expand_config_templates( $page_config, $theme_config ) );
	}

	public function render( $theme_config, $page_config ) {
		return React::render( $this->build_components_from_theme( $theme_config, $page_config ) );
	}
}
