<?php

class Component_Themes_Post_List extends Component_Themes_Component {
	public function render() {
		$posts = $this->get_prop( 'posts', array() );
		$class_name = $this->get_prop( 'className', '' );

		$new_children = array();
		foreach ( $posts as $post ) {
			$new_children[] = React::cloneChildren( $this->children, $post );
		}
		$new_children = ct_array_flatten( $new_children );

		return React::createElement( 'div', array( 'className' => $class_name ), $new_children );
	}

	public static function api_mapper( $get_api_endpoint ) {
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
}

$wrapped = Component_Themes::api_data_wrapper( 'Component_Themes_Post_List', 'Component_Themes_Post_List::api_mapper' );

Component_Themes::register_component( 'PostList', $wrapped );
