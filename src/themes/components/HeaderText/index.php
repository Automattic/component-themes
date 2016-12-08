<?php
class Component_Themes_HeaderText extends Component_Themes_Component {
	public function render() {
		$site_title = ct_get_value( $this->props, 'siteTitle', 'My Website' );
		$site_tagline = ct_get_value( $this->props, 'siteTagline', 'My home on the web' );
		$class_name = ct_get_value( $this->props, 'className', '' );
		$link = ct_get_value( $this->props, 'link', '' );
		return "<div class='$class_name'><a href='$link'>
      <h1 class='HeaderText__title'>$site_title</h1>
      <div class='HeaderText__tagline'>$site_tagline</div>
</a></div>";
	}
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_HeaderText', function( $api, $operations ) {
	$site_info = call_user_func( $operations['get_api_endpoint'], '/' );
	return [
		'siteTitle' => ct_get_value( $site_info, 'name' ),
		'siteTagline' => ct_get_value( $site_info, 'description' ),
		'link' => ct_get_value( $site_info, 'url' ),
	];
} );

Component_Themes::register_component( 'HeaderText', $wrapped );
