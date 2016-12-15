<?php
class Component_Themes_Component {
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

	public function render() {
		return '';
	}
}
