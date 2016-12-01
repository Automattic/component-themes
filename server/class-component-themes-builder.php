<?php

namespace Component_Themes;

class Not_Found_Component extends Component {
	public function render() {
		return "Could not find component '" . $this->get_prop( 'componentId' ) . "'";
	}
}

class Html_Component extends Component {
	public function __construct( $tag = 'div', $props = [], $children = [] ) {
		parent::__construct( $props, $children );
		$this->tag = $tag;
	}

	protected function render_props_as_html() {
		return implode( ' ', array_map( function( $key, $prop ) {
			// TODO: look out for quotes
			return "$key='$prop'";
		}, array_keys( $this->props ), $this->props ) );
	}

	public function render() {
		return '<' . $this->tag . " class='" . $this->get_prop( 'className' ) . "'" . $this->render_props_as_html() . '>' . $this->render_children() . '</' . $this->tag . '>';
	}
}

class Stateless_Component extends Component {
	public function __construct( $function_name, $props = [], $children = [] ) {
		parent::__construct( $props, $children );
		$this->function_name = $function_name;
	}

	public function render() {
		return call_user_func( $this->function_name, (object) $this->props, $this );
	}
}

class Builder {
	private $component_api_data = [];
	private $api;

	public function __construct( $options ) {
		$this->api = isset( $options['api'] ) ? $options['api'] : null;
	}

	public static function get_builder() {
		return new Builder( [
			'api' => new Api(),
		] );
	}

	public function render_element( $component ) {
		return $component->render();
	}

	public function create_element( $component_type, $props = [], $children = [] ) {
		$namespaced_type = __NAMESPACE__ . '\\' . $component_type;
		if ( ! ctype_upper( $component_type[0] ) ) {
			return new Html_Component( $component_type, $props, $children );
		}
		if ( function_exists( $namespaced_type ) ) {
			return new Stateless_Component( $component_type, $props, $children );
		}
		if ( ! empty( $namespaced_type::$requiredApiData ) ) {
			$this->component_api_data[ $component_type ] = $this->api->fetchRequiredApiData( $namespaced_type::$requiredApiData );
			$props = array_merge( $props, $this->component_api_data[ $component_type ] );
		}
		return new $namespaced_type( $props, $children );
	}

	public function make_component_with( $component_config, $child_props = [] ) {
		if ( ! isset( $component_config['componentType'] ) ) {
			$name = isset( $component_config['id'] ) ? $component_config['id'] : json_encode( $component_config );
			return $this->create_element( 'Not_Found_Component', [ 'componentId' => $name ] );
		}
		$found_component = $this->get_component_by_type( $component_config['componentType'] );
		$child_components = isset( $component_config['children'] ) ? array_map( function( $child ) use ( &$child_props ) {
			return $this->make_component_with( $child, $child_props );
		}, $component_config['children'] ) : [];
		$props = isset( $component_config['props'] ) ? $component_config['props'] : [];
		$component_props = array_merge( $props, [ 'child_props' => $child_props, 'className' => $this->build_classname_for_component( $component_config ) ] );
		return $this->create_element( $found_component, $component_props, $child_components );
	}

	private function get_component_by_type( $type ) {
		$namespaced_type = __NAMESPACE__ . '\\' . $type;
		if ( function_exists( $namespaced_type ) || class_exists( $namespaced_type ) ) {
			return $type;
		}
		return 'Not_Found_Component';
	}

	private function build_component_from_config( $component_config, $component_data ) {
		$componentId   = isset( $component_config['id'] ) ? $compnent_config['id'] : null;
		$componentType = isset( $component_config['componentType'] ) ? $component_config['componentType'] : null;

		if ( ! $componentType ) {
			$name = $componentId ? $componentId : json_encode( $component_config );
			return $this->create_element( 'Not_Found_Component', [ 'componentId' => $name ] );
		}
		$found_component = $this->get_component_by_type( $componentType );
		$child_components = isset( $component_config['children'] ) ? array_map( function( $child ) use ( &$component_data ) {
			return $this->build_component_from_config( $child, $component_data );
		}, $component_config['children'] ) : [];
		$props = isset( $component_config['props'] ) ? $component_config['props'] : [];
		$data = isset( $component_data[ $componentId ] ) ? $component_data[ $componentId ] : [];
		$component_props = array_merge( $props, $data, [ 'componentId' => $componentId, 'className' => $this->build_classname_for_component( $component_config ) ] );

		return $this->create_element( $found_component, $component_props, $child_components );
	}

	private function build_classname_for_component( $component_config ) {
		$type = empty( $component_config['componentType'] ) ? '' : $component_config['componentType'];
		$id = empty( $component_config['id'] ) ? '' : $component_config['id'];
		return implode( ' ', [ $type, $id ] );
	}

	private function expand_config_partials( $component_config, $partials ) {
		if ( isset( $component_config['partial'] ) ) {
			$partial_key = $component_config['partial'];
			if ( isset( $partials[ $partial_key ] ) ) {
				return $partials[ $partial_key ];
			}
			print_r( $partials );
			echo  '['.$partial_key .']<br>';
			throw new Exception( 'No partial found matching ' . $partial_key );
		}
		if ( isset( $component_config['children'] ) ) {
			$expander = function( $child ) use ( &$partials ) {
				return $this->expand_config_partials( $child, $partials );
			};
			$children = array_map( $expander, $component_config['children'] );
			$new_config = array_merge( $component_config, [ 'children' => $children ] );
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
		$theme_config = $this->merge_themes( $this->get_default_theme(), $theme_config );
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

	private function get_default_theme() {
		if ( ! isset( $this->default_theme ) ) {
			$this->default_theme = json_decode( file_get_contents( dirname( dirname( __FILE__ ) ) . '/src/themes/default.json' ), true );
		}
		return $this->default_theme;
	}

	private function merge_theme_property( $property, $theme1, $theme2 ) {
		$theme1[ $property ] = isset( $theme1[ $property ] ) ? $theme1[ $property ] : [];
		$theme2[ $property ] = isset( $theme2[ $property ] ) ? $theme2[ $property ] : [];
		if ( ! is_array( $theme1[ $property ] ) || ! is_array( $theme2[ $property ] ) ) {
			return $theme2[ $property ];
		}
		return array_merge( $theme1[ $property ], $theme2[ $property ] );
	}

	private function merge_themes( $theme1, $theme2 ) {
		$theme = [];
		$properties = array_unique( array_keys( $theme1 ) + array_keys( $theme2 ) );
		foreach ( $properties as $property ) {
			$theme[ $property ] = $this->merge_theme_property( $property, $theme1, $theme2 );
		}
		return $theme;
	}

	private function build_components_from_theme( $theme_config, $page_config, $component_data ) {
		$theme_config = $this->merge_themes( $this->get_default_theme(), $theme_config );
		$partials = isset( $theme_config['partials'] ) ? $theme_config['partials'] : [];
		return $this->build_component_from_config( $this->expand_config_partials( $this->expand_config_templates( $page_config, $theme_config ), $partials ), $component_data );
	}

	public function render( $theme_config, $page_config, $component_data = [] ) {
		return $this->render_element( $this->build_components_from_theme( $theme_config, $page_config, $component_data ) );
	}

	public function get_component_api_data() {
		return $this->component_api_data;
	}
}
