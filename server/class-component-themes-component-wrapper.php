<?php

class Component_Themes_Component_Wrapper {
	protected $component;

	public function __construct( $component ) {
		$this->component = $component;
	}

	public function get_styles() {
		if ( $component instanceof Component_Themes_Component_Wrapper ) {
			return $component->get_styles();
		}

		if ( is_string( $component ) && is_subclass_of( $component, 'Component_Themes_Component' ) ) {
			return call_user_func( array( $component, 'get_styles' ) );
		}

		return null;
	}
}
