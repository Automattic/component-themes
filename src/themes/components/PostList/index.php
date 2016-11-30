<?php
class Component_Themes_PostList extends Component_Themes_Component {
	public function render() {
		$posts = $this->get_prop( 'posts', [] );
		if ( count( $posts ) < 1 ) {
			return '<p>No posts</p>';
		}
		$default_post_config = [
			'componentType' => 'PostBody',
			'children' => [
				[ 'componentType' => 'PostTitle' ],
				[ 'componentType' => 'PostDate' ],
				[ 'componentType' => 'PostAuthor' ],
				[ 'componentType' => 'PostContent' ],
			],
		];
		$post_config = null !== $this->get_prop( 'post' ) ? $this->get_prop( 'post' ) : $default_post_config;
		$render_blog_post = function( $post ) use ( &$post_config ) {
			$component = $this->make_component_with( $post_config, $post );
			return $component->render();
		};
		return "<div class='" . $this->get_prop( 'className' ) . "'>" . implode( '', array_map( $render_blog_post, $posts ) ) . '</div>';
	}
}

