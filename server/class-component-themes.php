<?php

namespace Component_Themes;

require( __DIR__ . '/class-component-themes-component.php' );
require( __DIR__ . '/class-component-themes-api.php' );
require( __DIR__ . '/class-component-themes-builder.php' );
require( __DIR__ . '/class-component-themes-styles.php' );
require( __DIR__ . '/class-react.php' );

class Component_Themes {
	public function __construct() {
		$this->register_autoload();
	}

	public function render_page( $theme, $slug, $page = [], $content = [] ) {
		$builder = Builder::get_builder();
		$page = ( ! empty( $page ) ) ? $page : $builder->get_template_for_slug( $theme, $slug );
		$output = '<div class="ComponentThemes">';
		$style = new Styles();
		$css = $style->build_styles_from_theme( $theme );
		$output .= "<style class='theme-styles'>$css</style>";
		$output .= $builder->render( $theme, $page, $content );
		$output .= '<script>window.ComponentThemesApiData=' . json_encode( $builder->get_component_api_data() ) . '</script>';
		$output .= '</div>';
		return $output;
	}

	/**
	 * Register a function for autoloading components
	 */
	private function register_autoload() {
		spl_autoload_register( function( $class ) {
			$class_parts = explode( '\\', $class );

			// Handle only the same namespace
			if ( 1 === count( $class_parts ) || __NAMESPACE__ !== $class_parts[0]  ) {
				return;
			}

			$pure_class = array_pop( $class_parts );
			$class_file_path = PLUGIN_DIR . '/src/themes/components/' . $pure_class . '/index.php';
			if ( file_exists( $class_file_path ) ) {
				require_once $class_file_path;
			}
		} );
	}
}
