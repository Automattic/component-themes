<?php
class Component_Themes_Component {
	public static $styles;

	public $props;
	public $children;

	public function __construct( $props = array(), $children = array() ) {
		$this->props = $props;
		$this->children = $children;
	}

	public function get_prop( $key, $default = null ) {
		return ct_get_value( $this->props, $key, $default );
	}

	public function render_children() {
		return React::renderChildren( $this->children );
	}

	public function clone_children( $props = array() ) {
		return React::cloneChildren( $this->children, $props );
	}

	public function render() {
		return '';
	}

	public static function get_styles( $component ) {
		$properties = get_class_vars( $component );
		return $properties[ 'styles' ];
	}
}
