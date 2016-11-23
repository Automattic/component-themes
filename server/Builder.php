<?php
namespace Prometheus;

class NotFoundComponent extends Component {
	public function render() {
		return "Could not find component '" . $this->getProp( 'componentId' ) . "'";
	}
}

class HtmlComponent extends Component {
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

class StatelessComponent extends Component {
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
		$builder = new Builder();
		$out = $builder->createElement( $componentType, $props, $children );
		if ( is_string( $out ) ) {
			return $out;
		}
		return $out->render();
	}
}

class Builder {
	public function renderElement( $component ) {
		return $component->render();
	}

	public function createElement( $componentType, $props = [], $children = [] ) {
		if ( ! ctype_upper( $componentType[ 0 ] ) ) {
			return new HtmlComponent( $componentType, $props, $children );
		}
		if ( function_exists( $componentType ) ) {
			return new StatelessComponent( $componentType, $props, $children );
		}
		return new $componentType( $props, $children );
	}

	public function makeComponentWith( $componentConfig, $childProps = [] ) {
		if ( ! isset( $componentConfig['componentType'] ) ) {
			$name = isset( $componentConfig['id'] ) ? $componentConfig['id'] : json_encode( $componentConfig );
			return $this->createElement( 'Prometheus\\NotFoundComponent', [ 'componentId' => $name ] );
		}
		$foundComponent = $this->getComponentByType( $componentConfig['componentType'] );
		$childComponents = isset( $componentConfig['children'] ) ? array_map( function( $child ) use ( &$childProps ) {
			return $this->makeComponentWith( $child, $childProps );
		}, $componentConfig['children'] ) : [];
		$props = isset( $componentConfig['props'] ) ? $componentConfig['props'] : [];
		$className = implode( ' ', [ $componentConfig['componentType'] ] );
		$componentProps = array_merge( $props, [ 'childProps' => $childProps, 'className' => $className ] );
		return $this->createElement( $foundComponent, $componentProps, $childComponents );
	}

	private function getComponentByType( $id ) {
		return in_array( $id, $this->getComponents() ) ? 'Prometheus\\' . $id : 'Prometheus\\NotFoundComponent';
	}

	public function getComponents() {
		return [
			'TextWidget',
			'MenuWidget',
			'SearchWidget',
			'FooterText',
			'HeaderText',
			'ColumnComponent',
			'PageLayout',
			'RowComponent',
			'PostList',
			'PostBody',
			'PostTitle',
			'PostDateAndAuthor',
			'PostContent',
		];
	}

	private function buildComponentFromConfig( $componentConfig, $componentData ) {
		if ( ! isset( $componentConfig['componentType'] ) ) {
			$name = isset( $componentConfig['id'] ) ? $componentConfig['id'] : json_encode( $componentConfig );
			return $this->createElement( 'Prometheus\\NotFoundComponent', [ 'componentId' => $name ] );
		}
		$foundComponent = $this->getComponentByType( $componentConfig['componentType'] );
		$childComponents = isset( $componentConfig['children'] ) ? array_map( function( $child ) use ( &$componentData ) {
			return $this->buildComponentFromConfig( $child, $componentData );
		}, $componentConfig['children'] ) : [];
		$props = isset( $componentConfig['props'] ) ? $componentConfig['props'] : [];
		$data = isset( $componentData[ $componentConfig['id'] ] ) ? $componentData[ $componentConfig['id'] ] : [];
		$className = implode( ' ', [ $componentConfig['componentType'], $componentConfig['id'] ] );
		$componentProps = array_merge( $props, $data, [ 'componentId' => $componentConfig['id'], 'className' => $className ] );
		return $this->createElement( $foundComponent, $componentProps, $childComponents );
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
}
