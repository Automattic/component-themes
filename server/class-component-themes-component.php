<?php

namespace Component_Themes;

abstract class Component {
	protected $builder;

	public function __construct( $props = [], $children = [] ) {
		$this->props = $props;
		$this->children = $children;
		$this->builder = Builder::get_builder();
	}

	protected function get_prop( $key, $default = null ) {
		return isset( $this->props[ $key ] ) ? $this->props[ $key ] : $default;
	}

	protected function get_prop_from_parent( $key, $default = null ) {
		$child_props = isset( $this->props['childProps'] ) ? $this->props['childProps'] : null;
		if ( ! isset( $child_props ) ) {
			return $default;
		}
		return isset( $child_props[ $key ] ) ? $child_props[ $key ] : $default;
	}

	protected function make_component_with( $config, $data ) {
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
