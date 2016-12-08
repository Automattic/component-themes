<?php
require( __DIR__ . '/class-component-themes-helpers.php' );
require( __DIR__ . '/class-component-themes-component.php' );
require( __DIR__ . '/class-component-themes-api.php' );
require( __DIR__ . '/class-component-themes-builder.php' );
require( __DIR__ . '/class-component-themes-styles.php' );
require( __DIR__ . '/class-react.php' );

class Component_Themes {
	public function render_page( $theme, $slug, $page = [], $content = [] ) {
		$builder = Component_Themes_Builder::get_builder();
		$theme = $builder->merge_themes( $this->get_default_theme(), $theme );
		$page = ( ! empty( $page ) ) ? $page : $builder->get_template_for_slug( $theme, $slug );
		$output = '<div class="ComponentThemes">';
		$style = new Component_Themes_Styles();
		$css = $style->build_styles_from_theme( $theme );
		$output .= "<style class='theme-styles'>$css</style>";
		$output .= $builder->render( $theme, $page, $content );
		$output .= '<script>window.ComponentThemesApiData=' . json_encode( Component_Themes_Api::get_api() ) . '</script>';
		$output .= '</div>';
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

	public static function style_component( $component, $styles ) {
		return Component_Themes_Styles::style_component( $component, $styles );
	}

	public static function api_data_wrapper( $component, $map_api_to_props ) {
		return Component_Themes_Api::api_data_wrapper( $component, $map_api_to_props );
	}
}
