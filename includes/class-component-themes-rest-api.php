<?php

class Component_Themes_Rest_API {

	var $namespace = 'component-themes/v1';

	function init() {
		register_rest_route( $this->namespace, '/settings', array( 'methods' => 'GET', 'callback' => array( $this, 'settings' ) ) );
	}

	function settings( $data ) {
		return array(
			'name' => get_bloginfo( 'name' ),
			'url' => get_bloginfo( 'url' ),
			'wpurl' => get_bloginfo( 'wpurl' ),
			'description' => get_bloginfo( 'description' ),
			'date_format' => get_option( 'date_format' ),
			'time_format' => get_option( 'time_format' ),
		);
	}
}
