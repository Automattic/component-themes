<?php

class Component_Themes_Post_List extends Component_Themes_Component {
	protected $current_post = null;

	public function render() {
		$posts = $this->get_prop( 'posts', array() );
		$class_name = $this->get_prop( 'className', '' );

		$new_children = array();
		foreach ( $posts as $post ) {
			$this->current_post = $post;
			$new_children[] = React::mapChildren( $this->children, array( $this, 'render_child_with_post' ) );
		}
		$new_children = ct_array_flatten( $new_children );

		return React::createElement( 'div', array( 'className' => $class_name ), $new_children );
	}

	public function render_child_with_post( $child ) {
		return React::cloneElement( $child, $this->current_post );
	}
}

function Component_Themes_Post_List_Api_Mapper( $get_api_endpoint ) {
	$posts_data = ct_or( call_user_func( $get_api_endpoint, '/wp/v2/posts' ), array() );

	$posts = array();
	foreach ( $posts_data as $post ) {
		$author = call_user_func( $get_api_endpoint, '/wp/v2/users/' . $post['author'] );
		$posts[] = array(
			'title' => $post['title']['rendered'],
			'date' => $post['date'],
			'content' => $post['content']['rendered'],
			'link' => $post['link'],
			'author' => $author['name'],
		);
	}

	return array( 'posts' => $posts );
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_Post_List', 'Component_Themes_Post_List_Api_Mapper' );

Component_Themes::register_component( 'PostList', $wrapped );
