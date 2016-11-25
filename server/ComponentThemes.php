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

class ComponentThemes {
	public function renderPage( $theme, $slug, $page = [], $content = [] ) {
		$page = ( ! empty( $page ) ) ? $page : $this->getTemplateForSlug( $theme, $slug );
		$output = '<div class="ComponentThemes">';
		$builder = new ComponentThemes_Builder();
		$style = new ComponentThemes_Styles();
		$css = $style->buildStylesFromTheme( $theme );
		$output .= "<style class='theme-styles'>$css</style>";
		$output .= $builder->render( $theme, $page, $content );
		$output .= '</div>';
		return $output;
	}

	public function getTemplateForSlug( $theme, $slug ) {
		$builder = new ComponentThemes_Builder();
		return $builder->getTemplateForSlug( $theme, $slug );
	}
}
