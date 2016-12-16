<?php

class Component_Themes_Api_Wrapper extends Component_Themes_Component_Wrapper {

	protected $map_api_to_props = null;
	protected $global_state = null;

	public function __construct( &$component, &$map_api_to_props, &$global_state ) {
		parent::__construct( $component );

		$this->map_api_to_props = $map_api_to_props;
		$this->global_state = $state;
	}

	public function create( $props, $children ) {
		$context = ct_get_value( $props, 'context', array() );
		$api_props = ct_get_value( $context, 'apiProps', array() );

		// merge api_props to the context
		foreach ( $api_props as $name=>$prop ) {
			$this->global_state[ $name ] = $prop;
		}

		$new_props = call_user_func( $this->map_api_to_props, 'Component_Themes_Api::get_api_endpoint', $this->global_state, $props );

		$props = array_merge( $props, $new_props );

		return React::createElement( $this->component, $props, $children );
	}

}
