<?php
class Component_Themes_Api {
	private $server;

	public function fetch_required_api_data( $data ) {
		if ( ! isset( $this->server ) ) {
			$this->server = rest_get_server();
		}
		$data_for_component = [];
		foreach ( $data as $key => $endpoint ) {
			$data_for_component[ $key ] = $this->fetch_required_api_endpoint( $endpoint );
		}
		return $data_for_component;
	}

	private function fetch_required_api_endpoint( $endpoint ) {
		$request = new WP_REST_Request( 'GET', $endpoint );
		$response = $this->server->dispatch( $request );
		if ( 200 !== $response->get_status() ) {
			return [];
		}
		return $response->get_data();
	}
}
