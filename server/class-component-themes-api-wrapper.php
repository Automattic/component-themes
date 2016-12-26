<?php

class Component_Themes_Api_Wrapper extends Component_Themes_Component_Wrapper {

	protected $map_api_to_props = null;

	public function __construct( &$component, &$map_api_to_props ) {
		parent::__construct( $component );

		$this->map_api_to_props = $map_api_to_props;
	}

	public function create( $props, $children ) {
		$context = ct_get_value( $props, 'context', array() );
		$api_props = ct_get_value( $context, 'apiProps', array() );

		// merge api_props to the context
		$api_state = array_merge( Component_Themes_Api::get_api(), $api_props );
		Component_Themes_Api::set_api( $api_state );

		$new_props = call_user_func( $this->map_api_to_props, 'Component_Themes_Api::get_api_endpoint', $api_state, $props );

		$props = array_merge( $props, $new_props );

		return React::createElement( $this->component, $props, $children );
	}

}
