<?php
class Component_Themes_Api {
	private $server;

	public function api_data_wrapper( $props, $context, $endpoints, $class_name ) {
		$api = ct_get_value( $context, 'apiProps', [] );
		foreach ( $endpoints as $endpoint ) {
			if ( ! isset( $api[ $endpoint ] ) ) {
				// TODO: this needs to persist for all components, not just this one
				$api[ $endpoint ] = $this->fetch_required_api_endpoint( $endpoint );
			}
		}
		$new_props = call_user_func( array( $class_name, 'map_api_to_props' ), $api );
		return array_merge( $props, $new_props );
	}

	public function fetch_required_api_endpoint( $endpoint ) {
		if ( ! isset( $this->server ) ) {
			$this->server = rest_get_server();
		}
		$request = new WP_REST_Request( 'GET', $endpoint );
		$response = $this->server->dispatch( $request );
		if ( 200 !== $response->get_status() ) {
			return [ $endpoint => null ];
		}
		return $response->get_data();
	}
}
