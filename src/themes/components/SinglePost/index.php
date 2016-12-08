<?php
class Component_Themes_SinglePost extends Component_Themes_Component {
	public function render() {
		$post_data = $this->get_prop( 'postData', [] );
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
		return "<div class='" . $this->get_prop( 'className' ) . "'>" . call_user_func( $render_blog_post, $post_data ) . '</div>';
	}
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_SinglePost', function( $get_api_endpoint, $state ) {
	$info = ct_get_value( $state, 'pageInfo', [] );
	$post_id = ct_get_value( $info, 'postId' );
	if ( ! $post_id ) {
		return [];
	}
	$post = call_user_func( $get_api_endpoint, '/wp/v2/posts/' . $post_id );
	$author = call_user_func( $get_api_endpoint, '/wp/v2/users/' . $post['author'] );
	return [
		'postData' => [
			'title' => $post['title']['rendered'],
			'date' => $post['date'],
			'content' => $post['content']['rendered'],
			'link' => $post['link'],
			'author' => $author['name'],
		],
	];
} );
Component_Themes::register_component( 'SinglePost', $wrapped );

