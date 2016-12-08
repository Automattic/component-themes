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
				[ 'partial' => 'PostDateAndAuthor' ],
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

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_PostList', function( $api, $operations ) {
	$posts_data = call_user_func( $operations['get_api_endpoint'], '/wp/v2/posts' );
	$posts = array_map( function( $post ) {
		return [
			'title' => $post['title']['rendered'],
			'date' => $post['date'],
			'content' => $post['content']['rendered'],
			'link' => $post['link'],
		];
	}, ct_or( $posts_data, [] ) );
	return [ 'posts' => $posts ];
} );
Component_Themes::register_component( 'PostList', $wrapped );
