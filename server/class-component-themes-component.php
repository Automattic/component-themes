<?php
abstract class Component_Themes_Component {
	protected $builder;

	public function __construct( $props = [], $children = [] ) {
		$this->props = $props;
		$this->children = $children;
		$this->builder = Component_Themes_Builder::get_builder();
	}

	public function get_prop( $key, $default = null ) {
		return ct_get_value( $this->props, $key, $default );
	}

	public function get_prop_from_parent( $key, $default = null ) {
		$child_props = ct_get_value( $this->props, 'child_props', [] );
		return ct_get_value( $child_props, $key, $default );
	}

	public function make_component_with( $config, $data ) {
		return $this->builder->make_component_with( $config, $data );
	}

	protected function render_child( $child ) {
		if ( is_string( $child ) ) {
			return $child;
		}
		return $child->render();
	}

	public function render_children() {
		if ( ! is_array( $this->children ) ) {
			return $this->render_child( $this->children );
		}
		return implode( ' ', array_map( function( $child ) {
			return $this->render_child( $child );
		}, $this->children ) );
	}

	public function render() {
		return '';
	}
}
