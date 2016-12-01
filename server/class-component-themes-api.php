<?php

namespace Component_Themes;

class Api {
	public function fetchRequiredApiData( $data ) {
		$dataForComponent = [];
		foreach ( $data as $key => $endpoint ) {
			$dataForComponent[ $key ] = $this->fetchRequiredApiEndpoint( $endpoint );
		}
		return $dataForComponent;
	}

	private function fetchRequiredApiEndpoint( $key ) {
		switch( $key ) {
		case '/':
			return [
				'name' => get_bloginfo( 'name', 'display' ),
				'description' => get_bloginfo( 'description', 'display' ),
			];
		case '/posts':
			return [];
		}
		return null;
	}
}
