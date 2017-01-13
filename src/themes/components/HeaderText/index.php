<?php
class Component_Themes_HeaderText extends Component_Themes_Component {
	public function render() {
		$site_title = $this->get_prop( 'siteTitle', 'My Website' );
		$site_tagline = $this->get_prop( 'siteTagline', 'My home on the web' );
		$class_name = $this->get_prop( 'className', '' );
		$link = $this->get_prop( 'link', '' );

		$title_elem = React::createElement( 'h1', array( 'className' => 'HeaderText__title' ), array( $site_title ) );
		$tagline_elem = React::createElement( 'div', array( 'className' => 'HeaderText__tagline' ), array( $site_tagline ) );
		$link_elem = React::createElement( 'a', array( 'href' => $link ), array( $title_elem, $tagline_elem ) );

		return React::createElement( 'div', array( 'className' => $class_name ), array( $link_elem ) );
	}

	public static function api_mapper( $get_api_endpoint ) {
		$site_info = call_user_func( $get_api_endpoint, '/' );
		return array(
			'siteTitle' => ct_get_value( $site_info, 'name' ),
			'siteTagline' => ct_get_value( $site_info, 'description' ),
			'link' => ct_get_value( $site_info, 'url' ),
		);
	}
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_HeaderText', 'Component_Themes_HeaderText::api_mapper');

Component_Themes::register_component( 'HeaderText', $wrapped );
