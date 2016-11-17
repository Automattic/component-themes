<?php
require( __DIR__ . '/Component.php' );
require( __DIR__ . '/Builder.php' );
require( __DIR__ . '/Styles.php' );
require( __DIR__ . '/../src/themes/components/SearchWidget/index.php' );
require( __DIR__ . '/../src/themes/components/MenuWidget/index.php' );
require( __DIR__ . '/../src/themes/components/RowComponent/index.php' );
require( __DIR__ . '/../src/themes/components/TextWidget/index.php' );
require( __DIR__ . '/../src/themes/components/PostList/index.php' );
require( __DIR__ . '/../src/themes/components/PostBody/index.php' );
require( __DIR__ . '/../src/themes/components/PostTitle/index.php' );
require( __DIR__ . '/../src/themes/components/PostDateAndAuthor/index.php' );
require( __DIR__ . '/../src/themes/components/PostContent/index.php' );
require( __DIR__ . '/../src/themes/components/ColumnComponent/index.php' );
require( __DIR__ . '/../src/themes/components/FooterText/index.php' );
require( __DIR__ . '/../src/themes/components/HeaderText/index.php' );
require( __DIR__ . '/../src/themes/components/PageLayout/index.php' );

class StrangerThemes {
	public function renderPage( $theme, $page, $content = [] ) {
		$output = '<div class="StrangerThemes">';
		$builder = new \Prometheus\Builder();
		$style = new \Prometheus\Styles();
		$css = $style->buildStylesFromTheme( $theme );
		$component_styles = $style->getComponentStyles( $builder->getComponents() );
		$output .= "<style class='component-styles'>$component_styles</style>";
		$output .= "<style class='theme-styles'>$css</style>";
		$output .= $builder->render( $theme, $page, $content );
		$output .= '</div>';
		return $output;
	}

	public function getTemplateForSlug( $theme, $slug ) {
		$builder = new \Prometheus\Builder();
		return $builder->getTemplateForSlug( $theme, $slug );
	}
}
