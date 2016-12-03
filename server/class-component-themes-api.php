<?php
class Component_Themes_Api {
	private $server;
	private $api = [];

	public function get_api() {
		return $this->api;
	}

	public function api_data_wrapper( $props, $context, $endpoints, $class_name ) {
		$this->api = array_merge( $this->api, ct_get_value( $context, 'apiProps', [] ) );
		foreach ( $endpoints as $endpoint ) {
			if ( ! isset( $this->api[ $endpoint ] ) ) {
				$this->api[ $endpoint ] = $this->fetch_required_api_endpoint( $endpoint );
			}
		}
		$new_props = call_user_func( array( $class_name, 'map_api_to_props' ), $this->api );
		return array_merge( $props, $new_props );
	}

	public function fetch_required_api_endpoint( $endpoint ) {
		if ( ! isset( $this->server ) ) {
			$this->server = rest_get_server();
		}
		$request = new WP_REST_Request( 'GET', $endpoint );
		$response = $this->server->dispatch( $request );
		if ( 200 !== $response->get_status() ) {
			return null;
		}
		return $response->get_data();
	}
}
