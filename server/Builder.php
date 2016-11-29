<?php
class ComponentThemes_NotFoundComponent extends ComponentThemes_Component {
	public function render() {
		return "Could not find component '" . $this->getProp( 'componentId' ) . "'";
	}
}

class ComponentThemes_HtmlComponent extends ComponentThemes_Component {
	public function __construct( $tag = 'div', $props = [], $children = [] ) {
		$this->tag = $tag;
		$this->props = $props;
		$this->children = $children;
	}

	protected function renderPropsAsHtml() {
		return implode( ' ', array_map( function( $key, $prop ) {
			// TODO: look out for quotes
			return "$key='$prop'";
		}, array_keys( $this->props ), $this->props ) );
	}

	public function render() {
		return "<" . $this->tag . " class='" . $this->getProp( 'className' ) . "'" . $this->renderPropsAsHtml() . ">" . $this->renderChildren() . "</" . $this->tag . ">";
	}
}

class ComponentThemes_StatelessComponent extends ComponentThemes_Component {
	public function __construct( $function_name, $props = [], $children = [] ) {
		$this->function_name = $function_name;
		$this->props = $props;
		$this->children = $children;
	}

	public function render() {
		return call_user_func( $this->function_name, (object) $this->props );
	}
}

class React {
	public static function createElement( $componentType, $props = [], $children = [] ) {
		$builder = ComponentThemes_Builder::getBuilder();
		$out = $builder->createElement( $componentType, $props, $children );
		if ( is_string( $out ) ) {
			return $out;
		}
		return $out->render();
	}
}

class ComponentThemes_Builder {
	private $componentApiData = [];
	private $api;

	public function __construct( $options ) {
		$this->api = isset( $options[ 'api' ] ) ? $options[ 'api' ] : null;
	}

	public static function getBuilder() {
		return new ComponentThemes_Builder( [
			'api' => new ComponentThemes_Api(),
		] );
	}

	public function renderElement( $component ) {
		return $component->render();
	}

	public function createElement( $componentType, $props = [], $children = [] ) {
		if ( ! ctype_upper( $componentType[ 0 ] ) ) {
			return new ComponentThemes_HtmlComponent( $componentType, $props, $children );
		}
		if ( function_exists( $componentType ) ) {
			return new ComponentThemes_StatelessComponent( $componentType, $props, $children );
		}
		if ( ! empty( $componentType::$requiredApiData ) ) {
			$pureType = $this->stripNamespaceFromComponentType( $componentType );
			$this->componentApiData[ $pureType ] = $this->api->fetchRequiredApiData( $componentType::$requiredApiData );
			$props = array_merge( $props, $this->componentApiData[ $pureType ] );
		}
		return new $componentType( $props, $children );
	}

	public function makeComponentWith( $componentConfig, $childProps = [] ) {
		if ( ! isset( $componentConfig['componentType'] ) ) {
			$name = isset( $componentConfig['id'] ) ? $componentConfig['id'] : json_encode( $componentConfig );
			return $this->createElement( 'ComponentThemes_NotFoundComponent', [ 'componentId' => $name ] );
		}
		$foundComponent = $this->getComponentByType( $componentConfig['componentType'] );
		$childComponents = isset( $componentConfig['children'] ) ? array_map( function( $child ) use ( &$childProps ) {
			return $this->makeComponentWith( $child, $childProps );
		}, $componentConfig['children'] ) : [];
		$props = isset( $componentConfig['props'] ) ? $componentConfig['props'] : [];
		$componentProps = array_merge( $props, [ 'childProps' => $childProps, 'className' => $this->buildClassNameForComponent( $componentConfig ) ] );
		return $this->createElement( $foundComponent, $componentProps, $childComponents );
	}

	private function stripNamespaceFromComponentType( $type ) {
		return str_replace( 'ComponentThemes_', '', $type );
	}

	private function getComponentByType( $type ) {
		$namespacedType = 'ComponentThemes_' . $type;
		if ( function_exists( $namespacedType ) || class_exists( $namespacedType ) ) {
			return $namespacedType;
		}
		return 'ComponentThemes_NotFoundComponent';
	}

	private function buildComponentFromConfig( $componentConfig, $componentData ) {
		if ( ! isset( $componentConfig['componentType'] ) ) {
			$name = isset( $componentConfig['id'] ) ? $componentConfig['id'] : json_encode( $componentConfig );
			return $this->createElement( 'ComponentThemes_NotFoundComponent', [ 'componentId' => $name ] );
		}
		$foundComponent = $this->getComponentByType( $componentConfig['componentType'] );
		$childComponents = isset( $componentConfig['children'] ) ? array_map( function( $child ) use ( &$componentData ) {
			return $this->buildComponentFromConfig( $child, $componentData );
		}, $componentConfig['children'] ) : [];
		$props = isset( $componentConfig['props'] ) ? $componentConfig['props'] : [];
		$data = isset( $componentData[ $componentConfig['id'] ] ) ? $componentData[ $componentConfig['id'] ] : [];
		$componentProps = array_merge( $props, $data, [ 'componentId' => $componentConfig['id'], 'className' => $this->buildClassNameForComponent( $componentConfig ) ] );
		return $this->createElement( $foundComponent, $componentProps, $childComponents );
	}

	private function buildClassNameForComponent( $componentConfig ) {
		$type = empty( $componentConfig['componentType'] ) ? '' : $componentConfig['componentType'];
		$id = empty( $componentConfig['id'] ) ? '' : $componentConfig['id'];
		return implode( ' ', [ $type, $id ] );
	}

	private function expandConfigPartials( $componentConfig, $partials ) {
		if ( isset( $componentConfig['partial'] ) ) {
			$partial_key = $componentConfig['partial'];
			if ( isset( $partials[ $partial_key ] ) ) {
				return $partials[ $partial_key ];
			}
			throw new \Exception( "No partial found matching " . $partial_key );
		}
		if ( isset( $componentConfig['children'] ) ) {
			$expander = function( $child ) use ( &$partials ) {
				return $this->expandConfigPartials( $child, $partials );
			};
			$children = array_map( $expander, $componentConfig['children'] );
			$newConfig = array_merge( $componentConfig, [ 'children' => $children ] );
			return $newConfig;
		}
		return $componentConfig;
	}

	private function expandConfigTemplates( $componentConfig, $themeConfig ) {
		if ( isset( $componentConfig['template'] ) ) {
			$key = $componentConfig['template'];
			return $this->getTemplateForSlug( $themeConfig, $key );
		}
		return $componentConfig;
	}

	public function getTemplateForSlug( $themeConfig, $slug ) {
		$originalSlug = $slug;
		if ( ! isset( $themeConfig['templates'] ) ) {
			throw new \Exception( "No template found matching " . $slug . " and no templates were defined in the theme" );
		}
		// Try a '404' template, then 'home'
		if ( ! isset( $themeConfig['templates'][ $slug ] ) ) {
			$slug = '404';
		}
		if ( ! isset( $themeConfig['templates'][ $slug ] ) ) {
			$slug = 'home';
		}
		if ( ! isset( $themeConfig['templates'][ $slug ] ) ) {
			throw new \Exception( "No template found matching " . $originalSlug . " and no 404 or home templates were defined in the theme" );
		}
		$template = $themeConfig['templates'][ $slug ];
		if ( isset( $template['template'] ) ) {
			return $this->getTemplateForSlug( $themeConfig, $template['template'] );
		}
		return $template;
	}

	private function buildComponentsFromTheme( $themeConfig, $pageConfig, $componentData ) {
		$partials = isset( $themeConfig['partials'] ) ? $themeConfig['partials'] : [];
		return $this->buildComponentFromConfig( $this->expandConfigPartials( $this->expandConfigTemplates( $pageConfig, $themeConfig ), $partials ), $componentData );
	}

	public function render( $themeConfig, $pageConfig, $componentData = [] ) {
		return $this->renderElement( $this->buildComponentsFromTheme( $themeConfig, $pageConfig, $componentData ) );
	}

	public function getComponentApiData() {
		return $this->componentApiData;
	}
}
