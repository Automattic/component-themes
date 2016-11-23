<?php
abstract class ComponentThemes_Component {
	public function __construct( $props = [], $children = [] ) {
		$this->props = $props;
		$this->children = $children;
	}

	protected function getProp( $key, $default = null ) {
		return isset( $this->props[ $key ] ) ? $this->props[ $key ] : $default;
	}

	protected function getPropFromParent( $key, $default = null ) {
		$childProps = $this->getProp( 'childProps' );
		if ( ! isset( $childProps ) ) {
			return $default;
		}
		return isset( $childProps[ $key ] ) ? $childProps[ $key ] : $default;
	}

	protected function renderChild( $child ) {
		if ( is_string( $child ) ) {
			return $child;
		}
		return $child->render();
	}

	public function renderChildren() {
		if ( ! is_array( $this->children ) ) {
			return $this->renderChild( $this->children );
		}
		return implode( ' ', array_map( function( $child ) {
			return $this->renderChild( $child );
		}, $this->children ) );
	}

	public function render() {
		return '';
	}

	public static function getStyles() {
		return null;
	}
}
