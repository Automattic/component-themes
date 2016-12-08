<?php
class Component_Themes_Api {
	private static $server;
	private static $state = [ 'api' => [] ];

	public static function get_api() {
		return self::$state;
	}

	public static function api_data_wrapper( $component, $map_api_to_props ) {
		return function( $props, $children ) use ( &$component, &$map_api_to_props ) {
			$context = ct_get_value( $props, 'context', [] );
			self::$state = array_merge( self::$state, ct_get_value( $context, 'apiProps', [] ) );
			$operations = [ 'get_api_endpoint' => [ 'Component_Themes_Api', 'get_api_endpoint' ] ];
			$new_props = call_user_func( $map_api_to_props, self::$state, $operations, $props );
			$props = array_merge( $props, $new_props );
			return React::createElement( $component, $props, $children );
		};
	}

	public static function get_api_endpoint( $endpoint ) {
		if ( ! isset( self::$state['api'][ $endpoint ] ) ) {
			self::$state['api'][ $endpoint ] = self::fetch_required_api_endpoint( $endpoint );
		}
		return self::$state['api'][ $endpoint ];
	}

	public static function fetch_required_api_endpoint( $endpoint ) {
		if ( ! isset( self::$server ) ) {
			self::$server = rest_get_server();
		}
		$request = new WP_REST_Request( 'GET', $endpoint );
		$response = self::$server->dispatch( $request );
		if ( 200 !== $response->get_status() ) {
			return null;
		}
		return $response->get_data();
	}
}
