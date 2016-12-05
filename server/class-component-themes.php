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
		$page = ( ! empty( $page ) ) ? $page : $builder->get_template_for_slug( $theme, $slug );
		$output = '<div class="ComponentThemes">';
		$style = new Component_Themes_Styles();
		$css = $style->build_styles_from_theme( $theme );
		$output .= "<style class='theme-styles'>$css</style>";
		$output .= $builder->render( $theme, $page, $content );
		$output .= '<script>window.ComponentThemesApiData=' . json_encode( $builder->get_component_api_data() ) . '</script>';
		$output .= '</div>';
		return $output;
	}
}
