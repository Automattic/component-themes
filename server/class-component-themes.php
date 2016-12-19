<?php
require( __DIR__ . '/class-component-themes-helpers.php' );
require( __DIR__ . '/class-component-themes-component.php' );
require( __DIR__ . '/class-component-themes-component-wrapper.php' );
require( __DIR__ . '/class-component-themes-api.php' );
require( __DIR__ . '/class-component-themes-api-wrapper.php' );
require( __DIR__ . '/class-component-themes-builder.php' );
require( __DIR__ . '/class-component-themes-styles.php' );
require( __DIR__ . '/class-react.php' );

class Component_Themes {
	public function render_page( $theme, $info, $page = array(), $content = array() ) {
		$builder = Component_Themes_Builder::get_builder();
		$theme = $builder->merge_themes( $this->get_default_theme(), $theme );
		$slug = ( 'post' === $info['type'] ) ? 'post' : $info['slug'];
		$page = ( ! empty( $page ) ) ? $page : $builder->get_template_for_slug( $theme, $slug );

		$style = new Component_Themes_Styles();
		$css = $style->build_styles_from_theme( $theme );

		// css from the theme
		$output = '<script id="component-themes-component-styles">' . $builder->get_component_styles() . '</style>';
		$output = '<style id="component-themes-theme-styles">' . $css . '</style>';

		// rendered html string
		$output .= '<div class="ComponentThemes">';
		$output .= $builder->render( $theme, $page, $content );
		$output .= '</div>';

		// api data
		$state = array_merge( Component_Themes_Api::get_api(), array( 'pageInfo' => $info ) );
		Component_Themes_Api::set_api( $state );
		$output .= '<script id="component-themes-api-data">window.ComponentThemesApiData=' . json_encode( Component_Themes_Api::get_api() ) . '</script>';

		return $output;
	}

	private function get_default_theme() {
		if ( ! isset( $this->default_theme ) ) {
			$this->default_theme = json_decode( file_get_contents( dirname( dirname( __FILE__ ) ) . '/src/themes/default.json' ), true );
		}
		return $this->default_theme;
	}

	public static function register_component( $type, $component ) {
		return Component_Themes_Builder::register_component( $type, $component );
	}

	public static function register_partial( $type, $component_config ) {
		return Component_Themes_Builder::register_partial( $type, $component_config );
	}

	public static function api_data_wrapper( $component, $map_api_to_props ) {
		return Component_Themes_Api::api_data_wrapper( $component, $map_api_to_props );
	}
}
