<?php
class Component_Themes_HeaderText extends Component_Themes_Component {
	public function render() {
		$site_title = ct_get_value( $this->props, 'siteTitle', 'My Website' );
		$site_tagline = ct_get_value( $this->props, 'siteTagline', 'My home on the web' );
		$class_name = ct_get_value( $this->props, 'className', '' );
		return "<div class='$class_name'>
      <h1 class='HeaderText__title'>$site_title</h1>
      <div class='HeaderText__tagline'>$site_tagline</div>
</div>";
	}

	public static $required_api_endpoints = [ '/' ];

	public static function map_api_to_props( $api ) {
		$site_info = ct_get_value( $api, '/' );
		return [
			'siteTitle' => ct_get_value( $site_info, 'name' ),
			'siteTagline' => ct_get_value( $site_info, 'description' ),
		];
	}
}

Component_Themes::register_component( 'HeaderText', 'Component_Themes_HeaderText' );
