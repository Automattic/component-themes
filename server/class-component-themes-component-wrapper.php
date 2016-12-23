<?php

class Component_Themes_Component_Wrapper {
	protected $component;

	public function __construct( $component ) {
		$this->component = $component;
	}

	public function get_styles() {
		if ( $this->component instanceof Component_Themes_Component_Wrapper ) {
			return $this->component->get_styles();
		}

		if ( is_string( $this->component ) && is_subclass_of( $this->component, 'Component_Themes_Component' ) ) {
			return Component_Themes_Component::get_styles( $this->component );
		}

		return null;
	}
}
